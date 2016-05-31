<?php
if (isset($_GET["term"]) and ($_GET["term"] != ""))
	$s = $_GET["term"];
else
	exit("Your search query was empty, please enter something.|\n");

if (isset($_GET["p"]) and ($_GET["p"] != ""))
	$p = $_GET["p"];
else
	exit("The value for 'p' is not valid. Try 'displayname' or 'samaccountname'.|\n");


// connect to ldap server
//$ldapconn = ldap_connect("eileenfisher.net")
$ldapconn = ldap_connect("efadc01.eileenfisher.net")
    or $ldapconn = ldap_connect("eileenfisher.net")
         or die("Could not connect to LDAP server.\n");

if ($ldapconn){
	ldap_set_option($ldapconn, LDAP_OPT_PROTOCOL_VERSION, 3);
	ldap_set_option($ldapconn, LDAP_OPT_REFERRALS, 0);
	ldap_set_option($ldapconn, LDAP_OPT_SIZELIMIT, 1000);
	// binding to ldap server
	$ldapbind = @ldap_bind($ldapconn, "svcscan@eileenfisher.net", "ef12345")
		or die("ERROR: LDAP bind failed...\n");

	$dn = "DC=eileenfisher, DC=net";
	$justthese = array($p, "mail");
	//print_r($justthese);
	//$filter = "(&(givenname=$search*)(something else))";
	$filter = "($p=$s*)";
	//echo $filter;
	$result = ldap_search($ldapconn, $dn, $filter, $justthese)
	  or die("ERROR: Failed to search the AD Tree|\n");
	$entries = ldap_get_entries($ldapconn, $result);
	if ($entries["count"] == 0)
	{
		$result = ldap_search($ldapconn, $dn, "(mail=$s*)", $justthese);
		$entries = ldap_get_entries($ldapconn, $result);
	}
	//print_r($entries);
	$arrResult = array();
	if ($p == "displayname"){
	    for ($i = 0, $j = 0; $i < $entries["count"]; $i++){
	        if (isset($entries[$i]["displayname"][0]) and isset($entries[$i]["mail"][0])){
				$arrResult[$j]['label'] = $entries[$i]["displayname"][0];
				$arrResult[$j]['value'] = $entries[$i]["mail"][0];

		        $j++;
	        }
	    }
	 }elseif ($p == "samaccountname"){
	    for ($i = 0, $j = 0; $i < $entries["count"]; $i++){
	        if (isset($entries[$i]["mail"][0])){
		        $arrResult[$j]['label'] = $entries[$i]["samaccountname"][0];
	            $arrResult[$j]['value'] = $entries[$i]["mail"][0];
		        
		        $j++;
	        }
	    }
	 }
	//print_r($arrResult);

	echo json_encode($arrResult);

	ldap_unbind($ldapconn);
}
else
	die("Could not connect to LDAP server.|\n");

?>
