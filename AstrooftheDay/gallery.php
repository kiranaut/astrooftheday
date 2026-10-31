<?php

//memeriksa session pengguna
require_once __DIR__ . "/astrooftheday/includes/auth.php";
require_once __DIR__ . "/astrooftheday/includes/languange.php";
require_once __DIR__ . "/astrooftheday/includes/helpers.php";

//menghubungkan database
require_once __DIR__ . "/astrooftheday/config/db.php";

//mengambil data APOD dari database
$sql = "SELECT * FROM apod ORDER BY date DESC";

$result = $conn->query($sql);

$page_title = t("gallery_title");
require __DIR__ . "/astrooftheday/includes/header.php";

?>

<h2><?php echo e(t("gallery_title")); ?></h2>

<p><?php echo e(t("welcome", $_SESSION["username"])); ?></p>

<?php

//memeriksa data tersedia
if ($result->num_rows > 0) {

    //menampilkan setiap data APOD
    while ($row = $result->fetch_assoc()) {

?>

    <div class="gallery-item">

        <h3>
            <a href="detail.php?id=<?php echo (int)$row["id"]; ?>">
                <?php echo e($row["title"]); ?>
            </a>
        </h3>

        <p><?php echo e(t("date_label")); ?>: <?php echo e($row["date"]); ?></p>

        <?php if ($row["media_type"] == "image" && !empty($row["image_url"])) { ?>

            <img src="<?php echo e($row["image_url"]); ?>"
                 alt="<?php echo e($row["title"]); ?>"
                 width="300" loading="lazy">

        <?php } else { ?>

            <p><?php echo e(t("video_note")); ?></p>

        <?php } ?>

        <p><?php echo e($row["explanation"]); ?></p>

        <p>
            <?php echo e(t("copyright_label")); ?>:
            <?php echo e($row["copyright"] !== "" ? $row["copyright"] : t("unknown")); ?>
        </p>

        <p><a href="detail.php?id=<?php echo (int)$row["id"]; ?>"><?php echo e(t("view_detail")); ?></a></p>

    </div>

<?php

    }

} else {

    echo "<p>" . e(t("no_results")) . "</p>";

}

require __DIR__ . "/astrooftheday/includes/footer.php";

?>
