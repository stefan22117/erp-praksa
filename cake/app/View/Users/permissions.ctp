<!-- Custom style -->
<style>
    .ajaxLoader { text-align:center; padding: 100px; background: #f8f8f8; }
</style>

<!-- Breadcrumbs -->
<ul class="breadcrumbs">
    <li><?php echo $this->Html->link(__('Početna'), '/'); ?></li>
    <li><?php echo $this->Html->link(__('Korisnici'), array('controller' => 'Users', 'action' => 'index')); ?></li>
    <li class="last"><a href="#" onclick="return false"><?php echo __('Dozvole'); ?></a></li>
</ul>

<!-- Messages -->
<div id='alert'><?php echo $this->Session->flash(); ?></div>

<!-- Title -->
<div class="name_add_search">
    <div class="name_of_page"><h3><i class="icon-lock"></i> <?php echo __('Korisničke dozvole').' - '.$user['User']['first_name'].' '.$user['User']['last_name']; ?></h3></div>
</div>

<!-- Main content -->
<div class="content_data">

<div id="ajaxPermission">
    <div class="ajaxLoader">
        <?php echo $this->Html->image('submit_loader.gif', array('alt' => 'Loader', 'width' => '20')); ?>
        <span>Podaci se učitavaju. Molimo sačekajte...</span>
    </div>
</div>

</div>

<!-- Custom script -->
<script>
    $(document).ready(function(){
        $.ajax({
            url: "<?php echo $this->Html->url(array('controller' => 'HrPermissions', 'action' => 'viewUnits', $user['User']['id'], 0)); ?>" ,
            success: function(response) {
                $('#ajaxPermission').html(response);
            }
        });
    });
</script>
