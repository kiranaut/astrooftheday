<?php

//memeriksa session pengguna
require_once __DIR__ . "/astrooftheday/includes/auth.php";
require_once __DIR__ . "/astrooftheday/includes/languange.php";
require_once __DIR__ . "/astrooftheday/includes/helpers.php";

//menghubungkan database
require_once __DIR__ . "/astrooftheday/config/db.php";

//penghapusan hanya boleh lewat POST (bukan lewat link biasa)
if ($_SERVER["REQUEST_METHOD"] != "POST") {
    redirect("data.php");
}

//memeriksa token CSRF
if (!csrf_valid($_POST["csrf_token"] ?? "")) {
    flash_set("error", "err_csrf");
    redirect("data.php");
}

//memeriksa ID data
$id = ambil_id($_POST["id"] ?? null);

if ($id === null) {
    flash_set("error", "err_invalid_id");
    redirect("data.php");
}

//menghapus data berdasarkan ID
try {
    $stmt = $conn->prepare("DELETE FROM apod WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        flash_set("success", "msg_deleted");
    } else {
        flash_set("error", "err_not_found");
    }

    $stmt->close();

} catch (mysqli_sql_exception $e) {
    flash_set("error", "err_save_failed");
}

redirect("data.php");

?>
