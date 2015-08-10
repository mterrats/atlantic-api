<?php

////
//  atlantic.net PHP API class (examples)
//  https://www.atlantic.net/community/howto/api-documentation/
//
//	couple pieces taken from this basic example:
//	https://www.atlantic.net/community/howto/atlantic-net-api-php-example/
////

class Atlantic {

	var $format = "json"; // only works with json ;)
	var $apiurl = "https://cloudapi.atlantic.net/api.php";
	var $datef  = array("RFC2616" => 'D, d M Y H:i:s \G\M\T',
			    "ISO8601" => 'Y-m-d\TH:i:s\Z',
			    "MYSQL"   => 'Y-m-d H:i:s');
	var $query;
	var $res;

	// methods available
	var $methods = "describe-plan,describe-image,list-instances,run-instance,describe-instance,reboot-instance,terminate-instance";

	//
	function Atlantic() {
		if (!defined("ATL_API_KEY")  or 
		    !defined("ATL_API_PKEY") or 
		    !defined("ATL_API_VER")) { 
			die("Missing global vars\n");
		}
	}

	//
	function call_method($method="") {
		$tmp = explode(",", $this->methods);
		if (!in_array($method, $tmp)) return false;
		$this->start_engine($method);
		return true;
	}

	//
	function output($print=false) {
		if ($this->format == "json") $this->res = json_decode($this->res,true);
		if (!$print) return $this->res;
		echo $this->res;
	}
	
	// core method
	function start_engine($method) {		
		$now   = time();
		$today = gmdate($this->datef["RFC2616"], $now);
		$ts    = gmdate($this->datef["ISO8601"], $now);
		$ruid  = $this->generate_uid();
		$this->build_query(array("ACSAccessKeyId" => ATL_API_KEY,
					 "Action"         => $method,
					 "Version"        => ATL_API_VER,
					 "Format"         => $this->format,
					 "Timestamp"      => $now+21,
					 "Rndguid"        => $ruid));

		$sts   = $this->query["Timestamp"] . $this->query["Rndguid"];

		//
		$this->build_query(array("Signature" => base64_encode(hash_hmac('sha256', $sts, ATL_API_PKEY, true))));

		// convert this array into string
		$querystring        = $this->atqs($this->query);

		// debug
		//echo "\n\n*** QUERY STRING: $querystring\n\n".print_r($args,true);

		$ch = curl_init($this->apiurl . '?' . $querystring);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
		$this->res = curl_exec($ch);
		curl_close($ch);
	}

	//
	function build_query($array) {
		if (is_array($array)) {
			foreach ($array as $k => $v) $this->query[$k] = $v;
		}
	}

	//
	function generate_uid() {
		return sprintf(
			'%04X%04X%04X%04X%04X%04X%04X%04X%04X',
			mt_rand(0, 65535), mt_rand(16384, 20479),
			mt_rand(0, 65535), mt_rand(16384, 20479),
			mt_rand(32768, 49151), mt_rand(0, 65535),
			mt_rand(0, 65535), mt_rand(0, 65535),
			mt_rand(32768, 49151));
	}

	//
	function atqs($array) {
		$temp = array();
		foreach ($array as $key => $value) {
		    $temp[] = rawurlencode($key) . '=' . rawurlencode($value);
		}
		return implode('&', $temp);
	}

}
