<?php
$title = "تعديل الموظف"; 
include "includes/header.php";
include "includes/nav.php";
include "data/connection.php";
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

if(isset($_GET['id'])){
  $empid = $_GET['id'];
  $sql = "SELECT * FROM employees WHERE id = $empid ";
  $result = $conn->query($sql);
  if ($result && $result->num_rows > 0) {
    $employee = $result->fetch_assoc();
    $name = $employee['name'];
    $destination = $employee['image'];
    $dateOfBirth = $employee['date_of_birth'];
    $phone = $employee['phone'];
    $address = $employee['address'];
    $Criminal_record_destination = $employee['criminal_record'];
    $job = $employee['job'];
    $daily_reward = $employee['daily_reward'];
    $base_salary = $employee['base_salary'];
    $is_permanent = $employee['is_permanent'];  // Make sure this is fetched from the database
    $is_fixed_worker = $employee['is_fixed_worker'];  // Make sure this is fetched from the database
    $site = $employee['site'];
    $start_date = $employee['start_date'];
    $end_date = $employee['end_date'];
  } else {
    // Handle error if employee not found
    $_SESSION['errors'] = ["Employee not found."];
    redirect("../employee.php");
  }
}
?>
<head>
  <title>تعديل </title>
</head>
<body class="bg-light bg-gradient">

  <div class="container w-50 mt-5 mb-5">
    <!--------form---------->
    <form action="handeller/handelUpdateEmployee.php" method="POST" enctype="multipart/form-data" class="row g-3 bg-light border border-dark rounded">
      <div class="col-md-6 text-right">
        <label for="name" class="form-label mt-3">الاسم</label>
        <input type="text" name="name" value="<?=$name?>" class="form-control">
      </div>
      <div class="col-md-6 text-right">
        <label for="code" class="form-label mt-3">الكود</label>
        <input type="text" value="<?=$empid?>" name="id" class="form-control" readonly>
      </div>

      <div class="col-md-6 text-right">
        <label for="DOB" class="form-label">تاريخ الميلاد</label>
        <input type="date" value="<?=$dateOfBirth?>" name="dateOfBirth" class="form-control">
      </div>
      <div class="col-md-6 text-right">
        <label for="phone" class="form-label">رقم التليفون</label>
        <input type="text" value="<?=$phone?>" name="phone" class="form-control">
      </div>
      <div class="col-12 text-right">
        <label for="address" class="form-label">العنوان</label>
        <input type="text" name="address" value="<?=$address?>" class="form-control" placeholder="1234 Main St">
      </div>

      <div class="col-12 text-right form-group">
        <label for="job" class="form-label">الوظيفة</label>
        <select name="job" class="form-control">
          <option value="مشرف سقالات" <?= ($job == 'مشرف سقالات') ? 'selected' : '' ?>>مشرف سقالات</option>
          <option value="مهندس" <?= ($job == 'مهندس') ? 'selected' : '' ?>>مهندس</option>
          <option value="عامل" <?= ($job == 'عامل') ? 'selected' : '' ?>>عامل</option>
          <option value="محاسب" <?= ($job == 'محاسب') ? 'selected' : '' ?>>محاسب</option>
          <option value="مدير موقع" <?= ($job == 'مدير موقع') ? 'selected' : '' ?>>مدير موقع</option>
        </select>
      </div>

      <div class="col-8 text-right col-auto">
        <label for="Salary" class="form-label">الاجر اليومي</label>
        <input type="text" name="daily_reward" value="<?=$daily_reward?>" class="form-control" placeholder="Enter daily wage">
      </div>
      <div class="col-8 text-right col-auto">
        <label for="Salary" class="form-label"> الراتب الشهري</label>
        <input type="text" name="base_salary" value="<?=$base_salary?>" class="form-control" placeholder="Enter base salary">
      </div>

      <div class="form-check col-auto text-right mt-4">
        <label class="col-form-check-label">معين</label>
        <input type="checkbox" name="is_permanent" class="col-form-check-input" <?= ($is_permanent == 1) ? 'checked' : '' ?>>
      </div>
      <div class="form-check col-auto text-right mt-4">
        <label class="col-form-check-label">يعمل ثابت</label>
        <input type="checkbox" name="is_fixed_worker" class="col-form-check-input" <?= ($is_fixed_worker == 1) ? 'checked' : '' ?>>
      </div>

      <div class="col-12 text-right form-group">
        <label for="site" class="form-label">الموقع</label>
        <select id="site" name="site" class="form-control">
          <option value="titan" <?= ($site == 'titan') ? 'selected' : '' ?>>titan</option>
          <option value="amryah" <?= ($site == 'amryah') ? 'selected' : '' ?>>amryah</option>
          <option value="site2" <?= ($site == 'site2') ? 'selected' : '' ?>>الموقع2</option>
          <option value="site3" <?= ($site == 'site3') ? 'selected' : '' ?>>الموقع3</option>
          <option value="site4" <?= ($site == 'site4') ? 'selected' : '' ?>>الموقع4</option>
          <option value="site5" <?= ($site == 'site5') ? 'selected' : '' ?>>الموقع5</option>
        </select>
      </div>

      <div class="col-md-6 text-right">
        <label for="start" class="form-label">تاريخ التعيين</label>
        <input type="date" name="start_date" value="<?=$start_date?>" class="form-control">
      </div>
      <div class="col-md-6 text-right">
        <label for="end" class="form-label">نهاية التعاقد</label>
        <input type="date" name="end_date" value="<?=$end_date?>" class="form-control">
      </div>

      <div class="col-md-12 text-center">
        <button type="submit" class="btn btn-success mt-3 mb-3">تعديل</button>
      </div>
    </form>
  </div>

  <script src="js/popper.min.js"></script>
  <script src="js/jquery-3.7.1.js"></script>
  <script src="js/bootstrap.js"></script>
</body>

</html>
