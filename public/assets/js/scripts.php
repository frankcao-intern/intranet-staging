<?php
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
include "./lib/jquery.easing.1.3.js";
include "./lib/jquery-ui-1.8.20.custom.js";
include "./lib/jquery.fancybox.js";
include "./lib/jquery.fancybox-thumbs.js";
include "./lib/jquery.qtip.js";
include "./lib/jwplayer.js";
include "./lib/jquery.message.js";
include "./lib/jquery.lazyload.js";
include "./lib/jquery.cookie.js";
include "./lib/jquery.placeholder.js";
include "./lib/jquery.checkbox.js";
include "./lib/jquery.mousewheel-3.0.6.pack.js";
include "./lib/jquery.scrollpane.js";
include "./lib/guidely.js";
include "./lib/json2.js";
include "./main.js";
echo "\n\n";

if (extension_loaded('zlib')) {
	ob_end_flush();
}
