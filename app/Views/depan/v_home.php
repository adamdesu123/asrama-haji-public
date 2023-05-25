<?php
foreach ($record as $key => $value) {
    ?>
<link href="<?php echo base_url('berita') ?>/css/styles.css" rel="stylesheet" />
<div class="post-preview">
    <a href="post.html">
        <h2 class="post-title"><?php echo $value['post_title'] ?></h2>
        <h3 class="post-subtitle"><?php echo $value['post_description'] ?>
        </h3>
    </a>
    <p class="post-meta">
        Posted by
        <a href="#!"><?php echo post_penulis($value['username']) ?>
        </a>
        on <?php echo tanggal_id($value['post_time']) ?>

    </p>
</div>
<hr class="my-4" />
<?php
}
?>
<?php
echo $pager->simpleLinks('ft', 'depan')
?>