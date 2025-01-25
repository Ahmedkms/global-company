<?php
$title = "المرتبات";
include "includes/header.php";
include "includes/nav.php";
include "data/connection.php";

// الحصول على الشهر والسنة من المدخلات
$search_month = isset($_GET['month']) ? $_GET['month'] : date('Y-m'); // الشهر الحالي إذا لم يتم إدخال شيء
$search_month_parts = explode("-", $search_month);
$search_year = $search_month_parts[0];
$search_month = $search_month_parts[1];

$sql = "
  WITH AttendanceCount AS (
    SELECT 
        employee_id,
        COUNT(*) AS attendance_days
    FROM attendance
    WHERE status = 'present'
    GROUP BY employee_id
),
EntitlementsSummary AS (
    SELECT 
        employee_id,
        SUM(CASE WHEN entitlement_name = 'ساعات اضافية' THEN amount ELSE 0 END) AS overtime,
        SUM(CASE WHEN entitlement_name = 'ساعات اضافية على30' THEN amount ELSE 0 END) AS overtime_30,
        SUM(CASE WHEN entitlement_name = 'حافز بعدد الايام' THEN amount ELSE 0 END) AS incentive_days,
        SUM(CASE WHEN entitlement_name = 'حافز بالقيمة' THEN amount ELSE 0 END) AS incentive_value,
        SUM(CASE WHEN entitlement_name = 'بدل انتقال' THEN amount ELSE 0 END) AS transport_allowance,
        SUM(CASE WHEN entitlement_name = 'فروقات شهور سابقة' THEN amount ELSE 0 END) AS previous_differences,
        SUM(CASE WHEN entitlement_name = 'اخر اضافي' THEN amount ELSE 0 END) AS other_extra,
        SUM(amount) AS total_entitlements
    FROM entitlements
    GROUP BY employee_id
),
DeductionsSummary AS (
    SELECT 
        employee_id,
        SUM(CASE WHEN deduction_name = 'سلف' THEN amount ELSE 0 END) AS advance,
        SUM(CASE WHEN deduction_name = 'تأمينات' THEN amount ELSE 0 END) AS insurance,
        SUM(CASE WHEN deduction_name = 'اجازات بالخصم' THEN amount ELSE 0 END) AS leave_deduction,
        SUM(CASE WHEN deduction_name = 'جزاءات' THEN amount ELSE 0 END) AS penalties,
        SUM(CASE WHEN deduction_name = 'غياب' THEN amount ELSE 0 END) AS absence,
        SUM(amount) AS total_deductions
    FROM deductions
    GROUP BY employee_id
)
SELECT 
    e.id AS employee_id,
    e.name AS employee_name,
    e.site,
    e.base_salary,
    e.job,
    e.daily_reward,
    COALESCE(ac.attendance_days, 0) AS attendance_days,
    COALESCE(es.overtime, 0) AS overtime,
    COALESCE(es.overtime_30, 0) AS overtime_30,
    COALESCE(es.incentive_days, 0) AS incentive_days,
    COALESCE(es.incentive_value, 0) AS incentive_value,
    COALESCE(es.transport_allowance, 0) AS transport_allowance,
    COALESCE(es.previous_differences, 0) AS previous_differences,
    COALESCE(es.other_extra, 0) AS other_extra,
    COALESCE(ds.advance, 0) AS advance,
    COALESCE(ds.insurance, 0) AS insurance,
    COALESCE(ds.leave_deduction, 0) AS leave_deduction,
    COALESCE(ds.penalties, 0) AS penalties,
    COALESCE(ds.absence, 0) AS absence,
    COALESCE(es.total_entitlements, 0) AS total_entitlements,
    COALESCE(ds.total_deductions, 0) AS total_deductions,
    (COALESCE(es.total_entitlements, 0) - COALESCE(ds.total_deductions, 0)) AS net_salary
FROM employees e
LEFT JOIN AttendanceCount ac ON e.id = ac.employee_id
LEFT JOIN EntitlementsSummary es ON e.id = es.employee_id
LEFT JOIN DeductionsSummary ds ON e.id = ds.employee_id;

";

$result = $conn->query($sql);

?>

