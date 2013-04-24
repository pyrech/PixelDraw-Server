<?php

namespace PixelDraw;


class Room {

  private $id = 0;
  private $name = "";
  private $max_player = 5;
  private $creator = 0;
  private $players = array();

  public function __construct($name, $player_id) {
    $this->id = uniqid();
    $this->name = $name;
    $this->creator = $player_id;
  }

  public function asItemList() {
    return array('room_id'      => $this->id,
                 'room_name'    => $this->name,
                 'count_player' => $this->countPlayers(),
                 'max_player'   => $this->max_player,
                 'creator'      => $this->creator);
  }

  public function addPlayer(Player $player) {
    if ($this->creator == 0) {
      $this->creator = $player->getId();
    }
    $this->players[$player->getId()] = $player;
  }

  public function removePlayer(Player $player) {
    if (array_key_exists($player->getId(), $this->players)) {
      unset($this->players[$player->getId()]);
    }
    if ($this->creator == $player->getId()) {
      $this->creator = reset($this->players);
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

}