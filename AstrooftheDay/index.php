<?php

session_start();

//pengguna yang sudah login ke dashboard, selain itu ke halaman login
if (isset($_SESSION["user_id"])) {
    header("Location: dashboard.php");
} else {
    header("Location: login.php");
}

exit;

?>
