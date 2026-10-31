<?php
//header bersama untuk semua halaman
//variabel $page_title diisi oleh halaman yang memanggil
require_once __DIR__ . "/languange.php";
require_once __DIR__ . "/helpers.php";

$sudah_login = isset($_SESSION["user_id"]);
$halaman_ini = basename($_SERVER["SCRIPT_NAME"]);

//daftar menu navigasi: file => kunci teks
$menu_navigasi = [
    "dashboard.php" => "nav_dashboard",
    "data.php"      => "nav_data",
    "gallery.php"   => "nav_gallery",
    "add.php"       => "nav_add",
    "import.php"    => "nav_import",
];
?>
<!DOCTYPE html>
<html lang="<?php echo e($_SESSION["lang"]); ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo e($page_title ?? t("app_name")); ?> - Astro of the Day</title>
    <link rel="stylesheet" href="astrooftheday/assets/css/style.css">
</head>
<body>
<div class="container">

    <header class="topbar">
        <h1 class="brand">Astro of the Day</h1>

        <?php if ($sudah_login) { ?>
        <nav class="nav">
            <?php foreach ($menu_navigasi as $file => $kunci) { ?>
                <a href="<?php echo e($file); ?>"
                   class="<?php echo $halaman_ini == $file ? "active" : ""; ?>">
                    <?php echo e(t($kunci)); ?>
                </a>
            <?php } ?>
            <a href="logout.php"><?php echo e(t("nav_logout")); ?></a>
        </nav>
        <?php } ?>

        <div class="lang">
            <?php echo e(t("lang_label")); ?>:
            <a href="languange.php?lang=id"
               class="<?php echo $_SESSION["lang"] == "id" ? "active" : ""; ?>">ID</a>
            |
            <a href="languange.php?lang=en"
               class="<?php echo $_SESSION["lang"] == "en" ? "active" : ""; ?>">EN</a>
        </div>
    </header>

    <?php flash_show(); ?>

    <main>
