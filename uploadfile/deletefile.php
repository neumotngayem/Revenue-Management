<script>	
	console.log('Hello upload');
</script>
<?php

if(isset($_POST['fileid']) && isset($_POST['filename'])){
    $fileid = $_POST['fileid'];
    $filename = $_POST['filename'];
    echo($fileid);
    $servername = "localhost";
    $username = "root";
    $password = "root123456@";
    $dbname = "stovestore";
    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    $sql = "DELETE FROM fileupload WHERE FILE_ID='$fileid'";
    $conn->query($sql);

    $sql = "DELETE FROM receipt WHERE FILE_ID='$fileid'";
    $conn->query($sql);

    $path = "./uploads/".$filename;
    unlink($path);

    $conn->close();
}
?>