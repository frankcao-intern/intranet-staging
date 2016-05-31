<?php

set_time_limit(600); //10 minutes

$db = new PDO("mysql:dbname=intranet;host=localhost", 'intranet', 'JApvxThGrzRBsVp9');

$f = fopen(dirname(__FILE__)."/delete_revisions-".date("Y").".log", "a+");

fwrite($f, date("Y-m-d H:i:s")." -------------------------------------------------------------------------\n");
fwrite($f, date("Y-m-d H:i:s")." Deleting old revisions\n");

$limit_rev = 3;

$q = $db->exec("
	DELETE FROM fn_revisions r
	WHERE revision_id IN
		(SELECT rev.revision_id FROM
			(SELECT r1.revision_id, r1.page_id, r1.date_created, count(*)
				FROM fn_revisions r1
				JOIN fn_revisions r2 ON r1.page_id = r2.page_id AND r1.date_created <= r2.date_created
				GROUP BY r1.page_id, r1.date_created
				HAVING count(*)  > $limit_rev
			) rev
		)
	");

fwrite($f, date("Y-m-d H:i:s")." Delete complete, affected rows: $q\n");

fclose($f);

unset($db);
?>
