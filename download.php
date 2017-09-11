<?php
$actual_link =  "http://" . $_SERVER['SERVER_NAME'];
//echo $actual_link;

$file_name = 'contacts.csv';
$file_url = 'https://instagram-profiles.herokuapp.com/download.php' . $file_name;
header('Content-type: text/csv');
//header("Content-Transfer-Encoding: Binary"); 
header("Content-disposition: attachment; filename=\"".$file_name."\""); 
readfile($file_url);
exit;
?>
