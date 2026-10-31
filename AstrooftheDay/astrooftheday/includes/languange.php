<?php
//memulai session
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

//bahasa yang didukung, bahasa default adalah Indonesia
$bahasa_tersedia = ["id", "en"];

if (
    !isset($_SESSION["lang"]) ||
    !in_array($_SESSION["lang"], $bahasa_tersedia, true)
) {
    $_SESSION["lang"] = "id";
}

//kamus teks antarmuka
$kamus = [

    "id" => [
        "app_name" => "Astro of the Day",
        "lang_label" => "Bahasa",
        "nav_dashboard" => "Dashboard",
        "nav_data" => "Data APOD",
        "nav_gallery" => "Galeri",
        "nav_add" => "Tambah Data",
        "nav_import" => "Impor CSV",
        "nav_logout" => "Keluar",
        "welcome" => "Selamat datang, %s!",

        "login_title" => "Halaman Masuk",
        "login_heading" => "Selamat datang di Astro of the Day!",
        "username" => "Nama pengguna",
        "password" => "Kata sandi",
        "login_btn" => "Masuk",
        "err_login_user_required" => "Nama pengguna wajib diisi.",
        "err_login_pass_required" => "Kata sandi wajib diisi.",
        "err_login_invalid" => "Nama pengguna atau kata sandi salah :(",

        "dashboard_title" => "Dashboard",
        "total_data" => "Total data",
        "total_images" => "Data bertipe gambar",
        "total_videos" => "Data bertipe video",
        "date_range" => "Rentang tanggal",
        "menu" => "Menu",

        "data_title" => "Data APOD",
        "search_placeholder" => "Cari judul atau hak cipta...",
        "search_btn" => "Cari",
        "reset" => "Atur ulang",
        "col_no" => "No",
        "col_date" => "Tanggal",
        "col_title" => "Judul",
        "col_type" => "Tipe",
        "col_copyright" => "Hak cipta",
        "col_action" => "Aksi",
        "act_detail" => "Detail",
        "act_edit" => "Ubah",
        "act_delete" => "Hapus",
        "confirm_delete" => "Yakin ingin menghapus data ini?",
        "no_results" => "Belum ada data yang cocok. :(",
        "showing" => "Menampilkan %d-%d dari %d data",
        "prev" => "Sebelumnya",
        "next" => "Berikutnya",

        "add_title" => "Tambah Data APOD",
        "edit_title" => "Ubah Data APOD",
        "detail_title" => "Detail Data APOD",
        "label_date" => "Tanggal",
        "label_title" => "Judul",
        "label_explanation" => "Penjelasan",
        "label_copyright" => "Hak cipta (opsional)",
        "label_media_type" => "Tipe media",
        "label_url" => "URL media",
        "label_hdurl" => "URL resolusi tinggi (opsional)",
        "opt_image" => "Gambar",
        "opt_video" => "Video",
        "btn_save" => "Simpan",
        "btn_update" => "Perbarui",
        "btn_cancel" => "Batal",
        "btn_back" => "Kembali",
        "errors_found" => "Periksa kembali isian Anda:",

        "err_date_required" => "Tanggal wajib diisi.",
        "err_date_invalid" => "Format tanggal tidak valid (gunakan YYYY-MM-DD).",
        "err_date_range" => "Tanggal harus antara 1995-06-16 dan hari ini.",
        "err_date_duplicate" => "Data untuk tanggal tersebut sudah ada.",
        "err_title_required" => "Judul wajib diisi.",
        "err_title_long" => "Judul maksimal 255 karakter.",
        "err_expl_required" => "Penjelasan wajib diisi.",
        "err_expl_short" => "Penjelasan minimal 20 karakter.",
        "err_copyright_long" => "Hak cipta maksimal 255 karakter.",
        "err_media_invalid" => "Tipe media harus gambar atau video.",
        "err_url_required" => "URL media wajib diisi.",
        "err_url_invalid" => "URL media tidak valid (harus diawali http:// atau https://).",
        "err_hdurl_invalid" => "URL resolusi tinggi tidak valid (harus diawali http:// atau https://).",
        "err_csrf" => "Sesi formulir tidak valid. Silakan muat ulang halaman.",
        "err_save_failed" => "Data gagal disimpan. Silakan coba lagi.",
        "err_not_found" => "Data tidak ditemukan.",
        "err_invalid_id" => "ID data tidak valid.",

        "msg_added" => "Data berhasil ditambahkan.",
        "msg_updated" => "Data berhasil diperbarui.",
        "msg_deleted" => "Data berhasil dihapus.",

        "gallery_title" => "Galeri Astronomi",
        "video_note" => "Entri ini berisi video atau tidak memiliki gambar.",
        "unknown" => "Tidak diketahui",
        "copyright_label" => "Hak cipta",
        "date_label" => "Tanggal",
        "view_detail" => "Lihat detail",
        "open_link" => "Buka tautan media",

        "import_title" => "Impor Data APOD",
        "import_desc" => "Unggah dataset APOD (CSV) di bawah ini!",
        "import_choose" => "Pilih berkas CSV:",
        "import_btn" => "Impor Data",
        "import_done" => "Impor selesai!",
        "import_err_nofile" => "Silakan pilih berkas CSV terlebih dahulu!",
        "import_err_cols" => "Kolom CSV tidak sesuai format yang dibutuhkan.",
        "import_err_empty" => "Berkas CSV kosong. :(",
        "import_err_open" => "Berkas CSV gagal dibuka. :(",
        "import_err_prepare" => "Query database gagal disiapkan.",
        "import_ok_rows" => "Berhasil diimpor: %d baris",
        "import_fail_rows" => "Baris gagal: %d baris",
    ],

    "en" => [
        "app_name" => "Astro of the Day",
        "lang_label" => "Language",
        "nav_dashboard" => "Dashboard",
        "nav_data" => "APOD Data",
        "nav_gallery" => "Gallery",
        "nav_add" => "Add Data",
        "nav_import" => "Import CSV",
        "nav_logout" => "Logout",
        "welcome" => "Welcome, %s!",

        "login_title" => "Login Page",
        "login_heading" => "Welcome to Astro of the Day!",
        "username" => "Username",
        "password" => "Password",
        "login_btn" => "Login",
        "err_login_user_required" => "Please fill your username first.",
        "err_login_pass_required" => "Please fill your password first.",
        "err_login_invalid" => "Incorrect username or password :(",

        "dashboard_title" => "Dashboard",
        "total_data" => "Total data",
        "total_images" => "Image entries",
        "total_videos" => "Video entries",
        "date_range" => "Date range",
        "menu" => "Menu",

        "data_title" => "APOD Data",
        "search_placeholder" => "Search title or copyright...",
        "search_btn" => "Search",
        "reset" => "Reset",
        "col_no" => "No",
        "col_date" => "Date",
        "col_title" => "Title",
        "col_type" => "Type",
        "col_copyright" => "Copyright",
        "col_action" => "Action",
        "act_detail" => "Detail",
        "act_edit" => "Edit",
        "act_delete" => "Delete",
        "confirm_delete" => "Are you sure you want to delete this entry?",
        "no_results" => "No matching data yet. :(",
        "showing" => "Showing %d-%d of %d entries",
        "prev" => "Previous",
        "next" => "Next",

        "add_title" => "Add APOD Data",
        "edit_title" => "Edit APOD Data",
        "detail_title" => "APOD Data Detail",
        "label_date" => "Date",
        "label_title" => "Title",
        "label_explanation" => "Explanation",
        "label_copyright" => "Copyright (optional)",
        "label_media_type" => "Media type",
        "label_url" => "Media URL",
        "label_hdurl" => "High-resolution URL (optional)",
        "opt_image" => "Image",
        "opt_video" => "Video",
        "btn_save" => "Save",
        "btn_update" => "Update",
        "btn_cancel" => "Cancel",
        "btn_back" => "Back",
        "errors_found" => "Please check your input:",

        "err_date_required" => "Date is required.",
        "err_date_invalid" => "Invalid date format (use YYYY-MM-DD).",
        "err_date_range" => "Date must be between 1995-06-16 and today.",
        "err_date_duplicate" => "An entry for that date already exists.",
        "err_title_required" => "Title is required.",
        "err_title_long" => "Title must be at most 255 characters.",
        "err_expl_required" => "Explanation is required.",
        "err_expl_short" => "Explanation must be at least 20 characters.",
        "err_copyright_long" => "Copyright must be at most 255 characters.",
        "err_media_invalid" => "Media type must be image or video.",
        "err_url_required" => "Media URL is required.",
        "err_url_invalid" => "Invalid media URL (must start with http:// or https://).",
        "err_hdurl_invalid" => "Invalid high-resolution URL (must start with http:// or https://).",
        "err_csrf" => "Invalid form session. Please reload the page.",
        "err_save_failed" => "Failed to save data. Please try again.",
        "err_not_found" => "Data not found.",
        "err_invalid_id" => "Invalid data ID.",

        "msg_added" => "Data was added successfully.",
        "msg_updated" => "Data was updated successfully.",
        "msg_deleted" => "Data was deleted successfully.",

        "gallery_title" => "Astronomy Picture Gallery",
        "video_note" => "This entry contains a video or has no image available.",
        "unknown" => "Unknown",
        "copyright_label" => "Copyright",
        "date_label" => "Date",
        "view_detail" => "View detail",
        "open_link" => "Open media link",

        "import_title" => "Import APOD Data",
        "import_desc" => "Please upload your APOD CSV dataset below!",
        "import_choose" => "Select CSV file:",
        "import_btn" => "Import Data",
        "import_done" => "Import is done!",
        "import_err_nofile" => "Please select a CSV file first!",
        "import_err_cols" => "CSV columns do not match the required format.",
        "import_err_empty" => "CSV file is empty. :(",
        "import_err_open" => "Failed to open CSV file. :(",
        "import_err_prepare" => "Failed to prepare database query.",
        "import_ok_rows" => "Successfully imported: %d rows",
        "import_fail_rows" => "Failed rows: %d rows",
    ],
];

//mengambil teks sesuai bahasa yang tersimpan di session
function t($key, ...$args)
{
    global $kamus;

    $lang = $_SESSION["lang"];
    $teks = $kamus[$lang][$key] ?? $kamus["en"][$key] ?? $key;

    if (count($args) > 0) {
        return vsprintf($teks, $args);
    }

    return $teks;
}

?>
