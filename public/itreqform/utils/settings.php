<?php
/**
 * Created by: cravelo
 * Date: 11/3/11
 * Time: 2:20 PM
 */

$http = (isset($_SERVER['HTTPS']) and ($_SERVER['HTTPS'] == 'on')) ? 'https://' : 'http://';
$uri = $http.$_SERVER['HTTP_HOST'].'/itreqform/';

if (!defined("EF_IT_REQ_FORM")) { header("Location: $uri"); }

$settings = array(
	//add any other pages here
	"pages" => array(
		"form" => '../views/form',
		"thank_you" => '../controllers/formproc',
		"approved" => '../controllers/mgrformproc'
	),
	'base_url' => $uri
);
