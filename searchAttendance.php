<?php
// تضمين ملفات الاتصال
$title = "البحث";
include "includes/header.php";
include "includes/nav.php";
include "data/connection.php";

// تحقق إذا كان التاريخ مرسل في الطلب
if (isset($_GET['search_date'])) {
    $searchDate = $_GET['search_date'];

    // استعلام لاسترجاع البيانات
    $stmt = $conn->prepare("
        SELECT e.id AS employee_id, e.name AS employee_name, a.attendance_date, 
               IF(a.status IS NULL, 'Absent', a.status) AS status
        FROM employees e
        LEFT JOIN attendance a 
        ON e.id = a.employee_id AND a.attendance_date = ?
    ");
    $stmt->bind_param("s", $searchDate);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    // إذا لم يتم إدخال تاريخ، عرض رسالة تنبيه
    $_SESSION['errors'][] = "الرجاء إدخال تاريخ للبحث";
    header("Location: attendence.php");
    exit();
}
?>

<!-- عرض الجدول -->
<div class="container mt-5">
    <h3>نتائج البحث عن الحضور</h3>
    <table class="table table-hover mt-3 text-right" id="attendance-table">
        <thead>
            <tr>
                <th scope="col">الكود</th>
                <th scope="col">اسم الموظف</th>
                <th scope="col">تاريخ اليوم</th>
                <th scope="col">الحضور</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // عرض البيانات داخل الجدول
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    // تحقق من حالة الحضور
                    $checked = ($row['status'] == 'Present') ? 'checked' : '';
                    echo "<tr>
                            <td>{$row['employee_id']}</td>
                            <td>{$row['employee_name']}</td>
                            <td>{$row['attendance_date']}</td>
                            <td>
                                <input type='checkbox' name='attendance[{$row['employee_id']}]' value='Present' {$checked}>
                            </td>
                          </tr>";
                }
            } else {
                echo "<tr>
                        <td colspan='4' class='text-center'>لا توجد بيانات للعرض</td>
                      </tr>";
            }
            ?>
        </tbody>
        
    </table>

    <!-- <button class="btn btn-primary" type="submit">تعديل الكل</button> -->

</div>

<script src="js/popper.min.js"></script>
<script src="js/jquery-3.7.1.js"></script>
<script src="js/bootstrap.js"></script>
</body>
</html>
