<?php
/**
 * User: cravelo
 * Date: Sep 22, 2010
 * Time: 2:50:08 PM
 */
set_time_limit(600); //10 minutes

$db = new PDO("mysql:dbname=intranet;host=localhost", 'intranet', 'JApvxThGrzRBsVp9');

$f = fopen(dirname(__FILE__)."/update_searchindex-".date("Y-m-d").".log", "a+");

fwrite($f, date("Y-m-d H:i:s")." -------------------------------------------------------------------------\n");
fwrite($f, date("Y-m-d H:i:s")." Updating index from updated revisions\n");

$db->beginTransaction();

//this query might take a while
$yesterday = date("Y-m-d", strtotime("-1 week"));
$query = $db->query("
			SELECT rev.*, fn_pages.title, fn_pages.date_published, rel.section_id, sections.title as section_title,  fn_tags.tag_id, fn_tags.tag_name, fn_permissions.access
			FROM (SELECT * FROM (
					SELECT r.page_id, r.revision_text
					FROM fn_revisions r
					WHERE r.date_created >= '$yesterday'
					ORDER BY r.date_created DESC) rev1
				GROUP BY rev1.page_id) rev
			JOIN fn_pages ON rev.page_id=fn_pages.page_id
			JOIN fn_templates ON fn_pages.template_id=fn_templates.template_id
			JOIN fn_tag_matches ON rev.page_id=fn_tag_matches.page_id # added by Frank!
			JOIN fn_tags ON fn_tag_matches.tag_id=fn_tags.tag_id # added by Frank!
			JOIN fn_permissions ON fn_permissions.page_id=rev.page_id # added by Frank!
			LEFT JOIN fn_pages_pages rel ON rel.page_id=fn_pages.page_id
			LEFT JOIN fn_pages sections on rel.section_id=sections.page_id
			WHERE fn_pages.published=1 AND fn_pages.deleted=0 AND fn_templates.page_type<>'system';
		");

$queryArr = $query->fetchAll();

$len = count($queryArr);
$pages = 0;
$ar = 0;
if ($len > 0){
	$insertValues = array();

	for ($i = 0; $i < $len; $i++){
		$row = $queryArr[$i];
		if ($row['revision_text'] != ""){
			//$row['revision_text'] = json_decode($row['revision_text']);
			$row['revision_text'] = strip_tags($row['revision_text']);
			//print_r($row);
			$value = "(";
			$value .= $db->quote($row['page_id']).",";
			$value .= "'page',";
			$value .= $db->quote($row['title']).",";
			$value .= $db->quote($row['revision_text']).",";
			$value .= $db->quote($row['date_published']).",";
			$value .= $db->quote($row['section_id']).",";
			$value .= $db->quote($row['section_title']);
			$value .= $db->quote($row['tag_id']).","; // Added by Frank!
			$value .= $db->quote($row['tag_name']).","; // Added by Frank!
			$value .= $db->quote($row['access']); // Added by Frank!
			$value .= ")";

			$insertValues[] = $value;

			if (($i != 0) and (($i % 50) == 0) or ($i == ($len - 1))){
				$strInsertQuery = "INSERT INTO fn_searchindex(obj_id, obj_type, page_title, page_content,
								page_date_published, section_id, section_title, tag_id, tag_name, access) VALUES ";
						$strInsertQuery .= implode(",", $insertValues);
						$strInsertQuery .= " ON DUPLICATE KEY UPDATE obj_id=VALUES(obj_id), obj_type=VALUES(obj_type),
								page_title=VALUES(page_title), page_content=VALUES(page_content),
								page_date_published=VALUES(page_date_published), section_id=VALUES(section_id),
								section_title=VALUES(section_title), tag_id=VALUES(tag_id), tag_name=VALUES(tag_name), access=VALUES(access)";

				//echo "$i of ".($len - 1)."<br><br>";
				$ar = $db->exec($strInsertQuery);

				if ($ar === 0){
					$db->rollBack();
					break;
				}else{
					$pages += $ar;
					$insertValues = array();
				}
			}
		}
	}

	if ($ar !== 0){ $db->commit(); }
}

fwrite($f, date("Y-m-d H:i:s")." Update complete, affected rows: $pages\n");

fclose($f);

unset($db);
