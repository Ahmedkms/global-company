<?php 
include "logicCode/functions.php";
session_start();
unset($_SESSION["authenticate"]);
session_destroy();

redirect("index.php");