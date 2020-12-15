<ul class="breadcrumbs margin20">
    <li><?php echo $this->Html->link('Home', '/'); ?></li>
    <li><?php echo $this->Js->link('Users', array('controller' => 'Users', 'action' => 'index'), array('update' => '#container', 'buffer' => false, 'before' => '$(".submit_loader").show();', 'success' => 'history.pushState(null, "MikroERP - List users", this.url); $(document).attr("title", "MikroERP - List users");')); ?></li>
    <li class="last"><a href="" onclick="return false"> Add</a></li>
</ul>
<div id='alert'><?php echo $this->Session->flash(); ?></div>

<div class="name_add_search">
    <div class="name_of_page"><h3>Add user</h3></div>
    <div class="add_and_search">
        <div class="search"><?php echo $this->Form->create('User', array('action' => 'search')); ?>
        <?php echo $this->Form->input('name', array('label' => false, 'class' => 'input_search pictureInput', 'placeholder' =>'Podaci za pretragu'));?>
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

    <?php echo $this->Form->create('User', array('action' => 'add')); ?>

        <div class="content_text_input">
            <label for="UserUsername"> Username</label>
            <?php echo $this->Form->input('username', array('class' => 'col_9', 'placeholder' => 'Username', 'required' => false, 'label' => false)); ?>
        </div>
        <div class="content_text_input">
            <label for="UserPassword"> Password</label>
            <?php echo $this->Form->input('password', array('class' => 'col_9', 'placeholder' => 'Password', 'required' => false, 'label' => false)); ?>
        </div>
        <div class="content_text_input">
            <label for="UserEmail"> E-mail</label>
            <?php echo $this->Form->input('email', array('class' => 'col_9', 'placeholder' => 'E-mail', 'required' => false, 'label' => false)); ?>
        </div>
        <div class="content_text_input">
            <label for="UserFirstName"> First name</label>
            <?php echo $this->Form->input('first_name', array('class' => 'col_9', 'placeholder' => 'First name', 'required' => false, 'label' => false)); ?>
        </div>
        <div class="content_text_input">
            <label for="UserLastName"> Last name</label>
            <?php echo $this->Form->input('last_name', array('class' => 'col_9', 'placeholder' => 'Last name', 'required' => false, 'label' => false)); ?>
        </div>
        <div class="content_text_input">
            <label for="UserGroupId"> Select User Group</label>
            <?php echo $this->Form->input('group_id', array('options' => $groups, 'empty' => '(Choose group)', 'required' => false, 'class' => 'col_9', 'label' => false)); ?>
        </div>
        <div class="content_text_input">
            <label for="UserActive"> Aktivan</label>
            <?php echo $this->Form->input('active', array('class' => 'col_9', 'placeholder' => 'E-mail', 'required' => false, 'label' => false,'checked' => true)); ?>
        </div>
        <div class="buttons_box">
            <div class="button_box">
            <?php echo $this->Js->submit('Add user', array(
                    'update' => '#container',
                    'div' => false,
                    'class' => "button blue",
                    'style' => "margin:20px 0 20px 0;",
                    'buffer' => false,
                    'before' => '$(".submit_loader").show();',
                    'success' => 'history.pushState(null, "MikroERP - List users", "'.Router::url(array('controller' => 'users', 'action' => 'index')).'"); $(document).attr("title", "MikroERP - List users");'
                ));?>
            </div>
            <div class="button_box margin5_left">
            <?php
                echo $this->Js->link('Cancel', array('controller' => 'Users', 'action' => 'index'), array('update' => '#container', 'buffer' => false, 'htmlAttributes' => array('class' => 'button', 'style' => 'margin:20px 0 20px 0;'), 'before' => '$(".submit_loader").show();', 'success' => 'history.pushState(null, "MikroERP - List users", this.url); $(document).attr("title", "MikroERP - List users");'));
            ?>
            <?php echo $this->Form->end(); ?>
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
    $('input[id=UserUsername]').focus();
    <?php if(!empty($isAjax)){ ?>
        var url = "<?php echo Router::url(array('controller' => 'Users', 'action' => 'add')); ?>";
        history.pushState(null, "MikroERP - Dodaj korisnika", url); $(document).attr("title", "MikroERP - Dodaj korisnika");
    <?php } ?>
});
</script>