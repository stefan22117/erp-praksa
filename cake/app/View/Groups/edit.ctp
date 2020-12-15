<ul class="breadcrumbs">
    <li><?php echo $this->Html->link('Home', '/'); ?></li>
    <li><?php echo $this->Js->link('Groups', array('controller' => 'Groups', 'action' => 'index'), array('update' => '#container', 'buffer' => false, 'before' => '$(".submit_loader").show();', 'success' => 'history.pushState(null, "MikroERP - List groups", this.url); $(document).attr("title", "MikroERP - List groups");')); ?></li>
    <li class="last"><a href="" onclick="return false">Izmena magacina</a></li>
</ul>
<div id='alert'><?php echo $this->Session->flash(); ?></div>

<div class="name_add_search">
    <div class="name_of_page"><h3>Edit group</h3></div>
    <div class="add_and_search">
        <div class="search"><?php echo $this->Form->create('Group', array('action' => 'search')); ?>
            <?php echo $this->Form->input('nameSearch', array('label' => false, 'class' => 'input_search pictureInput', 'value' => false, 'placeholder' =>'Podaci za pretragu'));?>
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
	        <label for="GroupName"> Group name</label>
			<?php echo $this->Form->input('name', array('class' => 'col_9', 'required' => true, 'required' => false, 'label' => false)); ?>
		</div>
		<?php echo $this->Form->input('id', array('type' => 'hidden')); ?>
		<div class="content_text_input">	
			<?php echo $this->Js->submit('Edit group', array(
                        'url' => array('action' => 'edit'),
                        'update' => '#container',
                        'div' => false,
                        'class' => "button blue",
                        'style' => "margin:20px 0 20px 0;",
                        'buffer' => false,
                        'before' => '$(".submit_loader").show();',
                        'success' => 'history.pushState(null, "MikroERP - List groups", "'.Router::url(array('controller' => 'groups', 'action' => 'index')).'"); $(document).attr("title", "MikroERP - List groups");'
                    )); ?>

			<?php echo $this->Js->link('Cancel', array('controller' => 'Groups', 'action' => 'index'), array('update' => '#container', 'buffer' => false, 'htmlAttributes' => array('class' => 'button', 'style' => 'margin:20px 0 20px 0;'), 'before' => '$(".submit_loader").show();', 'success' => 'history.pushState(null, "MikroERP - List users", this.url); $(document).attr("title", "MikroERP - List users");')); ?>
		</div>

	<?php echo $this->Form->end(); ?>
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
    <?php if(!empty($isAjax)){ ?>
        var url = "<?php echo Router::url(array('controller' => 'Groups', 'action' => 'edit', $this->request->data['Group']['id'])); ?>";
        history.pushState(null, "MikroERP - Korisnici", url); $(document).attr("title", "MikroERP - Korisnici");
    <?php } ?>
});
</script>