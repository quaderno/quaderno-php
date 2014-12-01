<?php
/* General interface that implements the calls
 to the message coding and transport library */
abstract class QuadernoBase {

  const SANDBOX_URL = 'http://sandbox-quadernoapp.com/';
  const PRODUCTION_URL = 'https://quadernoapp.com/';
  protected static $API_KEY = null;
  protected static $SUBDOMAIN = null;
  protected static $URL = null;
  
  static function init($key, $subdomain, $sandbox=false) {
    self::$API_KEY = $key;
    self::$SUBDOMAIN = $subdomain;
    self::$URL = self::quadernoHost($subdomain, $sandbox);
  }

  static function apiCall($method, $model, $id='', $params=null, $data=null) {
    $url = self::$URL . "api/v1/" . $model . ($id != '' ? "/" . $id : '') . ".json";
    if (isset($params)) $url .= "?" . http_build_query($params);	
    return QuadernoJSON::exec($url, $method, self::$API_KEY, "foo", $data);    
  }
  
  static function authorization($key, $sandbox=false){
    self::$URL = $sandbox ? self::SANDBOX_URL : self::PRODUCTION_URL;
	   $response = self::apiCall("GET", 'authorization');
    return $response['data'];
  }
 
  static function ping() {
    return self::responseIsValid(self::apiCall("GET", 'ping'));
  }

  static function delete($model, $id) {
    return self::apiCall("DELETE", $model, $id);
  }

  static function deleteNested($parentmodel, $parentid, $model, $id) {
    return self::delete($parentmodel . "/" . $parentid . "/" . $model, $id);
  }

  static function deliver($model, $id) {
    return self::apiCall("GET", $model, $id . '/deliver');
  }

  static function find($model, $params=null) {
	   return self::apiCall("GET", $model, '', $params);
  } 

  static function findByID($model, $id) {
	   return self::apiCall("GET", $model, $id);
  } 

  static function save($model, $data, $id) {
	   return self::apiCall(($id ? "PUT" : "POST"), $model, $id, null, $data);
  }

  static function calculate($params){
	   return self::apiCall("GET", 'taxes', 'calculate', $params);
  }

  static function saveNested($parentmodel, $parentid, $model, $data) {
    return self::save($parentmodel . "/" . $parentid . "/" . $model, $data, null);
  }

  static function responseIsValid($response) {
    return isset($response) &&
            !$response['error'] &&
            intval($response['http_code']/100) == 2;
  }

  static function quadernoHost($subdomain, $sandbox) {
    $url = $sandbox ? "http://{$subdomain}.sandbox-quadernoapp.com/" : "https://{$subdomain}.quadernoapp.com/";
    return $url;
  }
}
