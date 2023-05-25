<div class="card-header">
    <i class="fas fa-table me-1"></i>
    <?php echo $templateJudul ?>
</div>
<div class="card-body">
    <?php
$session = \Config\Services::session();
if ($session->getFlashdata('warning')) {
    ?>
                <div class="alert alert-warning">
                    <ul>
                        <?php
foreach ($session->getFlashdata('warning') as $val) {
        ?>
                            <li> <?php echo $val ?> </li>
                            <?php
}
    ?>
                    </ul>
                </div>
                <?php
}
if ($session->getFlashdata('success')) {
    ?>
                <div class="alert alert-success">
                    <?php echo $session->getFlashdata('success') ?>
                </div>
                <?php
}
?>
    <form action="" method="POST" enctype="multipart/form-data">
    <div class="mb-3">
        <label for="input_asrama_nama" class="form-label">Nama Asrama</label>
        <input type="text" class="form-control" id="input_asrama_nama" placeholder="NAMA ASRAMA" name="asrama_nama" value="<?php echo (isset($asrama_nama)) ? $asrama_nama : "" ?>">
    </div>
    <div class="mb-3">
        <label for="input_asrama_status" class="form-label">Status</label>
        <select name="asrama_status" class="form-select">
            <option value="active"<?php echo (isset($asrama_status) && $asrama_status == 'active') ? "selected" : "" ?>>Aktif</option>
            <option value="inactive"<?php echo (isset($asrama_status) && $asrama_status == 'inactive') ? "selected" : "" ?>>Tidak Aktif</option>
    </select>
    </div>
    <?php
if (isset($asrama_gambar)) {
    ?>
    <div class= "mb-3">
        <img src="<?php echo base_url(LOKASI_UPLOAD . "/" . $asrama_gambar) ?>" class="pb-2 mb-2 img-thumbnail w-50">
    </div>
    <?php
}
?>
    <div class="mb-3">
        <label for="input_asrama_gambar" class="form-label">Gambar Asrama</label>
        <input type="file" class="form-control" id="input_asrama_gambar" placeholder="JUDUL" name="asrama_gambar" value="<?php echo (isset($asrama_gambar)) ? $asrama_gambar : "" ?>">
    </div>
    <div class="mb-3">
        <label for="input_asrama_fasilitas" class="form-label">Konten</label>
        <textarea name="asrama_fasilitas" id="summernote" rows="10" class="form-control"><?php echo (isset($asrama_fasilitas)) ? $asrama_fasilitas : "" ?></textarea>
    </div>
    <div>
        <input type="submit" name="submit" value="Simpan Data" class="btn btn-primary">
    </div>
    </form>
</div>