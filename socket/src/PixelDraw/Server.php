<?php

namespace PixelDraw;

use Ratchet\ConnectionInterface as Conn;

class Server implements \Ratchet\Wamp\WampServerInterface {

    const EVENT_PLAYER = 0;
    const EVENT_SERVER = 1;
    const EVENT_ROOM   = 2;
    const EVENT_DRAW   = 3;

    protected $database = null;
    protected $rooms = array();
    protected $players = array();

    public function onPublish(Conn $conn, $topic, $event, array $exclude, array $eligible) {
      $this->log('onPublish '.$topic->getId(), $conn);
      $player = $this->getCurrentPlayer($conn);
      if (!$this->roomExists($topic->getId())) {
        $this->log('Invalid topic ('.$topic->getId().')', $player);
        //$conn->send('Invalid topic ('.$topic->getId().')');
        return;
      }
      $this->log($event);
      $room = $this->getRoom($topic->getId());
      if (!$player->isInRoom($room)) {
        $this->log('Publish forbidden '.$room->toString(), $player);
        //$conn->send('Publish forbidden'.$room->toString());
        return;
      }
      if (!is_array($event)) {
        $this->log('Invalid event data received. Should be a PHP array.', $player);
        //$conn->send('Invalid event data received. You must send an object.');
        return;
      }
      $required = array('type', 'event');
      foreach($required as $key) {
        if (!array_key_exists($key, $event)) {
          $this->log('Missing data key ('.$key.')', $player);
          return;
        }
      }
      switch($event['type']) {
        case self::EVENT_PLAYER :
          $required = array('msg');
          foreach($required as $key) {
            if (!array_key_exists($key, $event['event'])) {
              $this->log('Missing event key ('.$key.')', $player);
              return;
            }
          }
          $event['event']['player_id'] = $player->getId();
          $word = $room->getWordName();
          if ($room->getState() == Room::STATE_IN_GAME) {
            if (strtolower(trim($event['event']['msg'])) == strtolower(trim($word))) {
              $this->launchServerEvent($player->getName().' found the word !');
              // set score
              // check if everybody found the word
              return;
            }
          }
          break;

        case self::EVENT_SERVER :
        case self::EVENT_ROOM :
          $this->log('Event server and room forbidden', $player);
          break;

        case self::EVENT_DRAW :
          $required = array('picture');
          foreach($required as $key) {
            if (!array_key_exists($key, $event['event'])) {
              $this->log('Missing event key ('.$key.')', $player);
              return;
            }
          }
          if ($room->getState() != Room::STATE_IN_GAME) {
            $this->log('Draw forbidden because room not in state "in game"', $player);
            return;
          }
          if (!$room->isDrawer($player)) {
            $this->log('Draw forbidden because player is not the drawer', $player);
            return;
          }
          break;

        default :
          $this->log('Invalid event type ('.$event['type'].')', $player);
          return;
          break;
      }
      $this->log('broadcast event');
      $topic->broadcast($event);
    }

