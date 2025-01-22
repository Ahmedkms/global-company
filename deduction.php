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
        <input class=" col-3 form-control " type="date" placeholder="اسم الموظف او الكود">
        <button class="col-1 btn btn-success mr-2"> بحث</button>
        <button class="col-2 btn btn-primary mr-auto p-2" data-toggle="modal" data-target="#exampleModal"
          data-whatever="@mdo">اضافة استقطاع</button>
      </div>
    </div>
    <!-- start ofmodal form-->
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
                <label for="bandname" class="col-form-label">الاستقطاعات:</label>
                <select name="bandname" id="bandname" class="form-control">
                  <option value="loan">سلف</option>
                  <option value="insurance">تأمينات</option>
                  <option value="vacation">اجازات بالخصم</option>
                  <option value="penalties">جزاءات</option>
                  <option value="absence">غياب</option>
                </select>
              </div>
              <div class="form-group">
                <label for="notes" class="col-form-label"></label>قيمة الاستقطاع</label>
                <input type="number" name="amount" id="amount" class="form-control"></input>
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
    <!-- start of tap section-->
    <ul class="nav nav-tabs">
      <li class="nav-item">
        <a class="nav-link" href="entitlements.php?id=<?= $employeeId ?>">الاستحقاقات</a>
      </li>
      <li class="nav-item">
        <a class="nav-link active" href="deduction.php?id=<?= $employeeId ?>">الاستقطاعات</a>
      </li>
    </ul>
    <!--End of tap section-->
    <!--add mostakt3at form-->
    <div class="container">
      <!--add mosthkat form-->

      <!--mosthkat table-->
      <table class=" table table-hover mt-3 text-right">
        <thead>
          <tr class="table">
            <th scope="col">كود الاستقطاع</th>
            <th scope="col">سلف</th>
            <th scope="col">تأمينات </th>
            <th scope="col">إجازات بالخصم </th>
            <th scope="col"> جزاءات </th>
            <th scope="col"> غياب </th>
            <th scope="col"> المجموع </th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>001</td>
            <td> 28</td>
            <td> 45</td>
            <td> 230</td>
            <td> 34 </td>
            <td> 12</td>
            <td>12678</td>
          </tr>

        </tbody>
      </table>
    </div>
    <!--End of mosthkat table-->
  </div>
  <script>
    $('#exampleModal').on('show.bs.modal', function(event) {
      var button = $(event.relatedTarget) // Button that triggered the modal
      var recipient = button.data('whatever') // Extract info from data-* attributes
      // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
      // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
      var modal = $(this)
      modal.find('.modal-title').text('New message to ' + recipient)
      modal.find('.modal-body input').val(recipient)
    })
  </script>
  <script src="js/popper.min.js"></script>
  <script src="js/jquery-3.7.1.js"></script>
  <script src="js/bootstrap.js"></script>
</body>

</html>