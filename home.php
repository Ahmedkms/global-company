<?php
$title = "الشركة العالمية"; 
include "includes/header.php";
include "includes/nav.php";
?>

<body>

  <!-- logIN and SIGN up menu-->
  <!-- end of navbar section-->
  <div class="container">

    <!-- Slider section-->
    <div id="carouselExampleIndicators" class="carousel slide ">
      <ol class="carousel-indicators">
        <li data-target="#carouselExampleIndicators" data-slide-to="0" class="active" aria-current="true"
          aria-label="Slide 1"></li>
        <li data-target="#carouselExampleIndicators" data-slide-to="1" aria-label="Slide 2"></li>
        <li data-target="#carouselExampleIndicators" data-slide-to="2" aria-label="Slide 3"></li>
      </ol>
      <div class="carousel-inner">
        <div class="carousel-item active">
          <img src="images/cover.png" class="d-block w-100">
        </div>
        <div class="carousel-item">
          <img src="images/cover.png" class="d-block w-100">
        </div>
        <div class="carousel-item">
          <img src="images/cover.png" class="d-block w-100">
        </div>
      </div>
      <div class="carousel-control-prev" data-target="#carouselExampleIndicators" data-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
      </div>
      <div class="carousel-control-next" data-target="#carouselExampleIndicators" data-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
      </div>
    </div>
  </div>


  <script src="js/popper.min.js"></script>
  <script src="js/jquery-3.7.1.js"></script>
  <script src="js/bootstrap.js"></script>
</body>

</html>