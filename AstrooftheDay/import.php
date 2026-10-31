<?php

//memeriksa session pengguna
require_once __DIR__ . "/astrooftheday/includes/auth.php";
require_once __DIR__ . "/astrooftheday/includes/languange.php";
require_once __DIR__ . "/astrooftheday/includes/helpers.php";

//menghubungkan database
require_once __DIR__ . "/astrooftheday/config/db.php";

$message = "";
$success = 0;
$fail = 0;

//memeriksa apakah tombol import ditekan
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    //memeriksa apakah file sudah dipilih
    if (isset($_FILES["csv_file"]) &&
        $_FILES["csv_file"]["error"] == 0) {

        $file = $_FILES["csv_file"]["tmp_name"];

        //membuka file CSV
        $handle = fopen($file, "r");

        if ($handle !== false) {

            //membaca baris pertama sebagai header
            $header = fgetcsv($handle, 0, ",", '"', "\\");

            if ($header !== false) {

                //membersihkan nama kolom
                $header[0] = preg_replace('/^\xEF\xBB\xBF/', '', $header[0]);

                $header = array_map("trim", $header);

                //memeriksa kolom CSV
                $kolom_wajib = [
                    "date",
                    "title",
                    "explanation",
                    "copyright",
                    "media_type",
                    "url",
                    "hdurl"
                ];

                if (count(array_diff($kolom_wajib, $header)) == 0) {

                    //menyiapkan query untuk memasukkan data
                    $sql = "INSERT INTO apod
                            (date, title, explanation, copyright,
                             media_type, image_url, hdurl)
                            VALUES (?, ?, ?, ?, ?, ?, ?)
                            ON DUPLICATE KEY UPDATE
                            title = VALUES(title),
                            explanation = VALUES(explanation),
                            copyright = VALUES(copyright),
                            media_type = VALUES(media_type),
                            image_url = VALUES(image_url),
                            hdurl = VALUES(hdurl)";

                    $stmt = $conn->prepare($sql);

                    if ($stmt) {

                        //membaca data CSV satu per satu
                        while (($row = fgetcsv($handle, 0, ",", '"', "\\")) !== false) {

                            // Melewati baris kosong
                            if (count($row) != count($header)) {
                                $fail++;
                                continue;
                            }

                            //menggabungkan header dengan data
                            $data = array_combine($header, $row);

                            //mengambil isi setiap kolom
                            $date = trim($data["date"]);
                            $title = trim($data["title"]);
                            $explanation = trim($data["explanation"]);
                            $copyright = bersihkan_teks($data["copyright"]);
                            $media_type = trim($data["media_type"]);
                            $image_url = trim($data["url"]);
                            $hdurl = trim($data["hdurl"]);

                            //memeriksa format tanggal
                            $tanggal = DateTime::createFromFormat(
                                "!Y-m-d",
                                $date
                            );

                            if (
                                !$tanggal ||
                                $tanggal->format("Y-m-d") != $date ||
                                empty($title) ||
                                empty($explanation)
                            ) {
                                $fail++;
                                continue;
                            }

                            //memasukkan data ke database
                            $stmt->bind_param(
                                "sssssss",
                                $date,
                                $title,
                                $explanation,
                                $copyright,
                                $media_type,
                                $image_url,
                                $hdurl
                            );

                            try {
                                $stmt->execute();
                                $success++;
                            } catch (mysqli_sql_exception $e) {
                                $fail++;
                            }
                        }

                        $stmt->close();

                        $message = "import_done";

                    } else {
                        $message = "import_err_prepare";
                    }

                } else {
                    $message = "import_err_cols";
                }

            } else {
                $message = "import_err_empty";
            }

            fclose($handle);

        } else {
            $message = "import_err_open";
        }

    } else {
        $message = "import_err_nofile";
    }
}

$page_title = t("import_title");
require __DIR__ . "/astrooftheday/includes/header.php";

?>

<h2><?php echo e(t("import_title")); ?></h2>

<p><?php echo e(t("import_desc")); ?></p>

<section class="card">

    <form method="POST" enctype="multipart/form-data" class="form">

        <label for="csv_file"><?php echo e(t("import_choose")); ?></label>

        <input type="file" id="csv_file" name="csv_file" accept=".csv" required>

        <div class="actions">
            <button type="submit" class="btn"><?php echo e(t("import_btn")); ?></button>
        </div>

    </form>

</section>

<?php if (!empty($message)) { ?>

    <div class="alert <?php echo $message == "import_done" ? "alert-success" : "alert-error"; ?>">
        <p><?php echo e(t($message)); ?></p>

        <p><?php echo e(t("import_ok_rows", $success)); ?></p>

        <p><?php echo e(t("import_fail_rows", $fail)); ?></p>

        <a href="data.php"><?php echo e(t("nav_data")); ?></a> |
        <a href="gallery.php"><?php echo e(t("nav_gallery")); ?></a>
    </div>

<?php } ?>

<?php require __DIR__ . "/astrooftheday/includes/footer.php"; ?>
