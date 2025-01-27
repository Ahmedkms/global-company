<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include "../data/connection.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $attendanceDate = $_POST['attendance_date'];
    $attendanceData = $_POST['attendance'];

    // بدء معاملة قاعدة البيانات
    $conn->begin_transaction();

    try {
        foreach ($attendanceData as $employeeId => $status) {
            $status = ($status === 'Present') ? 'Present' : 'Absent';

            // تحقق إذا كان السجل موجودًا مسبقًا
            $checkStmt = $conn->prepare("
                SELECT id FROM attendance 
                WHERE employee_id = ? AND attendance_date = ?
            ");
            $checkStmt->bind_param("is", $employeeId, $attendanceDate);
            $checkStmt->execute();
            $checkResult = $checkStmt->get_result();

            if ($checkResult->num_rows > 0) {
                // إذا كان السجل موجودًا، قم بتحديثه
                $updateStmt = $conn->prepare("
                    UPDATE attendance 
                    SET status = ? 
                    WHERE employee_id = ? AND attendance_date = ?
                ");
                $updateStmt->bind_param("sis", $status, $employeeId, $attendanceDate);
                $updateStmt->execute();
            } else {
                // إذا لم يكن السجل موجودًا، قم بإضافته
                $insertStmt = $conn->prepare("
                    INSERT INTO attendance (employee_id, attendance_date, status) 
                    VALUES (?, ?, ?)
                ");
                $insertStmt->bind_param("iss", $employeeId, $attendanceDate, $status);
                $insertStmt->execute();
            }
        }

        // حفظ التعديلات
        $conn->commit();
        $_SESSION['success'] = "تم تحديث الحضور بنجاح";
        header("Location: ../attendance.php");
        exit();
    } catch (Exception $e) {
        $conn->rollback();
        $_SESSION['errors'][] = "حدث خطأ أثناء تحديث البيانات: " . $e->getMessage();
        header("Location: ../attendance.php");
        exit();
    }
}
?>
