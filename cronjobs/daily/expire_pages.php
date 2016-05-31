<?php
/**
 * User: nguimaraes
 * Date: Oct 15, 2010
 *
 * table Pages, fields "expiration_date" and "deleted"
 * this function will run once a day
 * when expiration_date = today, set deleted = 1
 */

set_time_limit(600); //10 minutes

$db = new PDO("mysql:dbname=intranet;host=localhost", 'intranet', 'JApvxThGrzRBsVp9');

$f = fopen(dirname(__FILE__)."/expire_pages-".date("Y").".log", "a+");

fwrite($f, date("Y-m-d H:i:s")." -------------------------------------------------------------------------\n");
fwrite($f, date("Y-m-d H:i:s")." Marking expired pages as deleted\n");

$q = $db->exec("
	UPDATE fn_pages
	SET deleted = 1, published = 0
	WHERE deleted = 0
	AND expiration_date = CURDATE()
");

fwrite($f, date("Y-m-d H:i:s")." Update complete, pages marked: $q\n");

fclose($f);

unset($db);
