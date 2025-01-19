<?php
$title = "تعديل المصروف";
include "includes/header.php";
include "includes/nav.php";
include "data/connection.php";


if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$errors = $_SESSION['errors'] ?? [];

if (isset($_GET["id"])) {
    $id = $_GET["id"];
    $sql = "SELECT * FROM masrofat WHERE id = $id ";
    $result = $conn->query($sql);
    if ($result && $result->num_rows > 0) {
        $masroof = $result->fetch_assoc();
        $bandname = $masroof["bandname"];
        $amount = $masroof["amount"];
        $date = $masroof["date"];
        $comment = $masroof["comment"];
    }
}
?>
<body>
    <!-- navbar section-->

    <div class="container">
        <form action="handeller/handelUpdateMasrofat.php" method="POST" class="mt-4">
            <div class="row align-items-center g-2">

                <!-- حقل البند -->
                <div class="col-md-1">
                    <input type="text" name="id" class="form-control" placeholder="كود البند" value="<?= htmlspecialchars($id) ?>">
                </div>
                <div class="col-md-2">
                    <input type="text" name="bandname" class="form-control" placeholder="البند" value="<?= htmlspecialchars($bandname) ?>">
                    <?php if (isset($errors['bandname'])): ?>
                        <div class="error"><?= $errors['bandname'] ?></div>
                    <?php endif; ?>
                </div>

                <!-- حقل المبلغ -->
                <div class="col-md-2">
                    <input type="text" name="amount" class="form-control" placeholder="المبلغ" value="<?= htmlspecialchars($amount) ?>">
                    <?php if (isset($errors['amount'])): ?>
                        <div class="error"><?= $errors['amount'] ?></div>
                    <?php endif; ?>
                </div>

                <!-- حقل التاريخ -->

                <div class="col-md-2">
                    <input type="date" name="date" class="form-control" value="<?= htmlspecialchars($date) ?>">
                    <?php if (isset($errors['date'])): ?>
                        <div class="error"><?= $errors['date'] ?></div>
                    <?php endif; ?>
                </div>

                <!-- حقل ملاحظات -->
                <div class="col-md-3">
                    <input type="text" name="comment" class="form-control" placeholder="ملاحظات" value="<?= htmlspecialchars($comment) ?>">
                </div>
                <?php unset($_SESSION['old_data']); ?>
                <!-- زر إضافة بند -->
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">تعديل البند</button>
                </div>
            </div>
        </form>
    </div>
    <!--end of masrofat form-->

    <script src="js/popper.min.js"></script>
    <script src="js/jquery-3.7.1.js"></script>
    <script src="js/bootstrap.js"></script>
</body>