<?php

//mengganti bahasa antarmuka, pilihan disimpan di $_SESSION["lang"]
require_once __DIR__ . "/astrooftheday/includes/languange.php";

$pilihan = $_GET["lang"] ?? "";

//hanya menerima bahasa yang terdaftar
if (in_array($pilihan, $bahasa_tersedia, true)) {
    $_SESSION["lang"] = $pilihan;
}

//kembali ke halaman sebelumnya (beserta query-nya) agar pengguna tidak berpindah halaman
$tujuan = "index.php";

if (!empty($_SERVER["HTTP_REFERER"])) {
    $bagian = parse_url($_SERVER["HTTP_REFERER"]);
    $file = basename($bagian["path"] ?? "");

    //hanya file .php di folder ini yang diperbolehkan, bukan halaman ini sendiri
    if (preg_match('/^[a-z_]+\.php$/', $file) && $file != "languange.php") {
        $tujuan = $file;

        if (!empty($bagian["query"])) {
            $tujuan .= "?" . $bagian["query"];
        }
    }
}

header("Location: " . $tujuan);
exit;

?>
