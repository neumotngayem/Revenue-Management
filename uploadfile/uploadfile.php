<?php
include 'excel_reader.php';     // include the class
$ds          = DIRECTORY_SEPARATOR;
$storeFolder = 'uploads';
 
if (!empty($_FILES)) {
	
    //If file is sent to the page, store the file object to a temporary variable. 
    $tempFile = $_FILES['file']['tmp_name'];      
    
	//Create the absolute path of the destination folder.
    $targetPath = dirname( __FILE__ ) . $ds. $storeFolder . $ds;
	
	$ext = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);    
	
    $targetFile =  $targetPath.'targetfile.'.$ext;  //5
 
    move_uploaded_file($tempFile,$targetFile); //6
}
?> 