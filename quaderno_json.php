<?php
class QuadernoJSON {
  static function exec($url, $method, $username, $password, $data=null) {
    // Initialization
    $ch = curl_init($url);

    $json = $data ? json_encode($data) : null;

    // cURL configuration options
    $options = array (
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_USERPWD => $username . ":" . $password,
      CURLOPT_CUSTOMREQUEST => $method,
      CURLOPT_HTTPHEADER => array('Content-type: application/json')
      );

    if ($json) $options += array(CURLOPT_POSTFIELDS => $json);

    curl_setopt_array($ch, $options);

    // Get results
    $result['data'] = curl_exec($ch);
    $result['error'] = curl_errno($ch);
    $result['format_error'] = curl_error($ch);
    $result += curl_getinfo($ch);
    curl_close($ch);
    if ($result['data']) $result['data'] = json_decode($result['data'], true);

    return $result;
  }
}