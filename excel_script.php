<?php
set_include_path('C:\wamp64\bin\php\php5.6.25\pear');
include('Net/SSH2.php');
include('Net/SFTP.php');

if ($_POST['rdb'])
{
	$server_ip = '10.100.8.133';
	
}
else{
	$server_ip = '172.16.60.222';
}

$sftp = new Net_SFTP('10.100.10.17');
if (!$sftp->login('root', 'drfirst(%!)')) {
    exit('Login Failed');
}
$target_dir = "/data/jira/sharedhome/data/";
$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
$dest_path ='/data/jira/sharedhome/data/Tier1WeeklySchedule.csv';
if(isset($_FILES['fileToUpload'])){
      $errors= array();
      $file_name = $_FILES['fileToUpload']['name'];
	 
      $file_size =$_FILES['fileToUpload']['size'];
      $file_tmp =$_FILES['fileToUpload']['tmp_name'];
      $file_type=$_FILES['fileToUpload']['type'];
	   $filename = end(explode(".", $file_name));
	   echo $filename;
	   echo <br>;
      $file_ext=strtolower(end(explode('.',$_FILES['fileToUpload']['name'])));
      
      $extensions= array("csv");
      
      if(in_array($file_ext,$extensions)=== false){
         $errors[]="extension not allowed, please choose a CSV file.";
      }
	  //Code to check if the file exists on the server. If exists rename the previous file with current timestamp as a suffix.
	  $date = new DateTime();
	  echo $target_dir.$file_name;
	  if ($sftp->file_exists($dest_path))
	  {
		  //echo $dest_path.'<br>';
		  //echo $target_dir.'BCK'.$date->format('Ymd.Hi').$file_name;
		 //echo $target_dir.'BCK'.$date->format('Y-m-d H:i').$file_name;
      //$sftp->rename($dest_path,$target_dir.'BCK'.$date->format('YmdHi').$file_name);
	  echo " The file already exists.";
	  
	  }	
	  else{	
		  echo "file does not exists";  	  
	  }
}
      
      if(empty($errors)==true){
		 echo $file_size;
		 //$sftp->put('/data/jira/sharedhome/data/Tier1WeeklySchedule.csv',$file_tmp , NET_SFTP_LOCAL_FILE);
		 $sftp->get('/data/jira/sharedhome/data/Tier1WeeklySchedule.csv','c:/Tier1WeeklySchedule.csv');
		 link ('c:/Tier1WeeklySchedule.csv', 'Download1' );
         echo "Success";
      }else{
         print_r($errors);
      }



?>

<!DOCTYPE html>
<html>
<body>

<form action="excel_script.php" method="post" enctype="multipart/form-data">
	<input type = "radio" name = "rdb" value = "Staging"/> Staging
	<input type = "radio" name = "rdb" value = "Production"/>Production
	<br>
    Select file to upload:
    <input type="file" name="fileToUpload" id="fileToUpload">
    <input type="submit" value="Upload" name="submit">
</form>

</body>
</html>
