<?php

//memeriksa session pengguna
require_once __DIR__ . "/astrooftheday/includes/auth.php";
require_once __DIR__ . "/astrooftheday/includes/languange.php";
require_once __DIR__ . "/astrooftheday/includes/helpers.php";

//menghubungkan database
require_once __DIR__ . "/astrooftheday/config/db.php";

//pengaturan pencarian dan halaman
$per_halaman = 10;
$cari = bersihkan_teks($_GET["q"] ?? "");
$halaman = ambil_id($_GET["page"] ?? 1) ?? 1;

//karakter khusus LIKE diberi escape agar dibaca apa adanya
$pola = "%" . addcslashes($cari, "%_\\") . "%";

//menghitung total data yang cocok
$stmt = $conn->prepare(
    "SELECT COUNT(*) FROM apod WHERE title LIKE ? OR copyright LIKE ?"
);
$stmt->bind_param("ss", $pola, $pola);
$stmt->execute();
$stmt->bind_result($total);
$stmt->fetch();
$stmt->close();

$total_halaman = max(1, (int)ceil($total / $per_halaman));

if ($halaman > $total_halaman) {
    $halaman = $total_halaman;
}

$offset = ($halaman - 1) * $per_halaman;

//mengambil data untuk halaman ini
$stmt = $conn->prepare(
    "SELECT id, date, title, copyright, media_type
     FROM apod
     WHERE title LIKE ? OR copyright LIKE ?
     ORDER BY date DESC
     LIMIT ? OFFSET ?"
);
$stmt->bind_param("ssii", $pola, $pola, $per_halaman, $offset);
$stmt->execute();
$result = $stmt->get_result();

//membuat link halaman dengan menyertakan kata pencarian
function link_halaman($nomor, $cari)
{
    $param = ["page" => $nomor];

    if ($cari !== "") {
        $param["q"] = $cari;
    }

    return "data.php?" . http_build_query($param);
}

$page_title = t("data_title");
require __DIR__ . "/astrooftheday/includes/header.php";

?>

<h2><?php echo e(t("data_title")); ?></h2>

<div class="toolbar">
    <form method="GET" class="search">
        <input type="text" name="q" value="<?php echo e($cari); ?>"
               placeholder="<?php echo e(t("search_placeholder")); ?>">
        <button type="submit" class="btn"><?php echo e(t("search_btn")); ?></button>
        <?php if ($cari !== "") { ?>
            <a href="data.php" class="btn btn-secondary"><?php echo e(t("reset")); ?></a>
        <?php } ?>
    </form>

    <a href="add.php" class="btn">+ <?php echo e(t("nav_add")); ?></a>
</div>

<?php if ($result->num_rows > 0) { ?>

    <div class="table-wrap">
    <table class="table">
        <thead>
            <tr>
                <th><?php echo e(t("col_no")); ?></th>
                <th><?php echo e(t("col_date")); ?></th>
                <th><?php echo e(t("col_title")); ?></th>
                <th><?php echo e(t("col_type")); ?></th>
                <th><?php echo e(t("col_copyright")); ?></th>
                <th><?php echo e(t("col_action")); ?></th>
            </tr>
        </thead>
        <tbody>
            <?php $nomor = $offset + 1; ?>
            <?php while ($row = $result->fetch_assoc()) { ?>
                <tr>
                    <td><?php echo $nomor++; ?></td>
                    <td class="nowrap"><?php echo e($row["date"]); ?></td>
                    <td><?php echo e($row["title"]); ?></td>
                    <td><?php echo e($row["media_type"] == "video" ? t("opt_video") : t("opt_image")); ?></td>
                    <td><?php echo e($row["copyright"] !== "" ? $row["copyright"] : t("unknown")); ?></td>
                    <td class="nowrap">
                        <a href="detail.php?id=<?php echo (int)$row["id"]; ?>"><?php echo e(t("act_detail")); ?></a> |
                        <a href="edit.php?id=<?php echo (int)$row["id"]; ?>"><?php echo e(t("act_edit")); ?></a> |

                        <form method="POST" action="delete.php" class="inline"
                              onsubmit="return confirm(<?php echo e(json_encode(t("confirm_delete"))); ?>);">
                            <input type="hidden" name="csrf_token" value="<?php echo e(csrf_token()); ?>">
                            <input type="hidden" name="id" value="<?php echo (int)$row["id"]; ?>">
                            <button type="submit" class="link-danger"><?php echo e(t("act_delete")); ?></button>
                        </form>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
    </div>

    <div class="pagination">
        <span>
            <?php echo e(t("showing", $offset + 1, min($offset + $per_halaman, $total), $total)); ?>
        </span>

        <span>
            <?php if ($halaman > 1) { ?>
                <a href="<?php echo e(link_halaman($halaman - 1, $cari)); ?>">&laquo; <?php echo e(t("prev")); ?></a>
            <?php } ?>

            <strong><?php echo $halaman; ?> / <?php echo $total_halaman; ?></strong>

            <?php if ($halaman < $total_halaman) { ?>
                <a href="<?php echo e(link_halaman($halaman + 1, $cari)); ?>"><?php echo e(t("next")); ?> &raquo;</a>
            <?php } ?>
        </span>
    </div>

<?php } else { ?>

    <p><?php echo e(t("no_results")); ?></p>

<?php } ?>

<?php

$stmt->close();
require __DIR__ . "/astrooftheday/includes/footer.php";

?>
