<?php

session_start();

//menyimpan pilihan bahasa agar tetap konsisten setelah logout
$lang = $_SESSION["lang"] ?? "id";

//menghapus semua data session
$_SESSION = [];

//menghapus session
session_destroy();

//membuat session baru yang hanya berisi pilihan bahasa
session_start();
session_regenerate_id(true);
$_SESSION["lang"] = $lang;

//kembali ke login page
header("Location: login.php");
exit;

?>
