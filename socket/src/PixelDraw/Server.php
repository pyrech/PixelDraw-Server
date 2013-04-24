<?php

namespace PixelDraw;

use Ratchet\ConnectionInterface as Conn;

class Server implements \Ratchet\Wamp\WampServerInterface {

    protected $rooms = array();
    protected $players = array();

    public function onPublish(Conn $conn, $topic, $event, array $exclude, array $eligible) {
        $this->log('onPublish '.$topic->getId(), $conn);
        var_dump($event);
        $topic->broadcast($event);
    }

    public function onCall(Conn $conn, $id, $topic, array $params) {
        $player = $this->getCurrentPlayer($conn);
        $result = array('method' => $topic->getId());
        $this->log('onCall '.$topic->getId(), $player);
        //var_dump($params);
        switch($topic->getId()) {
          case "login":
            $player->leaveRoom();
            if (! $this->assertParams($params, array('name'), $conn, $id, $topic)) return;
            $this->log('login '.$player->getName().' -> '.$params['name']);
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
            if (! $this->assertPlayerExists($params['room_id'], $conn, $id, $topic)) return;
            $room = $this->getRoom($params['room_id']);
            $result['room'] = $room->asItemHash();
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
            if ($room->isFull()) {
              $this->log('Room full', $player);
              $conn->callError($id, $topic, 'Room full');
              return;
            }
            $player->joinRoom($room);
            $result['room'] = $room->asItemHash();
            $result['result'] = 'ok';
            $conn->callResult($id, $result);
            break;

          case "leave_room":
            $this->leaveCurrentRoom($player);
            $result['result'] = 'ok';
            $conn->callResult($id, $result);
            break;

          default:
            $this->log('method ('.$result['method'].') not supported', $player);
            $conn->callError($id, $topic, 'method ('.$result['method'].') not supported');
            break;
        }
    }

    // Gestion of subscriptions
    public function onSubscribe(Conn $conn, $topic) {
      if ()
    }
    public function onUnSubscribe(Conn $conn, $topic) {}

    // Socket connection
    public function onOpen(Conn $conn) {
      $player = new Player($this->getCurrentPlayerId($conn));
      $this->players[$player->getId()] = $player;
      $this->log('onOpen', $player);
    }
    public function onClose(Conn $conn) {
      $player = $this->getCurrentPlayer($conn);
      $this->log('onClose', $player);
      $this->leaveCurrentRoom($player);
      unset($this->players[$player->getId()]);
    }
    public function onError(Conn $conn, \Exception $e) {}

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

    // Leave a player from a room and update this player and room
    public function leaveCurrentRoom(Player $player) {
      $room = $player->getRoom();
      $player->leaveRoom();
      // If room empty, delete it
      if ($room != null && $room->countPlayers() < 1) {
        unset($this->rooms[$room->getId()]);
      }
    }

    public function log($msg, $player = null) {
      $str = '['.date('d-m-Y H:i:s').'] ';
      if ($player instanceof Conn) {
        $player = $this->getCurrentPlayer($player);
      }
      if ($player instanceof Player) {
        $str .= 'Player '.$player->toString().' - ';
      }
      echo $str, $msg, "\n";
    }

    // Mainly use for tests
    public function populate() {
      for($i=0;$i<10;$i++) {
        $room = new Room('room '.$i, 0);
        $this->rooms[$room->getId()] = $room;
      }
    }
}