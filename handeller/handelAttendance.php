<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// الاتصال بقاعدة البيانات
include "../data/connection.php";
$errors = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $attendanceDate = $_POST['attendance_date'];

    if (empty($attendanceDate)) {
        $errors[] = "التاريخ غير موجود.";
    }

    $date = new DateTime($attendanceDate);
    $month = $date->format('m');
    $year = $date->format('Y');

    if (!empty($errors)) {
        $_SESSION["errors"] = $errors;
        header("location: ../attendance.php");
        exit;
    } else {
        $sql = "SELECT id FROM employees";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $attendanceData = $_POST['attendance'];
            $hasPresent = false;

            foreach ($attendanceData as $status) {
                if ($status === 'Present') {
                    $hasPresent = true;
                    break;
                }
            }

            if (!$hasPresent) {
                $_SESSION["isPresent"] = "يجب أن يكون هناك موظف واحد على الأقل حاضر.";
                header("location: ../attendance.php");
                exit;
            }

            $conn->begin_transaction();

            try {
                while ($row = $result->fetch_assoc()) {
                    $employeeId = $row['id'];
                    $status = isset($attendanceData[$employeeId]) && $attendanceData[$employeeId] == 'Present' ? 'Present' : 'Absent';

                    // التحقق مما إذا كان السجل موجودًا
                    $checkSql = "SELECT id FROM attendance WHERE employee_id = ? AND attendance_date = ?";
                    $checkStmt = $conn->prepare($checkSql);
                    $checkStmt->bind_param('is', $employeeId, $attendanceDate);
                    $checkStmt->execute();
                    $checkResult = $checkStmt->get_result();

                    if ($checkResult->num_rows > 0) {
                        // إذا كان السجل موجودًا، قم بالتحديث
                        $updateSql = "UPDATE attendance SET status = ?, month = ?, year = ? WHERE employee_id = ? AND attendance_date = ?";
                        $updateStmt = $conn->prepare($updateSql);
                        $updateStmt->bind_param('siiis', $status, $month, $year, $employeeId, $attendanceDate);
                        $updateStmt->execute();
                    } else {
                        // إذا لم يكن السجل موجودًا، قم بالإدخال
                        $insertSql = "INSERT INTO attendance (employee_id, attendance_date, status, month, year) VALUES (?, ?, ?, ?, ?)";
                        $insertStmt = $conn->prepare($insertSql);
                        $insertStmt->bind_param('issii', $employeeId, $attendanceDate, $status, $month, $year);
                        $insertStmt->execute();
                    }
                }

                $conn->commit();
                $_SESSION["success"] = "تم حفظ بيانات الحضور بنجاح.";
                header("location: ../attendance.php");
                exit;
            } catch (Exception $e) {
                $conn->rollback();
                $_SESSION["errors"] = ["حدث خطأ أثناء حفظ البيانات: " . $e->getMessage()];
                header("location: ../attendance.php");
                exit;
            }
        } else {
            $_SESSION["errors"] = ["لا توجد بيانات للموظفين."];
            header("location: ../attendance.php");
            exit;
        }
    }
}
?>
