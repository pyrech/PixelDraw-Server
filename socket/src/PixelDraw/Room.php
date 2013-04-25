<?php

namespace PixelDraw;


class Room {

  const STATE_WAITING_ROOM    = 1;
  const STATE_DRAWER_CHOOSING = 2;
  const STATE_IN_GAME         = 3;

  public static $states = array(self::STATE_WAITING_ROOM    => "waiting room",
                                self::STATE_DRAWER_CHOOSING => "drawer choosing",
                                self::STATE_IN_GAME         => "in game");

  private $id = 0;
  private $name = "";
  private $state = 0;
  private $max_player = 5;
  private $admin_id = 0;
  private $drawer_id = 0;
  private $players = array();
  private $word_id = 0;
  private $word_name = "";

  public function __construct($name, $player_id) {
    $this->id = uniqid('room-');
    $this->name = $name;
    $this->admin_id = $player_id;
    $this->state = self::STATE_WAITING_ROOM;
  }

  public function asItemHash() {
    return array('id'           => $this->id,
                 'name'         => $this->name,
                 'state'        => $this->state,
                 'players'      => $this->getPlayersAsHash(),
                 'count_player' => $this->countPlayers(),
                 'max_player'   => $this->max_player,
                 'admin_id'     => $this->admin_id);
  }
  public function getPlayersAsHash() {
    $players = array();
    foreach ($this->players as $player) {
      $players[] = $player->asItemHash();
    }
    return $players;
  }

  public function getPlayers() {
    return $this->players;
  }

  public function addPlayer(Player $player) {
    if (empty($this->admin_id)) {
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

  public function getWordId() {
    return $this->word_id;
  }

  public function getWordName() {
    return $this->word_name;
  }

  public function setWord($word_id, $word_name) {
    $this->word_id = $word_id;
    $this->word_name = $word_name;
  }

  public function geDrawerId() {
    return $this->drawer_id;
  }

  public function setDrawerId($drawer_id) {
    if ($drawer_id instanceof Player) {
      $drawer_id = $drawer_id->getId();
    }
    $this->drawer_id = $drawer_id;
  }

  public function setState($state) {
    $this->state = $state;
  }

  public function getState() {
    return $this->state;
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
  public function isDrawer(Player $player) {
    return $this->drawer_id == $player->getId();
  }

}