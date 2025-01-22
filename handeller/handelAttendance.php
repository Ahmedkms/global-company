<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// الاتصال بقاعدة البيانات
include "../data/connection.php";
$errors = [];

// التحقق من وجود بيانات الحضور
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // جلب التاريخ المرسل من النموذج
    $attendanceDate = $_POST['attendance_date'];

    // التحقق من أن التاريخ غير فارغ
    if (empty($attendanceDate)) {
        $errors[]['date'] = "التاريخ غير موجود";
    }

    // استخراج الشهر والسنة من التاريخ
    $date = new DateTime($attendanceDate);
    $month = $date->format('m');  // الشهر
    $year = $date->format('Y');   // السنة

    // التحقق مما إذا كان التاريخ موجود بالفعل في جدول الحضور
    $checkSql = "SELECT id FROM attendance WHERE attendance_date = ? AND month = ? AND year = ?";
    $checkStmt = $conn->prepare($checkSql);
    $checkStmt->bind_param('sii', $attendanceDate, $month, $year);
    $checkStmt->execute();
    $checkResult = $checkStmt->get_result();

    // إذا تم العثور على السجل، إرجاع خطأ
    if ($checkResult->num_rows > 0) {
        $errors[] = "تم تسجيل الحضور لهذا التاريخ مسبقًا.";
    }

    // إذا كانت هناك أخطاء
    if (!empty($errors)) {
        $_SESSION["errors"] = $errors;
        header("location: ../attendance.php");
        exit;
    } else {
        // جلب جميع الموظفين
        $sql = "SELECT id FROM employees";
        $result = $conn->query($sql);

        // إذا كانت القائمة تحتوي على موظفين
        if ($result->num_rows > 0) {
            // التعامل مع الحضور لكل موظف
            $attendanceData = $_POST['attendance'];

            // تحقق من وجود على الأقل موظف واحد حالته Present
            $hasPresent = false;
            foreach ($attendanceData as $status) {
                if ($status === 'Present') {
                    $hasPresent = true;
                    break;
                }
            }

            // إذا لم يكن هناك أي موظف حاضر، إرجاع خطأ
            if (!$hasPresent) {
                $_SESSION["isPresent"] = "يجب أن يكون هناك موظف واحد على الأقل حاضر.";
                header("location: ../attendance.php");
                exit;
            }

            // بدء المعاملات (لتنفيذها بشكل متسلسل)
            $conn->begin_transaction();

            try {
                // المرور على جميع الموظفين
                while ($row = $result->fetch_assoc()) {
                    $employeeId = $row['id'];

                    // التحقق مما إذا كان الموظف قد حضر
                    $status = isset($attendanceData[$employeeId]) && $attendanceData[$employeeId] == 'Present' ? 'Present' : 'Absent';

                    // إعداد استعلام الإدخال
                    $sql = "INSERT INTO attendance (employee_id, attendance_date, status, month, year) VALUES (?, ?, ?, ?, ?)";

                    // تحضير الاستعلام
                    $stmt = $conn->prepare($sql);
                    if ($stmt === false) {
                        throw new Exception("Error preparing statement: " . $conn->error);
                    }

                    // ربط البيانات
                    $stmt->bind_param('issii', $employeeId, $attendanceDate, $status, $month, $year);

                    // تنفيذ الاستعلام
                    $stmt->execute();

                    // التحقق من نجاح التنفيذ
                    if ($stmt->affected_rows === 0) {
                        throw new Exception("Error inserting data for employee ID: $employeeId");
                    }
                }

                // التحقق من أن كل شيء تم بنجاح
                $conn->commit();
                $_SESSION["success"] = "تم إضافة الحضور بنجاح";
                header("location: ../attendance.php");
                exit;
            } catch (Exception $e) {
                // في حالة حدوث خطأ، التراجع عن المعاملات
                $conn->rollback();
                echo "حدث خطأ: " . $e->getMessage();
            }
        } else {
            echo "لا توجد بيانات للموظفين.";
        }
    }
}
?>