    public function onCall(Conn $conn, $id, $topic, array $params) {
        $player = $this->getCurrentPlayer($conn);
        $result = array('method' => $topic->getId());
        $this->log('onCall '.$topic->getId(), $player);
        //var_dump($params);
        switch($topic->getId()) {
          case "login":
            $this->leaveCurrentRoom($player);
            if (! $this->assertParams($params, array('name'), $conn, $id, $topic)) return;
            $this->log('Change login "'.$player->getName().'" to "'.$params['name'].'"');
            $player->setName($params['name']);
            $result['player'] = $player->asItemHash();
            $result['result'] = 'ok';
            $conn->callResult($id, $result);
            break;

          case "get_info_player":
            if (! $this->assertParams($params, array('player_id'), $conn, $id, $topic)) return;
            if (! $this->assertPlayerExists($params['player_id'], $conn, $id, $topic)) return;
            $player_ = $this->getPlayer($params['player_id']);
            $result['player'] = $player_->asItemHash();
            $result['result'] = 'ok';
            $conn->callResult($id, $result);
            break;

          case "get_info_room":
            if (! $this->assertParams($params, array('room_id'), $conn, $id, $topic)) return;
            if (! $this->assertRoomExists($params['room_id'], $conn, $id, $topic)) return;
            $room = $this->getRoom($params['room_id']);
            $result['room'] = $room->asItemHash();
            $result['result'] = 'ok';
            $conn->callResult($id, $result);
            break;

          case "get_room_players":
            if (! $this->assertParams($params, array('room_id'), $conn, $id, $topic)) return;
            if (! $this->assertRoomExists($params['room_id'], $conn, $id, $topic)) return;
            $room = $this->getRoom($params['room_id']);
            $result['players'] = $room->getPlayersAsHash();
            $result['result'] = 'ok';
            $conn->callResult($id, $result);
            break;

          case "get_room_list":
            $result['rooms'] = array();
            foreach ($this->rooms as $room) {
              $result['rooms'][] = $room->asItemHash();
            }
            $result['result'] = 'ok';
            $conn->callResult($id, $result);
            break;

          case "create_room":
            $this->leaveCurrentRoom($player);
            if (! $this->assertParams($params, array('room_name'), $conn, $id, $topic)) return;
            $room = new Room($params['room_name'], $player->getId());
            $this->rooms[$room->getId()] = $room;
            $this->log('Create room '.$room->toString(), $player);
            $player->joinRoom($room);
            $result['room'] = $room->asItemHash();
            $result['result'] = 'ok';
            $conn->callResult($id, $result);
            break;

          case "join_room":
            $this->leaveCurrentRoom($player);
            if (! $this->assertParams($params, array('room_id'), $conn, $id, $topic)) return;
            if (! $this->assertRoomExists($params['room_id'], $conn, $id, $topic)) return;
            $room = $this->getRoom($params['room_id']);
            if (! $this->assertRoomState(Room::STATE_WAITING_ROOM, $room, $conn, $id, $topic)) return;
            if ($room->isFull()) {
              $this->log('Room full', $player);
              $conn->callError($id, $topic, 'Room full');
              return;
            }
            $player->joinRoom($room);
            $result['room'] = $room->asItemHash();
            $result['result'] = 'ok';
            $conn->callResult($id, $result);
            $this->launchServerEvent($room, $player->getName().' join room.');
            $this->launchRoomEvent($room);
            break;

          case "leave_room":
            $this->leaveCurrentRoom($player);
            $result['result'] = 'ok';
            $conn->callResult($id, $result);
            break;

          case "get_categories":
            $result['categories'] = Words::collectCategories($this->database, 3);
            $result['result'] = 'ok';
            $conn->callResult($id, $result);
            break;

          case "get_word":
            if (! $this->assertParams($params, array('category_id'), $conn, $id, $topic)) return;
            if (! $this->assertCategoryExists($params['category_id'], $conn, $id, $topic)) return;
            if (! $this->assertPlayerInRoom($player, $conn, $id, $topic)) return;
            if (! $this->assertPlayerIsDrawer($player, $conn, $id, $topic)) return;
            $room = $player->getRoom();
            if (! $this->assertRoomState(Room::STATE_DRAWER_CHOOSING, $room, $conn, $id, $topic)) return;
            $result['word'] = Words::getRandomWord($this->database, $params['category_id']);
            $result['result'] = 'ok';
            $room->setWord($result['word']['id'], $result['word']['name']);
            $conn->callResult($id, $result);
            break;

          case "launch_game":
            if (! $this->assertPlayerInRoom($player, $conn, $id, $topic)) return;
            if (! $this->assertPlayerIsAdmin($player, $conn, $id, $topic)) return;
            $room = $player->getRoom();
            if (! $this->assertRoomState(Room::STATE_WAITING_ROOM, $room, $conn, $id, $topic)) return;
            $result['result'] = 'ok';
            $room->setState(Room::STATE_DRAWER_CHOOSING);
            $conn->callResult($id, $result);
            $this->launchRoomEvent($room);
            break;

          default:
            $this->log('method ('.$result['method'].') not supported', $player);
            $conn->callError($id, $topic, 'method ('.$result['method'].') not supported');
            break;
        }
    }

    // Gestion of subscriptions
    public function onSubscribe(Conn $conn, $topic) {
      $player = $this->getCurrentPlayer($conn);
      // Player must be in the room
      $room = $player->getRoom();
      if (!$room || $room->getId() != $topic->getId()) {
        $topic->remove($conn);
        // send a message to client ?
        return;
      }
    }

    public function onUnSubscribe(Conn $conn, $topic) {}

    // Socket connection
    public function onOpen(Conn $conn) {
      $player = new Player($this->getCurrentPlayerId($conn), $conn);
      $this->players[$player->getId()] = $player;
      $this->log('onOpen', $player);
    }
    public function onClose(Conn $conn) {
      $player = $this->getCurrentPlayer($conn);
      $this->log('onClose', $player);
      $this->leaveCurrentRoom($player);
      unset($this->players[$player->getId()]);
    }
    public function onError(Conn $conn, \Exception $e) {
      $conn->close();
    }

