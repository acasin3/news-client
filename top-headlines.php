<?php

if (!defined('API_URL')) define('API_URL', 'https://newsapi.org/v2/'); // INCLUDE TRAILING SLASH!!!!

$authorization = 'Authorization: Bearer <PASTE API KEY HERE>';
$cookie = 'Cookie: Authorization=<PASTE API KEY HERE>';

$url = API_URL . 'top-headlines';
$method = 'GET';
$data = ['country' => 'us'];
$url = sprintf("%s?%s", $url, http_build_query($data));
$headers = array( $authorization, $cookie );

$curl = curl_init();

curl_setopt_array(
          $curl, 
          array(
              CURLOPT_URL => $url,
              CURLOPT_RETURNTRANSFER => true,
              CURLOPT_ENCODING => '',
              CURLOPT_MAXREDIRS => 10,
              CURLOPT_TIMEOUT => 0,
              CURLOPT_FOLLOWLOCATION => true,
              CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
              CURLOPT_CUSTOMREQUEST => $method,
              CURLOPT_HTTPHEADER => $headers,
		          CURLOPT_SSL_VERIFYHOST => 2,
              CURLOPT_SSL_VERIFYPEER => false   // set to true in prod env 
          )
);

$response = curl_exec($curl);

if ( !curl_errno($curl) ) {
  $http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
}

curl_close($curl);

$arr_response = json_decode($response, true);

if ( $http_code == 200 ) {
  $arr_articles = $arr_response['articles'];
  echo '<pre>';
  print_r($arr_articles);
  echo '</pre>';
} else {
  $error = $arr_response['message'];
  echo $error;
}

?>
