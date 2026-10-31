<?php

//memulai session dan memuat bahasa
require_once __DIR__ . "/astrooftheday/includes/languange.php";
require_once __DIR__ . "/astrooftheday/includes/helpers.php";

//menghubungkan database
require_once __DIR__ . "/astrooftheday/config/db.php";

//halaman ini hanya menerima request POST
if ($_SERVER["REQUEST_METHOD"] != "POST") {
    redirect("login.php");
}

//mengambil username dan password
$username = trim($_POST["username"] ?? "");
$password = $_POST["password"] ?? "";

$error = [];

//validasi form
if ($username === "") {
    $error[] = "err_login_user_required";
}

if ($password === "") {
    $error[] = "err_login_pass_required";
}

//jika tidak ada error
if (empty($error)) {

    //mencari akun berdasarkan username
    $sql = "SELECT id, username, password
            FROM users
            WHERE username = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();

    $stmt->bind_result($user_id, $db_username, $hashed_password);

    //memeriksa apakah username ditemukan dan password cocok
    if ($stmt->fetch() && password_verify($password, $hashed_password)) {

        $stmt->close();

        //membuat session baru
        session_regenerate_id(true);

        $_SESSION["user_id"] = $user_id;
        $_SESSION["username"] = $db_username;

        //menuju dashboard
        redirect("dashboard.php");
    }

    $stmt->close();

    //pesan sengaja dibuat sama agar username tidak dapat ditebak
    $error[] = "err_login_invalid";
}

//kembali ke halaman login beserta pesan error
$_SESSION["login_error"] = $error;
redirect("login.php");

?>
