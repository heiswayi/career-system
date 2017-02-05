<?php

if (session_id() == '') { session_start(); }
else { session_regenerate_id(); }

if (!defined('DIR')) {	define('DIR', './'); }

require_once DIR . 'includes/static_structure.php';
require_once DIR . 'includes/config.php';
require_once DIR . 'includes/database.class.php';

function _userExist($username, $group) {
  $initDB = new hnSQL(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME, false);
  $query = $initDB->query("SELECT * FROM $group WHERE username='$username'");
  $row = $initDB->num($query);
  if ($row > 0) { return true; }
  else { return false; }
  $initDB->close();
}

// check if user is logged in
if (isset($_SESSION['user_login']) && isset($_SESSION['user_group'])) {
  // check if username is exist in database
  if(!_userExist($_SESSION['user_login'], 'jobseeker')) {
    header('Location: index.php');
  }
} else {
  header('Location: index.php');   
}

$js_username = $_SESSION['user_login'];
$db = new hnSQL(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME, false);
$query_jobseeker = $db->query("SELECT id FROM jobseeker WHERE username='$js_username'");
$get_param = $db->fetch_array($query_jobseeker);
$current_userID = $get_param['id'];

$sameAsID=true; // save file same as user id

// Max size PER file in KB
$max_file_size="5120";
// Max size for all files COMBINED in KB
$max_combined_size="10240";
//Maximum file uploades at one time
$file_uploads="1";
// Path to store files on your server If this fails use $fullpath below. With trailing slash.
$folder="./resume_repo/";
// Use random file names? true=yes (recommended), false=use original file name.
// Random names will help prevent files being denied because a file with that name already exists.
$random_name=false;
// Types of files that are acceptiable for uploading. Keep the array structure.
$allow_types=array("pdf");
// Only use this variable if you wish to use full server paths. Otherwise leave this empty. With trailing slash.
$fullpath="";
//Use this only if you want to password protect your upload form.
$password=""; 

function format_size($bytes) {
        if ($bytes >= 1073741824) {
            $bytes = number_format($bytes / 1073741824, 2) . ' GB';
        } else if ($bytes >= 1048576) {
            $bytes = number_format($bytes / 1048576, 2) . ' MB';
        } elseif ($bytes >= 1024) {
            $bytes = number_format($bytes / 1024, 2) . ' KB';
        } elseif ($bytes > 1) {
            $bytes = $bytes . ' bytes';
        } elseif ($bytes == 1) {
            $bytes = $bytes . ' byte';
        } else {
            $bytes = '0 bytes';
        }
        return $bytes;
}

// Initialize variables
$password_hash=md5($password);
$error="";
$success="";
$display_message="";
$file_ext=array();
$password_form="";

// Function to get the extension a file.
function get_ext($key) { 
	$key=strtolower(substr(strrchr($key, "."), 1));
	$key=str_replace("jpeg","jpg",$key);
	return $key;
}

// Filename security cleaning. Do not modify.
function cln_file_name($string) {
	$cln_filename_find=array("/\.[^\.]+$/", "/[^\d\w\s-]/", "/\s\s+/", "/[-]+/", "/[_]+/");
	$cln_filename_repl=array("", ""," ", "-", "_");
	$string=preg_replace($cln_filename_find, $cln_filename_repl, $string);
	return trim($string);
}

