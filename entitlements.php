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
<body>
  <div class="container-fluid">
    <div class="float-end text-right mt-2 mb-2">
      <div class="badge bg-info text-light">
        <h4>
          <span> كود الموظف:</span>
          <?=$employeeId ?>
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
          data-whatever="@mdo">اضافة استحقاق</button>
      </div>
    </div>

    <!-- modal form-->
    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
      aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">الاستحقاقات</h5>
          </div>
          <div class="modal-body">
            <form class="text-right">
              <div class="form-group">
                <label for="recipient-name" class="col-form-label">عدد أيام الحضور:</label>
                <input type="text" class="form-control" id="recipient-name">
              </div>
              <div class="form-group">
                <label for="recipient-name" class="col-form-label"> ساعات اضافية:</label>
                <input type="text" class="form-control" id="recipient-name">
              </div>
              <div class="form-group">
                <label for="recipient-name" class="col-form-label"> اخر اضافي:</label>
                <input type="text" class="form-control" id="recipient-name">
              </div>
              <div class="form-group">
                <label for="recipient-name" class="col-form-label"> حافز (عدد الأيام) :</label>
                <input type="text" class="form-control" id="recipient-name">
              </div>
              <div class="form-group">
                <label for="recipient-name" class="col-form-label"> حافز (قيمة ) :</label>
                <input type="text" class="form-control" id="recipient-name">
              </div>
              <div class="form-group">
                <label for="recipient-name" class="col-form-label"> مأموريات:</label>
                <input type="text" class="form-control" id="recipient-name">
              </div>
              <div class="form-group">
                <label for="recipient-name" class="col-form-label"> فروقات شهور سابقة:</label>
                <input type="text" class="form-control" id="recipient-name">
              </div>
            </form>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">اغلاق</button>
            <button type="button" class="btn btn-primary mr-2">اضافة </button>
          </div>
        </div>
      </div>
    </div>
    <!--end modal form-->

    <!-- start of tap section-->
    <ul class="nav nav-tabs">
      <li class="nav-item">
        <a class="nav-link active" href="entitlements.php?id=<?=$employeeId?>">الاستحقاقات</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="deduction.php?id=<?=$employeeId?>">الاستقطاعات</a>
      </li>
    </ul>
    <!--End of tap section-->
    <div class="container">

      <!--mosthkat table-->
      <table class=" table table-hover mt-3 text-right">
        <thead>
          <tr class="table">
            <th scope="col">كود الاستحقاق</th>
            <th scope="col">عدد أيام الحضور </th>
            <th scope="col">  التاريخ </th>
            <th scope="col">ساعات اضافية</th>
            <th scope="col">اخر اضافية</th>
            <th scope="col"> حافز </th>
            <th scope="col"> مأموريات </th>
            <th scope="col"> فروقات شهور سابقة </th>
            <th scope="col"> المجموع </th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>001</td>
            <td> 28</td>
            <td>20/1/2025</td>
            <td> 45</td>
            <td> 230</td>
            <td> 34 </td>
            <td> 12</td>
            <td>420 </td>
            <td>12678</td>
          </tr>
         
        </tbody>
      </table>
    </div>
    <!--End of mosthkat table-->
  </div>
  <script>
    $('#exampleModal').on('show.bs.modal', function (event) {
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