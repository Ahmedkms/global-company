<?php
include "../data/connection.php";
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include "../logicCode/functions.php";
include "../logicCode/validation.php";
$error =  [];
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    foreach ($_POST as $key => $values) {
        $$key = $values;
    }
    $name = sanitizeInput($name);
    $user = sanitizeInput($user);


    //validate name
    if (required($name)) {
        $error[] = "name is required input";
    } elseif (minimumchars($name, 3)) {
        $error[] = "name must be more than 3 chars";
    } elseif (maximumchars($name, 50)) {
        $error[] = "name can not be more than 20 character";
    }

    //validate email 
    if (required($user)) {
        $error[] = " email is required";
    }
    //validate password 
    if (required($password)) {
        $error[] = "password is required input";
    } elseif (minimumchars($password, 6)) {
        $error[] = "password must be more than 6 chars";
    }

    //validate confirm password

    if (required($confirm_password)) {
        $error[] = "confirm_password is required input";
    } elseif ($confirm_password !== $password) {
        $error[] = "confirm password must be the same with password";
    }
   

    //error handling
    if (!empty($error)) {
        $_SESSION["errors"] = $error;
        redirect("../register.php");
    }
    // handel authentication for ideal inputs and store data in files
    else {
        $_SESSION["authenticate"] = [$name, $user];
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $sql = "INSERT INTO users (name,user,password)
    VALUES('$name','$user','$hashedPassword')";

        if ($conn->query($sql) === true) {
            redirect("../index.php");
            exit;
        }
    var_dump($name);
    }
}
