<?php

function clickable_url($ret){
  $ret = preg_replace("#(^|[\n ])([\w]+?://[\w]+[^ \"\n\r\t< ]*)#", "\\1<a href=\"\\2\" target=\"_blank\">\\2</a>", $ret);
  $ret = preg_replace("#(^|[\n ])((www|ftp)\.[^ \"\t\n\r< ]*)#", "\\1<a href=\"http://\\2\" target=\"_blank\">http://\\2</a>", $ret);
  return $ret;
}

function random_key($len, $readable = false, $hash = false){
	$key = '';

	if ($hash)
		$key = substr(sha1(uniqid(rand(), true)), 0, $len);
	else if ($readable)
	{
		$chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';

		for ($i = 0; $i < $len; ++$i)
			$key .= substr($chars, (mt_rand() % strlen($chars)), 1);
	}
	else
		for ($i = 0; $i < $len; ++$i)
			$key .= chr(mt_rand(33, 126));

	return $key;
}

function filename_slug($string, $force_lowercase = true, $anal = false) {
    $strip = array("~", "`", "!", "@", "#", "$", "%", "^", "&", "*", "(", ")", "_", "=", "+", "[", "{", "]",
                   "}", "\\", "|", ";", ":", "\"", "'", "&#8216;", "&#8217;", "&#8220;", "&#8221;", "&#8211;", "&#8212;",
                   "—", "–", ",", "<", ".", ">", "/", "?");
    $clean = trim(str_replace($strip, "", strip_tags($string)));
    $clean = preg_replace('/\s+/', "-", $clean);
    $clean = ($anal) ? preg_replace("/[^a-zA-Z0-9]/", "", $clean) : $clean ;
    return ($force_lowercase) ?
        (function_exists('mb_strtolower')) ?
            mb_strtolower($clean, 'UTF-8') :
            strtolower($clean) :
        $clean;
}

// Usage example: echo encryptDecrypt('password', 'encrypt-decrypt this',0);
function encrypt_decrypt($key, $string, $decrypt){
    if($decrypt)
    {
        $decrypted = rtrim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, md5($key), base64_decode($string), MCRYPT_MODE_CBC, md5(md5($key))), "\0");
        return $decrypted;
    }else{
        $encrypted = base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, md5($key), $string, MCRYPT_MODE_CBC, md5(md5($key))));
        return $encrypted;
    }
}

function get_extension($filename){
  $myext = substr($filename, strrpos($filename, '.'));
  return str_replace('.','',$myext);
}

// Usage example: $thefile = filesize('test_file.mp3'); echo format_size($thefile);
function format_size($size) {
      $sizes = array(" Bytes", " KB", " MB", " GB", " TB", " PB", " EB", " ZB", " YB");
      if ($size == 0) { return('n/a'); } else {
      return (round($size/pow(1024, ($i = floor(log($size, 1024)))), 2) . $sizes[$i]); }
}

/*
Usage example:
$email_args = array(
'from'=>'my_email@testserver.com <mr. Sender>',
'to' =>'test_recipient@testgmail.com <camila>, test_recipient2@testgmail.com <anderson>',
'cc' =>'test_cc123_recipient@testgmail.com <christopher>, test_cc321_recipient2@testgmail.com <francisca>',
'subject' =>'This is my Subject Line',
'message' =>'<b style="color:red;">This</b> is my <b>HTML</b> message. <br />This message will be sent using <b style="color:green;">PHP mail</b>.',
);

if(php_html_email($email_args)){
echo 'Mail Sent';
}
*/
function php_html_email($email_args){
    $headers  = 'MIME-Version: 1.0' . "rn";
    $headers .= 'Content-type: text/html; charset=UTF-8' . "rn";
    $headers .=  'To:'.$email_args['to'] . "rn";
    $headers .=  'From:'.$email_args['from'] . "rn";
    if(!empty($email_args['cc'])){$headers .= 'Cc:'.$email_args['cc'] . "rn";}
    $message_body = '<html><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8" />';
    $message_body .= '<title>'.$email_args["subject"].'</title>';
    $message_body .= '</head><body>';
    $message_body .= $email_args["message"];
    $message_body .= '</body></html>';
    if(@mail($email_args['to'], $email_args['subject'], $message_body, $headers))
    {
        return true;
    }else{
        return false;
    }
}

function autolink($message, $strip_tags = false){
    if ($strip_tags) { $message = strip_tags($message); }
    
    //Convert all urls to links
    $message = preg_replace('#([s|^])(www)#i', '$1http://$2', $message);
    $pattern = '#((http|https|ftp|telnet|news|gopher|file|wais)://[^s]+)#i';
    $replacement = '<a href="$1" target="_blank">$1</a>';
    $message = preg_replace($pattern, $replacement, $message);

    /* Convert all E-mail matches to appropriate HTML links */
    $pattern = '#([0-9a-z]([-_.]?[0-9a-z])*@[0-9a-z]([-.]?[0-9a-z])*\.';
    $pattern .= '[a-wyz][a-z](fo|g|l|m|mes|o|op|pa|ro|seum|t|u|v|z)?)#i';
    $replacement = '<a href="mailto:\1">\1</a>';
    $message = preg_replace($pattern, $replacement, $message);
    return $message;
}

function curPageURL(){
 $pageURL = 'http';
 if (!empty($_SERVER['HTTPS'])) {$pageURL .= "s";}
 $pageURL .= "://";
 if ($_SERVER["SERVER_PORT"] != "80") {
  $pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
 } else {
  $pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
 }
 return $pageURL;
}

function truncate($text, $length = 0){
        if ($length > 0 && strlen($text) > $length) // Truncate the item text if it is too long.
        {
                $tmp = substr($text, 0, $length); // Find the first space within the allowed length.
                $tmp = substr($tmp, 0, strrpos($tmp, ' '));
                if (strlen($tmp) >= $length - 3) { // If we don't have 3 characters of room, go to the second space within the limit.
                        $tmp = substr($tmp, 0, strrpos($tmp, ' '));
                }
                $text = $tmp.'...';
        }
        return $text;
}

function generate_password($length=9, $strength=0) {
	$vowels = 'aeuy';
	$consonants = 'bdghjmnpqrstvz';
	if ($strength >= 1) {
		$consonants .= 'BDGHJLMNPQRSTVWXZ';
	}
	if ($strength >= 2) {
		$vowels .= "AEUY";
	}
	if ($strength >= 4) {
		$consonants .= '23456789';
	}
	if ($strength >= 8 ) {
		$vowels .= '@#$%';
	}

	$password = '';
	$alt = time() % 2;
	for ($i = 0; $i < $length; $i++) {
		if ($alt == 1) {
			$password .= $consonants[(rand() % strlen($consonants))];
			$alt = 0;
		} else {
			$password .= $vowels[(rand() % strlen($vowels))];
			$alt = 1;
		}
	}
	return $password;
}

function htmlencode_string($str){
	return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}

// Trim whitespace including non-breaking space
function trim_string($str, $charlist = " \t\n\r\0\x0B\xC2\xA0"){
	return utf8_trim($str, $charlist);
}

// Convert \r\n and \r to \n
function linebreak_string($str){
	return str_replace(array("\r\n", "\r"), "\n", $str);
}

function username($str) {
  return preg_match('/^[a-zA-Z0-9_]+$/', $str);
}

function standard_chars($str) {
  return preg_match('/^[a-zA-Z0-9_\-\.\s]+$/', $str);
}

function address($str) {
  return nl2br(stripslashes(rtrim($str)));
}

function normalize($str) {
  return stripslashes(rtrim($str));
}

?>