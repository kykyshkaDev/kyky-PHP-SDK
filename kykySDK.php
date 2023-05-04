<?php
class kykySDK {
	private $apiEndpoint="https://api.kykyshka.ru/v1/service/cpa/";
	
	private function _apiCall($method,$args) {
		$resp=file_get_contents($this->apiEndpoint.$method."?".http_build_query($args));
		return json_decode($resp);
	}
	
	function checkToken($token) {
		$args=["token"=>$token];
		return $this->_apiCall("checkToken",$args);
	}
	function register($token) {
		$args=["token"=>$token];
		return $this->_apiCall("register",$args);
	}
	function approve($token) {
		$args=["token"=>$token];
		return $this->_apiCall("approve",$args);
	}
}
?>