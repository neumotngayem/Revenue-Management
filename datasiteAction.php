<?php
    require('config.php');
    $sql = "SELECT * FROM site";

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