// If a password is set, they must login to upload files.
If($password) {
	
	//Verify the credentials.
	If($_POST['verify_password']==true) {
		If(md5($_POST['check_password'])==$password_hash) {
			setcookie("CSRU",$password_hash);
			sleep(1); //seems to help some people.
			header("Location: http://".$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']);
			exit;
		}
	}

	//Show the authentication form
	If($_COOKIE['CSRU']!=$password_hash) {
    $password_form='<form class="form-horizontal upload-form" style="margin:20px" method="post" action="'.$_SERVER['PHP_SELF'].'">';
    $password_form.='<div class="control-group">';
    $password_form.='<label class="control-label" for="upload">Password required:</label>';
    $password_form.='<div class="controls control-row">';
    $password_form.='<input type="password" name="check_password" placeholder="Enter password to verify">';
    $password_form.='</div>';
    $password_form.='</div>';
    $password_form.='<div class="form-actions">';
    $password_form.='<input type="hidden" name="verify_password" value="true" />';
    $password_form.='<button type="submit" class="btn btn-primary">Verify Password</button>';
    $password_form.='</div>';
    $password_form.='</form>';
	}
	
} // If Password

// Dont allow submit if $password_form has been populated
If(($_POST['submit']==true) && ($password_form=="")) {

	//Tally the size of all the files uploaded, check if it's over the ammount.	
	If(array_sum($_FILES['file']['size']) > $max_combined_size*1024) {
		
		$error.='<div class="alert alert-error"><b>FAILED:</b> All Files <b>REASON:</b> Combined file size is to large.</div>';
		
	// Loop though, verify and upload files.
	} Else {

		// Loop through all the files.
		For($i=0; $i <= $file_uploads-1; $i++) {
			
			// If a file actually exists in this key
			If($_FILES['file']['name'][$i]) {

				//Get the file extension
				$file_ext[$i]=get_ext($_FILES['file']['name'][$i]);
				
				// Randomize file names
				/*
				If($random_name){
					$file_name[$i]=time()+rand(0,100000);
				} Else {
					$file_name[$i]=cln_file_name($_FILES['file']['name'][$i]);
				}
				*/
				
				if($sameAsID){
          $file_name[$i]=$current_userID;
        } else {
					$file_name[$i]=cln_file_name($_FILES['file']['name'][$i]);
				}
	
				// Check for blank file name
				If(str_replace(" ", "", $file_name[$i])=="") {
					
					$error.= '<div class="alert alert-error"><b>FAILED:</b> '.$_FILES['file']['name'][$i].' <b>REASON:</b> Blank file name detected.</div>';
				
				//Check if the file type uploaded is a valid file type. 
				}	Else If(!in_array($file_ext[$i], $allow_types)) {
								
					$error.= '<div class="alert alert-error"><b>FAILED:</b> '.$_FILES['file']['name'][$i].' <b>REASON:</b> Invalide file type.</div>';
								
				//Check the size of each file
				} Else if($_FILES['file']['size'][$i] > ($max_file_size*1024)) {
					
					$error.= '<div class="alert alert-error"><b>FAILED:</b> '.$_FILES['file']['name'][$i].' <b>REASON:</b> File to large.</div>';
					
				// Check if the file already exists on the server..
				} Else if(file_exists($folder.$file_name[$i].".".$file_ext[$i])) {
	
					//$error.= '<div class="alert alert-error"><b>FAILED:</b> '.$_FILES['file']['name'][$i].' <b>REASON:</b> File already exists.</div>';
					
					unlink($folder.$file_name[$i].".".$file_ext[$i]);
					
					If(move_uploaded_file($_FILES['file']['tmp_name'][$i],$folder.$file_name[$i].".".$file_ext[$i])) {
						
						$success.='<div class="alert alert-success"><b>SUCCESS:</b> '.$_FILES['file']['name'][$i].' has been uploaded and overwritten.</div>';
						
					} Else {
						$error.='<div class="alert alert-error"><b>FAILED:</b> '.$_FILES['file']['name'][$i].' <b>REASON:</b> General upload failure.</div>';
					}
					
				} Else {
					
					If(move_uploaded_file($_FILES['file']['tmp_name'][$i],$folder.$file_name[$i].".".$file_ext[$i])) {
						
						$file_path = $folder.$file_name[$i].".".$file_ext[$i];
						$db->query("UPDATE jobseeker SET has_resume='1', resume_path='$file_path' WHERE id='$current_userID'");
						$success.='<div class="alert alert-success"><b>SUCCESS:</b> '.$_FILES['file']['name'][$i].' has been uploaded.</div>';
						
					} Else {
						$error.='<div class="alert alert-error"><b>FAILED:</b> '.$_FILES['file']['name'][$i].' <b>REASON:</b> General upload failure.</div>';
					}
					
				}
							
			} // If Files
		
		} // For
		
	} // Else Total Size
	
	If(($error=="") && ($success=="")) {
		$error.='<div class="alert alert-error"><b>FAILED:</b> No files selected</div>';
	}

	$display_message=$success.$error;

} // $_POST AND !$password_form

?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <?php html_head_meta(); ?>
    <?php html_head_css(); ?>
    <?php html_head_js(); ?>
  </head>

  <body>

    <?php html_body_topmenu(); ?>
    <?php html_body_header('Upload Resume'); ?>

    <div class="container">

      <div class="row">
      
      <div class="span9">
          
          <div class="box" style="background:#f8f8f8">
          <h4 class="box-title" style="background:#eee"><i class="icon-pencil muted"></i> Upload Resume</h4>
          
<?php
if ($password_form) {
	
	echo $password_form;

} else {
?>
          
<form class="form-horizontal upload-form" style="margin:20px" enctype="multipart/form-data" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
  
  <div class="control-group">
    <div class="controls control-row">
      <div class="alert" style="margin:0">
      <ul>
      <li><span class="label label-info">Note!</span> Your resume file is available to all employers.</li>
      <li>Allowed file type: <strong><?php echo implode($allow_types, ", ");?></strong></li>
      <li>Max. size per file: <strong><?php echo format_size($max_file_size*1024); ?></strong></li>
      <li>Max. size for all files combined: <strong><?php echo format_size($max_combined_size*1024); ?></strong></li>
      <li>If your resume file already exists, it will be automatically overwritten.</li>
<?php
$query_status = $db->query("SELECT has_resume,resume_path FROM jobseeker WHERE id='$current_userID'");
$row = $db->fetch_array($query_status);
$has_resume = $row['has_resume'];
$resume_path =$row['resume_path'];
if ($has_resume == '1') { echo '<li><strong>CHECK STATUS: <span class="hn-color-green">FILE ALREADY EXISTS</span></strong> <a href="dl.php?uid='.$current_userID.'"><i class="icon-download-alt hn-color-purple tip-top" title="Download resume/CV"></i></a></li>'; }
else { echo '<li><strong>CHECK STATUS: <span class="hn-color-red">NOT UPLOAD YET</span></strong></li>'; }
?>
      </ul>
      </div>
    </div>
  </div>
  
<?php if ($display_message) { ?>
	<div class="control-group">
    <div class="controls control-row">
      <?php echo $display_message; ?>
    </div>
  </div>
<?php } ?>

<?php for($i=0;$i <= $file_uploads-1;$i++) { ?>
  <div class="control-group">
    <label class="control-label" for="upload">Upload file:</label>
    <div class="controls control-row">
      <input type="file" name="file[]" />
    </div>
  </div>
<?php } ?>
  
  <div class="form-actions">
    <input type="hidden" name="submit" value="true" />
    <button type="submit" class="btn btn-primary">Upload File</button>
  </div>
  
</form>

<?php } ?>

          </div>
          
        </div><!-- /.span4 -->

       
        <div class="span3">
        
        <?php usermenu_jobseeker(''); ?>
          
        </div><!-- /.span3 -->
        
      </div><!-- /.row -->

      <?php html_body_footer(); ?>

    </div> <!-- /container -->

    <?php html_body_footer_js(); ?>

  </body>
</html>