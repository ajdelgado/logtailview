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
include_once("db.inc.php");
function Message($TYPE,$MSG) {
	$DATE=date("r");
	echo "<DIV CLASS='message-" . $TYPE . "'>" . $DATE . " " . $MSG . "</DIV>\n";
}

function GetProgramName($ID) {
	$QUERY="SELECT name FROM programs WHERE crc=" . $ID;
	$REQUEST=MySQLQuery($QUERY);
	if (!$REQUEST) {
		Message("error","Error querying database for program name.<BR>Query: " . $QUERY);
	} else {
		$ROW=mysql_fetch_row($REQUEST);
		return $ROW[0];
	}
}

function GetFacilityName($ID) {
	$QUERY="SELECT name FROM facilities WHERE code=" . $ID;
	$REQUEST=MySQLQuery($QUERY);
	if (!$REQUEST) {
		Message("error","Error querying database for facility name.<BR>Query: " . $QUERY);
	} else {
		$ROW=mysql_fetch_row($REQUEST);
		return $ROW[0];
	}
}

function ColorizeSeverity($SEVERITY_LEVEL) {
	$INV_PERCENT=(9-$SEVERITY_LEVEL)*255/9;
	$PERCENT=$SEVERITY_LEVEL*255/9;
	$COLOR="#" . dechex($INV_PERCENT) . dechex($PERCENT) . "00";
	return $COLOR;
}

function ListPrograms() {
	$QUERY="SELECT crc,name FROM programs";
	$REQUEST=MySQLQuery($QUERY);
	if (!$REQUEST) {
		Message("error","Error querying database for program list.<BR>Query: " . $QUERY);
	} else {
		while ($ROW=mysql_fetch_row($REQUEST)) {
			$REC['id']=$ROW[0];
			$REC['name']=$ROW[1];
			$LIST[]=$REC;
		}
		return $LIST;
	}
}

?>
