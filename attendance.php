<?php
$title = "الحضور";
include "includes/header.php";
include "includes/nav.php";
include "data/connection.php";
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (isset($_SESSION['errors'])) {
    foreach ($_SESSION['errors'] as $error) {
        // Ensure $error is a string before using addslashes
        if (is_string($error)) {
            echo "<script>
                alert('" . addslashes($error) . "');
            </script>";
        } 
    }
    unset($_SESSION['errors']); // Clear errors after displaying them
}
if (isset($_SESSION['success'])) {
  
      // Ensure $error is a string before using addslashes
      if (is_string($_SESSION['success'])) {
          echo "<script>
              alert('" . addslashes($_SESSION['success']) . "');
          </script>";
      } 
      unset($_SESSION['success']);
  }

  //check if attendance information not absence for allemployee
  if (isset($_SESSION['isPresent'])) {
  
    // Ensure $error is a string before using addslashes
    if (is_string($_SESSION['isPresent'])) {
        echo "<script>
            alert('" . addslashes($_SESSION['isPresent']) . "');
        </script>";
    } 
    unset($_SESSION['isPresent']);
}



$sql = "SELECT id, name FROM employees";
$result = $conn->query($sql);
?>

<body>
<div class="container-fluid">
    <!-- start of tab section-->
    <ul class="nav nav-tabs">
        <li class="nav-item">
            <a class="nav-link " href="salary.php">المرتبات</a>
        </li>
        <li class="nav-item">
            <a class="nav-link active" href="attendance.php">الحضور اليومي</a>
        </li>
    </ul>

    <!--search and add tab-->
    <div class="row mt-2">
        <label class="col-md-1">الشهر:</label>
        <input class="col-md-2 form-control me-2" type="date" id="month-search" placeholder="Search" aria-label="Search">
        <button class="col-md-1 mr-1 btn btn-success" id="search-btn" type="button">بحث</button>
        <div class="col-md-3"></div>
        <label class="col-md-1">التاريخ:</label>
        <input class="col-md-2 form-control me-2" type="date" id="manual-date" placeholder="Search" aria-label="Search">
        <button class="col-md-1 mr-1 btn btn-warning" id="add-day" type="button">اضافة يوم</button>
    </div>
    <script>
        document.getElementById('search-btn').addEventListener('click', function () {
            const searchDate = document.getElementById('month-search').value;

            if (!searchDate) {
                alert('يجب إدخال تاريخ للبحث');
                return;
            }

            // Redirect to searchAttendance.php with the date as a query parameter
            window.location.href = `searchAttendance.php?search_date=${searchDate}`;
        });
    </script>

    <!--End of tab section-->
    <form action="handeller/handelAttendance.php" method="POST" class="form mb-2">
        <!--attendance table-->
        <table class="table table-hover mt-3 text-right" id="attendance-table">
            <thead>
            <tr class="table">
                <th scope="col">الكود</th>
                <th scope="col">اسم الموظف</th>
                <th scope="col">تاريخ اليوم</th>
                <th scope="col">الحضور</th>
            </tr>
            </thead>
            <tbody>
            <?php
            // Display the data inside the table
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                            <td>{$row['id']}</td>
                            <td>{$row['name']}</td>
                            <td class='date-cell'></td>
                            <td>
                                <input type='checkbox' name='attendance[{$row['id']}]' value='Present'>
                            </td>
                          </tr>";
                }
            } else {
                echo "<tr>
                        <td colspan='4'>لا توجد بيانات للعرض</td>
                      </tr>";
            }
            ?>
            </tbody>
        </table>
        <!-- Hidden input to send the date with the form -->
        <input type="hidden" name="attendance_date" id="attendance-date">
        <button class="btn btn-primary" type="submit">حفظ الكل</button>
    </form>
</div>

<script>
    // On page load
    document.addEventListener('DOMContentLoaded', function () {
        const today = new Date().toISOString().split('T')[0];

        // Set default date in the hidden input
        document.getElementById('attendance-date').value = today;

        // Set default date in table cells
        const dateCells = document.querySelectorAll('.date-cell');
        dateCells.forEach(cell => {
            cell.textContent = today; // Set date in the cell
        });
    });

    // On clicking "Add Day" button
    document.getElementById('add-day').addEventListener('click', function () {
        // Get the manually entered date
        const manualDate = document.getElementById('manual-date').value;

        // Check if a date was entered
        if (!manualDate) {
            // If no date entered, alert the user
            alert('يجب إدخال التاريخ قبل إضافة اليوم');
            return; // Stop the function
        }

        // Get today's date
        const today = new Date();
        today.setHours(0, 0, 0, 0); // Normalize time for accurate comparison

        // Convert manualDate to a Date object
        const selectedDate = new Date(manualDate);

        // Check if the entered date is in the future
        if (selectedDate > today) {
            alert('لا يمكن إدخال يوم لم يأتِ بعد');
            return; // Stop the function
        }

        // If a valid date is entered, update the table
        // Update all date cells in the table
        const dateCells = document.querySelectorAll('.date-cell');
        dateCells.forEach(cell => {
            cell.textContent = manualDate; // Set date in the cell
        });

        // Update the hidden input to send with the form
        document.getElementById('attendance-date').value = manualDate;
    });
</script>

<script src="js/popper.min.js"></script>
<script src="js/jquery-3.7.1.js"></script>
<script src="js/bootstrap.js"></script>
</body>
</html>
