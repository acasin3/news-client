<?php

namespace CubesSoft\RestHelper;

class RestClient {
	private $_http_protocol;
	
	function __construct( $http_protocol ) {
		$this->_http_protocol = $http_protocol;
	}
	
	function get($url, $headers = []) {
		/*
			Description : Makes a GET api call.
			Returns		: json
			Author		: Alejandro Casin III (a.casin.3rd@gmail.com)
			Sample usage ( assumes $base_id and $api_key values have been defined ):
				$url = 'https://api.airtable.com/v0/' . $base_id . '/DesignType';
				$headers = ['Authorization: Bearer ' . $api_key];

				$rest_client = new \CubesSoft\RestHelper\RestClient;
				$api_response  = $rest_client->get($url, $headers);
		*/
		return $this->execute('GET', $url, null, $headers);
	}
	
	function post($url, $json, $headers = []) {
		/*
			Description : Makes a POST api call.
			Returns		: json
			Author		: Alejandro Casin III (a.casin.3rd@gmail.com)
			Sample usage ( assumes $base_id and $api_key values have been defined ):
				$url = 'https://api.airtable.com/v0/' . $base_id . '/DesignType';
				$json = json_encode( ['width'=> 3, 'height' => 4] );
				$headers = ['Authorization: Bearer ' . $api_key];

				$rest_client = new \CubesSoft\RestHelper\RestClient;
				$api_response  = $rest_client->post($url, $json, $headers);
		*/
		return $this->execute('POST', $url, $json, $headers);
	}

	function patch($url, $json, $headers = []) {
		/*
			Description : Makes a PATCH api call.
			Returns		: json
			Author		: Alejandro Casin III (a.casin.3rd@gmail.com)
			Sample usage ( assumes $base_id and $api_key values have been defined ):
				$url = 'https://api.airtable.com/v0/' . $base_id . '/DesignType';
				$json = json_encode( ['width'=> 5, 'height' => 12] );
				$headers = ['Authorization: Bearer ' . $api_key];

				$rest_client = new \CubesSoft\RestHelper\RestClient;
				$api_response  = $rest_client->patch($url, $json, $headers);
		*/
		return $this->execute('PATCH', $url, $json, $headers);
	}

	function delete($url, $json, $headers = []) {
		/*
			Description : Makes a DELETE api call.
			Returns		: json
			Author		: Alejandro Casin III (a.casin.3rd@gmail.com)
			Sample usage ( assumes $base_id and $api_key values have been defined ):
				$url = 'https://api.airtable.com/v0/' . $base_id . '/DesignType';
				$json = json_encode( ['width'=> 5, 'height' => 12] );
				$headers = ['Authorization: Bearer ' . $api_key];

				$rest_client = new \CubesSoft\RestHelper\RestClient;
				$api_response  = $rest_client->delete($url, $json, $headers);
		*/
		return $this->execute('DELETE', $url, $json, $headers);
	}

	private function execute($method, $url, $json = null, $headers = null) {
		/*
			Description : Wrapper around the CURL command.
			Author		: Alejandro Casin III (a.casin.3rd@gmail.com)
			Sample usage:
				See the functions above.
		*/
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
								CURLOPT_TIMEOUT => 300,
								CURLOPT_USERAGENT => 'Mozilla/5.0 (Windows; U; Windows NT 6.1; en-US; rv:1.9.1.2) Gecko/20090729 Firefox/3.5.2 GTB5'
							)
						);

		// don't verify if connecting from http
		if ( $this->_http_protocol == 'http' ) 
			curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		
		if ( $headers != null ) {
			curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
		}
		
		if ( $json != null ) {
			curl_setopt($curl, CURLOPT_POSTFIELDS, $json);
		}					

		// Execute curl...
		$response = curl_exec($curl);

		// Throw error, if any.
		if (curl_errno($curl)) {
			$error_msg = curl_error($curl);
			throw new \Exception($error_msg);
		}

		curl_close($curl);
		
		return $response;
	}
}
