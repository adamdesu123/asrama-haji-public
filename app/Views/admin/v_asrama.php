<?php
$halaman = "asrama";
?>
<div class="card-header">
    <i class="fas fa-table me-1"></i>
    <?php echo $templateJudul ?>
</div>
<div class="card-body">
    <div class="row mb-3">
        <div class="col-lg-3 pt-1 pb-1">
            <form action="" method="get">
                <input type="text" placeholder="Cari Data" name="katakunci" class="form-control" value="<?php echo $katakunci ?>">
            </form>
        </div>
        <div class="col-lg-9 pt-1 pb-1 text-end">
            <a href="<?php echo site_url("admin/$halaman/tambah") ?>" class="btn btn-primary">Tambah Data</a>
        </div>
    </div>
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
    <table class="table table-bordered">
    <thead>
        <tr>
            <th class="col-1">No.</th>
            <th class="col-6">Nama Asrama</th>
            <th class="col-6">Status</th>
            <th class="col-2 text-center">Aksi</th>
        </tr>
    </thead>
    <tbody>
        <?php
foreach ($record as $value) {
    $asrama_id = $value['asrama_id'];
    $edit = site_url("admin/$halaman/edit/$asrama_id");
    $delete = site_url("admin/$halaman/?aksi=hapus&asrama_id=$asrama_id");
    ?>
            <tr>
                <td><?php echo $nomor ?></td>
                <td><?php echo $value['asrama_nama'] ?></td>
                <td><?php echo $value['asrama_status'] ?></td>
                <td>
                    <a href='<?php echo $edit ?>' id="asrama_id" name="asrama_id" class="btn btn-sm btn-warning">EDIT</a>
                    <a href='<?php echo $delete ?>' id="asrama_id" name="asrama_id" onclick="return confirm('Apakah Anda Yakin Akan Menghapus Data Ini?')" class="btn btn-sm btn-danger">HAPUS</a>
                </td>
            </tr>
            <?php
$nomor++;
}
?>
    </tbody>
    </table>
    <?php echo $pager->links('dt', 'datatable') ?>
</div>