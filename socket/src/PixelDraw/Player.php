<?php

namespace PixelDraw;


class Player {

  private $id = 0;
  private $name = "";
  private $room = 0;

  public function Player($id) {
    $this->id = $id;
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
    $room->addPlayer();
  }

  public function leaveRoom() {
    $this->room->removePlayer($this);
    unset($this->room);
  }

  public function toString() {
    $str = ''.$this->id;
    if (strlen($this->name) > 0) {
      $str .= ' ('.$this->name.')';
    }
    return $str;
  }

}