<?php
$title = "المستحقات";
include "includes/header.php";
include "includes/nav.php";
include "data/connection.php";

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

  // جلب بيانات الاستقطاعات الخاصة بالموظف
  $sql_deductions = "SELECT * FROM deductions WHERE employee_id = ?";
  $stmt_deductions = $conn->prepare($sql_deductions);
  $stmt_deductions->bind_param("i", $employeeId);
  $stmt_deductions->execute();
  $result_deductions = $stmt_deductions->get_result();
}
?>

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
      <!--search and add button-->
      <div class="row mt-3 d-flex container">
        <input class="col-3 form-control" type="date" placeholder="اسم الموظف او الكود">
        <button class="col-1 btn btn-success mr-2">بحث</button>
        <button class="col-2 btn btn-primary mr-auto p-2" data-toggle="modal" data-target="#exampleModal"
          data-whatever="@mdo">اضافة استقطاع</button>
      </div>
    </div>

    <!-- start of modal form-->
    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
      aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">الاستقطاعات</h5>
          </div>
          <div class="modal-body">
            <form action="handeller/handelDeduction.php" method="POST" class="text-right">
              <input type="hidden" name="employee_id" value="<?= $employeeId ?>">

              <div class="form-group">
                <label for="deductionName" class="col-form-label">نوع الاستقطاع</label>:</label>
                <select name="deductionName" id="bandname" class="form-control">
                  <option value="سلف">سلف</option>
                  <option value="تأمينات">تأمينات</option>
                  <option value="اجازات بالخصم">اجازات بالخصم</option>
                  <option value="جزاءات">جزاءات</option>
                  <option value="غياب">غياب</option>
                </select>
              </div>

              <div class="form-group">
                <label for="amount" class="col-form-label">قيمة الاستقطاع:</label>
                <input type="number" name="amount" id="amount" class="form-control " step="any">
              </div>

              <div class="form-group">
                <label for="date" class="col-form-label">تاريخ الاستقطاع:</label>
                <input type="date" class="form-control" name="date" value="<?= date('Y-m-d') ?>">
              </div>

              <div class="form-group">
                <label for="notes" class="col-form-label">ملاحظات:</label>
                <textarea name="notes" id="notes" class="form-control" rows="3"></textarea>
              </div>

              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">اغلاق</button>
                <button type="submit" class="btn btn-primary mr-2">اضافة</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
    <!--end modal form-->

    <!-- start of tab section-->
    <ul class="nav nav-tabs">
      <li class="nav-item">
        <a class="nav-link" href="entitlements.php?id=<?= $employeeId ?>">الاستحقاقات</a>
      </li>
      <li class="nav-item">
        <a class="nav-link active" href="deduction.php?id=<?= $employeeId ?>">الاستقطاعات</a>
      </li>
    </ul>

    <!--End of tab section-->

    <!--start of deductions table-->
    <div class="container">
      <table class="table table-hover mt-3 text-right">
        <thead>
          <tr class="table">
            <th scope="col">كود الاستقطاع</th>
            <!-- <th scope="col">كود الموظف</th> -->
            <th scope="col">نوع الاستقطاع </th>
            <th scope="col">قيمة الاستقطاع</th>
            <th scope="col">تاريخ الاستقطاع</th>
            <th scope="col">ملاحظات</th>
            <!-- <th scope="col">###</th> -->

          </tr>
        </thead>
        <tbody>
          <?php
          // عرض الاستقطاعات الخاصة بالموظف
          if ($result_deductions->num_rows > 0) {
            while ($row = $result_deductions->fetch_assoc()) {
              echo "<tr>";
              echo "<td>" . $row['id'] . "</td>"; // كود الاستقطاع
              // echo "<td>" . $row['employee_id'] . "</td>"; // كود الموظف
              echo "<td>" . $row['deduction_name'] . "</td>"; // اسم الاستقطاع
              echo "<td>" . $row['amount'] . "</td>"; // قيمة الاستقطاع
              echo "<td>" . $row['date'] . "</td>"; // تاريخ الاستقطاع
              echo "<td>" . $row['notes'] . "</td>"; // الملاحظات
            //  echo "<td>". "<a href='#' class='btn btn-danger'>حذف</a>"."</td>";

              echo "</tr>";
            }
          } else {
            echo "<tr><td colspan='6'>لا توجد استقطاعات لهذا الموظف.</td></tr>";
          }
          ?>
        </tbody>
      </table>
    </div>
    <!--end of deductions table-->
  </div>

  <script src="js/popper.min.js"></script>
  <script src="js/jquery-3.7.1.js"></script>
  <script src="js/bootstrap.js"></script>
</body>
</html>
