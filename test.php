<?php
// تأكد من استلام الـ id عبر الـ GET
if (isset($_GET["id"])) {
    $id = $_GET['id'];
    
    // إنشاء اتصال بقاعدة البيانات
    include 'data/connection.php';

    // استعلام لحساب عدد أيام الحضور للموظف الذي تكون حالته "Present"
    $sql = "SELECT COUNT(DISTINCT attendance_date) AS attendance_count 
            FROM attendance 
            WHERE employee_id = ? AND status = 'Present'";

    // تحضير الاستعلام
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id); // ربط الـ id مع الاستعلام

    // تنفيذ الاستعلام
    $stmt->execute();

    // استلام النتائج
    $result = $stmt->get_result();
    if ($row = $result->fetch_assoc()) {
        $attendanceCount = $row['attendance_count'];
        echo "عدد أيام الحضور للموظف الذي رقم هويته: " . $id . " هو " . $attendanceCount . " يوم.";
    } else {
        echo "لا توجد بيانات للحضور لهذا الموظف.";
    }

    // إغلاق الاتصال
    $stmt->close();
    $conn->close();

    
}
?>
