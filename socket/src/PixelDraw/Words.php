<?php

namespace PixelDraw;


class Words {

  private static $query_collect = null;
  private static $query_exists = null;
  private static $query_word = null;

  public static function collectCategories(\PDO $pdo, $limit) {
    if (empty(self::$query_collect)) {
      self::$query_collect = $pdo->prepare('SELECT * FROM category LIMIT '.intval($limit).' ORDER BY RAND( )');
    }
    self::$query_collect->execute();
    return self::$query_collect->fetch_all();
  }

  public static function existsCategory(\PDO $pdo, $category_id) {
    if (empty(self::$query_exists)) {
      self::$query_exists = $pdo->prepare('SELECT COUNT(*) as count FROM category WHERE id = :id');
    }
    self::$query_exists->execute(array(':id' => intval($category_id)));
    $res = self::$query_exists->fetch();
    return $res['count'] > 0;
  }

  public static function getRandomWord(\PDO $pdo, $category_id) {
    if (empty(self::$query_word)) {
      self::$query_word = $pdo->prepare('SELECT * as count FROM word WHERE category_id = :category_id LIMIT 1 ORDER BY RAND( )');
    }
    self::$query_word->execute(array(':category_id' => intval($category_id)));
    return self::$query_word->fetch();
  }

}