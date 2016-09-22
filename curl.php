<?php
	class Curl{
		public $cookie = '';
		public $cookieExpire = 0;
		public $respBody = '';
		public $respHeader = '';

		public function get( $url, $cookie = null ) {
			$curl = curl_init($url);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($curl, CURLOPT_HEADER, true);
			if($cookie !== null) {
				$this -> cookie = $cookie;
			}
			if($this -> cookie != '') {
				curl_setopt($curl, CURLOPT_COOKIE, $this -> cookie);
			}
			$resp = curl_exec($curl);
			curl_close($curl);
			$data = explode("\r\n\r\n", $resp, 2);
			$this -> respHeader = $data[0];
			if(isset($data[1])) {
				$this -> respBody = $data[1];
			}
			return $this -> respBody;
		}

		public function post( $url, $data){	
			$curl = curl_init($url);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($curl, CURLOPT_HEADER, true);
			curl_setopt($curl, CURLOPT_POST, true);
			curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data));

			if($this -> cookie != '') {
				curl_setopt($curl, CURLOPT_COOKIE, $this -> cookie);
			}
			$resp = curl_exec($curl);
			curl_close($curl);
			$data = explode("\r\n\r\n", $resp, 2);
			$this -> respHeader = $data[0];
			if(isset($data[1])) {
				$this -> respBody = $data[1];
			}
			$this-> respHeader = $respHeader = explode("\n", $data[0]);
			foreach($respHeader as $header) {
				if(preg_match("#^ *Set-Cookie *: *(.+)#", $header, $match)) {
					$data = explode(';', $match[1]);
					$this -> cookie = $data[0];
					$time = explode('=', $data[1]);
					$this -> cookieExpire = strtotime($time[1]);
				}
			}
			return $this -> respBody;
		}
	}
