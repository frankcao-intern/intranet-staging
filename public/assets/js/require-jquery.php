<?php
/**
 * @filename require-jquery.php.php5
 * @author   : cravelo
 * @date     : 9/26/12 2:01 PM
 */

if (extension_loaded('zlib')) {
	// initialize ob_gzhandler function to send and compress data
	ob_start('ob_gzhandler');
}

header("Content-type: application/javascript");

// set a date in the future
$offset = 60 * 60 * 24 * 30 * 6; //6 months
header ("Expires: " . gmdate ("D, d M Y H:i:s", time() + $offset) . " GMT");
header ("cache-control: max-age=" . $offset . ", must-revalidate");

$pf_date = date("D, d M Y H:i:s", filemtime(__FILE__));
header("Last-Modified: $pf_date GMT");

/**
 * Global javascript code
 */
include "./lib/require.js";
include "./lib/jquery-1.8.1.js";

if (extension_loaded('zlib')) {
	ob_end_flush();
}
