<?php
$pager->setSurroundCount(0)
?>
<div class="row">
    <div class="d-flex justify-content-start mb-4">
        <?php if ($pager->hasPrevious()) {?>
        <a class="btn btn-primary text-uppercase" href="<?php echo $pager->getPrevious() ?>">&lsaquo; New Post</a>
        <?php }?>
    </div>
    <div class="d-flex justify-content-end mb-4">
        <?php if ($pager->hasNext()) {?>
        <a class="btn btn-primary text-uppercase" href="<?php echo $pager->getNext() ?>">Older Post &rsaquo;</a>
        <?php }?>
    </div>
</div>