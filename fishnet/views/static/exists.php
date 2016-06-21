<?php
include '../../config/database.php';

echo 'Article: ' . htmlspecialchars($_GET["article"]);
echo 'Section: ' . htmlspecialchars($_GET["section"]);
//echo 'Publish Date: ' . htmlspecialchars($_GET["pubDate"]);
//echo 'Exp Date: ' . htmlspecialchars($_GET["expDate"]);
$this->db->select('pubDate');
$this->db->select('expDate');
$this->db->where('article', htmlspecialchars($_GET["article"]));
$this->db->where('section', htmlspecialchars($_GET["section"]));

$q = $this->db->get('pubdates');


//if id is unique we want just one row to be returned
$data = array_shift($q->result_array());
if(strlen($data['pubDate'])>1) {
	echo("The page is already published to this section");
	// needs an update statement
	$this->db->set('article', $_GET["article"]);
	$this->db->set('section', $_GET["section"]);
	$this->db->set('pubdate', $_GET["pubDate"]);
	$this->db->set('expDate', $_GET["expDate"]);
	$this->db->update('pubdates');
} else
{
	echo ("The page is NOT published yet.");
	// needs insert statement
	$this->db->set('article', $_GET["article"]);
	$this->db->set('section', $_GET["section"]);
	$this->db->set('pubdate', $_GET["pubDate"]);
	$this->db->set('expDate', $_GET["expDate"]);
	$this->db->insert('pubdates');
}



?>