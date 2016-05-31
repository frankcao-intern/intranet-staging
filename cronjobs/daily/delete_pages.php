<?php
/**
 * User: nguimaraes
 * Date: Nov 4, 2010
 *
 * table Pages, field "deleted" -> 0 (not deleted), 1 (deleted)
 * when user deletes a page, the field deleted will be set to 1
 * this cronjob will delete permanently all the records that have deleted=1 and were deleted more then 90 days ago
 * the date it was deleted is in audit table
 */

set_time_limit(600); //10 minutes

$db = new PDO("mysql:dbname=intranet;host=localhost", 'intranet', 'JApvxThGrzRBsVp9');

$f = fopen(dirname(__FILE__)."/delete_pages-".date("Y").".log", "a+");

fwrite($f, date("Y-m-d H:i:s")." -------------------------------------------------------------------------\n");
fwrite($f, date("Y-m-d H:i:s")." Deleting pages\n");

$q = $db->exec("
	DELETE FROM fn_pages p
	WHERE deleted = 1 AND page_id IN
		(SELECT page_id FROM
			(SELECT * FROM
				(SELECT * FROM fn_audit ORDER BY `page_id`, `when` DESC) last
			GROUP BY page_id) grouped
		WHERE `what` = 'deleted' AND `when` <= '".date("Y-m-d H:i:s", strtotime("-90 days"))."')
	");

fwrite($f, date("Y-m-d H:i:s")." Delete complete, affected rows: $q\n");

fclose($f);

unset($db);

//SELECT * FROM `pages`,audit WHERE pages.page_id=audit.page_id and pages.deleted=1 and audit.what='deleted' group by audit.page_id order by audit.page_id,audit.when desc
?>
