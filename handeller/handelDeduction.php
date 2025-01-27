<?php
include "../data/connection.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // print_r($_POST);
    $id = intval($_POST['employee_id']); // ID الموظف يجب أن يكون صحيحًا
    $date = $_POST['date']; // تاريخ الاستقطاع
    $deductionName = $_POST['deductionName']; // نوع الاستقطاع
    $amount = floatval($_POST['amount']); // قيمة الاستقطاع (عشرية)
    $notes = $_POST['notes']; // الملاحظات


  $employeeQuery = "SELECT daily_reward, is_permanent, base_salary FROM employees WHERE id = ?";
    $employeeStmt = $conn->prepare($employeeQuery);
    $employeeStmt->bind_param("i", $id);
    $employeeStmt->execute();
    $employeeResult = $employeeStmt->get_result();

    if ($employeeResult->num_rows > 0) {
        $employeeData = $employeeResult->fetch_assoc();

        $dailyReward = $employeeData['daily_reward'];
        $isPermanent = $employeeData['is_permanent'];
        $base_salary = $employeeData['base_salary'];
        if ($isPermanent) {
            $deductionName = trim($deductionName);
            if ($deductionName === "اجازات بالخصم" || $deductionName === "جزاءات" || $deductionName === "غياب") {
                $amountByDay = ($base_salary / 24) + $dailyReward;
                $amount = $amountByDay * $amount;
            }
        } else {
            $deductionName = trim($deductionName);
            if ($deductionName === "اجازات بالخصم" || $deductionName === "جزاءات" || $deductionName === "غياب") {
                $amount = $dailyReward * $amount;
            }
        }
        
    }

    // var_dump($isPermanent);
    // var_dump($amount);
    // var_dump($deductionName);
    // exit;

    if ($id > 0 && !empty($deductionName) && $amount > 0 && !empty($date)) {
        $sql = "INSERT INTO deductions (employee_id, deduction_name, amount, date, notes) 
                VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("isdss", $id, $deductionName, $amount, $date, $notes);

        if ($stmt->execute()) {
            
           $_SESSION['success']="تم اضافة البند بشكل صحيح";
           
           header("location: ../deduction.php?id=".$id);
           exit;

        } else {
           $_SESSION ['error'] =  "حدث خطأ أثناء الإضافة: " . $stmt->error;
        }
    } else {
        $_SESSION['errors'] = "تأكد من إدخال جميع البيانات بشكل صحيح.";
    }
}
?>
