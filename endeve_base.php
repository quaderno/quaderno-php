<?php
class EndeveBase {

  const TESTING_URL = 'http://localhost:3000/';
  const PRODUCTION_URL = "https://www.endeve.com/";
  protected static $API_KEY = null;
  protected static $ACCOUNT_ID = null;
  protected static $URL = null;

  static function init($key, $account_id, $testing=false) {
    self::$API_KEY = $key;
    self::$ACCOUNT_ID = $account_id;
    self::$URL = $testing ? self::TESTING_URL : self::PRODUCTION_URL;
  }

  static function responseIsValid($response) {
    return isset($response) && !$response['error'] && intval($response['http_code']/100) == 2;
  }

  static function findByID($model, $id) {
    $url = self::$URL . self::$ACCOUNT_ID . "/api/v1/" . $model . "/" . $id . ".json";
    return EndeveJSON::get($url, self::$API_KEY, "foo");
  }

  static function find($model, $query) {
    $url = self::$URL . self::$ACCOUNT_ID . "/api/v1/" . $model . ".json";
    return EndeveJSON::get($url, self::$API_KEY, "foo");
  }

}
?>