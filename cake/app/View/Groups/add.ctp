<ul class="breadcrumbs">
    <li><?php echo $this->Html->link('Home', '/'); ?></li>
    <li><?php echo $this->Js->link('Groups', array('controller' => 'Groups', 'action' => 'index'), array('update' => '#container', 'buffer' => false, 'before' => '$(".submit_loader").show();', 'success' => 'history.pushState(null, "MikroERP - List groups", this.url); $(document).attr("title", "MikroERP - List groups");')); ?></li>
    <li class="last"><a href="" onclick="return false">Add</a></li>
</ul>
<div id='alert'><?php echo $this->Session->flash(); ?></div>

<div class="name_add_search">
    <div class="name_of_page"><h3>Add group</h3></div>
    <div class="add_and_search">
        <div class="search"><?php echo $this->Form->create('Group', array('action' => 'search')); ?>
            <?php echo $this->Form->input('nameSearch', array('label' => false, 'class' => 'input_search pictureInput', 'placeholder' => 'Podaci za pretragu')); ?>
            <?php echo $this->Js->submit('Search', array(
                        'url' => array('action' => 'search'),
                        'update' => '#container',
                        'div' => false,
                        'class' => "button_search",
                        'buffer' => false,
                        'before' => '$(".submit_loader").show();',
                        'success' => 'history.pushState(null, "MikroERP - Search", "'.Router::url(array('controller' => 'users', 'action' => 'search')).'"); $(document).attr("title", "MikroERP - Search");'
                    ));?>
            <?php echo $this->Form->end(); ?></div>
    </div>
</div>

<div class="content_data">
<div class="formular">

<?php echo $this->Form->create('Group'); ?>
    <div class="content_text_input">
        <label for="Groupname"> Group name</label>
        <?php echo $this->Form->input('name', array('label' => false, 'class' => 'col_9', 'required' => false, 'placeholder' => 'Group')); ?>
    </div>

    <div class="content_text_input">
        <div class="buttons_box">
            <div class="button_box">
            <?php echo $this->Js->submit('Add group', array(
                    'update' => '#container',
                    'div' => false,
                    'class' => "button blue",
                    'style' => "margin:20px 0 20px 0;",
                    'buffer' => false,
                    'before' => '$(".submit_loader").show();',
                    'success' => 'history.pushState(null, "MikroERP - List groups", "'.Router::url(array('controller' => 'groups', 'action' => 'index')).'"); $(document).attr("title", "MikroERP - List groups");'
                ));?>
            </div>
            <div class="button_box margin5_left">
            <?php
                echo $this->Js->link('Cancel', array('controller' => 'Groups', 'action' => 'index'), array('update' => '#container', 'buffer' => false,'htmlAttributes' => array('class' => 'button', 'style' => 'margin:20px 0 20px 0;'), 'before' => '$(".submit_loader").show();', 'success' => 'history.pushState(null, "MikroERP - List users", this.url); $(document).attr("title", "MikroERP - List users");'));
            ?>
            <?php echo $this->Form->end(); ?>
        </div>
        </div>
    </div>
</div>
</div>
<div class="clear"></div>
<div class="submit_loader">
    <?php echo $this->Html->image('submit_loader.gif', array('alt' => 'Loader')); ?>
    <h2>Molimo sačekajte...</h2>
</div>

<script>
$('#container').ready(function(){
    $(".submit_loader").hide();
    $('input[id=GroupName]').focus();
    <?php if(!empty($isAjax)){ ?>
        var url = "<?php echo Router::url(array('controller' => 'Groups', 'action' => 'add')); ?>";
        history.pushState(null, "MikroERP - Korisnici", url); $(document).attr("title", "MikroERP - Korisnici");
    <?php } ?>
});
</script>