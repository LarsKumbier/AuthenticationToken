<?php

/**
 * An authentication token generator
 *
 * @author		Lars Kumbier <lars.kumbier@dl.srh.de>
 * @copyright	GPLv2
 * @usage		Create an array of data and call GetTokenByArray($data)
 */
class AuthenticationToken {
	/**
	 * A valid algorithm
	 * @var	string
	 */
	private $algorithm = 'sha256';
	
	/**
	 * A shared secret
	 * @var	string
	 */
	private $secret    = 'NedLyoQuishjiwetRiOvfietulrished';
	
	
	/**
	 * Default constructor, optionally sets options by array
	 * @var	array 	$options
	 */
	public function __construct($options=null) {
		$this->SetParametersByArray($options);
	}
	
	
	/**
	 * Automagically calls setter functions for given array
	 * @var	array 	[$options]
	 * @return	void
	 * @throws	Exception 	Invalid Parameters
	 */
	public function SetParametersByArray($options=null) {
		if ($options === null || empty($options))
			return;
		
		if (!is_array($options))
			throw new Exception('Method requires a non-empty array');
		
		$valid = get_class_methods(__CLASS__);
		foreach ($options as $key => $value) {
			$setMethod = 'Set'.ucfirst($key);
			if (method_exists(__CLASS__, $setMethod)) {
				$this->$setMethod($value);
				continue;
			}
			
			trigger_error("No Setter found for parameter '$key'", E_USER_WARNING);
		}
	}
	
	
	/**
	 * Setter for hash algorithm
	 * @var	string	$newAlgorithm
	 * @throws	Exception	Invalid Algorithm
	 */
	public function SetAlgorithm($newAlgorithm) {
		$validAlgorithms = hash_algos();
		if (!in_array($newAlgorithm, $validAlgorithms))
			throw new Exception('Invalid algorithm for hash() - valid algorithms are: '
					.implode(', ', $validAlgorithms));
		$this->algorithm = $newAlgorithm;
	}
	
	
	/**
	 * Getter for Algorithm
	 * @return string
	 */
	public function GetAlgorithm() {
		return $this->algorithm;
	}
	
	
	/**
	 * Getter for valid algorithms
	 * @return	array	List of valid algorithms
	 */
	public function GetValidAlgorithms() {
		return hash_algos();
	}
	
	
	/**
	 * Setter for the hashsecret
	 * @var	string	$newSecret
	 * @throws	Exception	Too small or large secret
	 */
	public function SetSecret($newSecret) {
		$length = strlen($newSecret);
		if ($length < 16 || $length > 128)
			throw new Exception ('New secret is too small or too large');
		
		$this->secret = $newSecret;		
	}
	
	
	/**
	 * Returns an authentication token
	 * @var	array	$data
	 * @return string
	 */
	public function GetTokenByArray($array) {
		$joined = array();
		ksort($array);
		foreach ($array as $key => $value)
			$joined[] = $key.'='.$value;
		
		$plaintext = implode('|', $joined);
		unset($joined);
		unset($array);
		
		$plaintext .= $this->secret;
		
		return hash($this->algorithm, $plaintext, false);
	}
}
