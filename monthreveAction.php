<?php
    require('config.php');
    if(isset($_POST["site"])){
        $site = $_POST["site"];
        $sql = "SELECT DATE_FORMAT(rec.SELL_DATE, '%b') AS MONTH_SELL, SUM(stv.price*rec.quantity) AS MONTH_EARN FROM receipt rec JOIN stove stv ON rec.STOVE_ID = stv.ID";
        if($site != "all"){
            $sql .= " WHERE rec.LOCA_ID = '$site'";
        }
        $sql .= " GROUP BY DATE_FORMAT(rec.SELL_DATE, '%Y/%c') ORDER BY DATE_FORMAT(rec.SELL_DATE, '%Y/%c') ASC LIMIT 12";
        $result = $mysqli->query($sql);
        $monthNameArr = array();
        $monthEarnArr = array();
        if ($result->num_rows > 0) {
            $i = 0;
            while($row = $result->fetch_assoc()) {
                $monthNameArr[$i] = $row['MONTH_SELL'];
                $monthEarnArr[$i] = $row['MONTH_EARN'];
                $i+=1;
            }
        }
        $resultJson = [
            "monthNameArr" => $monthNameArr,
            "monthEarnArr" => $monthEarnArr
        ];

        echo json_encode($resultJson);
    }
?>