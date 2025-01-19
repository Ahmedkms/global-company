<?php
include "../logicCode/functions.php";
include "../logicCode/validation.php";
include "../data/connection.php";
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$errors = [];
if(isset($_SERVER["REQUEST_METHOD"])=="POST"){
    // foreach($_POST as $key => $value){
    //     $$key = $value;
    // }
    $bandname = $_POST["bandname"] ?? '';
    $amount = $_POST["amount"] ?? '';
    $date = $_POST["date"] ?? '';
    $comment = $_POST["comment"] ?? '';

    
    //validation of bandname

    if(required($bandname)){
        $errors['bandname']= "ادخل اسم البند" ;
    }

    //validate amount 
    if(required($amount)){
        $errors ['amount'] = "ادخل المبلغ المدفوع للبند";
    }
    //validate date 

    if (required($date)) {
        $errors['date'] = "ادخل تاريخ البند";
    } else {
        $dobTimestamp = strtotime($date);
        $currentTimestamp = time();

        if ($dobTimestamp > $currentTimestamp) {
            $errors['date'] = "لا يمكن ادخال تاريخ فى المستقبل";
        }
    }

    //validate comment 
    if(empty($comment)){
        $comment = "لا يوجد ملاحظات";
    }

if(!empty($errors)){
    $_SESSION["errors"] = $errors;
    $_SESSION["old_data"] = $_POST;
    $err = $_SESSION['errors'];
    redirect("../masrofat.php");
}else{
    $sql = "INSERT INTO masrofat (bandname, amount, date, comment) 
            VALUES (?, ?, ?, ?)";


        // Prepare the statement
        $stmt = $conn->prepare($sql);

        // Check if the statement preparation is successful
        if ($stmt === false) {
            die('MySQL prepare error: ' . $conn->error);
        }

        // Bind the parameters to the query
        $stmt->bind_param(
            'sdss',  
          $bandname,
          $amount,
          $date,
          $comment
        );
        
        

    

        // Execute the statement
        if ($stmt->execute()) {
            $_SESSION['success'] = "masrofat added successfully!";
           redirect("../masrofat.php");
            
        } else {
            $_SESSION['errors'] = ["Error occurred while adding the employee."];
            redirect("../masrofat.php");
        }
      
        // Close the statement
        $stmt->close();
        // Close the connection
        $conn->close();
}


}