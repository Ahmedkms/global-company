
<?php
include "../data/connection.php";
include("../logicCode/functions.php");

if (isset($_GET["id"])) {
    $id = $_GET["id"];
    $sql = "DELETE FROM masrofat WHERE id =$id";
    if ($conn->query($sql) === true) {
        redirect("../masrofat.php");
        exit;
    } else {
        echo $conn->error;
    }
}