<body>
  <div class="container-fluid">
    <!-- start of tap section-->
    <ul class="nav nav-tabs">
      <li class="nav-item">
        <a class="nav-link active" href="salary.php">المرتبات</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="attendance.php">الحضور اليومي</a>
      </li>
    </ul>
    <!-- Search tap-->
    <form class="d-flex text-right mt-2" method="GET" action="salary.php">
      <label class="col-md-1">الشهر:</label>
      <input class="col-md-2 form-control me-2" type="month" name="month" value="<?= isset($_GET['month']) ? $_GET['month'] : date('Y-m'); ?>" required>
      <button class="col-md-1 mr-1 btn btn-success" type="submit">انشاء تقرير</button>
    </form>
    <!--mostakt3at table-->
    <div class="table-responsive">
      <table class=" table table-hover mt-3 text-right">
        <thead>
          <tr>
            <th scope="col">الكود</th>
            <th scope="col">اسم الموظف</th>
            <th scope="col">الموقع</th>
            <th scope="col">الراتب الأساسي</th>
            <th scope="col">الوظيفة</th>
            <th scope="col">الراتب اليومي</th>
            <th scope="col">عدد أيام الحضور</th>
            <th scope="col">ساعات إضافية</th>
            <th scope="col">ساعات إضافية على 30</th>
            <th scope="col">حافز بعدد الأيام</th>
            <th scope="col">حافز بالقيمة</th>
            <th scope="col">بدل انتقال</th>
            <th scope="col">فروقات شهور سابقة</th>
            <th scope="col">إضافي آخر</th>
            <th scope="col">سلف</th>
            <th scope="col">التأمينات</th>
            <th scope="col">الإجازات بالخصم</th>
            <th scope="col">جزاءات</th>
            <th scope="col">غياب</th>
            <th scope="col">إجمالي الاستحقاقات</th>
            <th scope="col">إجمالي الاستقطاعات</th>
            <th scope="col">صافي الراتب</th>
          </tr>
        </thead>

        <tbody>
  <?php
  if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
      // تحويل القيم إلى أرقام إذا لم تكن موجودة
      $attendance_days = (int)$row['attendance_days']; // تحويل إلى عدد صحيح
      $overtime = (float)$row['overtime']; // تحويل إلى عدد عشري
      $base_salary = (float)$row['base_salary']; // تحويل إلى عدد عشري
      $overtime_30 = (float)$row['overtime_30'];
      $incentive_days = (float)$row['incentive_days'];
      $incentive_value = (float)$row['incentive_value'];
      $transport_allowance = (float)$row['transport_allowance'];
      $previous_differences = (float)$row['previous_differences'];
      $other_extra = (float)$row['other_extra'];
      $advance = (float)$row['advance'];
      $insurance = (float)$row['insurance'];
      $leave_deduction = (float)$row['leave_deduction'];
      $penalties = (float)$row['penalties'];
      $absence = (float)$row['absence'];
      $total_entitlements = (float)$row['total_entitlements'];
      $total_deductions = (float)$row['total_deductions'];
      $net_salary = (float)$row['net_salary'];

      echo "<tr>";
      echo "<td>" . $row['employee_id'] . "</td>";
      echo "<td>" . $row['employee_name'] . "</td>";
      echo "<td>" . $row['site'] . "</td>";
      echo "<td>" .  $base_salary  . "</td>";
      echo "<td>" . $row['job'] . "</td>";
      echo "<td>" . $row['daily_reward'] . "</td>";
      echo "<td>" . $attendance_days . "</td>";
      echo "<td>" . $overtime . "</td>";
      echo "<td>" . $overtime_30 . "</td>";
      echo "<td>" . $incentive_days . "</td>";
      echo "<td>" . $incentive_value . "</td>";
      echo "<td>" . $transport_allowance . "</td>";
      echo "<td>" . $previous_differences . "</td>";
      echo "<td>" . $other_extra . "</td>";
      echo "<td>" . $advance . "</td>";
      echo "<td>" . $insurance . "</td>";
      echo "<td>" . $leave_deduction . "</td>";
      echo "<td>" . $penalties . "</td>";
      echo "<td>" . $absence . "</td>";
      echo "<td>" . $total_entitlements . "</td>";
      echo "<td>" . $total_deductions . "</td>";
      echo "<td>" . $net_salary . "</td>";
      echo "</tr>";
    }
  } else {
    echo "<tr><td colspan='20'>لا توجد بيانات</td></tr>";
  }
  ?>
</tbody>

      </table>
    </div>
  </div>
  <script src="js/popper.min.js"></script>
  <script src="js/jquery-3.7.1.js"></script>
  <script src="js/bootstrap.js"></script>
</body>

</html>