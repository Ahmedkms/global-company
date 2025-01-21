<?php 
$title = "المرتبات";
include "includes/header.php";
include "includes/nav.php";
?>

<body>
 <div class="container-fluid">
    <!-- start of tap section-->
    <ul class="nav nav-tabs">
      <li class="nav-item">
        <a class="nav-link active" href="salary.php">المرتبات</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="attendance.php">الحضور اليومي</a>
      </li>
    </ul>
    <!-- Search tap-->
    <form class="d-flex text-right mt-2">
      <label class="col-md-1">الشهر:</label>
      <input class="col-md-2 form-control me-2" type="Date" placeholder="Search" aria-label="Search">
      <button class="col-md-1 mr-1 btn btn-success" type="submit">بحث</button>
    </form>
    <!--mostakt3at table-->
    <div class="table-responsive">
      <table class=" table table-hover mt-3 text-right">
        <thead>
          <tr class="table">
            <th scope="col">الكود</th>
            <th scope="col">الاسم </th>
            <th scope="col">الموقع</th>
            <th scope="col">الراتب</th>
            <th scope="col">الوظيفة</th>
            <th scope="col">الراتب اليومي</th>
            <th scope="col">عدد ايام الحضور</th>
            <th scope="col">اجمالي الراتب</th>
            <th scope="col">سلف</th>
            <th scope="col">تأمينات اجتماعية</th>
            <th scope="col">اجازات بالخصم</th>
            <th scope="col">جزاءات</th>
            <th scope="col">فروقات شهور</th>
            <th scope="col">غياب</th>
            <th scope="col">حافز</th>
            <th scope="col">مأموريات</th>
            <th scope="col">سهر</th>
            <th scope="col">اجمالي الاستحقاقات</th>
            <th scope="col">اجمالي الاستقطاعات</th>
            <th scope="col">صافي الراتب</th>
            <th scope="col">تقرير </th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td >0</td>
            <td > 0</td>
            <td >0</td>
            <td >0</td>
            <td >0</td>
            <td >0 </td>
            <td > 0</td>
            <td > 0</td>
            <td >0</td>
            <td > 0</td>
            <td > 0</td>
            <td >0</td>
            <td >0 </td>
            <td >0</td>
            <td >0</td>
            <td >0</td>
            <td >0</td>
            <td > 0</td>
            <td >0 </td>
            <td >0 </td>
            <td >0 </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
    <script src="js/popper.min.js"></script>
    <script src="js/jquery-3.7.1.js"></script>
    <script src="js/bootstrap.js"></script>
</body>

</html>