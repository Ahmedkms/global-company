<?php 
include "includes/header.php";
include "includes/nav.php";
?>

<body>
  <form action="">
    <div class="container w-50 mt-5 ">
      <div class="form-group">
        <label > name</label>
        <input class="form-control" type="text" placeholder="enter your name!">
        <small class="form-text text-muted"> *necessary</small>
      </div>

      <div class="form-group">
        <label > Email</label>
        <input class="form-control" type="text" placeholder="enter your Email!">
        <small class="form-text text-muted"> *necessary</small>
      </div>

      <div class="form-group">
        <label > password</label>
        <input class="form-control" type="password" placeholder="enter your password!">
        <small class="form-text text-muted"> *necessary</small>
      </div>

      <div class="form-group">
        <select class="form-select"> select
          <option value="">male</option>
          <option value="">female</option>
        </select>
      </div>

      <div class="form-check">
        <input type="checkbox" class="form-check-input">
        <label class="form-check-label"> remmber me!</label>
      </div>

      <div class="form-group">
        <button class="btn btn-primary"> submit</button>
      </div>

    </div>
  </form>  

    <script src="js/popper.min.js"></script>
    <script src="js/jquery-3.7.1.js"></script>
    <script src="js/bootstrap.js"></script>
</body>

</html>