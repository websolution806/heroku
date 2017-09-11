<?php
$actual_link =  "http://" . $_SERVER['SERVER_NAME'];
//echo $actual_link;

$file_name = 'contacts.csv';
$file_url = 'http://" . $_SERVER["SERVER_NAME"]' . $file_name;
header('Content-type: text/csv');
//header("Content-Transfer-Encoding: Binary"); 
header("Content-disposition: attachment; filename=\"".$file_name."\""); 
readfile($file_url);
exit;
?>
