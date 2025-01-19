<?php
include "../data/connection.php";
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include "../logicCode/functions.php";
include "../logicCode/validation.php";
$error = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $user = isset($_POST["user"]) ? sanitizeInput($_POST["user"]) : '';
    $password = isset($_POST["password"]) ? sanitizeInput($_POST["password"]) : '';


    if (required($user)) {
        $error[] = " username is required";
    } 

    //validate password 
    if (required($password)) {
        $error[] = "password is required input";
    } 
    // error handling
    if (!empty($error)) {
        $_SESSION["errors"] = $error;
        redirect("../login.php");
    }
    // handeling login data and check if it exist or not
    else{
    $sql = "SELECT user, password FROM users WHERE user = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $user);
        $stmt->execute();
        $result = $stmt->get_result();
        $isExist = false;
    
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            if (password_verify($password, $row['password'])) {
                $isExist = true;
            }
        }
        
        if ($isExist) {
            
            $_SESSION["authenticate"] = ["name" => $name, "user" => $user];
            redirect("../index.php");
        } else {
            $error[] = "Invalid email or password";
            $_SESSION["errors"] = $error;
            redirect("../login.php");
        }

    }
    
}