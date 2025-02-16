<?php 
$title = "اضافة  ";
include "includes/header.php";
include "includes/nav.php";
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}
?>

<body class="bg-light bg-gradient">

  <div class="container w-50 mt-5 mb-5">
    <!--------form---------->
   <?php
$errors = $_SESSION['errors'] ?? [];
$old_input = $_SESSION['old_input'] ?? [];

// مسح بيانات الجلسة بعد استخدامها
unset($_SESSION['errors']);
unset($_SESSION['old_input']);
?>

<form action="handeller/handelAddEmployee.php" method="POST" enctype="multipart/form-data" class="row g-3 bg-light border border-dark rounded">
  <div class="col-md-12 text-right">
    <label for="name" class="form-label mt-3">الاسم</label>
    <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($old_input['name'] ?? '') ?>">
    <?php if (isset($errors['name'])): ?>
      <div class="error" style="color: red;"><?= $errors['name'] ?></div>
    <?php endif; ?>
  </div>

  <div class="col-md-12 text-right">
    <label for="pic" class="form-label">الصورة الشخصية</label>
    <input type="file" name="image" class="form-control">
    <?php if (isset($errors['image'])): ?>
      <div class="error" style="color: red;"><?= $errors['image'] ?></div>
    <?php endif; ?>
  </div>

  <div class="col-md-6 text-right">
    <label for="DOB" class="form-label">تاريخ الميلاد</label>
    <input type="date" name="dateOfBirth" class="form-control" value="<?= htmlspecialchars($old_input['dateOfBirth'] ?? '') ?>">
    <?php if (isset($errors['dateOfBirth'])): ?>
      <div class="error" style="color: red;"><?= $errors['dateOfBirth'] ?></div>
    <?php endif; ?>
  </div>

  <div class="col-md-6 text-right">
    <label for="phone" class="form-label">رقم التليفون</label>
    <input type="text" name="phone" class="form-control" value="<?= htmlspecialchars($old_input['phone'] ?? '') ?>">
    <?php if (isset($errors['phone'])): ?>
      <div class="error" style="color: red;"><?= $errors['phone'] ?></div>
    <?php endif; ?>
  </div>

  <div class="col-12 text-right">
    <label for="address" class="form-label">العنوان</label>
    <input type="text" name="address" class="form-control" placeholder="1234 Main St" value="<?= htmlspecialchars($old_input['address'] ?? '') ?>">
    <?php if (isset($errors['address'])): ?>
      <div class="error" style="color: red;"><?= $errors['address'] ?></div>
    <?php endif; ?>
  </div>

  <div class="col-md-12 text-right">
    <label for="feesh" class="form-label">الفيش</label>
    <input type="file" name="Criminal_record" class="form-control">
    <?php if (isset($errors['Criminal_record'])): ?>
      <div class="error" style="color: red;"><?= $errors['Criminal_record'] ?></div>
    <?php endif; ?>
  </div>

  <div class="col-12 text-right form-group">
    <label for="job" class="form-label">الوظيفة</label>
    <select name="job" class="form-control">
      <option value="ملاحظ سقالات" <?= (isset($old_input['job']) && $old_input['job'] == 'ملاحظ سقالات') ? 'selected' : '' ?>>ملاحظ سقالات</option>
      <option value="مهندس مساحه" <?= (isset($old_input['job']) && $old_input['job'] == 'مهندس مساحة') ? 'selected' : '' ?>>مهندس مساحه</option>
      <option value="مدير مالى وادارى" <?= (isset($old_input['job']) && $old_input['job'] == 'مدير مالى وادارى') ? 'selected' : '' ?>>مدير مالى وادارى</option>
      <option value="محاسب" <?= (isset($old_input['job']) && $old_input['job'] == 'محاسب') ? 'selected' : '' ?>>محاسب</option>
      <option value="مدير موقع" <?= (isset($old_input['job']) && $old_input['job'] == 'مدير موقع') ? 'selected' : '' ?>>مدير موقع</option>
      <option value="إداري" <?= (isset($old_input['job']) && $old_input['job'] == 'إداري') ? 'selected' : '' ?>>إداري</option>
      <option value="امن صناعي" <?= (isset($old_input['job']) && $old_input['job'] == 'امن صناعي') ? 'selected' : '' ?>>امن صناعي</option>
      <option value="أخصائي سلامة وصحة مهنية" <?= (isset($old_input['job']) && $old_input['job'] == 'أخصائي سلامة وصحة مهنية') ? 'selected' : '' ?>>أخصائي سلامة وصحة مهنية</option>
      <option value="مهندس" <?= (isset($old_input['job']) && $old_input['job'] == 'مهندس') ? 'selected' : '' ?>>مهندس</option>
      <option value="فني" <?= (isset($old_input['job']) && $old_input['job'] == 'فني') ? 'selected' : '' ?>>فني</option>
      <option value="مساعد" <?= (isset($old_input['job']) && $old_input['job'] == 'مساعد') ? 'selected' : '' ?>>مساعد</option>
      <option value="غفير" <?= (isset($old_input['job']) && $old_input['job'] == 'غفير') ? 'selected' : '' ?>>غفير</option>
      <option value="سائق" <?= (isset($old_input['job']) && $old_input['job'] == 'سائق') ? 'selected' : '' ?>>سائق</option>
      <option value="أمين مخزن" <?= (isset($old_input['job']) && $old_input['job'] == 'أمين مخزن') ? 'selected' : '' ?>>أمين مخزن</option>
      <option value="عتال" <?= (isset($old_input['job']) && $old_input['job'] == 'عتال') ? 'selected' : '' ?>>عتال</option>
      <option value="لحام" <?= (isset($old_input['job']) && $old_input['job'] == 'لحام') ? 'selected' : '' ?>>لحام</option>





      






      

    </select>
    <?php if (isset($errors['job'])): ?>
      <div class="error" style="color: red;"><?= $errors['job'] ?></div>
    <?php endif; ?>
  </div>

  <div class="col-8 text-right col-auto">
    <label for="Salary" class="form-label">الاجر اليومي</label>
    <input type="number" name="daily_reward" class="form-control" value="<?= htmlspecialchars($old_input['daily_reward'] ?? '') ?>">
    <?php if (isset($errors['daily_reward'])): ?>
      <div class="error" style="color: red;"><?= $errors['daily_reward'] ?></div>
    <?php endif; ?>
  </div>
  <div class="col-8 text-right col-auto">
    <label for="Salary" class="form-label"> الراتب الشهرى</label>
    <input type="number" name="base_salary" class="form-control" value="<?= htmlspecialchars($old_input['base_salary'] ?? '') ?>">
    <?php if (isset($errors['base_salary'])): ?>
      <div class="error" style="color: red;"><?= $errors['base_salary'] ?></div>
    <?php endif; ?>
  </div>

  <div class="form-check col-auto text-right mt-4">
    <label class="col-form-check-label">معين</label>
    <input type="checkbox" name="is_permanent" class="col-form-check-input" <?= isset($old_input['is_permanent']) && $old_input['is_permanent'] ? 'checked' : '' ?>>
  </div>

  <div class="form-check col-auto text-right mt-4">
    <label class="col-form-check-label">يعمل ثابت</label>
    <input type="checkbox" name="is_fixed_worker" class="col-form-check-input" <?= isset($old_input['is_fixed_worker']) && $old_input['is_fixed_worker'] ? 'checked' : '' ?>>
  </div>

  <div class="col-12 text-right form-group">
    <label for="site" class="form-label">الموقع</label>
    <select id="site" name="site" class="form-control">
    <option value="تيتان بنى سويف" <?= (isset($_SESSION['site']) && $_SESSION['site'] == 'تيتان بنى سويف') ? 'selected' : '' ?>>تيتان بنى سويف</option>
      <option value="بورسعيد" <?= (isset($_SESSION['site']) && $_SESSION['site'] == 'بورسعيد') ? 'selected' : '' ?>>بورسعيد</option>
      <option value="العامرية" <?= (isset($_SESSION['site']) && $_SESSION['site'] == 'العامرية') ? 'selected' : '' ?>>العامرية</option>
      <option value="الماكس" <?= (isset($_SESSION['site']) && $_SESSION['site'] == 'الماكس') ? 'selected' : '' ?>>الماكس</option>
      <option value="المخذن" <?= (isset($_SESSION['site']) && $_SESSION['site'] == 'المخذن') ? 'selected' : '' ?>>المخذن</option>
      <option value="خدمات الشركة" <?= (isset($_SESSION['site']) && $_SESSION['site'] == 'خدمات الشركة') ? 'selected' : '' ?>>خدمات الشركة</option>
      <option value="الادارة" <?= (isset($_SESSION['site']) && $_SESSION['site'] == 'الادارة') ? 'selected' : '' ?>>الادارة</option>
    </select>
    <?php if (isset($errors['site'])): ?>
      <div class="error" style="color: red;"><?= $errors['site'] ?></div>
    <?php endif; ?>
  </div>

  <div class="col-md-6 text-right">
    <label for="start" class="form-label">تاريخ التعيين</label>
    <input type="date" name="start_date" class="form-control" value="<?= htmlspecialchars($old_input['start_date'] ?? '') ?>">
    <?php if (isset($errors['start_date'])): ?>
      <div class="error" style="color: red;"><?= $errors['start_date'] ?></div>
    <?php endif; ?>
  </div>

  <div class="col-md-6 text-right">
    <label for="end" class="form-label">نهاية التعاقد</label>
    <input type="date" name="end_date" class="form-control" value="<?= htmlspecialchars($old_input['end_date'] ?? '') ?>">
    <?php if (isset($errors['end_date'])): ?>
      <div class="error" style="color: red;"><?= $errors['end_date'] ?></div>
    <?php endif; ?>
  </div>

  <div class="col-md-12 text-center">
    <button type="submit" class="btn btn-success mt-3 mb-3">اضافة</button>
  </div>
</form>


  </div>


  <script src="js/popper.min.js"></script>
  <script src="js/jquery-3.7.1.js"></script>
  <script src="js/bootstrap.js"></script>
</body>

</html>