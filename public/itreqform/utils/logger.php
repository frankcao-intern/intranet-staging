<?php
/**
 * Created by: cravelo
 * Date: 11/7/11
 * Time: 10:20 AM
 */
 
function write_log($message){
	$f = fopen("../logs/".date('Y-m-d').'.log', 'a+');
	fwrite($f, date('H-i-s - ').$message."\r\n");
	fclose($f);
}
