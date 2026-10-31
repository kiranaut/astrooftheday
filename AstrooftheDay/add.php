<?php

//memeriksa session pengguna
require_once __DIR__ . "/astrooftheday/includes/auth.php";
require_once __DIR__ . "/astrooftheday/includes/languange.php";
require_once __DIR__ . "/astrooftheday/includes/helpers.php";

//menghubungkan database
require_once __DIR__ . "/astrooftheday/config/db.php";

//nilai awal form
$form = [
    "date"        => date("Y-m-d"),
    "title"       => "",
    "explanation" => "",
    "copyright"   => "",
    "media_type"  => "image",
    "url"         => "",
    "hdurl"       => "",
];

$errors = [];

//memeriksa apakah form dikirim
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    //membersihkan input
    $form = baca_input_apod($_POST);

    if (!csrf_valid($_POST["csrf_token"] ?? "")) {

        $errors["form"] = "err_csrf";

    } else {

        //memvalidasi kelengkapan data
        $errors = validasi_apod($form);

        //memeriksa apakah tanggal sudah dipakai data lain
        if (!isset($errors["date"])) {
            $stmt = $conn->prepare("SELECT id FROM apod WHERE date = ?");
            $stmt->bind_param("s", $form["date"]);
            $stmt->execute();
            $stmt->store_result();

            if ($stmt->num_rows > 0) {
                $errors["date"] = "err_date_duplicate";
            }

            $stmt->close();
        }

        //jika semua valid, simpan ke database
        if (empty($errors)) {

            try {
                $stmt = $conn->prepare(
                    "INSERT INTO apod
                     (date, title, explanation, copyright, media_type, image_url, hdurl)
                     VALUES (?, ?, ?, ?, ?, ?, ?)"
                );

                $stmt->bind_param(
                    "sssssss",
                    $form["date"],
                    $form["title"],
                    $form["explanation"],
                    $form["copyright"],
                    $form["media_type"],
                    $form["url"],
                    $form["hdurl"]
                );

                $stmt->execute();
                $stmt->close();

                flash_set("success", "msg_added");
                redirect("data.php");

            } catch (mysqli_sql_exception $e) {
                $errors["form"] = "err_save_failed";
            }
        }
    }
}

$page_title = t("add_title");
$tombol_kunci = "btn_save";
require __DIR__ . "/astrooftheday/includes/header.php";

?>

<h2><?php echo e(t("add_title")); ?></h2>

<section class="card">
    <?php require __DIR__ . "/astrooftheday/includes/form_apod.php"; ?>
</section>

<?php require __DIR__ . "/astrooftheday/includes/footer.php"; ?>
