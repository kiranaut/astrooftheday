<?php
//fungsi bantu yang dipakai bersama oleh banyak halaman

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

//escape output HTML (mencegah XSS)
function e($nilai)
{
    return htmlspecialchars((string)$nilai, ENT_QUOTES, "UTF-8");
}

//membersihkan teks satu baris: buang tag HTML dan rapikan spasi
function bersihkan_teks($nilai)
{
    $nilai = strip_tags((string)$nilai);
    $nilai = preg_replace('/\s+/u', " ", $nilai);
    return trim($nilai);
}

//membersihkan teks panjang: buang tag HTML, baris baru tetap dipertahankan
function bersihkan_paragraf($nilai)
{
    return trim(strip_tags((string)$nilai));
}

//membersihkan URL dengan filter_var
function bersihkan_url($nilai)
{
    return filter_var(trim((string)$nilai), FILTER_SANITIZE_URL);
}

//memeriksa URL: harus valid dan memakai http/https
function url_valid($url)
{
    return filter_var($url, FILTER_VALIDATE_URL) !== false &&
        preg_match('/^https?:\/\//i', $url) === 1;
}

//membaca ID dari input, hasilnya angka bulat positif atau null
function ambil_id($nilai)
{
    $id = filter_var($nilai, FILTER_VALIDATE_INT, [
        "options" => ["min_range" => 1]
    ]);

    return $id === false ? null : $id;
}

//pesan singkat (flash message) yang tampil sekali setelah redirect
function flash_set($tipe, $kunci)
{
    $_SESSION["flash"] = ["type" => $tipe, "key" => $kunci];
}

function flash_show()
{
    if (!isset($_SESSION["flash"])) {
        return;
    }

    $flash = $_SESSION["flash"];
    unset($_SESSION["flash"]);

    $kelas = "alert-info";

    switch ($flash["type"]) {
        case "success":
            $kelas = "alert-success";
            break;
        case "error":
            $kelas = "alert-error";
            break;
    }

    echo '<div class="alert ' . $kelas . '">' . e(t($flash["key"])) . "</div>";
}

//token CSRF untuk melindungi form POST
function csrf_token()
{
    if (empty($_SESSION["csrf"])) {
        $_SESSION["csrf"] = bin2hex(random_bytes(32));
    }

    return $_SESSION["csrf"];
}

function csrf_valid($token)
{
    return isset($_SESSION["csrf"]) &&
        is_string($token) &&
        hash_equals($_SESSION["csrf"], $token);
}

//membaca dan membersihkan input form data APOD
function baca_input_apod($sumber)
{
    $data = [
        "date"        => trim($sumber["date"] ?? ""),
        "title"       => bersihkan_teks($sumber["title"] ?? ""),
        "explanation" => bersihkan_paragraf($sumber["explanation"] ?? ""),
        "copyright"   => bersihkan_teks($sumber["copyright"] ?? ""),
        "media_type"  => trim($sumber["media_type"] ?? ""),
        "url"         => bersihkan_url($sumber["url"] ?? ""),
        "hdurl"       => bersihkan_url($sumber["hdurl"] ?? ""),
    ];

    //video tidak memiliki versi resolusi tinggi
    if ($data["media_type"] == "video") {
        $data["hdurl"] = "";
    }

    return $data;
}

//memvalidasi kelengkapan dan format data APOD
//hasilnya array: nama kolom => kunci pesan error
function validasi_apod($data)
{
    $error = [];

    //validasi tanggal
    $tanggal = DateTime::createFromFormat("!Y-m-d", $data["date"]);

    if ($data["date"] === "") {
        $error["date"] = "err_date_required";
    } elseif (!$tanggal || $tanggal->format("Y-m-d") !== $data["date"]) {
        $error["date"] = "err_date_invalid";
    } elseif ($data["date"] < "1995-06-16" || $data["date"] > date("Y-m-d")) {
        $error["date"] = "err_date_range";
    }

    //validasi judul
    if ($data["title"] === "") {
        $error["title"] = "err_title_required";
    } elseif (mb_strlen($data["title"]) > 255) {
        $error["title"] = "err_title_long";
    }

    //validasi penjelasan
    if ($data["explanation"] === "") {
        $error["explanation"] = "err_expl_required";
    } elseif (mb_strlen($data["explanation"]) < 20) {
        $error["explanation"] = "err_expl_short";
    }

    //validasi hak cipta
    if (mb_strlen($data["copyright"]) > 255) {
        $error["copyright"] = "err_copyright_long";
    }

    //validasi tipe media
    switch ($data["media_type"]) {
        case "image":
            //gambar boleh memiliki URL resolusi tinggi
            if ($data["hdurl"] !== "" && (!url_valid($data["hdurl"]) || strlen($data["hdurl"]) > 500)) {
                $error["hdurl"] = "err_hdurl_invalid";
            }
            break;

        case "video":
            break;

        default:
            $error["media_type"] = "err_media_invalid";
            break;
    }

    //validasi URL media
    if ($data["url"] === "") {
        $error["url"] = "err_url_required";
    } elseif (!url_valid($data["url"]) || strlen($data["url"]) > 500) {
        $error["url"] = "err_url_invalid";
    }

    return $error;
}

//mengalihkan halaman lalu menghentikan skrip
function redirect($halaman)
{
    header("Location: " . $halaman);
    exit;
}

?>
