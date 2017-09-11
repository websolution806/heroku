<?php
$actual_link =  "http://" . $_SERVER['SERVER_NAME'];
//echo $actual_link;
function get_user_data($username){
	$url = "https://www.instagram.com/$username/?__a=1";
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_URL,$url);
	$result=curl_exec($ch);
	return json_decode($result,true);	
}



$files = glob('upload/*'); //get all file names
foreach($files as $file){
    if(is_file($file))
    unlink($file); //delete file
}
if(isset($_POST['submit'])) {
	$username = $_POST['username'];
	$contentq = get_user_data($username);
	$user_name = $contentq['user']['username'];		
	$user_full_name= $contentq ['user']['full_name'];	
	$porfile_pic_url = $contentq['user']['profile_pic_url']; 	
	$followed_by= $contentq['user']['followed_by']['count']; 	
	$follows = $contentq['user']['follows']['count']; 	
	$profile_hd_url = $contentq['user']['profile_pic_url_hd']; 
	
	
	if($user_name != ""){
		$list = array($user_name.','.$user_full_name.','.$porfile_pic_url.','.$followed_by.','.$follows.','.$profile_hd_url);
		$file = fopen("contacts.csv","w");
		fputcsv($file,explode(',','username,full_name,profile_pic_url,followed_by,follows_count,profile_pic_url_hd'));
		foreach ($list as $line){
			fputcsv($file,explode(',',$line));
		}
		 $single_user_result = '<a href="'.$actual_link.'/download.php">Download CSV</a>';
	}else{
		echo "User not found";
	}
}
if(isset($_POST["upload_file"]) ) {
	$files = glob('upload/*'); //get all file names
	foreach($files as $file){
		if(is_file($file))
		unlink($file); //delete file
	}
   if(isset($_FILES["file"])) {
            //if there was an error uploading the file
        if ($_FILES["file"]["error"] > 0) {
            echo "Return Code: " . $_FILES["file"]["error"] . "<br />";
        }else{
             //Print file details
             //echo "Upload: " . $_FILES["file"]["name"] . "<br />";
             //echo "Type: " . $_FILES["file"]["type"] . "<br />";
             //echo "Size: " . ($_FILES["file"]["size"] / 1024) . " Kb<br />";
             //echo "Temp file: " . $_FILES["file"]["tmp_name"] . "<br />";
			
			$storagename = "users_list.csv";
            //if file already exists
            if (file_exists("upload/" . $storagename)) {
				echo $_FILES["file"]["name"] . " already exists. ";
            }else{
                //Store file in directory "upload" with the name of "uploaded_file.txt"
				move_uploaded_file($_FILES["file"]["tmp_name"], "upload/" . $storagename);
				//echo "Stored in: " . "upload/" . $_FILES["file"]["name"] . "<br />"; 
				
				
				$file = fopen("upload/users_list.csv","r");
				//print_r(fgetcsv($file));
				
				$users_file = fopen("contacts.csv","w");
				
				fputcsv($users_file, explode(',','username,full_name,profile_pic_url,followed_by,follows_count,profile_pic_url_hd_'));
				fclose($users_file);
				while(!feof($file))
				  {	
					$userName = fgetcsv($file);
					if($userName[0] != ""){ 
						$contents = get_user_data($userName[0]); 
						$user_name = $contents['user']['username'];	
						if($user_name == ""){
							$user_name = $userName[0];
						}
						$user_full_name= $contents ['user']['full_name'];
						if($user_full_name == ""){
							$user_full_name = "not found";
						}						
						$porfile_pic_url = $contents['user']['profile_pic_url']; 
						if($porfile_pic_url == ""){
							$porfile_pic_url = "not found";
						}						
						$followed_by = $contents['user']['followed_by']['count']; 
						if($followed_by == ""){
							$followed_by = "not found";
						}						
						$follows = $contents['user']['follows']['count'];
						if($follows == ""){
							$follows = "not found";
						}						
						$profile_hd_url = $contents['user']['profile_pic_url_hd']; 
						if($profile_hd_url == ""){
							$profile_hd_url = "not found";
						}
						//echo $user_name.' '.$user_full_name.'<br>';
						
						$list = $user_name.','.$user_full_name.','.$porfile_pic_url.','.$followed_by.','.$follows.','.$profile_hd_url;
						//echo $list."<br>";
						$users_file = fopen("contacts.csv","a");
						fputcsv($users_file, explode(',',$list));
						fclose($users_file);
					}
  
				}
							
				fclose($file);
				$multi_user = '<a class="link" href="'.$actual_link.'/download.php">Download CSV</a>';
            }
        }
    } else {
             echo "No file selected <br />";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</head>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<div class="container">  
	<div id="loginbox" style="margin-top:50px;" class="mainbox col-md-6 col-md-offset-1 col-sm-8 col-sm-offset-2">     
        <div class="panel panel-info" >
            <div style="padding-top:30px" class="panel-body" >
				<form id="loginform" enctype="multipart/form-data" class="form-horizontal" action="" method="POST">
						<div style="margin-bottom: 25px" class="input-group">
							<span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
							<input id="username" type="text" class="form-control" name="username" placeholder="Instagram username" required>
						</div>
					<div style="margin-top:10px" class="form-group">
						<div class="col-sm-12 controls">
						  <button type="submit" name="submit" class="btn btn-primary" >Run</button>
						  <?php echo $single_user_result;?>
						</div>
					</div>  
			   </form>
				<div>
					<h3>Upload csv file:</h3>
					<form action="" method="post" enctype="multipart/form-data">
					<table>
						<tr>
							<td width="20%"><input type="file" name="file" id="file" /></td>
							<td><input type="submit" name="upload_file" value="Submit" required/></td>
							<td> <?php echo $multi_user; ?></td>
						</tr>
					</table>
					</form>
				</div>						   
	
			</div>                     
		</div>  
	</div>
</div> 
</html>

	
