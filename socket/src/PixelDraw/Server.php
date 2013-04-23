<?php

namespace PixelDraw;

use Ratchet\ConnectionInterface as Conn;

class Server implements Ratchet\Wamp\WampServerInterface {

    protected $rooms = array();
    protected $players = array();
    protected $room_lookup = array();

    public function onPublish(Conn $conn, $topic, $event, array $exclude, array $eligible) {
        $this->log('onPublish '.$topic->getId(), $conn);
        var_dump($event);

        $topic->broadcast($event);
    }

    public function onCall(Conn $conn, $id, $topic, array $params) {
        $this->log('onCall '.$topic->getId(), $conn);
        //var_dump($params);
        switch($topic->getId()) {
          case "connection":
            $required = array('name');
            if (! $this->checkParams($params, $required)) {
              $this->log('Missing required params ('.join(', ', $required).')', $conn);
              $conn->callError($id, $topic, 'Missing required params ('.join(', ', $required).')');
              return;
            }
            $this->players[$this->getPlayerId($conn)]['name'] = $params['name'];
            $conn->callResult($id, array('result' => 'ok'));
            break;
          case "get_room_list":
            $rooms = array();
            foreach ($this->rooms as $key => $value) {
              $rooms[] = array_merge(array('room_id' => $key), $value);
            }
            $conn->callResult($id, array('rooms' => $rooms));
            break;
          case "create_room":
            $this->leaveCurrentRoom($conn);
            $required = array('room_name');
            if (! $this->checkParams($params, $required)) {
              $this->log('Missing required params ('.join(', ', $required).')', $conn);
              $conn->callError($id, $topic, 'Missing required params ('.join(', ', $required).')');
              return;
            }
            $room_id = uniqid();
            $this->rooms[$room_id] = array('name' => $params['room_name'],
                                           'count_player' => 0,
                                           'max_player' => 5,
                                           'creator' => $this->getPlayerId($conn));
            if ($this->isRoomFull($room_id)) {
              $this->log('Room full', $conn);
              $conn->callError($id, $topic, 'Room full');
              return;
            }
            $this->joinRoom($conn, $room_id);
            $conn->callResult($id, array('room_id' => $room_id,
                                         'room_name' => $params['room_name']));
            break;
          case "join_room":
            $this->leaveCurrentRoom($conn);
            $required = array('room_id');
            if (! $this->checkParams($params, $required)) {
              $this->log('Missing required params ('.join(', ', $required).')', $conn);
              $conn->callError($id, $topic, 'Missing required params ('.join(', ', $required.')'));
              return;
            }
            if (! $this->roomExists($params['room_id'])) {
              $conn->callError($id, $topic, 'Invalid room id');
              return;
            }
            $this->joinRoom($conn, $params['room_id']);
            $conn->callResult($id, array('room_id' => $params['room_id'],
                                         'result' => 'ok'));
            break;
          default:
            $conn->callError($id, $topic, 'method not supported');
            break;
        }
    }

    // No need to anything, since WampServer adds and removes subscribers to Topics automatically
    public function onSubscribe(Conn $conn, $topic) {}
    public function onUnSubscribe(Conn $conn, $topic) {}

    public function onOpen(Conn $conn) {
      $this->players[$this->getPlayerId($conn)] = array();
      $this->log('onOpen', $conn);
    }
    public function onClose(Conn $conn) {
      $this->log('onClose', $conn);
      $this->leaveCurrentRoom($conn);
      unset($this->players[$this->getPlayerId($conn)]);
    }
    public function onError(Conn $conn, \Exception $e) {}
    public function roomExists($room_id) {
      return array_key_exists($room_id, $this->rooms);
    }
    public function checkParams($params, $required) {
      foreach ($required as $key) {
        if (! array_key_exists($key, $params)) {
          return false;
        }
      }
      return true;
    }
    public function getPlayerId(Conn $conn) {
      return $conn->WAMP->sessionId;
    }
    public function joinRoom(Conn $conn, $room_id) {
      $this->room_lookup[$this->getPlayerId($conn)] = $room_id;
      $this->rooms[$room_id]['count_player']++;
    }
    public function leaveCurrentRoom(Conn $conn) {
      if (array_key_exists($this->getPlayerId($conn), $this->room_lookup)
        && ($room_id = $this->room_lookup[$this->getPlayerId($conn)])
        && $this->roomExists($room_id)) {
        unset($this->room_lookup[$this->getPlayerId($conn)]);
        $this->rooms[$room_id]['count_player']--;
        if ($this->rooms[$room_id]['count_player'] < 1) {
          unset($this->rooms[$room_id]);
        }
      }
    }
    public function isRoomFull($room_id) {
      //if ($this->roomExists($room_id)
       //   &&)
      return false;
    }
    public function log($msg, $conn = null) {
      $str = '['.date('d-m-Y H:i:s').'] ';
      if ($conn) {
        $str .= 'Player '.$this->getPlayerId($conn);
        if (array_key_exists('name', $this->players[$this->getPlayerId($conn)])) {
          $str .= ' ('.$this->players[$this->getPlayerId($conn)]['name'].')';
        }
        $str .= ' - ';
      }
      echo $str, $msg, "\n";
    }
    public function populate() {
      for($i=0;$i<10;$i++) {
        $room_id = uniqid();
        $this->rooms[$room_id] = array('name' => 'LOLILOL',
                                       'count_player' => 0,
                                       'max_player' => 5,
                                       'creator' => 0);
      }
    }
}