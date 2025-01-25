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

// تحديد الشهر والسنة من المدخلات أو الشهر والسنة الحاليين افتراضيًا
$search_month = isset($_GET['month']) ? $_GET['month'] : date('Y-m');
$search_month_parts = explode("-", $search_month);
$search_year = $search_month_parts[0];
$search_month = $search_month_parts[1];

?>

  <!-- navbar section-->
  <div class="container">
    <form action="masrofat.php" method="GET" class="d-flex text-right mt-2">
      <div class="row align-items-center g-3">
        <label class="col-md-1">الشهر:</label>
        <input class="col-md-2 form-control me-2" type="month" name="month" value="<?= isset($_GET['month']) ? $_GET['month'] : date('Y-m'); ?>" required>
        <button class="col-md-1 mr-1 btn btn-success" type="submit">بحث </button>
    </form>

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
        <div class="col-md-2">
          <input type="date" name="date" class="form-control" value="<?= htmlspecialchars($_SESSION['old_data']['date'] ?? '') ?>">
          <?php if (isset($errors['date'])): ?>
            <div class="error"><?= $errors['date'] ?></div>
          <?php endif; ?>
        </div>
        <div class="col-md-2">
          <select name="site" id="site" class="form-control">
      <option value="titan" <?= (isset($_SESSION['site']) && $_SESSION['site'] == 'titan') ? 'selected' : '' ?>>titan</option>
      <option value="amryah" <?= (isset($_SESSION['site']) && $_SESSION['site'] == 'amryah') ? 'selected' : '' ?>>ammreah</option>
      <option value="site2" <?= (isset($_SESSION['site']) && $_SESSION['site'] == 'site2') ? 'selected' : '' ?>>الموقع2</option>
      <option value="site3" <?= (isset($_SESSION['site']) && $_SESSION['site'] == 'site3') ? 'selected' : '' ?>>الموقع3</option>
      <option value="site4" <?= (isset($_SESSION['site']) && $_SESSION['site'] == 'site4') ? 'selected' : '' ?>>الموقع4</option>
      <option value="site5" <?= (isset($_SESSION['site']) && $_SESSION['site'] == 'site5') ? 'selected' : '' ?>>الموقع5</option>
          </select>
        </div>

        <!-- حقل ملاحظات -->
        <div class="col-md-2">
          <input type="text" name="comment" class="form-control" placeholder="ملاحظات" value="<?= htmlspecialchars($_SESSION['old_data']['comment'] ?? '') ?>">
        </div>
        <?php unset($_SESSION['old_data']); ?>
        <!-- زر إضافة بند -->
        <div class="col-md-2">
          <button type="submit" class="btn btn-primary w-100">إضافة بند</button>
        </div>
      </div>
    </form>
    <!--end of masrofat form-->

    <!--Masrofat table-->
    <table class="table text-right table-hover mt-3">
      <thead>
        <tr class="table">
          <th scope="col">الكود</th>
          <th scope="col">البند</th>
          <th scope="col">المبلغ </th>
          <th scope="col">التاريخ</th>
          <th scope="col">الموقع</th>
          <th scope="col">ملاحظات</th>
          <th scope="col">تعديل / حذف</th>
        </tr>
      </thead>
      <tbody>
        <?php
        // Query to retrieve filtered masrofat data
        $sql = "
        SELECT id, bandname, amount, date,site, comment 
        FROM masrofat 
        WHERE MONTH(date) = $search_month AND YEAR(date) = $search_year
      ";
        $result = $conn->query($sql);
        $summisionOfMasrofat = 0;

        if ($result->num_rows > 0) {
          while ($row = $result->fetch_assoc()) {
            $id = $row["id"];
            $summisionOfMasrofat += $row['amount'];
            echo "<tr>";
            echo "<td>" . $row['id'] . "</td>";
            echo "<td>" . $row['bandname'] . "</td>";
            echo "<td>" . $row['amount'] . "</td>";
            echo "<td>" . $row['date'] . "</td>";
            echo "<td>" . $row['site'] . "</td>";
            echo "<td>" . $row['comment'] . "</td>";

            echo "<td>
                      <a href='updateMasrofat.php?id=$id' class='btn btn-warning'>تعديل</a>
                      <a href='masrofat/deleteMasrofat.php?id=$id' class='btn btn-danger'>حذف</a>
                    </td>";
            echo "</tr>";
          }
        } else {
          echo "<tr><td colspan='6'>لا توجد بيانات</td></tr>";
        }

        ?>
      </tbody>
    </table>
  </div>
  <!--End of Masrofat table-->

  <script src="js/popper.min.js"></script>
  <script src="js/jquery-3.7.1.js"></script>
  <script src="js/bootstrap.js"></script>
  </body>

  </html>