    // Assert methods (send callError if check failed)
    public function assertParams($params, $required, $conn, $id, $topic) {
      foreach ($required as $key) {
        if (! array_key_exists($key, $params)) {
          $this->log('Missing required params ('.$key.')', $conn);
          $conn->callError($id, $topic, 'Missing required params ('.$key.')');
          return false;
        }
      }
      return true;
    }
    public function assertRoomExists($room_id, $conn, $id, $topic) {
      if (! $this->roomExists($room_id)) {
        $this->log('Invalid room id ('.$room_id.')', $conn);
        $conn->callError($id, $topic, 'Invalid room id ('.$room_id.')');
        return false;
      }
      return true;
    }
    public function assertPlayerExists($player_id, $conn, $id, $topic) {
      if (! $this->playerExists($player_id)) {
        $this->log('Invalid player id ('.$player_id.')');
        $conn->callError($id, $topic, 'Invalid player id ('.$player_id.')');
        return false;
      }
      return true;
    }
    public function assertCategoryExists($category_id, $conn, $id, $topic) {
      if (! Words::existsCategory($this->database, $category_id)) {
        $this->log('Invalid category id ('.$category_id.')');
        $conn->callError($id, $topic, 'Invalid category id ('.$category_id.')');
        return false;
      }
      return true;
    }
    public function assertPlayerInRoom(Player $player, $conn, $id, $topic) {
      $room = $player->getRoom();
      if ($room == null || !$this->roomExists($room->getId())) {
        $this->log('Player should be in a room', $player);
        $conn->callError($id, $topic, 'Player should be in a room');
        return false;
      }
      return true;
    }
    public function assertPlayerIsDrawer(Player $player, $conn, $id, $topic) {
      $room = $player->getRoom();
      if (!$room->isDrawer($player)) {
        $this->log('Forbidden : the player is not the drawer', $player);
        $conn->callError($id, $topic, 'Forbidden : the player is not the drawer');
        return false;
      }
      return true;
    }
    public function assertPlayerIsAdmin(Player $player, $conn, $id, $topic) {
      $room = $player->getRoom();
      if (!$room->isDrawer($player)) {
        $this->log('Forbidden : the player is not the admin', $player);
        $conn->callError($id, $topic, 'Forbidden : the player is not the admin');
        return false;
      }
      return true;
    }
    public function assertRoomState($room_state, Room $room, $conn, $id, $topic) {
      if ($room->getState() != $room_state) {
        $this->log('Forbidden : the room is not in state '.Room::$states[$state], $player);
        $conn->callError($id, $topic, 'Forbidden : the room is not in state '.Room::$states[$state]);
        return false;
      }
      return true;
    }

    public function roomExists($room_id) {
      return array_key_exists($room_id, $this->rooms);
    }
    public function playerExists($player_id) {
      return array_key_exists($player_id, $this->players);
    }

    // Methods to get player_id, Player object or Room object
    public function getCurrentPlayerId(Conn $conn) {
      return 'player-'.$conn->WAMP->sessionId;
    }
    public function getCurrentPlayer(Conn $conn) {
      return $this->getPlayer($this->getCurrentPlayerId($conn));
    }
    public function getPlayer($player_id) {
      if (array_key_exists($player_id, $this->players)) {
        return $this->players[$player_id];
      }
      $this->log('Invalid player ('.$player_id.')');
      return null;
    }
    public function getRoom($room_id) {
      if (array_key_exists($room_id, $this->rooms)) {
        return $this->rooms[$room_id];
      }
      $this->log('Invalid room ('.$room_id.')');
      return null;
    }

    public function setDatabase($db) {
      $this->database = $db;
    }

    // Leave a player from a room and update this player and room
    public function leaveCurrentRoom(Player $player) {
      $room = $player->getRoom();
      if ($room != null) {
        $player->leaveRoom();
        // If room empty, delete it
        if ($room->countPlayers() < 1) {
          unset($this->rooms[$room->getId()]);
          return;
        }
        $this->launchServerEvent($room, $player->getName().' has left.');
        $this->launchRoomEvent($room);
      }
    }

    protected function launchRoomEvent(Room $room) {
      $event = array('type'  => self::EVENT_ROOM,
                     'event' => array('room'    => $room->asItemHash()));
      $this->log('launch room event');
      $this->broadcast($room, $event);
    }

    protected function launchServerEvent(Room $room, $msg) {
      $event = array('type'  => self::EVENT_SERVER,
                     'event' => array('msg' => $msg));
      $this->log('launch server event ('.$msg.')');
      $this->broadcast($room, $event);
    }

    protected function broadcast(Room $room, $event, Conn $exclude = null) {
      foreach ($room->getPlayers() as $player) {
        $client = $player->getConn();       
        if ($client !== $exclude) {
          $client->event($room->getId(), $event);
        }
      }
    }

    public function log($msg, $player = null) {
      $str = '';
      if ($player instanceof Conn) {
        $player = $this->getCurrentPlayer($player);
      }
      if ($player instanceof Player) {
        $str .= $player->toString().' - ';
      }
      if (!is_string($msg)) {
        $msg = var_export($msg, true);
      }
      $str .= $msg;
      error_log($str);
      //echo $str, , "\n";
    }

    // Mainly use for tests
    public function populate() {
      for($i=0;$i<10;$i++) {
        $room = new Room('room '.$i, 0);
        $this->rooms[$room->getId()] = $room;
      }
    }
}