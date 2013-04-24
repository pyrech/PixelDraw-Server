<?php

namespace PixelDraw;


class Words {

  private static $query_collect = null;
  private static $query_exists = null;
  private static $query_word = null;

  public static function collectCategories(\PDO $pdo, $limit) {
    if ($this->query_collect == null) {
      $this->query_collect == $pdo->prepare('SELECT * FROM category LIMIT '.intval($limit).' ORDER BY RAND( )');
    }
    $this->query_collect->exec();
    return $this->query_collect->fetch_all();
  }

  public static function existsCategory(\PDO $pdo, $category_id) {
    if ($this->query_exists == null) {
      $this->query_exists == $pdo->prepare('SELECT COUNT(*) as count FROM category WHERE id = :id');
    }
    $this->query_exists->exec(array(':id' => intval($category_id)));
    $res = $this->query_exists->fetch();
    return $res['count'] > 0;
  }

  public static function getRandomWord(\PDO $pdo, $category_id) {
    if ($this->query_word == null) {
      $this->query_word == $pdo->prepare('SELECT * as count FROM word WHERE category_id = :category_id LIMIT 1 ORDER BY RAND( )');
    }
    $this->query_word->exec(array(':category_id' => intval($category_id)));
    return $this->query_word->fetch();
  }

}