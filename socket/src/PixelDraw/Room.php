<?php

namespace PixelDraw;


class Room {

  private $id = 0;
  private $name = "";
  private $max_player = 5;
  private $creator = 0;
  private $players = array();

  public function Room($name, $player_id) {
    $this->id = uniqid();
    $this->name = $name;
    $this->creator = $player_id;
  }

  public function asItemList() {
    return array('room_id'      => $this->room_id,
                 'room_name'    => $this->room_name,
                 'count_player' => $this->countPlayers(),
                 'max_player'   => $this->max_player,
                 'creator'      => $this->creator);
  }

  public function addPlayer(Player $player) {
    $player->joinRoom($this);
    $this->players[$player->getId()] = $player;
  }

  public function removePlayer(Player $player) {
    if (array_key_exists($player->getId(), $this->players)) {
      unset($this->players[$player->getId()]);
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