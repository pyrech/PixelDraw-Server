<?php

namespace PixelDraw;

use Ratchet\ConnectionInterface as Conn;

class Server implements \Ratchet\Wamp\WampServerInterface {

    protected $rooms = array();
    protected $players = array();

    public function onPublish(Conn $conn, $topic, $event, array $exclude, array $eligible) {
        $this->log('onPublish '.$topic->getId(), $this->getPlayer($conn));
        var_dump($event);
        $topic->broadcast($event);
    }

    public function onCall(Conn $conn, $id, $topic, array $params) {
        $player = $this->getPlayer($conn);
        $this->log('onCall '.$topic->getId(), $player);
        //var_dump($params);
        switch($topic->getId()) {
          case "connection":
            $player->leaveRoom();
            if (! $this->assertParams($params, array('name'), $conn, $id, $topic)) return;
            $player->setName($params['names']);
            $conn->callResult($id, array('result' => 'ok'));
            break;

          case "get_room_list":
            $rooms = array();
            foreach ($this->rooms as $room) {
              $rooms[] = $room->asItemList();
            }
            $conn->callResult($id, array('rooms' => $rooms));
            break;

          case "create_room":
            $this->leaveCurrentRoom($player);
            if (! $this->assertParams($params, array('room_name'), $conn, $id, $topic)) return;
            $room = new Room($params['room_name'], $this->getPlayerId($conn));
            $this->rooms[$room->getId()] = $room;
            $player->joinRoom($room);
            $conn->callResult($id, array('room_id'   => $room->getId(),
                                         'room_name' => $room->getName());
            break;

          case "join_room":
            $this->leaveCurrentRoom($player);
            if (! $this->assertParams($params, array('room_id'), $conn, $id, $topic)) return;
            if (! $this->assertRoomExists($params['room_id']), $conn, $id, $topic) return;
            $room = $this->getRoom($params['room_id']);
            if ($room->isFull()) {
              $this->log('Room full', $player);
              $conn->callError($id, $topic, 'Room full');
              return;
            }
            $player->joinRoom($room);
            $conn->callResult($id, array('room_id' => $room->getId(),
                                         'result' => 'ok'));
            break;

          case "quit_room":
            $this->leaveCurrentRoom($player);
            $conn->callResult($id, array('result' => 'ok'));
            break;

          default:
            $conn->callError($id, $topic, 'method not supported');
            break;
        }
    }

    // No need to anything, since WampServer adds and removes subscribers to Topics automatically
    public function onSubscribe(Conn $conn, $topic) {}
    public function onUnSubscribe(Conn $conn, $topic) {}

    // Socket connection
    public function onOpen(Conn $conn) {
      $player = new Player($this->getPlayerId($conn));
      $this->players[$player->getId()] = $player;
      $this->log('onOpen', $player);
    }
    public function onClose(Conn $conn) {
      $player = $this->getPlayer($conn);
      $this->log('onClose', $player);
      $this->leaveCurrentRoom($conn);
      unset($this->players[$player->id]);
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
      if (! $this->roomExists($params['room_id'])) {
        $this->log('Invalid room id', $conn);
        $conn->callError($id, $topic, 'Invalid room id');
        return false;
      }
      return true;
    }

    public function roomExists($room_id) {
      return array_key_exists($room_id, $this->rooms);
    }

    // Methods to get player_id, Player object or Room object
    public function getPlayerId(Conn $conn) {
      return $conn->WAMP->sessionId;
    }
    public function getPlayer(Conn $conn) {
      $player_id = $this->getPlayerId($conn);
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
      if ($room->countPlayers() < 1) {
        unset($this->rooms[$room->getId()]);
      }
    }

    public function log($msg, $player = null) {
      $str = '['.date('d-m-Y H:i:s').'] ';
      if ($player) {
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