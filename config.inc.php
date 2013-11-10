<?php
/*
 *  Log tail view
 * Show the last messages in the log table
 * 
 * Antonio J. Delgado 2010 
 * Licensed under General Public License v3
 * 
 */
$CONFIG['dbserver']="localhost";
$CONFIG['dbname']="syslog";
$CONFIG['dbuser']="dbuser";
$CONFIG['dbpass']="dbpass";

$CONFIG['tail message count']=30;
$CONFIG['log table']="logs";
$CONFIG['default tail refresh']=5;
?>
