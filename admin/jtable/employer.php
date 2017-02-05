<?php

require_once '../../includes/config.php';
require_once '../../includes/functions.php';

try
{
	//Open database connection
	$con = mysql_connect(DB_SERVER,DB_USERNAME,DB_PASSWORD);
	mysql_select_db(DB_NAME, $con);

	//Getting records (listAction)
	if($_GET["action"] == "list")
	{
		//Get record count
		$result = mysql_query("SELECT COUNT(*) AS RecordCount FROM employer;");
		$row = mysql_fetch_array($result);
		$recordCount = $row['RecordCount'];

		//Get records from database
		$result = mysql_query("SELECT * FROM employer ORDER BY " . $_GET["jtSorting"] . " LIMIT " . $_GET["jtStartIndex"] . "," . $_GET["jtPageSize"] . ";");
		
		//Add all records to an array
		$rows = array();
		while($row = mysql_fetch_array($result))
		{
		    $rows[] = $row;
		}

		//Return result to jTable
		$jTableResult = array();
		$jTableResult['Result'] = "OK";
		$jTableResult['TotalRecordCount'] = $recordCount;
		$jTableResult['Records'] = $rows;
		print json_encode($jTableResult);
	}
	//Creating a new record (createAction)
	else if($_GET["action"] == "create")
	{
		//Insert record into database
		$username = mysql_real_escape_string(htmlspecialchars($_POST['username']));
		$password = mysql_real_escape_string(htmlspecialchars($_POST['password']));
		$pname = mysql_real_escape_string(htmlspecialchars($_POST['person_name']));
		$department = mysql_real_escape_string(htmlspecialchars($_POST['department']));
		$cname = mysql_real_escape_string(htmlspecialchars($_POST['company_name']));
		$pprefix = mysql_real_escape_string(htmlspecialchars($_POST['person_prefix']));
		$clogo = mysql_real_escape_string(htmlspecialchars($_POST['company_logo_url']));
		$cweb = mysql_real_escape_string(htmlspecialchars($_POST['company_website']));
		$cinfo = mysql_real_escape_string(htmlspecialchars($_POST['company_info']));
		$phone = mysql_real_escape_string(htmlspecialchars($_POST['phone']));
		$mobile = mysql_real_escape_string(htmlspecialchars($_POST['mobile']));
		$email = mysql_real_escape_string(htmlspecialchars($_POST['email']));
		$is_sponsor = $_POST['is_sponsor'];
		$sponsor_type = mysql_real_escape_string(htmlspecialchars($_POST['sponsor_type']));
		$is_avail = $_POST['is_available'];
		$ctag = mysql_real_escape_string(htmlspecialchars($_POST['company_tag']));
		
		$result = mysql_query("INSERT INTO employer (username, password, person_name, department, company_name, person_prefix, company_logo_url, company_website, company_info, phone, mobile, email, is_sponsor, sponsor_type, is_available, company_tag) VALUES ('$username', '$password', '$pname', '$department', '$cname', '$pprefix', '$clogo', '$cweb', '$cinfo', '$phone', '$mobile', '$email', '$is_sponsor', '$sponsor_type', '$is_avail', '$ctag')");
		
		//Get last inserted record (to return to jTable)
		$result = mysql_query("SELECT * FROM employer WHERE id=LAST_INSERT_ID();");
		$row = mysql_fetch_array($result);

		//Return result to jTable
		$jTableResult = array();
		$jTableResult['Result'] = "OK";
		$jTableResult['Record'] = $row;
		print json_encode($jTableResult);
	}
	//Updating a record (updateAction)
	else if($_GET["action"] == "update")
	{
		//Update record in database
		$username = mysql_real_escape_string(htmlspecialchars($_POST['username']));
		$password = mysql_real_escape_string(htmlspecialchars($_POST['password']));
		$pname = mysql_real_escape_string(htmlspecialchars($_POST['person_name']));
		$department = mysql_real_escape_string(htmlspecialchars($_POST['department']));
		$cname = mysql_real_escape_string(htmlspecialchars($_POST['company_name']));
		$pprefix = mysql_real_escape_string(htmlspecialchars($_POST['person_prefix']));
		$clogo = mysql_real_escape_string(htmlspecialchars($_POST['company_logo_url']));
		$cweb = mysql_real_escape_string(htmlspecialchars($_POST['company_website']));
		$cinfo = mysql_real_escape_string(htmlspecialchars($_POST['company_info']));
		$phone = mysql_real_escape_string(htmlspecialchars($_POST['phone']));
		$mobile = mysql_real_escape_string(htmlspecialchars($_POST['mobile']));
		$email = mysql_real_escape_string(htmlspecialchars($_POST['email']));
		$is_sponsor = $_POST['is_sponsor'];
		$sponsor_type = mysql_real_escape_string(htmlspecialchars($_POST['sponsor_type']));
		$is_avail = $_POST['is_available'];
		$ctag = mysql_real_escape_string(htmlspecialchars($_POST['company_tag']));
		$id = $_POST['id'];
		
		$result = mysql_query("UPDATE employer SET username='$username', password='$password', company_name='$cname', company_info='$cinfo', company_website='$cweb', company_logo_url='$clogo', company_tag='$ctag', person_prefix='$pprefix', person_name='$pname', phone='$phone', mobile='$mobile', email='$email', department='$department', is_sponsor='$is_sponsor', sponsor_type='$sponsor_type', is_available='$is_avail' WHERE id='$id'");

		//Return result to jTable
		$jTableResult = array();
		$jTableResult['Result'] = "OK";
		print json_encode($jTableResult);
	}
	//Deleting a record (deleteAction)
	else if($_GET["action"] == "delete")
	{
		//Delete from database
		$id = $_POST['id'];
		$result = mysql_query("DELETE FROM employer WHERE id='$id'");

		//Return result to jTable
		$jTableResult = array();
		$jTableResult['Result'] = "OK";
		print json_encode($jTableResult);
	}

	//Close database connection
	mysql_close($con);

}
catch(Exception $ex)
{
    //Return error message
	$jTableResult = array();
	$jTableResult['Result'] = "ERROR";
	$jTableResult['Message'] = $ex->getMessage();
	print json_encode($jTableResult);
}
	
?>