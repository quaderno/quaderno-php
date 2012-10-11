<?php
class QuadernoBase {

  const TESTING_URL = 'http://localhost:3000/';
  const PRODUCTION_URL = "https://www.quaderno.com/";
  protected static $API_KEY = null;
  protected static $ACCOUNT_ID = null;
  protected static $URL = null;

  static function init($key, $account_id, $testing=false) {
    self::$API_KEY = $key;
    self::$ACCOUNT_ID = $account_id;
    self::$URL = $testing ? self::TESTING_URL : self::PRODUCTION_URL;
  }

  static function responseIsValid($response) {
    return isset($response) && !$response[
    'error'] && intval($response['http_code']/100) == 2;
  }

  static function findByID($model, $id) {
    $url = self::$URL . self::$ACCOUNT_ID . "/api/v1/" . $model . "/" . $id . ".json";
    return QuadernoJSON::get($url, self::$API_KEY, "foo");
  }

  static function find($model, $query) {
    $url = self::$URL . self::$ACCOUNT_ID . "/api/v1/" . $model . ".json";
    return QuadernoJSON::get($url, self::$API_KEY, "foo");
  }

  static function save($model, $data, $id) {
    $url = self::$URL . self::$ACCOUNT_ID . "/api/v1/" . $model;

    if ($id) {
      $url .= "/" . $id . ".json";
      QuadernoJSON::update($url, self::$API_KEY, "foo", $data);
    } else {
      $url .= ".json";
      QuadernoJSON::create($url, self::$API_KEY, "foo", $data);
    }
  }

}
?>