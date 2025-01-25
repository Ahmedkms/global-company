<?php
$title = "الحضور";
include "includes/header.php";
include "includes/nav.php";
include "data/connection.php";

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// عرض الأخطاء والرسائل الناجحة
if (isset($_SESSION['errors'])) {
    foreach ($_SESSION['errors'] as $error) {
        if (is_string($error)) {
            echo "<script>alert('" . addslashes($error) . "');</script>";
        }
    }
    unset($_SESSION['errors']);
}

if (isset($_SESSION['success']) && is_string($_SESSION['success'])) {
    echo "<script>alert('" . addslashes($_SESSION['success']) . "');</script>";
    unset($_SESSION['success']);
}

// عرض الحضور لليوم الحالي افتراضيًا
$today = date('Y-m-d');
$sql = "SELECT employees.id, employees.name, attendance.attendance_date AS attendance_date, attendance.status 
        FROM employees 
        LEFT JOIN attendance 
        ON employees.id = attendance.employee_id AND attendance.attendance_date = '$today'";

$result = $conn->query($sql);
?>

<body>
<div class="container-fluid">
    <!-- start of tab section-->
    <ul class="nav nav-tabs">
        <li class="nav-item">
            <a class="nav-link " href="salary.php">المرتبات</a>
        </li>
        <li class="nav-item">
            <a class="nav-link active" href="attendance.php">الحضور اليومي</a>
        </li>
    </ul>

    <!--search and add tab-->
    <div class="row mt-2">
        <label class="col-md-1">الشهر:</label>
        <input class="col-md-2 form-control me-2" type="date" id="month-search">
        <button class="col-md-1 mr-1 btn btn-success" id="search-btn" type="button">بحث</button>
        <div class="col-md-3"></div>
        <label class="col-md-1">التاريخ:</label>
        <input class="col-md-2 form-control me-2" type="date" id="manual-date" value="<?= $today ?>">
        <button class="col-md-1 mr-1 btn btn-warning" id="add-day" type="button">اضافة يوم</button>
    </div>

    <form action="handeller/handelAttendance.php" method="POST" class="form mb-2">
        <!--attendance table-->
        <table class="table table-hover mt-3 text-right" id="attendance-table">
            <thead>
            <tr class="table">
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
                    $checked = $row['status'] === 'Present' ? 'checked' : '';
                    echo "<tr>
                            <td>{$row['id']}</td>
                            <td>{$row['name']}</td>
                            <td class='date-cell'>{$today}</td>
                            <td>
                                <input type='checkbox' name='attendance[{$row['id']}]' value='Present' $checked>
                            </td>
                          </tr>";
                }
            } else {
                echo "<tr>
                        <td colspan='4'>لا توجد بيانات للعرض</td>
                      </tr>";
            }
            ?>
            </tbody>
        </table>
        <!-- Hidden input to send the date with the form -->
        <input type="hidden" name="attendance_date" id="attendance-date" value="<?= $today ?>">
        <button class="btn btn-primary" type="submit">حفظ الكل</button>
    </form>
</div>

<script>
    // البحث بالتاريخ
    document.getElementById('search-btn').addEventListener('click', function () {
        const searchDate = document.getElementById('month-search').value;
        if (!searchDate) {
            alert('يجب إدخال تاريخ للبحث');
            return;
        }
        window.location.href = `searchAttendance.php?search_date=${searchDate}`;
    });

    // تغيير التاريخ في الجدول
    document.getElementById('add-day').addEventListener('click', function () {
        const manualDate = document.getElementById('manual-date').value;
        if (!manualDate) {
            alert('يجب إدخال التاريخ قبل إضافة اليوم');
            return;
        }

        // إعادة تعيين جميع صناديق الحضور إلى غير محددة
        document.querySelectorAll('input[type="checkbox"]').forEach(checkbox => {
            checkbox.checked = false;
        });

        document.querySelectorAll('.date-cell').forEach(cell => {
            cell.textContent = manualDate;
        });
        document.getElementById('attendance-date').value = manualDate;
    });
</script>

<script src="js/popper.min.js"></script>
<script src="js/jquery-3.7.1.js"></script>
<script src="js/bootstrap.js"></script>
</body>
</html>
