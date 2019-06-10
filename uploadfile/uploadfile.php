<?php
require_once 'PHPExcel/PHPExcel.php';    // include the class
$ds          = DIRECTORY_SEPARATOR;
$storeFolder = 'uploads';
$servername = "localhost";
$username = "root";
$password = "root123456@";
$dbname = "stovestore";

if (!empty($_FILES)) {
	// Create connection
	$conn = new mysqli($servername, $username, $password, $dbname);
    //If file is sent to the page, store the file object to a temporary variable. 
    $tempFile = $_FILES['file']['tmp_name'];      
    
	//Create the absolute path of the destination folder.
    $targetPath = dirname( __FILE__ ) . $ds. $storeFolder . $ds;
	
	$ext = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
	
	$sql = "SELECT MAX(FILE_NAME) AS max FROM fileupload";
	$result = $conn->query($sql);
	$filename = "";
	if ($result->num_rows > 0) {
		$row = $result->fetch_assoc();
		if(isset($row["max"])){
			$max = $row["max"];
			$posPoint = strpos($max, ".");
			$posUnder = strpos($max, "_")+1;
			$lengCut =  $posPoint - $posUnder;
			$sequence = intval(substr($max,$posUnder, $lengCut))+1;
			$filename = "file_".$sequence;
		}else{
			$filename = "file_1";
		}
	}
    $targetFile =  $targetPath.$filename.'.'.$ext;
	$targetName =  $filename.'.'.$ext;
	move_uploaded_file($tempFile,$targetFile);
	
	$original = $_FILES['file']['name'];
	$sql = "INSERT INTO fileupload (FILE_ID, FILE_NAME, ORI_FILENAME, DATE_UPLOAD) VALUES  ('$filename','$targetName','$original',NOW())";
	$conn->query($sql);
	
	$file = "./uploads/".$targetName;
	$objFile = PHPExcel_IOFactory::identify($file);
	$objData = PHPExcel_IOFactory::createReader($objFile);
	$objData->setReadDataOnly(true);
	$objPHPExcel = $objData->load($file);
	$sheet = $objPHPExcel->setActiveSheetIndex(0);
	$Totalrow = $sheet->getHighestRow();
	$LastColumn = $sheet->getHighestColumn();
	$TotalCol = PHPExcel_Cell::columnIndexFromString($LastColumn);	
	$x = 2;
	$sql = "";
	while($x < $Totalrow) {
		$y = 0;
		$arrValues = [];
		while($y < $TotalCol) {
			$value = $sheet->getCellByColumnAndRow($y, $x)->getValue();
			$value = isset($value) ? $value : '' ;
			$arrValues[$y] = $value;
			$y++;
		}

		$sql .= "INSERT INTO receipt (STAFF_ID, LOCA_ID, STOVE_ID, SELL_DATE, QUANTITY, FILE_ID)
			VALUES ('$arrValues[0]', '$arrValues[1]', '$arrValues[2]', '$arrValues[3]', $arrValues[4], '$filename');";
		$x++;
	 }
	  $sql = substr($sql,0,strlen($sql)-1);
	if ($conn->multi_query($sql) === TRUE) {
		echo "New records created successfully";
	} else {
		echo "Error: " . $sql . "<br>" . $conn->error;
	}

	$conn->close();	
}
?> 