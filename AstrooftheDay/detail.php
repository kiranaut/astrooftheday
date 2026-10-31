<?php

//memeriksa session pengguna
require_once __DIR__ . "/astrooftheday/includes/auth.php";
require_once __DIR__ . "/astrooftheday/includes/languange.php";
require_once __DIR__ . "/astrooftheday/includes/helpers.php";

//menghubungkan database
require_once __DIR__ . "/astrooftheday/config/db.php";

//memeriksa ID data
$id = ambil_id($_GET["id"] ?? null);

if ($id === null) {
    flash_set("error", "err_invalid_id");
    redirect("data.php");
}

//mengambil data berdasarkan ID
$stmt = $conn->prepare("SELECT * FROM apod WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$row = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$row) {
    flash_set("error", "err_not_found");
    redirect("data.php");
}

$page_title = t("detail_title");
require __DIR__ . "/astrooftheday/includes/header.php";

?>

<h2><?php echo e(t("detail_title")); ?></h2>

<section class="card">

    <h3><?php echo e($row["title"]); ?></h3>

    <p>
        <strong><?php echo e(t("date_label")); ?>:</strong>
        <?php echo e($row["date"]); ?>
        &middot;
        <strong><?php echo e(t("col_type")); ?>:</strong>
        <?php echo e($row["media_type"] == "video" ? t("opt_video") : t("opt_image")); ?>
    </p>

    <?php if ($row["media_type"] == "image" && $row["image_url"] !== "") { ?>
        <img src="<?php echo e($row["image_url"]); ?>"
             alt="<?php echo e($row["title"]); ?>" class="detail-img">
    <?php } else { ?>
        <p><em><?php echo e(t("video_note")); ?></em></p>
        <p><a href="<?php echo e($row["image_url"]); ?>" target="_blank" rel="noopener noreferrer">
            <?php echo e(t("open_link")); ?></a></p>
    <?php } ?>

    <p class="explanation"><?php echo nl2br(e($row["explanation"])); ?></p>

    <p>
        <strong><?php echo e(t("copyright_label")); ?>:</strong>
        <?php echo e($row["copyright"] !== "" ? $row["copyright"] : t("unknown")); ?>
    </p>

    <div class="actions">
        <a href="edit.php?id=<?php echo (int)$row["id"]; ?>" class="btn"><?php echo e(t("act_edit")); ?></a>
        <a href="data.php" class="btn btn-secondary"><?php echo e(t("btn_back")); ?></a>
    </div>

</section>

<?php require __DIR__ . "/astrooftheday/includes/footer.php"; ?>
