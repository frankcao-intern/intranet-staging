<?php
/**
 * @author cravelo
 * @date   3/14/12
 * @time   3:25 PM
 */

/**
 * MY_Security
 * @package
 * @author cravelo
 * @property CI_Output $output
 */
class MY_Security extends CI_Security {
	public function __construct(){
		parent::__construct();
	}

	function csrf_show_error(){
		header('Status: 401', true, 401);
		exit();
	}
}
