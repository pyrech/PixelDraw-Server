<?php

namespace PixelDraw;


class Player {

  private $id = 0;
  private $name = "";
  private $room = 0;

  public function __construct($id) {
    $this->id = $id;
    $this->name = 'guest_'.substr(''.$id, -8);
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

  public function getRoom() {
      return $this->room;
  }

  public function joinRoom(Room $room) {
    if ($room->isFull()) {
      return;
    }
    $this->room = $room;
    $room->addPlayer($this);
  }

  public function leaveRoom() {
    if ($this->room != NULL) {
      $this->room->removePlayer($this);
      unset($this->room);
    }
  }

  public function toString() {
    $str = ''.$this->id;
    if (strlen($this->name) > 0) {
      $str .= ' ('.$this->name.')';
    }
    return $str;
  }

}