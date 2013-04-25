<?php

namespace PixelDraw;


class Player {

  private $id = 0;
  private $name = "";
  private $score = 0;
  private $room = null;
  private $conn = null;

  public function __construct($id, $conn) {
    $this->id = $id;
    $this->name = 'guest_'.substr(''.$id, -8);
    $this->conn = $conn;
  }

  public function getId() {
    return $this->id;
  }

  public function getName() {
    return $this->name;
  }

  public function setName($name) {
    $this->name = $name;
  }

  public function getConn() {
      return $this->conn;
  }

  public function getRoom() {
      return $this->room;
  }

  public function incrementScore($score) {
    $this->score += intval($score);
  }

  public function joinRoom(Room $room) {
    if ($room->isFull()) {
      return;
    }
    $this->room = $room;
    $room->addPlayer($this);
  }

  public function leaveRoom() {
    $this->score == 0;
    if ($this->room != NULL) {
      $this->room->removePlayer($this);
      $this->room == NULL;
    }
  }

  public function toString() {
    $str = ''.$this->id;
    if (strlen($this->name) > 0) {
      $str .= ' ('.$this->name.')';
    }
    return $str;
  }

  public function asItemHash() {
    $result = array('id'      => $this->id,
                    'name'    => $this->name,
                    'score'   => $this->score);
    if ($this->room != NULL) {
      $result['room_id'] = $this->room->getId();
    }
    else {
      $result['room_id'] = 0;
    }
    return $result;
  }

  public function isInRoom($room_id) {
    if ($room_id instanceof Room) {
      $room_id = $room_id->getId();
    }
    return $this->room && $this->room->getId() == $room_id;
  }

}