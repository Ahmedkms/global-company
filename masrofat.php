<?php
$title = "المصروفات";
include "includes/header.php";
include "includes/nav.php";
include "data/connection.php";


if (session_status() === PHP_SESSION_NONE) {
  session_start();
}


// Retrieve errors and old input from the session
$errors = $_SESSION['errors'] ?? [];
$old_input = $_SESSION['old_data'] ?? [];

// Clear session data after using it
unset($_SESSION['errors']);


?>


<body>
  <!-- navbar section-->

  <div class="container">
    <form action="handeller/handelMasroofat.php" method="POST" class="mt-4">
      <div class="row align-items-center g-3">

        <!-- حقل البند -->
        <div class="col-md-2">
          <input type="text" name="bandname" class="form-control" placeholder="البند" value="<?= htmlspecialchars($_SESSION['old_data']['bandname'] ?? '') ?>">
          <?php if (isset($errors['bandname'])): ?>
            <div class="error"><?= $errors['bandname'] ?></div>
          <?php endif; ?>
        </div>

        <!-- حقل المبلغ -->
        <div class="col-md-2">
          <input type="text" name="amount" class="form-control" placeholder="المبلغ" value="<?= htmlspecialchars($_SESSION['old_data']['amount'] ?? '') ?>">
          <?php if (isset($errors['amount'])): ?>
            <div class="error"><?= $errors['amount'] ?></div>
          <?php endif; ?>
        </div>

        <!-- حقل التاريخ -->
        <div class="col-md-3">
          <input type="date" name="date" class="form-control" value="<?= htmlspecialchars($_SESSION['old_data']['date'] ?? '') ?>">
          <?php if (isset($errors['date'])): ?>
            <div class="error"><?= $errors['date'] ?></div>
          <?php endif; ?>
        </div>

        <!-- حقل ملاحظات -->
        <div class="col-md-3">
          <input type="text" name="comment" class="form-control" placeholder="ملاحظات" value="<?= htmlspecialchars($_SESSION['old_data']['comment'] ?? '') ?>">
        </div>
        <?php unset($_SESSION['old_data']); ?>
        <!-- زر إضافة بند -->
        <div class="col-md-2">
          <button type="submit" class="btn btn-primary w-100">إضافة بند</button>
        </div>
      </div>
    </form>
  </div>
  <!--end of masrofat form-->

  <!--Masrofat table-->
  <table class="table table-hover mt-3">
    <thead>
      <tr class="table">
        <th scope="col">الكود</th>
        <th scope="col">البند</th>
        <th scope="col">المبلغ </th>
        <th scope="col">التاريخ</th>
        <th scope="col">ملاحظات</th>
        <th scope="col">تعديل / حذف</th>
      </tr>
    </thead>
    <tbody>
      <?php
      // Query to retrieve employee data
      $sql = "SELECT id, bandname, amount,date, comment FROM masrofat";
      $result = $conn->query($sql);

      if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
          $id = $row["id"];
          echo "<tr>";
          echo "<td>" . $row['id'] . "</td>";
          echo "<td>" . $row['bandname'] . "</td>";
          echo "<td>" . $row['amount'] . "</td>";
          echo "<td>" . $row['date'] . "</td>";
          echo "<td>" . $row['comment'] . "</td>";

          echo "<td>
                      <a href='updateMasrofat.php?id=$id' class='btn btn-warning'>تعديل</a>
                      <a href='masrofat/deleteMasrofat.php?id=$id' class='btn btn-danger'>حذف</a>
                    </td>";
          echo "</tr>";
        }
      } else {
        echo "<tr><td colspan='5'>لا توجد بيانات</td></tr>";
      }
      ?>
    </tbody>
  </table>
  <!--End of Masrofat table-->

  <script src="js/popper.min.js"></script>
  <script src="js/jquery-3.7.1.js"></script>
  <script src="js/bootstrap.js"></script>
</body>