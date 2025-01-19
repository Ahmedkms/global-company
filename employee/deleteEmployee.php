<?php
include "../data/connection.php";
include("../logicCode/functions.php");

if (isset($_GET["id"])) {
    $empID = $_GET["id"];
    $sql = "DELETE FROM employees WHERE id =$empID";
    if ($conn->query($sql) === true) {
        redirect("../employee.php");
        exit;
    } else {
        echo $conn->error;
    }
}


