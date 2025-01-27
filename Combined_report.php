<?php
$title = "التقرير الشهرى المجمع";
include "includes/header.php";
include "includes/nav.php";
include "data/connection.php";  // الاتصال بقاعدة البيانات

// تعيين اللغة العربية
setlocale(LC_TIME, 'ar_AR.UTF-8'); // تأكد من دعم اللغة العربية في السيرفر

// الحصول على الشهر والسنة من مدخلات المستخدم أو استخدام التاريخ الحالي
$selected_month = isset($_GET['month']) ? $_GET['month'] : date('Y-m');
$selected_date = DateTime::createFromFormat('Y-m', $selected_month);
$month_name_ar = strftime('%B %Y', $selected_date->getTimestamp()); // اسم الشهر والسنة بالعربية

// تقسيم الشهر المدخل إلى سنة وشهر
$search_year = intval($selected_date->format('Y'));
$search_month = intval($selected_date->format('m'));

// استعلام لجلب البيانات المجمعّة من جدول saved_salaries
$sql = "
  SELECT 
    employee_id,
    employee_name,
    base_salary + daily_reward * attendance_days AS total_salary,
    total_entitlements,
    total_deductions,
    net_salary
  FROM saved_salaries
  WHERE month = ? AND year = ?
";

$stmt = $conn->prepare($sql);
$stmt->bind_param('ii', $search_month, $search_year); // ربط المتغيرات من نوع integer
$stmt->execute();
$result = $stmt->get_result();
?>

<div class="container-fluid">
  <h4 class="text-center mt-5">الشركة العالمية للمقاولات العمومية والتوريدات</h4>
  
  <!-- إدخال الشهر -->
  <form class="d-flex justify-content-end mt-3 mb-3" method="GET" action="">
    <label class="me-2">اختر الشهر:</label>
    <input type="month" name="month" value="<?= $selected_month ?>" class="form-control col-md-2 me-2">
    <button type="submit" class="btn btn-primary">بحث</button>
  </form>

  <div class="float-end text-right mt-3 mb-3">
    <div class="badge bg-dark text-light">
      <h4>
        <span>مرتبات شهر:</span>
        <?= $month_name_ar ?> <!-- عرض الشهر باللغة العربية -->
      </h4>
    </div>
    <div class="badge bg-dark text-light">
      <h4>
        <span>اسم الموقع:</span>
        العامرية
      </h4>
    </div>
  </div>

  <!-- جدول المرتبات -->
  <table class="table table-hover mt-3 text-right">
    <thead>
      <tr class="table">
        <th scope="col">كود الموظف</th>
        <th scope="col">اسم الموظف</th>
        <th scope="col">اجمالي الاستحقاقات</th>
        <th scope="col">اجمالي الاستقطاعات</th>
        <th scope="col">صافي الراتب</th>
      </tr>
    </thead>
    <tbody>
      <?php
      if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
          echo "<tr>";
          echo "<td>{$row['employee_id']}</td>";
          echo "<td>{$row['employee_name']}</td>";
          echo "<td>" . number_format($row['total_entitlements'], 2) . "</td>";
          echo "<td>" . number_format($row['total_deductions'], 2) . "</td>";
          echo "<td>" . number_format($row['net_salary'], 2) . "</td>";
          echo "</tr>";
        }
      } else {
        echo "<tr><td colspan='5' class='text-center'>لا توجد بيانات للشهر المحدد</td></tr>";
      }
      ?>
    </tbody>
  </table>
</div>

<script src="js/popper.min.js"></script>
<script src="js/jquery-3.7.1.js"></script>
<script src="js/bootstrap.js"></script>
</body>
</html>
