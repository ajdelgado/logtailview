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
include_once("func.inc.php");

$REFRESH_TIME=$CONFIG['default tail refresh'];
$SEVERITY="";
$PROGRAM="";

//Starting session and initializing data with session data
if (!session_start()) {
	Message("error","Unable to start session for you");
}

if (isset($_SESSION['tail refresh'])) {
	$REFRESH_TIME=$_SESSION['tail refresh'];
} else {
	$_SESSION['tail refresh']=$REFRESH_TIME;
}
if (isset($_SESSION['severity'])) {
	$SEVERITY=$_SESSION['severity'];
} else {
	$_SESSION['severity']=$SEVERITY;
}
if (isset($_SESSION['program'])) {
	$PROGRAM=$_SESSION['program'];
} else {
	$_SESSION['program']=$PROGRAM;
}

//Evaluate incoming data
if (isset($_GET['speedup'])) {
	$REFRESH_TIME=$REFRESH_TIME - $_GET['speedup'];
	if ($REFRESH_TIME<1) {
		$REFRESH_TIME=1;
	}
	$_SESSION['tail refresh']=$REFRESH_TIME;
}

if (isset($_GET['speeddown'])) {
	$REFRESH_TIME=$REFRESH_TIME + $_GET['speeddown'];
	$_SESSION['tail refresh']=$REFRESH_TIME;
}
if (isset($_GET['severity'])) {
	if ($_GET['severity']!="") {
		$SEVERITY="severity " . $_GET['severity'];
		$_SESSION['severity']=$SEVERITY;
	} else {
		$SEVERITY="";
		$_SESSION['severity']=$SEVERITY;
	}
}
$WHERE_CLAUSULE=$SEVERITY;

if (isset($_GET['program'])) {
	if ($_GET['program']=="(Select)" || $_GET['program']=="") {
		$PROGRAM="";
		$_SESSION['program']=$PROGRAM;
	} else {
		$PROGRAM=" program =" . $_GET['program'] ;
		$_SESSION['program']=$PROGRAM;
	}
}
if ($WHERE_CLAUSULE!="" && $PROGRAM != "") {
	$WHERE_CLAUSULE .= " AND ";
}

$WHERE_CLAUSULE .= $PROGRAM;

if ($WHERE_CLAUSULE!="") {
	$WHERE_CLAUSULE=" WHERE " . $WHERE_CLAUSULE;
}

echo "<HTML>
	<HEAD>
		<TITLE>Log tail view</TITLE>
		<META HTTP-EQUIV='Refresh' CONTENT='" . $REFRESH_TIME . "; URL=index.php'>
		<LINK TYPE='text/css' REL='stylesheet' HREF='style.css'>
	</HEAD>
	<BODY>";

//echo "Session " . session_id() . "\n";
echo "<TABLE NAME='actions-table' ID='actions-table'>\n";
echo "<CAPTION>Actions and filters</CAPTION>\n";
echo "<TR>\n";
echo "<TH><A HREF='index.php?speedup=1'>Speed up</A></TH>\n";
echo "<TH><A HREF='index.php?speeddown=1'>Speed down</A></TH>\n";
echo "<TH><FORM NAME='severity-form' METHOD='get' ACTION='index.php'>
Severity<INPUT TYPE='text' NAME='severity' VALUE='" . trim($SEVERITY,"severity ") . "' SIZE='5'><BUTTON>Set</BUTTON>
</FORM></TH>\n";
echo "<TH><FORM NAME='program-form' METHOD='get' ACTION='index.php'>
Program:<SELECT NAME='program' OnChange='document.program-form.submit()'>
<OPTION VALUE='(Select)'>(Select)</OPTION>";
foreach (ListPrograms() as $LPROGRAM) {
	if ($LPROGRAM['id']==rtrim(ltrim($PROGRAM," program='"),"'")) {
		$SELECTED="selected ";
	} else {
		$SELECTED="";
	}
	echo "<OPTION " . $SELECTED . "VALUE='" . $LPROGRAM['id'] . "'>" . $LPROGRAM['name'] . "</OPTION>\n";
}
echo "</SELECT>
</FORM></TH>\n";
echo "</TR>
</TABLE>\n";

$QUERY="SELECT id FROM " . $CONFIG['log table'] . $WHERE_CLAUSULE;
$REQUEST=MySQLQuery($QUERY);
if (!$REQUEST) {
	Message("error","Error querying database for number of entries.<BR>Query: " . $QUERY);
} else {
	$COUNT=mysql_num_rows($REQUEST);
	if ($COUNT<1) {
		Message("info","There are no log entries to show.");
		echo $QUERY;
	} else {
		
		echo "<TABLE ID='log-table' CLASS='log-table'>\n";
		echo "<CAPTION CLASS='log-table-caption'>\n";
		echo "Showing last " . $CONFIG['tail message count'] . " records from a total of " . $COUNT . "\n";
		echo "</CAPTION>\n";
		echo "Reloading in " . $REFRESH_TIME . " seconds.\n";
		echo "<TR CLASS='log-table-row'>
	<TH CLASS='log-table-header-cell'>Date time</TH>
	<TH CLASS='log-table-header-cell'>Id</TH>
	<TH CLASS='log-table-header-cell'>Host</TH>
	<TH CLASS='log-table-header-cell'>Facility</TH>
	<TH CLASS='log-table-header-cell'>Sevirity</TH>
	<TH CLASS='log-table-header-cell'>Program</TH>
	<TH CLASS='log-table-header-cell'>Message</TH>
</TR>\n";
		if ($COUNT>$CONFIG['tail message count']+1) {
			$FIRST=$COUNT - $CONFIG['tail message count'];
			$QUERY2="SELECT * FROM " . $CONFIG['log table'] . $WHERE_CLAUSULE . " LIMIT " . $FIRST . ", " . $CONFIG['tail message count'];
		} else {
			$QUERY2="SELECT * FROM " . $CONFIG['log table'] . $WHERE_CLAUSULE;
		}
		$REQUEST2=MySQLQuery($QUERY2);
		if (!$REQUEST2) {
			Message("error","Error querying database for the last entries.<BR>Query: " . $QUERY2);
		} else {
			while ($ROW=mysql_fetch_row($REQUEST2)) {
				echo "<TR CLASS='log-table-row'>\n";
				echo "<TD CLASS='log-table-data-cell'>" . $ROW[9] . "</TD>\n";
				echo "<TD CLASS='log-table-data-cell'>" . $ROW[0] . "</TD>\n";
				echo "<TD CLASS='log-table-data-cell'>" . $ROW[1] . "</TD>\n";
				echo "<TD CLASS='log-table-data-cell'>" . GetFacilityName($ROW[2]) . "(" . $ROW[2] . ")</TD>\n";
				echo "<TD CLASS='log-table-data-cell' STYLE='background-color:" . ColorizeSeverity($ROW[3]) . ";'>" . $ROW[3] . "</TD>\n";
				echo "<TD CLASS='log-table-data-cell'>" . GetProgramName($ROW[4]) . "</TD>\n";
				echo "<TD CLASS='log-table-data-cell' STYLE='background-color:" . ColorizeSeverity($ROW[3]) . ";'>" . $ROW[5] . "</TD>\n";
				echo "</TR>\n";
			}
		}
	}
}
echo "</TABLE>\n";
echo "Antonio J. Delgado 2010\n";
echo "	</BODY>
</HTML>";
?>
