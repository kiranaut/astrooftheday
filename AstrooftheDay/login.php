<?php

require_once __DIR__ . "/astrooftheday/includes/languange.php";
require_once __DIR__ . "/astrooftheday/includes/helpers.php";

//pengguna yang sudah login tidak perlu melihat halaman login lagi
if (isset($_SESSION["user_id"])) {
    redirect("dashboard.php");
}

//mengambil pesan error dari proses login (tampil sekali)
$error_login = $_SESSION["login_error"] ?? [];
unset($_SESSION["login_error"]);

$page_title = t("login_title");
require __DIR__ . "/astrooftheday/includes/header.php";

?>

<section class="card card-narrow">

    <h2><?php echo e(t("login_heading")); ?></h2>

    <?php if (!empty($error_login)) { ?>
        <div class="alert alert-error">
            <?php foreach ($error_login as $kunci) { ?>
                <?php echo e(t($kunci)); ?><br>
            <?php } ?>
        </div>
    <?php } ?>

    <form method="POST" action="proses_post.php" class="form">

        <label for="username"><?php echo e(t("username")); ?></label>
        <input type="text" id="username" name="username" required autofocus>

        <label for="password"><?php echo e(t("password")); ?></label>
        <input type="password" id="password" name="password" required>

        <div class="actions">
            <button type="submit" class="btn"><?php echo e(t("login_btn")); ?></button>
        </div>

    </form>

</section>

<?php require __DIR__ . "/astrooftheday/includes/footer.php"; ?>
