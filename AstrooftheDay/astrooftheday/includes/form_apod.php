<?php
//form data APOD yang dipakai bersama oleh add.php dan edit.php
//variabel yang dibutuhkan: $form, $errors, $tombol_kunci, $id (opsional)
?>

<?php if (!empty($errors)) { ?>
    <div class="alert alert-error">
        <strong><?php echo e(t("errors_found")); ?></strong>
        <ul>
            <?php foreach ($errors as $kunci_error) { ?>
                <li><?php echo e(t($kunci_error)); ?></li>
            <?php } ?>
        </ul>
    </div>
<?php } ?>

<form method="POST" class="form" novalidate>

    <input type="hidden" name="csrf_token" value="<?php echo e(csrf_token()); ?>">
    <?php if (isset($id)) { ?>
        <input type="hidden" name="id" value="<?php echo e($id); ?>">
    <?php } ?>

    <label for="date"><?php echo e(t("label_date")); ?></label>
    <input type="date" id="date" name="date" value="<?php echo e($form["date"]); ?>" required>

    <label for="title"><?php echo e(t("label_title")); ?></label>
    <input type="text" id="title" name="title" maxlength="255" value="<?php echo e($form["title"]); ?>" required>

    <label for="explanation"><?php echo e(t("label_explanation")); ?></label>
    <textarea id="explanation" name="explanation" rows="8" required><?php echo e($form["explanation"]); ?></textarea>

    <label for="copyright"><?php echo e(t("label_copyright")); ?></label>
    <input type="text" id="copyright" name="copyright" maxlength="255" value="<?php echo e($form["copyright"]); ?>">

    <label for="media_type"><?php echo e(t("label_media_type")); ?></label>
    <select id="media_type" name="media_type">
        <option value="image" <?php echo $form["media_type"] == "image" ? "selected" : ""; ?>>
            <?php echo e(t("opt_image")); ?>
        </option>
        <option value="video" <?php echo $form["media_type"] == "video" ? "selected" : ""; ?>>
            <?php echo e(t("opt_video")); ?>
        </option>
    </select>

    <label for="url"><?php echo e(t("label_url")); ?></label>
    <input type="url" id="url" name="url" maxlength="500" placeholder="https://" value="<?php echo e($form["url"]); ?>" required>

    <label for="hdurl"><?php echo e(t("label_hdurl")); ?></label>
    <input type="url" id="hdurl" name="hdurl" maxlength="500" placeholder="https://" value="<?php echo e($form["hdurl"]); ?>">

    <div class="actions">
        <button type="submit" class="btn"><?php echo e(t($tombol_kunci)); ?></button>
        <a href="data.php" class="btn btn-secondary"><?php echo e(t("btn_cancel")); ?></a>
    </div>

</form>
