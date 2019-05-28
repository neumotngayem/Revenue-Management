<script>	
	console.log('Hello upload');
</script>
<?php
	require_once 'PHPExcel/PHPExcel.php';    // include the class

	$servername = "localhost";
	$username = "root";
	$password = "root123456@";
	$dbname = "stovestore";
	
	$file = "./uploads/targetfile.xlsx";
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

		$sql .= "INSERT INTO receipt (STAFF_ID, LOCA_ID, STOVE_ID, SELL_DATE, QUANTITY)
			VALUES ('$arrValues[0]', '$arrValues[1]', '$arrValues[2]', '$arrValues[3]', $arrValues[4]);";		
		$x++;
	 }
	  $sql = substr($sql,0,strlen($sql)-1);
	  // Create connection
	 $conn = new mysqli($servername, $username, $password, $dbname);
	if ($conn->multi_query($sql) === TRUE) {
		echo "New records created successfully";
	} else {
		echo "Error: " . $sql . "<br>" . $conn->error;
	}

	$conn->close();
	//header('Location: upload.php'); 
?>