<?php
	class Curl{
		public $cookie = '';
		public $cookieExpire = 0;
		public $respBody = '';
		public $respHeader;

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
			$respHeader = explode("\n", $data[0]);
			$this -> parseHeader($respHeader);
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
			$respHeader = explode("\n", $data[0]);
			$this -> parseHeader($respHeader);
			return $this -> respBody;
		}
		
		private function parseHeader($respHeader) {
			$this -> respHeader = array();
			foreach($respHeader as $header) {
				date_default_timezone_set("PRC");
				if(preg_match('#HTTP/\S* *(\d+) *(\S+)#', $header, $match)) {
					$this -> respHeader['status'] = trim($match[1]);
					$this -> respHeader['msg'] = trim($match[2]);
				}

				if(preg_match("#([^:]+)[\s]*:[\s]*(.+)$#U", $header, $match)) {
					$this -> respHeader[trim($match[1])] = trim($match[2]);
				}

				if(preg_match("#^ *Set-Cookie *: *(.+)#", $header, $match)) {
					$data = explode(';', $match[1]);
					$this -> cookie = $data[0];
					$time = explode('=', $data[1]);
					$this -> cookieExpire = strtotime($time[1]);
				}

			}
		}
	}
