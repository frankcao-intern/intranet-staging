<?php

set_time_limit(600); //10 minutes

$db = new PDO("mysql:dbname=intranet;host=localhost", 'intranet', 'JApvxThGrzRBsVp9');

$f = fopen(dirname(__FILE__)."/update_featured-".date("Y").".log", "a+");

fwrite($f, date("Y-m-d H:i:s")." -------------------------------------------------------------------------\n");
fwrite($f, date("Y-m-d H:i:s")." Updating featured\n");

$ar1 = $db->exec("UPDATE fn_pages
	SET featured = 1
	WHERE featured = 0
	AND featured_from IS NOT NULL
	AND (
	     (featured_from <= CURDATE() AND featured_until IS NULL)
	     OR CURDATE() BETWEEN featured_from AND featured_until
	)
");


$ar2 = $db->exec("UPDATE pages ".
	"SET featured = 0 ".
	"WHERE featured = 1 AND featured_until = CURDATE()");

fwrite($f, date("Y-m-d H:i:s")." Update complete, now featured: $ar1 un-featured: $ar2\n");

fclose($f);

unset($db);
?>
