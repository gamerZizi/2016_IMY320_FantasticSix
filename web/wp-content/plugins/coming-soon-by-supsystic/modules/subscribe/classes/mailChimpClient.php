<?php
/**
 * Super-simple, minimum abstraction MailChimp API v2 wrapper
 * 
 * Requires curl (I know, right?)
 * This probably has more comments than code.
 * 
 * @author Drew McLellan <drew.mclellan@gmail.com>
 * @version 1.0
 */
class mailChimpClientScs
{
	private $api_key;
	private $api_endpoint = 'https://<dc>.api.mailchimp.com/2.0/';

	private $last_error = '';


	/**
	 * Create a new instance
	 * @param string $api_key Your MailChimp API key
	 */
	function __construct($api_key)
	{

		$this->api_key = $api_key;
		$datacentre = '';
		if(strpos($this->api_key, '-') !== false) {
			list(, $datacentre) = explode('-', $this->api_key);
		}
		$this->api_endpoint = str_replace('<dc>', $datacentre, $this->api_endpoint);
	}




	/**
	 * Call an API method. Every request needs the API key, so that is added automatically -- you don't need to pass it in.
	 * @param  string $method The API method to call, e.g. 'lists/list'
	 * @param  array  $args   An array of arguments to pass to the method. Will be json-encoded for you.
	 * @return array          Associative array of json decoded API response.
	 */
	public function call($method, $args=array())
	{
		return $this->_raw_request($method, $args);
	}




	/**
	 * Performs the underlying HTTP request. Not very exciting
	 * @param  string $method The API method to be called
	 * @param  array  $args   Assoc array of parameters to be passed
	 * @return array          Assoc array of decoded result
	 */
	private function _raw_request($method, $args=array())
	{
		$args['apikey'] = $this->api_key;

		$url = $this->api_endpoint.'/'.$method.'.json';

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
		curl_setopt($ch, CURLOPT_CAINFO, dirname(__FILE__). DS. 'cacert.pem');

		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
		curl_setopt($ch, CURLOPT_USERAGENT, 'PHP-MCAPI/2.0');		
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_TIMEOUT, 10);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($args));
		$result = curl_exec($ch);
		if(empty($result)) {
			$errorNum = curl_errno($ch);
			$errorMsg = curl_error($ch);
		}
		curl_close($ch);
		if(empty($result)) {
			if(empty($errorMsg)) {
				switch( $errorNum ) {
					case 7:
						$this->last_error = 'You have restriction on your server to make curl requests. In most of such cases - this is connect with incorrect firewal setup. Please contact your hosting provider with this issue.';
						break;
				}
			} else
				$this->last_error = $errorMsg;
		}
		return $result ? json_decode($result, true) : false;
	}	
	public function getLastError() {
		return $this->last_error;
	}
	public function haveError() {
		return !empty($this->last_error);
	}
}
