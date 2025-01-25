<?php
$title = "المرتبات";
include "includes/header.php";
include "includes/nav.php";
include "data/connection.php";

// الحصول على الشهر والسنة من المدخلات
$search_month = isset($_GET['month']) ? $_GET['month'] : date('Y-m'); // الشهر الحالي إذا لم يتم إدخال شيء
$search_month_parts = explode("-", $search_month);
$search_year = intval($search_month_parts[0]);
$search_month = intval($search_month_parts[1]);

if (!is_numeric($search_month) || !is_numeric($search_year)) {
    die("Invalid month or year input");
}

$sql = "
  WITH AttendanceCount AS (
    SELECT 
        employee_id,
        COUNT(*) AS attendance_days
    FROM attendance
    WHERE status = 'present'
      AND MONTH(attendance_date) = $search_month
      AND YEAR(attendance_date) = $search_year
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
        SUM(CASE WHEN entitlement_name = 'مأموريات' THEN amount ELSE 0 END) AS errands,
        SUM(CASE WHEN entitlement_name = 'فروقات شهور سابقة' THEN amount ELSE 0 END) AS previous_differences,
        SUM(CASE WHEN entitlement_name = 'اخر اضافي' THEN amount ELSE 0 END) AS other_extra,
        SUM(amount) AS total_entitlements
    FROM entitlements
    WHERE MONTH(date) = $search_month
      AND YEAR(date) = $search_year
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
    WHERE MONTH(date) = $search_month
      AND YEAR(date) = $search_year
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
    COALESCE(es.errands, 0) AS errands,
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
if (!$result) {
    die("Query Error: " . $conn->error);
}
?>

<body>
  <div class="container-fluid">
    <!-- شريط التبويبات -->
    <ul class="nav nav-tabs">
      <li class="nav-item">
        <a class="nav-link active" href="salary.php">المرتبات</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="attendance.php">الحضور اليومي</a>
      </li>
    </ul>
    <!-- البحث -->
    <form class="d-flex text-right mt-2" method="GET" action="salary.php">
      <label class="col-md-1">الشهر:</label>
      <input class="col-md-2 form-control me-2" type="month" name="month" value="<?= isset($_GET['month']) ? $_GET['month'] : date('Y-m'); ?>" required>
      <button class="col-md-1 mr-1 btn btn-success" type="submit">إنشاء تقرير</button>
    </form>
    <!-- الجدول -->
    <div class="table-responsive">
      <table class="table table-hover mt-3 text-right">
        <thead>
          <tr>
            <th>الكود</th>
            <th>اسم الموظف</th>
            <th>الموقع</th>
            <th>الراتب الأساسي</th>
            <th>الوظيفة</th>
            <th>الراتب اليومي</th>
            <th>عدد أيام الحضور</th>
            <th>إجمالي الراتب</th>
            <th>ساعات إضافية</th>
            <th>ساعات إضافية على 30</th>
            <th>حافز بعدد الأيام</th>
            <th>حافز بالقيمة</th>
            <th>بدل انتقال</th>
            <th> مأموريات</th>
            <th>فروقات شهور سابقة</th>
            <th>إضافي آخر</th>
            <th>سلف</th>
            <th>التأمينات</th>
            <th>الإجازات بالخصم</th>
            <th>جزاءات</th>
            <th>غياب</th>
            <th>إجمالي الاستحقاقات</th>
            <th>إجمالي الاستقطاعات</th>
            <th>صافي الراتب</th>
            <th>###</th>
          </tr>
        </thead>
        <tbody>
          <?php
          while ($row = $result->fetch_assoc()) {
              $totalSalary = ($row['attendance_days'] * $row['daily_reward']) + $row['base_salary'];
              $net_salary = $row['net_salary'] + $totalSalary;
              $empid = $row['employee_id'];

              // إعداد استعلام الإدراج أو التحديث
              $query = "
                  INSERT INTO saved_salaries (
                      employee_id, employee_name, site, base_salary, job, daily_reward, attendance_days, 
                      overtime, overtime_30, incentive_days, incentive_value, transport_allowance,errands, 
                      previous_differences, other_extra, advance, insurance, leave_deduction, 
                      penalties, absence, total_entitlements, total_deductions, net_salary, month, year
                  ) VALUES (
                      $empid, '{$row['employee_name']}', '{$row['site']}', {$row['base_salary']}, '{$row['job']}', 
                      {$row['daily_reward']}, {$row['attendance_days']}, {$row['overtime']}, {$row['overtime_30']}, 
                      {$row['incentive_days']}, {$row['incentive_value']}, {$row['transport_allowance']},{$row['errands']}, 
                      {$row['previous_differences']}, {$row['other_extra']}, {$row['advance']}, {$row['insurance']}, 
                      {$row['leave_deduction']}, {$row['penalties']}, {$row['absence']}, {$row['total_entitlements']}, 
                      {$row['total_deductions']}, $net_salary, $search_month, $search_year
                  )
                  ON DUPLICATE KEY UPDATE 
                      employee_name = VALUES(employee_name),
                      site = VALUES(site),
                      base_salary = VALUES(base_salary),
                      job = VALUES(job),
                      daily_reward = VALUES(daily_reward),
                      attendance_days = VALUES(attendance_days),
                      overtime = VALUES(overtime),
                      overtime_30 = VALUES(overtime_30),
                      incentive_days = VALUES(incentive_days),
                      incentive_value = VALUES(incentive_value),
                      transport_allowance = VALUES(transport_allowance),
                      errands = VALUES(errands),
                      previous_differences = VALUES(previous_differences),
                      other_extra = VALUES(other_extra),
                      advance = VALUES(advance),
                      insurance = VALUES(insurance),
                      leave_deduction = VALUES(leave_deduction),
                      penalties = VALUES(penalties),
                      absence = VALUES(absence),
                      total_entitlements = VALUES(total_entitlements),
                      total_deductions = VALUES(total_deductions),
                      net_salary = VALUES(net_salary)
              ";
              $conn->query($query);

              // عرض الصفوف
              echo "<tr>";
              echo "<td>{$row['employee_id']}</td>";
              echo "<td>{$row['employee_name']}</td>";
              echo "<td>{$row['site']}</td>";
              echo "<td>{$row['base_salary']}</td>";
              echo "<td>{$row['job']}</td>";
              echo "<td>{$row['daily_reward']}</td>";
              echo "<td>{$row['attendance_days']}</td>";
              echo "<td>$totalSalary</td>";
              echo "<td>{$row['overtime']}</td>";
              echo "<td>{$row['overtime_30']}</td>";
              echo "<td>{$row['incentive_days']}</td>";
              echo "<td>{$row['incentive_value']}</td>";
              echo "<td>{$row['transport_allowance']}</td>";
              echo "<td>{$row['errands']}</td>";
              echo "<td>{$row['previous_differences']}</td>";
              echo "<td>{$row['other_extra']}</td>";
              echo "<td>{$row['advance']}</td>";
              echo "<td>{$row['insurance']}</td>";
              echo "<td>{$row['leave_deduction']}</td>";
              echo "<td>{$row['penalties']}</td>";
              echo "<td>{$row['absence']}</td>";
              echo "<td>{$row['total_entitlements']}</td>";
              echo "<td>{$row['total_deductions']}</td>";
              echo "<td>$net_salary</td>";
              echo "<td>
              <a href='entitlements.php?id=$empid' class='btn btn-success'>عرض</a>

            </td>";
              echo "</tr>";
          }
          ?>
        </tbody>
      </table>
    </div>
  </div>
</body>
