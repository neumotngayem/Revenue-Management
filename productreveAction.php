<?php
require('config.php');
if (isset($_POST["site"])) {
    $site = $_POST["site"];
    $sql = "SELECT stv.ID,stv.NM, SUM(stv.price*rec.quantity) AS STOVE_EARN, SUM(rec.QUANTITY) AS STOVE_QUANT FROM receipt rec JOIN stove stv ON rec.STOVE_ID = stv.ID";
    if ($site != "all") {
        $sql .= " WHERE rec.LOCA_ID = '$site'";
    }
    $sql .= " GROUP BY rec.STOVE_ID ORDER BY STOVE_EARN DESC LIMIT 5";
    $result = $mysqli->query($sql);
    $stoveIDArr = array();
    $stoveEarnArr = array();
    $stoveNmArr = array();
    if ($result->num_rows > 0) {
        $i = 0;
        while ($row = $result->fetch_assoc()) {
            $stoveIDArr[$i] = $row["ID"];
            $stoveEarnArr[$i] = $row["STOVE_EARN"];
            $stoveNmArr[$i] = $row["NM"];
            $i += 1;
        };
    }

    $resultJson = [
        "stoveIDArr"   => $stoveIDArr,
        "stoveEarnArr" => $stoveEarnArr,
        "stoveNmArr"   => $stoveNmArr
     ];

    echo json_encode($resultJson);
}
?>