<?php
$title = "المستحقات";
include "includes/header.php";
include "includes/nav.php";
include "data/connection.php"; // الاتصال بقاعدة البيانات

// التحقق من أن كود الموظف موجود
$employeeId = null;
$employeeName = "غير معروف";
if (isset($_GET['id'])) {
    $employeeId = $_GET['id'];

    // جلب بيانات الموظف من قاعدة البيانات
    $sql = "SELECT name FROM employees WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $employeeId);
    $stmt->execute();
    $result = $stmt->get_result();

    // التحقق من العثور على الموظف
    if ($result->num_rows > 0) {
        $employee = $result->fetch_assoc();
        $employeeName = $employee['name'];
    } else {
        $employeeName = "غير معروف";
    }
} 
?>
<!DOCTYPE html>
<html lang="ar">
<head>
  <meta charset="UTF-8">
  <title><?= $title ?></title>
  <link rel="stylesheet" href="css/bootstrap.css">
</head>
<body>
  <div class="container-fluid">
    <div class="float-end text-right mt-2 mb-2">
      <div class="badge bg-info text-light">
        <h4>
          <span> كود الموظف:</span>
          <?= $employeeId ?>
        </h4>
      </div>
      <div class="badge bg-info text-light">
        <h4>
          <span> اسم الموظف:</span>
          <?= $employeeName ?>
        </h4>
      </div>
      <!-- إضافة زر البحث وزر الإضافة -->
      <div class="row mt-3 d-flex container">
        <input class="col-3 form-control" type="text" placeholder="بحث باسم الموظف أو الكود">
        <button class="col-1 btn btn-success mr-2">بحث</button>
        <button class="col-2 btn btn-primary mr-auto p-2" data-toggle="modal" data-target="#exampleModal">إضافة استحقاق</button>
      </div>
    </div>

    <!-- نموذج الإضافة -->
    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">إضافة استحقاق</h5>
          </div>
          <div class="modal-body">
            <form action="handeller/handelEntitlement.php" method="POST" class="text-right">
              <input type="hidden" name="employee_id" value="<?= $employeeId ?>">

              <div class="form-group">
                <label for="entitlementName" class="col-form-label">نوع الاستحقاق:</label>
                <select name="entitlementName" id="entitlementName" class="form-control" required>
                  <option value="ساعات اضافية">ساعات اضافية</option>
                  <option value="ساعات اضافية على30">ساعات اضافية على30</option>
                  <option value="حافز بعدد الايام ">حافز بعددالايام</option>
                  <option value="حافز بالقيمة">حافز بالقيمة</option>
                  <option value="مأموريات">مأموريات</option>
                  <option value="فروقات شهور سابقة">فروقات شهور سابقة</option>
                  <option value="  بدل انتقال">بدل انتقال </option>
                  <option value="اخر اضافي">اخر اضافي</option>
                </select>
              </div>
              <div class="form-group">
                <label for="date" class="col-form-label">تاريخ الاستقطاع:</label>
                <input type="date" class="form-control" name="date" value="<?= date('Y-m-d') ?>">
              </div>

              <div class="form-group">
                <label for="entitlementValue" class="col-form-label">قيمة الاستحقاق:</label>
                <input type="number" name="amount" id="entitlementValue" class="form-control" step="any" required>
              </div>
              
              <div class="form-group">
                <label for="entitlementValue" class="col-form-label">ملاحظات</label>
                <input type="text" name="notes" id="entitlementValue" row="3" class="form-control" >
              </div>

              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">إغلاق</button>
                <button type="submit" class="btn btn-primary mr-2">إضافة</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>

    <!-- شريط التبويبات -->
    <ul class="nav nav-tabs">
      <li class="nav-item">
        <a class="nav-link active" href="entitlements.php?id=<?= $employeeId ?>">الاستحقاقات</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="deduction.php?id=<?= $employeeId ?>">الاستقطاعات</a>
      </li>
    </ul>

    <!-- جدول عرض الاستحقاقات -->
    <div class="container">
      <table class="table table-hover mt-3 text-right">
        <thead>
          <tr>
            <th scope="col">كود الاستحقاق</th>
            <th scope="col">نوع الاستحقاق</th>
            <th scope="col">قيمة الاستحقاق</th>
            <th scope="col">التاريخ</th>
            <th scope="col">ملاحظات</th>

          </tr>
        </thead>
        <tbody>
          <?php
          // جلب بيانات الاستحقاقات من قاعدة البيانات
          $sql_entitlements = "SELECT * FROM entitlements WHERE employee_id = ?";
          $stmt_entitlements = $conn->prepare($sql_entitlements);
          $stmt_entitlements->bind_param("i", $employeeId);
          $stmt_entitlements->execute();
          $result_entitlements = $stmt_entitlements->get_result();

          if ($result_entitlements->num_rows > 0) {
              while ($row = $result_entitlements->fetch_assoc()) {
                  echo "<tr>";
                  echo "<td>" . $row['id'] . "</td>";
                  echo "<td>" . $row['entitlement_name'] . "</td>";
                  echo "<td>" . $row['amount'] . "</td>";
                  echo "<td>" . $row['date'] . "</td>";
                  echo "<td>" . $row['notes'] . "</td>";

                  echo "</tr>";
              }
          } else {
              echo "<tr><td colspan='4'>لا توجد استحقاقات لهذا الموظف.</td></tr>";
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
