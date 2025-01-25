<?php 
$title = "الموظفين";
include "includes/header.php";
include "includes/nav.php";
include "data/connection.php";
?>

<body>
  <div class="container">
    <div class="row mt-3 d-flex">
      <input id="searchInput" class="col-3 form-control" type="text" placeholder="اسم الموظف او الكود">
      <button id="searchButton" class="col-1 btn btn-success">بحث</button>
      <button class="col-2 btn btn-primary mr-auto p-2" onclick="window.location.href='add-employee.php';">اضافة موظف</button>
    </div>

    <script>
  document.getElementById('searchButton').addEventListener('click', function () {
    const searchValue = document.getElementById('searchInput').value.trim();

    if (searchValue === '') {
      alert('يرجى إدخال اسم الموظف أو الكود للبحث.');
      return;
    }

    // تحقق من نوع الإدخال (رقم أم نص)
    if (/^\d+$/.test(searchValue)) {
      console.log("تم إدخال رقم: " + searchValue);
      // مثال: إعادة التوجيه أو تنفيذ عملية بحث برقم الموظف
      window.location.href = `searchForEmployee.php?id=${encodeURIComponent(searchValue)}`;
    } else {
      console.log("تم إدخال اسم: " + searchValue);
      // مثال: إعادة التوجيه أو تنفيذ عملية بحث باسم الموظف
      window.location.href = `searchForEmployee.php?name=${encodeURIComponent(searchValue)}`;
    }
  });
</script>
    <!-- employee table -->
    <table class="table table-hover mt-3 text-right">
      <thead>
        <tr class="table">
          <th scope="col">الكود</th>
          <th scope="col">اسم الموظف</th>
          <th scope="col">الوظيفة</th>
          <th scope="col">الموقع</th>
          <!-- <th scope="col">الراتب</th> -->
          <th scope="col"> تعديل / حذف</th>
        </tr>
      </thead>
      <tbody>
        <?php
          // Query to retrieve employee data
          $sql = "SELECT id, name, job, site FROM employees";
          $result = $conn->query($sql);

          if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
              $empID = $row['id'];
              echo "<tr>";
              echo "<td>" . $row['id'] . "</td>";
              echo "<td>" . $row['name'] . "</td>";
              echo "<td>" . $row['job'] . "</td>";
              echo "<td>" . $row['site'] . "</td>";
              // echo "<td>" . $row['base_salary'] . "</td>";

              echo "<td>
                      <a href='updateEmployee.php?id=$empID' class='btn btn-warning'>تعديل</a>
                      <a href='employee/deleteEmployee.php?id=$empID' class='btn btn-danger'>حذف</a>
                      <a href='entitlements.php?id=$empID' class='btn btn-success'>عرض</a>

                    </td>";
              echo "</tr>";
            }
          } else {
            echo "<tr><td colspan='5'>لا توجد بيانات</td></tr>";
          }
        ?>
      </tbody>
    </table>
  </div>

  <script src="js/popper.min.js"></script>
  <script src="js/jquery-3.7.1.js"></script>
  <script src="js/bootstrap.js"></script>
</body>
</html>
