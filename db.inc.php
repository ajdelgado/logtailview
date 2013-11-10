<?php
/*
 *  Log tail view
 * Show the last messages in the log table
 * 
 * Antonio J. Delgado 2010 
 * Licensed under General Public License v3
 * 
 */
include_once("config.inc.php");
$DB_SERVER=$CONFIG['dbserver'];
$DB_NAME=$CONFIG['dbname'];
$DB_USER=$CONFIG['dbuser'];
$DB_PASS=$CONFIG['dbpass'];

Function MySQLConnect() {
	global $DB_SERVER,$DB_USER,$DB_PASS,$DB_NAME;
	$LINK = mysql_connect($DB_SERVER, $DB_USER, $DB_PASS);
	if (!$LINK) {
		MySQLError("When connecting to {$DB_SERVER}.");
		}
	else {
		if (!mysql_select_db($DB_NAME,$LINK)) {
			MySQLError("when selecting database {$DB_NAME}.");
			}
		else {
				return $LINK;	
		}
	}		
}
Function MySQLQuery($QUERY) {
	$LINK=MySQLConnect();
	$RESULT = mysql_query($QUERY);
	if (!$RESULT) {
		MySQLError("While sending the query <i>{$QUERY}</i>.");
		return False;
	} else {
		return $RESULT;
	}
	mysql_close($LINK);
}
Function MySQLError($SITUATION) {
	global $DB_SERVER;
	echo "<div lang='es' id='Warning'>";
	echo "Error while talking to server '" . $DB_SERVER . "'.<br>";
	echo "Error #: " . mysql_errno() . ".<br>";
	switch (mysql_errno()) {
		case 1045:
			$ERROR_DESCRIPTION="Unauthorized";
			$ADVICE="Send correct user and password, please.";
			break;
		case 1044:
			$ERROR_DESCRIPTION="Access prohibited";
			$ADVICE="Send correct user and password, please.";
			break;
		case 1049:
			$ERROR_DESCRIPTION="Database don't exists";
			$ADVICE="<a href='./install.php'>Install</a> the database";
			break;
		case 1146:
			$ERROR_DESCRIPTION="Table don't exists (" . mysql_error();
			$ADVICE="<a href='./install.php'>Install</a> the database";
			break;
		default:
			$ERROR_DESCRIPTION=mysql_error();
			$ADVICE="No advice";
	}
	echo "Situation: {$SITUATION}<br>";
	echo "Description: {$ERROR_DESCRIPTION}<br>";
	echo "Advice: {$ADVICE}";
	echo "</div>";
	end;
}
?>
