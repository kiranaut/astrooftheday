<?php

//memeriksa session pengguna
require_once __DIR__ . "/astrooftheday/includes/auth.php";
require_once __DIR__ . "/astrooftheday/includes/languange.php";
require_once __DIR__ . "/astrooftheday/includes/helpers.php";

//menghubungkan database
require_once __DIR__ . "/astrooftheday/config/db.php";

//menghitung ringkasan data APOD
$sql = "SELECT COUNT(*) AS total,
               SUM(media_type = 'image') AS total_image,
               SUM(media_type = 'video') AS total_video,
               MIN(date) AS tanggal_awal,
               MAX(date) AS tanggal_akhir
        FROM apod";

$result = $conn->query($sql);
$ringkasan = $result->fetch_assoc();

$total_apod  = (int)$ringkasan["total"];
$total_image = (int)$ringkasan["total_image"];
$total_video = (int)$ringkasan["total_video"];

$rentang = "-";

if ($total_apod > 0) {
    $rentang = $ringkasan["tanggal_awal"] . " - " . $ringkasan["tanggal_akhir"];
}

$page_title = t("dashboard_title");
require __DIR__ . "/astrooftheday/includes/header.php";

?>

<h2><?php echo e(t("dashboard_title")); ?></h2>

<p><?php echo e(t("welcome", $_SESSION["username"])); ?></p>

<div class="stats">
    <div class="stat">
        <span class="stat-label"><?php echo e(t("total_data")); ?></span>
        <span class="stat-value"><?php echo $total_apod; ?></span>
    </div>
    <div class="stat">
        <span class="stat-label"><?php echo e(t("total_images")); ?></span>
        <span class="stat-value"><?php echo $total_image; ?></span>
    </div>
    <div class="stat">
        <span class="stat-label"><?php echo e(t("total_videos")); ?></span>
        <span class="stat-value"><?php echo $total_video; ?></span>
    </div>
    <div class="stat">
        <span class="stat-label"><?php echo e(t("date_range")); ?></span>
        <span class="stat-value stat-small"><?php echo e($rentang); ?></span>
    </div>
</div>

<h3><?php echo e(t("menu")); ?></h3>

<ul>
    <li><a href="data.php"><?php echo e(t("nav_data")); ?></a></li>
    <li><a href="add.php"><?php echo e(t("nav_add")); ?></a></li>
    <li><a href="gallery.php"><?php echo e(t("nav_gallery")); ?></a></li>
    <li><a href="import.php"><?php echo e(t("nav_import")); ?></a></li>
    <li><a href="logout.php"><?php echo e(t("nav_logout")); ?></a></li>
</ul>

<?php require __DIR__ . "/astrooftheday/includes/footer.php"; ?>
