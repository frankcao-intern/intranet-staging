<?php
$s = "";
if (isset($_GET['s'])){	$s = $_GET['s']; }

function cmp($a, $b){
	$aName = $a["cn"][0];
	$bName = $b["cn"][0];

	if ($aName == $bName) return 0;

	return ($aName < $bName) ? -1 : 1;
}

// connect to ldap server
$ldapconn = ldap_connect("efadc01.eileenfisher.net")
	or $ldapconn = ldap_connect("eileenfisher.net")
		 or die("Could not connect to LDAP server.");
if ($ldapconn)
{
	ldap_set_option($ldapconn, LDAP_OPT_PROTOCOL_VERSION, 3);
	ldap_set_option($ldapconn, LDAP_OPT_REFERRALS, 0);
	ldap_set_option($ldapconn, LDAP_OPT_SIZELIMIT, 1000);
	// binding to ldap server
	$ldapbind = ldap_bind($ldapconn, "svcscan@eileenfisher.net", "ef12345")
	  or die("ERROR: LDAP bind failed...");
		
	$dn = "DC=eileenfisher, DC=net";
	//$justthese = array("cn", "member", "distinguishedname", "mail", "showinaddressbook");
	$justthese = array("cn");
	//$filter = "(&(givenname=$search*)(something else))";
	$filter = "(&(objectCategory=group)(samaccounttype=268435457)(cn=\*$s*))";
	//echo $filter;
	$result = ldap_search($ldapconn, $dn, $filter, $justthese)
	  or die("ERROR: Failed to search the AD Tree");
	$entries = ldap_get_entries($ldapconn, $result);
	//print_r($entries);
	if ($entries["count"] == 0)
	{
	   die("No email distributions lists were found with that name");
	}
	$arrResult = array();
	$arrResult['selectID'] = $_GET['selectID'];
	$arrResult['length'] = $entries["count"];

	unset($entries["count"]);
	usort($entries, "cmp");

	for ($i = 0; $i < count($entries); $i++)
	{
		if ($entries[$i]["cn"][0] != "")
		{
			$arrResult[] = $entries[$i]["cn"][0];
		}
	}	
	
	echo json_encode($arrResult);
	ldap_unbind($ldapconn);
}
else
	die("Could not connect to LDAP server.");

?>
