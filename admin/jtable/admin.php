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
		$result = mysql_query("SELECT COUNT(*) AS RecordCount FROM admin;");
		$row = mysql_fetch_array($result);
		$recordCount = $row['RecordCount'];

		//Get records from database
		$result = mysql_query("SELECT * FROM admin ORDER BY " . $_GET["jtSorting"] . " LIMIT " . $_GET["jtStartIndex"] . "," . $_GET["jtPageSize"] . ";");
		
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
		$fullname = mysql_real_escape_string(htmlspecialchars($_POST['fullname']));
		$email = mysql_real_escape_string(htmlspecialchars($_POST['email']));
		$phone = mysql_real_escape_string(htmlspecialchars($_POST['phone']));
		$result = mysql_query("INSERT INTO admin (username, password, fullname, email, phone) VALUES ('$username', '$password', '$fullname', '$email', '$phone')");
		
		//Get last inserted record (to return to jTable)
		$result = mysql_query("SELECT * FROM admin WHERE id=LAST_INSERT_ID();");
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
		$fullname = mysql_real_escape_string(htmlspecialchars($_POST['fullname']));
		$email = mysql_real_escape_string(htmlspecialchars($_POST['email']));
		$phone = mysql_real_escape_string(htmlspecialchars($_POST['phone']));
		$id = $_POST['id'];
		$result = mysql_query("UPDATE admin SET username='$username', password='$password', fullname='$fullname', email='$email', phone='$phone' WHERE id='$id'");

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
		$result = mysql_query("DELETE FROM admin WHERE id='$id'");

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