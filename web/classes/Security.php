<?php

/*
require_once("classes/Security.php");

$s = new Security();
$plaintext = "message to be encrypted";

$ciphertext = $s->encrypt($plaintext);

echo "ciphertext:".$ciphertext."<br/>"."\n";

$original_plaintext = $s->decrypt($ciphertext);

echo "plaintext:".$original_plaintext."<br/>"."\n";

echo $s->hash("tester") . "<br>";
*/
	class Security 
	{
		//Constants
		public const CIPHER_METHOD = "AES-128-CBC";
		public const HASH_ALGORITHM = "SHA256";
		public const DEFAULT_KEY = "1234567812345678";

		//Fields
		private $_key = NULL;
		
		function __construct()
		{
			$this->_key = self::DEFAULT_KEY;
		}

		//Properties
		public function getKey()
		{
			return $this->_key;
		}

		public function setKey($key)
		{
			$this->_key = $key;
			return $this;
		}
		// Methods
		public function filterString($data, $doTrim)
		{
			$result = $data;
			
			$result = !isset($result) ? "" : ($doTrim ? trim(strip_tags($data)): strip_tags($data));
			
			return $result;
		}	

		public function encrypt($plaintext)
		{
			//$key previously generated safely, ie: openssl_random_pseudo_bytes
			//$plaintext = "message to be encrypted";

			$ivlen = openssl_cipher_iv_length(self::CIPHER_METHOD);
			$iv = openssl_random_pseudo_bytes($ivlen);			
			$ciphertext_raw = openssl_encrypt($plaintext, self::CIPHER_METHOD, $this->_key, $options=OPENSSL_RAW_DATA, $iv);
			$hmac = hash_hmac(self::HASH_ALGORITHM, $ciphertext_raw, $this->_key, $as_binary=true);
			$ciphertext = base64_encode( $iv.$hmac.$ciphertext_raw);

			$calcmac = hash_hmac(self::HASH_ALGORITHM, $ciphertext_raw, $this->_key, $as_binary=true);
			if (!hash_equals($hmac, $calcmac))//PHP 5.6+ timing attack safe comparison
			{
				//security issue
			}

			return $ciphertext;
		}

		public function decrypt($ciphertext)
		{
			$c = base64_decode($ciphertext);
			$ivlen = openssl_cipher_iv_length(self::CIPHER_METHOD);
			$iv = substr($c, 0, $ivlen);
			$hmac = substr($c, $ivlen, $sha2len=32);
			$ciphertext_raw = substr($c, $ivlen+$sha2len);
			$plaintext = openssl_decrypt($ciphertext_raw, self::CIPHER_METHOD, $this->_key, $options=OPENSSL_RAW_DATA, $iv);
			
			$calcmac = hash_hmac(self::HASH_ALGORITHM, $ciphertext_raw, $this->_key, $as_binary=true);			
			if (!hash_equals($hmac, $calcmac))//PHP 5.6+ timing attack safe comparison
			{
				//security issue
			}

			return $plaintext;
		}

		public function hash($plaintext)
		{
			return hash(self::HASH_ALGORITHM, $plaintext);
		}
	}

?>