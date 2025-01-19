<?php 
include "includes/header.php";
include "includes/nav.php";
?>

<body>
  
<div class="container text-right">
    <h1 class="my-4">تسجيل الدخول</h1>
    <?php if (isset($_SESSION["errors"])) :
   foreach($_SESSION["errors"] as $error):    
    ?> 
    <div class="alert alert-danger ">
        <?= $error ?>
    </div>
    <?php endforeach;
    endif; 
    unset($_SESSION["errors"]);
    ?>
    <form method="POST" action="handeller/handel_login.php">
        <div class="mb-3 col-8 ">
            <label for="user Name" class="form-label ">اسم المستخدم</label>
            <input type="text" class="form-control" id="user" name="user">
        </div>

        <div class="mb-3 col-8">
            <label for="password" class="form-label ">كلمة المرور</label>
            <input type="password" class="form-control" id="password" name="password" >
        </div>

        <button type="submit" class="btn btn-primary col-3 mr-3">تسجيل الدخول</button>
    </form>

    <!-- <p class="mt-3">Don't have an account? <a href="register.php">Register here</a></p> -->
</div>
  
</body>
</html>

