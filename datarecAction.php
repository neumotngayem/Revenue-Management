<?php
    require('config.php');
    $sql = "SELECT rec.STAFF_ID, rec.LOCA_ID, rec.STOVE_ID , rec.SELL_DATE, rec.QUANTITY, fu.ORI_FILENAME AS FILE_NAME FROM receipt rec JOIN fileupload fu ON rec.FILE_ID = fu.FILE_ID";

    $result = $mysqli->query($sql);


    while($row = $result->fetch_array(MYSQLI_ASSOC)){
        $data[] = $row;
    }

    $results = ["sEcho" => 1,
        "iTotalRecords" => count($data),
        "iTotalDisplayRecords" => count($data),
        "aaData" => $data ];
    echo json_encode($results);
?>