<?php
    require('config.php');
    $sql = "SELECT st.ID, st.NM, st.DOB, si.NM AS SITE_NAME FROM staff st JOIN site si ON st.SITE_ID = si.ID";

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