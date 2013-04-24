<?php

namespace PixelDraw;


class Room {

  private $id = 0;
  private $name = "";
  private $max_player = 5;
  private $admin_id = 0;
  private $players = array();

  public function __construct($name, $player_id) {
    $this->id = uniqid('room-');
    $this->name = $name;
    $this->admin_id = $player_id;
  }

  public function asItemHash() {
    return array('id'           => $this->id,
                 'name'         => $this->name,
                 'count_player' => $this->countPlayers(),
                 'max_player'   => $this->max_player,
                 'admin_id'   => $this->admin_id);
  }

  public function addPlayer(Player $player) {
    if ($this->admin_id == 0) {
      $this->admin_id = $player->getId();
    }
    $this->players[$player->getId()] = $player;
  }

  public function removePlayer(Player $player) {
    if (array_key_exists($player->getId(), $this->players)) {
      unset($this->players[$player->getId()]);
    }
    if ($this->admin_id == $player->getId()) {
      $this->admin_id = reset($this->players);
    }
  }

  public function countPlayers() {
    return count($this->players);
  }

  public function isFull() {
    return $this->countPlayers() >= $this->max_player;
  }

  public function getId() {
    return $this->id;
  }

  public function getName() {
    return $this->name;
  }

  public function toString() {
    $str = ''.$this->id;
    if (strlen($this->name) > 0) {
      $str .= ' ('.$this->name.')';
    }
    return $str;
  }

  public function isInRoom(Player $player) {
    return array_key_exists($player->getId(), $this->players);
  }

}