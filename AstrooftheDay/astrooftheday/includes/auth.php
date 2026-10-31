<?php
//memulai session
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

//memeriksa login pengguna
if (!isset($_SESSION["user_id"])) {

    #jika belum login diarahkan kembali ke halaman login
    header("Location: login.php");
    exit;
}

?>