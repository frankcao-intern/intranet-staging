<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by: cravelo
 * Date: 9/8/11
 * Time: 11:03 AM
 */

/**
 * Configuration items ----------------------------------------------------------------------------
 */

/**
 * These are the groups that should be added with read access, along with the creator's, when creating new pages
 * I know the number indices are not necessary but I thought it would make it explicit because they are
 * referenced in other settings
 */
$config['groups_config'] = array(
	"group_names" => array(
		'EF Employees',
		'SeCe Apparel Employees',
		'Retail Associates Group'
	),
	'EF Employees' => array(
		'id' => 3,
		'logo' => 'logo-gradient.png',
		'who_link' => 'who',
		'documents' => 'documents/forms',
		'show_most_downloaded' => true,
		'show_apps' => true
	),
	'SeCe Apparel Employees' => array(
		'id' => 5,
		'logo' => 'logo-canada.png',
		'who_link' => 'who/department/709',
		'documents' => 'documents/sece_forms',
		'show_most_downloaded' => false,
		'show_apps' => false
	),
	'Retail Associates Group' => array(
		'id' => 4,
		'logo' => 'logo-gradient.png',
		'who_link' => 'who',
		'documents' => 'documents/forms',
		'show_most_downloaded' => true,
		'show_apps' => true
	)
);

$config['uploads_dir'] = realpath(APPPATH."../").'/uploads/';

//Configure the full site_url for ajax and other requests, taking the VPN into consideration
$config['protocol'] = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ?
	"https" : "http";
$live_url = "fishnet.eileenfisher.com";

$config['site_url'] = $config['protocol']."://{$_SERVER['HTTP_HOST']}/";

$vpn = "cvpn/{$config['protocol']}/$live_url";
//if the uri contains the vpn stuff or the remote address is a VPN address then we are on the VPN.
if (
	(strpos($_SERVER['REQUEST_URI'], $vpn) !== FALSE) or
	(
		isset($_SERVER['HTTP_ACCEPT_ENCODING']) and ($_SERVER['HTTP_ACCEPT_ENCODING'] == 'identity')
	)
){
	$config['site_url'] = "{$config['site_url']}$vpn/";
}

