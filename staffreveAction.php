<?php
require('config.php');
if (isset($_POST["site"])){
    $site = $_POST["site"];
    $sql = "SELECT sta.ID,sta.NM, SUM(stv.price*rec.quantity) AS STAFF_EARN FROM receipt rec JOIN stove stv ON rec.STOVE_ID = stv.ID JOIN staff sta ON rec.STAFF_ID = sta.ID";
    if ($site != "all") {
        $sql .= " WHERE rec.LOCA_ID = '$site'";
    }
    $sql .= " GROUP BY rec.STAFF_ID ORDER BY STAFF_EARN DESC";
    $result = $mysqli->query($sql);
    $staffIDArr = array();
    $staffEarnArr = array();
    $staffNameArr = array();
    if ($result->num_rows > 0) {
        $i = 0;
        while ($row = $result->fetch_assoc()) {
            $staffIDArr[$i] = $row['ID'];
            $staffEarnArr[$i] = $row['STAFF_EARN'];
            $staffNameArr[$i] = $row["NM"];
            $i += 1;
        }
    }

    $resultJson = [
        "staffIDArr"   => $staffIDArr,
        "staffEarnArr" => $staffEarnArr,
        "staffNameArr"   => $staffNameArr
    ];

    echo json_encode($resultJson);
}
?>