<?php if (\App\Helpers\SessionHelper::has('success')): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <?php echo \App\Helpers\SessionHelper::get('success'); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    <?php \App\Helpers\SessionHelper::remove('success'); ?>
<?php endif; ?>

<?php if (\App\Helpers\SessionHelper::has('error')): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <?php echo \App\Helpers\SessionHelper::get('error'); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    <?php \App\Helpers\SessionHelper::remove('error'); ?>
<?php endif; ?>

<?php if (\App\Helpers\SessionHelper::has('info')): ?>
    <div class="alert alert-info alert-dismissible fade show" role="alert">
        <?php echo \App\Helpers\SessionHelper::get('info'); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    <?php \App\Helpers\SessionHelper::remove('info'); ?>
<?php endif; ?>