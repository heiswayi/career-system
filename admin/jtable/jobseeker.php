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
		$result = mysql_query("SELECT COUNT(*) AS RecordCount FROM jobseeker;");
		$row = mysql_fetch_array($result);
		$recordCount = $row['RecordCount'];

		//Get records from database
		$result = mysql_query("SELECT * FROM jobseeker ORDER BY " . $_GET["jtSorting"] . " LIMIT " . $_GET["jtStartIndex"] . "," . $_GET["jtPageSize"] . ";");
		
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
		$email = mysql_real_escape_string(htmlspecialchars($_POST['email']));
		$icno = mysql_real_escape_string(htmlspecialchars($_POST['ic_no']));
		$fullname = mysql_real_escape_string(htmlspecialchars($_POST['fullname']));
		$is_usm = $_POST['is_usm'];
		$study_field = mysql_real_escape_string(htmlspecialchars($_POST['study_field']));
		$is_undergrad = $_POST['is_undergrad'];
		$phone = mysql_real_escape_string(htmlspecialchars($_POST['phone']));
		$address = mysql_real_escape_string(htmlspecialchars($_POST['address']));
		$postcode = mysql_real_escape_string(htmlspecialchars($_POST['postcode']));
		$state = $_POST['state'];
		$has_resume = $_POST['has_resume'];
		$resume_path = $_POST['resume_path'];
		$gender = $_POST['gender'];
		$regdate = time();
		$result = mysql_query("INSERT INTO jobseeker (username, password, email, ic_no, fullname, is_usm, study_field, is_undergrad, phone, address, postcode, state, reg_date, has_resume, resume_path, gender) VALUES ('$username', '$password', '$email', '$icno', '$fullname', '$is_usm', '$study_field', '$is_undergrad', '$phone', '$address', '$postcode', '$state', '$regdate', '$has_resume', '$resume_path', '$gender')");
		
		//Get last inserted record (to return to jTable)
		$result = mysql_query("SELECT * FROM jobseeker WHERE id=LAST_INSERT_ID();");
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
		$email = mysql_real_escape_string(htmlspecialchars($_POST['email']));
		$icno = mysql_real_escape_string(htmlspecialchars($_POST['ic_no']));
		$fullname = mysql_real_escape_string(htmlspecialchars($_POST['fullname']));
		$is_usm = $_POST['is_usm'];
		$study_field = mysql_real_escape_string(htmlspecialchars($_POST['study_field']));
		$is_undergrad = $_POST['is_undergrad'];
		$phone = mysql_real_escape_string(htmlspecialchars($_POST['phone']));
		$address = mysql_real_escape_string(htmlspecialchars($_POST['address']));
		$postcode = mysql_real_escape_string(htmlspecialchars($_POST['postcode']));
		$state = $_POST['state'];
		$has_resume = $_POST['has_resume'];
		$resume_path = $_POST['resume_path'];
		$gender = $_POST['gender'];
		$id = $_POST['id'];
		$result = mysql_query("UPDATE jobseeker SET username='$username', password='$password', email='$email', ic_no='$icno', fullname='$fullname', is_usm='$is_usm', study_field='$study_field', is_undergrad='$is_undergrad', phone='$phone', address='$phone', postcode='$postcode', state='$state', has_resume='$has_resume', resume_path='$resume_path', gender='$gender' WHERE id='$id'");

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
		$result = mysql_query("DELETE FROM jobseeker WHERE id='$id'");
		$result2 = mysql_query("DELETE FROM apply_job WHERE jobseeker_id='$id'");

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