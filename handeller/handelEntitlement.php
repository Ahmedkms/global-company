<?php
include "../data/connection.php"; // الاتصال بقاعدة البيانات

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $employeeId = $_POST['employee_id'];
    $entitlementName = $_POST['entitlementName'];
    $amount = $_POST['amount'];
    $date = $_POST['date'];
    $notes = $_POST['notes'];

    // استرجاع معلومات الموظف
    $employeeQuery = "SELECT daily_reward, is_permanent, base_salary FROM employees WHERE id = ?";
    $employeeStmt = $conn->prepare($employeeQuery);
    $employeeStmt->bind_param("i", $employeeId);
    $employeeStmt->execute();
    $employeeResult = $employeeStmt->get_result();

    if ($employeeResult->num_rows > 0) {
        $employeeData = $employeeResult->fetch_assoc();

        $dailyReward = $employeeData['daily_reward'];
        $isPermanent = $employeeData['is_permanent'];
        $base_salary = $employeeData['base_salary'];

       

        if ($isPermanent) {
            $entitlementName = trim($entitlementName);
            if($entitlementName ==="ساعات اضافية على30"){
                $amountByDay = ($base_salary / 30)+ $dailyReward ;
                $amountOfhOur = $amountByDay /9;
                $amount =  $amountOfhOur * $amount;
            }elseif($entitlementName ==="ساعات اضافية"){
                $amountByDay = ($base_salary / 24)+ $dailyReward ;
                $amountOfhOur = $amountByDay /9;
                $amount =  $amountOfhOur * $amount;
            }elseif($entitlementName ==="حافز بعدد الايام"){
                $amountByDay = ($base_salary / 24)+ $dailyReward;
                $amount =  $amountByDay * $amount;

            }
      
        }else{
            $entitlementName = trim($entitlementName);
            if($entitlementName === "ساعات اضافية"){
                $amountByHour = $dailyReward /9;
                $amount = $amountByHour * $amount;
            }elseif($entitlementName ==="حافز بعدد الايام"){
                $amount =  $dailyReward * $amount;

            }

        }

        // إضافة الاستحقاق
        $sql = "INSERT INTO entitlements (employee_id, entitlement_name, amount, date, notes) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("isdss", $employeeId, $entitlementName, $amount, $date, $notes);

        if ($stmt->execute()) {
            header("Location: ../entitlements.php?id=$employeeId&success=1");
            exit();
        } else {
            echo "حدث خطأ أثناء إضافة الاستحقاق: " . $conn->error;
        }
    } else {
        echo "الموظف غير موجود في قاعدة البيانات.";
    }
}
?>

