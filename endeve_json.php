<?php
class EndeveJSON {
  static function get($url, $username, $password) {
    // Initialization
    $ch = curl_init($url);

    // cURL configuration options
    $options = array (
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_USERPWD => $username . ":" . $password
      //CURLOPT_HTTPHEADER => array('Content-type: application/json'),
      //CURLOPT_POSTFIELDS => $json_string
      );

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
?>