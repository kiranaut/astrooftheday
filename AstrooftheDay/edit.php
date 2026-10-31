<?php

//memeriksa session pengguna
require_once __DIR__ . "/astrooftheday/includes/auth.php";
require_once __DIR__ . "/astrooftheday/includes/languange.php";
require_once __DIR__ . "/astrooftheday/includes/helpers.php";

//menghubungkan database
require_once __DIR__ . "/astrooftheday/config/db.php";

//mengambil ID data yang akan diubah (dari URL saat membuka, dari form saat menyimpan)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = ambil_id($_POST["id"] ?? null);
} else {
    $id = ambil_id($_GET["id"] ?? null);
}

if ($id === null) {
    flash_set("error", "err_invalid_id");
    redirect("data.php");
}

//mengambil data lama berdasarkan ID
$stmt = $conn->prepare(
    "SELECT date, title, explanation, copyright, media_type, image_url, hdurl
     FROM apod WHERE id = ?"
);
$stmt->bind_param("i", $id);
$stmt->execute();
$lama = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$lama) {
    flash_set("error", "err_not_found");
    redirect("data.php");
}

//nilai awal form berasal dari data lama
$form = [
    "date"        => $lama["date"],
    "title"       => $lama["title"],
    "explanation" => $lama["explanation"],
    "copyright"   => $lama["copyright"],
    "media_type"  => $lama["media_type"],
    "url"         => $lama["image_url"],
    "hdurl"       => $lama["hdurl"],
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

        //memeriksa apakah tanggal dipakai data lain (selain data ini)
        if (!isset($errors["date"])) {
            $stmt = $conn->prepare("SELECT id FROM apod WHERE date = ? AND id <> ?");
            $stmt->bind_param("si", $form["date"], $id);
            $stmt->execute();
            $stmt->store_result();

            if ($stmt->num_rows > 0) {
                $errors["date"] = "err_date_duplicate";
            }

            $stmt->close();
        }

        //jika semua valid, perbarui data di database
        if (empty($errors)) {

            try {
                $stmt = $conn->prepare(
                    "UPDATE apod
                     SET date = ?, title = ?, explanation = ?, copyright = ?,
                         media_type = ?, image_url = ?, hdurl = ?
                     WHERE id = ?"
                );

                $stmt->bind_param(
                    "sssssssi",
                    $form["date"],
                    $form["title"],
                    $form["explanation"],
                    $form["copyright"],
                    $form["media_type"],
                    $form["url"],
                    $form["hdurl"],
                    $id
                );

                $stmt->execute();
                $stmt->close();

                flash_set("success", "msg_updated");
                redirect("data.php");

            } catch (mysqli_sql_exception $e) {
                $errors["form"] = "err_save_failed";
            }
        }
    }
}

$page_title = t("edit_title");
$tombol_kunci = "btn_update";
require __DIR__ . "/astrooftheday/includes/header.php";

?>

<h2><?php echo e(t("edit_title")); ?></h2>

<section class="card">
    <?php require __DIR__ . "/astrooftheday/includes/form_apod.php"; ?>
</section>

<?php require __DIR__ . "/astrooftheday/includes/footer.php"; ?>
