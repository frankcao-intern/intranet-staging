<?php
/**
 * @author cravelo
 */

/**
 * This is a class to provide a consistent result JSON object for all AJAX handlers
 * @author cravelo
 * @package Misc
 */
class CResult{
	public $isError;
	public $errorCode;
	public $errorStr;
	public $data;

	function  __construct() {
		$this->isError = false;
		$this->errorCode = 0;
		$this->errorStr = "";
		$this->data = "";
	}

	function  __destruct() {
		unset($this->isError);
		unset($this->errorCode);
		unset($this->errorStr);
		unset($this->data);
	}
}

?>
