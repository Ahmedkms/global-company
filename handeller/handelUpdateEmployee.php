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

    // Validate name
    if (required($name)) {
        $errors[] = "Name is required.";
    } elseif (minimumchars($name, 3)) {
        $errors[] = "Name must be more than 3 characters.";
    } elseif (maximumchars($name, 70)) {
        $errors[] = "Name cannot exceed 70 characters.";
    }

    // Validate date of birth
    if (required($dateOfBirth)) {
        $errors[] = "Date of birth is required.";
    } else {
        $dobTimestamp = strtotime($dateOfBirth);
        $currentTimestamp = time();
    }

    if ($dobTimestamp > $currentTimestamp) {
        $errors[] = "Date of Birth cannot be in the future.";
    }

    // Validate phone
    if (required($phone)) {
        $errors[] = "Phone is required.";
    } elseif (!preg_match('/^01[0125][0-9]{8}$/', $phone)) {
        $errors[] = "Invalid phone number. Must be 11 digits and start with 010, 011, 012, or 015.";
    }

    // Validate address
    if (required($address)) {
        $errors[] = "Address is required.";
    }

    // Validate job
    if (required($job)) {
        $errors[] = "Job is required.";
    }

    // Validate daily reward
    if (required($daily_reward)) {
        $errors[] = "Daily reward is required.";
    }

    // Validate is_permanent
    $is_permanent = isset($_POST['is_permanent']) ? 1 : 0;

    // Validate is_fixed_worker
    $is_fixed_worker = isset($_POST['is_fixed_worker']) ? 1 : 0;

    // Validate site
    if (required($site)) {
        $errors[] = "Site is required.";
    }

    // Validate start date
    if (required($start_date)) {
        $errors[] = "Start date is required.";
    }

    // Validate end date
    if (required($end_date)) {
        $errors[] = "End date is required.";
    }

    if (!empty($errors)) {
        $_SESSION['errors'] = $errors;
        var_dump($_SESSION['errors']);
        // redirect("../updateEmployee.php");
    } else {
        // Create the SQL query for update
        $sql = "UPDATE employees 
                SET name = ?, 
                    date_of_birth = ?, 
                    phone = ?, 
                    address = ?, 
                    job = ?, 
                    daily_reward = ?, 
                    is_permanent = ?, 
                    is_fixed_worker = ?, 
                    site = ?, 
                    start_date = ?, 
                    end_date = ? 
                WHERE id = ?";

        // Prepare the statement
        $stmt = $conn->prepare($sql);

        // Check if the statement preparation is successful
        if ($stmt === false) {
            die('MySQL prepare error: ' . $conn->error);
        }

        // Bind the parameters to the query
        $stmt->bind_param(
            'sssssdiisssi',  // Match the 12 parameters with correct types: 's' for strings, 'i' for integers
            $name,
            $dateOfBirth,
            $phone,
            $address,
            $job,
            $daily_reward,
            $is_permanent,
            $is_fixed_worker,
            $site,
            $start_date,
            $end_date,
            $id  // Make sure to include the employee ID for updating the correct record
        );

        // Execute the statement
        if ($stmt->execute()) {
            $_SESSION['success'] = "Employee updated successfully!";
            redirect("../employee.php");
        } else {
            $_SESSION['errors'] = ["Error occurred while updating the employee."];
            var_dump($_SESSION['errors']);
            // redirect("../updateEmployee.php");
        }

        // Close the statement and connection
        $stmt->close();
        $conn->close();
    }
}
?>
