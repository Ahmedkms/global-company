<?php
$title = "بحث عن موظف";
include "logicCode/functions.php";
include "data/connection.php";
include "includes/header.php";
include "includes/nav.php";
$employees = [];

if (isset($_GET["id"])) {
    $id = $_GET['id'];
    $sql = "SELECT * FROM employees WHERE id = $id";
    $result = $conn->query($sql);
    if ($result && $result->num_rows > 0) {
        $employee = $result->fetch_assoc();
        $employees[] = $employee; 
    }
} elseif (isset($_GET["name"])) {
    $name = $_GET['name'];
    $sql = "SELECT * FROM employees WHERE name LIKE '%$name%'"; 
    $result = $conn->query($sql);
    if ($result && $result->num_rows > 0) {
        while ($employee = $result->fetch_assoc()) {
            $employees[] = $employee; 
        }
    }
}
?>
<body>
    <div class="container">
        <!-- employee table -->
        <table class="table table-hover mt-3 text-right">
            <thead>
                <tr class="table">
                    <th scope="col">الكود</th>
                    <th scope="col">اسم الموظف</th>
                    <th scope="col">الوظيفة</th>
                    <th scope="col">الموقع</th>
                    <th scope="col"> تعديل / حذف</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if (!empty($employees)) {
                    foreach ($employees as $employee) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($employee['id']) . "</td>";
                        echo "<td>" . htmlspecialchars($employee['name']) . "</td>";
                        echo "<td>" . htmlspecialchars($employee['job']) . "</td>";
                        echo "<td>" . htmlspecialchars($employee['site']) . "</td>";
                        echo "<td>
                                <a href='updateEmployee.php?id=" . $employee['id'] . "' class='btn btn-warning'>تعديل</a>
                                <a href='employee/deleteEmployee.php?id=" . $employee['id'] . "' class='btn btn-danger'>حذف</a>
                                <a href='#?id=" . $employee['id'] . "' class='btn btn-success'>عرض</a>

                            </td>";
                        echo "</tr>";
                    }
                } else {

                    echo "<tr><td colspan='5'> لا توجد بيانات مشابهة</td></tr>";
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
