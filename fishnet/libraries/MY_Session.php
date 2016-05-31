<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * @author cravelo
 * @date 3/1/11
 * @time 5:59 PM
 */

require_once dirname(__FILE__) . '/../helpers/view_helper.php';

/**
 * this class extends Codeigniter's Session class
 * @author Carlos Ravelo
 * @package Misc
 * @property CI_Loader $load
 */
class MY_Session extends CI_Session {
   /*
    * Do not update an existing session on ajax calls
    *
    * @access public
    * @return void
    */
    function sess_update() {
        if ( !isAjax() ){
            parent::sess_update();
        }
    }
}
