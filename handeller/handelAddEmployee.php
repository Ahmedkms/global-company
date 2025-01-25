<?php
include "../logicCode/functions.php";
include "../logicCode/validation.php";
include "../data/connection.php";
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$errors = [];

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    foreach ($_POST as $key => $value) {
        $$key = $value;
    }


    $name = isset($_POST['name']) ? $_POST['name'] : '';
    $file = isset($_FILES['image']) ? $_FILES['image'] : null;
    $base_salary = isset($_POST['base_salary']) ? $_POST['base_salary'] : '';
    // Validate name
    if (required($name)) {
        $errors['name'] = "Name is required.";
    } 

    // Validate image file
    if ($file && $file['error'] === UPLOAD_ERR_OK) {
        $f_name = $file['name'];
        $f_tmp_name = $file['tmp_name'];
        $f_size = $file['size'];
        $ext = strtolower(pathinfo($f_name, PATHINFO_EXTENSION));

        $allowed = ["png", "jpg", "jpeg", "gif"];
        if (in_array($ext, $allowed)) {
            if ($f_size < 500000) { // Max size: 500KB
                $new_name = uniqid('', true) . "." . $ext;
                $destination = "../images/" . $new_name;
                move_uploaded_file($f_tmp_name, $destination);
            } 
        } else {
            $errors['image'] = "Invalid file type. Allowed: png, jpg, jpeg, gif.";
        }
    } 

    //validate date of birth
    if (required($dateOfBirth)) {
        // $errors['dateOfBirth'] = "date of birth is required.";
    } else {
        $dobTimestamp = strtotime($dateOfBirth);
        $currentTimestamp = time();
        if ($dobTimestamp > $currentTimestamp) {
            $errors['dateOfBirth'] = "Date of Birth cannot be in the future.";
        }
    }

    // validate phone
    if (required($phone)) {
        // $errors['phone'] = "phone is required.";
    } elseif (!preg_match('/^01[0125][0-9]{8}$/', $phone)) {
        $errors['phone'] = "Invalid phone number. Must be 11 digits and start with 010, 011, 012, or 015.";
    }

    //validate address 
    // if (required($address)) {
    //     $errors['address'] = "address is required.";
    // }

    // validate Criminal_record file 
    $Criminal_record = $_FILES['Criminal_record'];
    if ($Criminal_record && $Criminal_record['error'] === UPLOAD_ERR_OK) {
        $Criminal_record_fName = $Criminal_record['name'];
        $Criminal_record_f_tmp_name = $Criminal_record['tmp_name'];
        $Criminal_record_f_size = $Criminal_record['size'];
        $ext = strtolower(pathinfo($Criminal_record_fName, PATHINFO_EXTENSION));

        $allowed = ["png", "jpg", "jpeg", "gif"];
        if (in_array($ext, $allowed)) {
            if ($f_size < 500000) { // Max size: 500KB
                $Criminal_record_new_name = uniqid('', true) . "." . $ext;
                $Criminal_record_destination = "../Criminal_record/" . $Criminal_record_new_name;
                move_uploaded_file($Criminal_record_f_tmp_name, $Criminal_record_destination);
            } else {
                // $errors['Criminal_record'] = "File size exceeds 500KB.";
            }
        } else {
            $errors['Criminal_record'] = "Invalid file type. Allowed: png, jpg, jpeg, gif.";
        }
    } 

    // validate  jop
    if (required($job)) {
        $errors['job'] = "job is required";
    }
    //validate daily_reward
    if (required($daily_reward)) {
        $errors['daily_reward'] = "daily_reward is required";
    }
    //validate is_permanent
    $is_permanent = isset($_POST['is_permanent']) ? 1 : 0;
    //validate is_permanent
    $is_fixed_worker = isset($_POST['is_fixed_worker']) ? 1 : 0;

    //validate site 
    if (required($site)) {
        $errors['site'] = "site is required";
    }
    //validate start date 
    if (required($start_date)) {
        $errors['start_date'] = "start date is required";
    }
    //validate end date 
    if (required($end_date)) {
        $errors['end_date'] = "end date is required";
    }

    if (!empty($errors)) {
        $_SESSION['errors'] = $errors;
        $_SESSION['old_input'] = $_POST;
      
        redirect("../add-employee.php");
    } else {
        // Create the SQL query
        $sql = "INSERT INTO employees (name, image, date_of_birth, phone, address, criminal_record, job, daily_reward,base_salary,
            is_permanent, is_fixed_worker, site, start_date, end_date) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?,?)";


        // Prepare the statement
        $stmt = $conn->prepare($sql);

        // Check if the statement preparation is successful
        if ($stmt === false) {
            die('MySQL prepare error: ' . $conn->error);
        }

        // Bind the parameters to the query
        $stmt->bind_param(
            'sssssssddiisss',  // Match the 13 parameters with correct types
            $name,
            $destination,
            $dateOfBirth,
            $phone,
            $address,
            $Criminal_record_destination,
            $job,
            $daily_reward,
            $base_salary,
            $is_permanent,
            $is_fixed_worker,
            $site,
            $start_date,
            $end_date
        );
        
        

    

        // Execute the statement
        if ($stmt->execute()) {
            $_SESSION['success'] = "Employee added successfully!";
           redirect("../employee.php");
       
        } else {
            $_SESSION['errors'] = ["Error occurred while adding the employee."];
            redirect("../add-employee.php");
        }
        // Close the statement
        $stmt->close();
        // Close the connection
        $conn->close();
    }
}
