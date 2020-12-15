<ul class="breadcrumbs">
    <li><?php echo $this->Html->link('Home', '/'); ?></li>
    <li><?php echo $this->Js->link('Menus', array('controller' => 'Menus', 'action' => 'index'), array('update' => '#container', 'buffer' => false, 'before' => '$(".submit_loader").show();', 'success' => 'history.pushState(null, "MikroERP - List menus", this.url); $(document).attr("title", "MikroERP - List menus");')); ?></li>
    <li><?php echo $this->Js->link('Menu Elements', array('controller' => 'MenuElements', 'action' => 'index'), array('update' => '#container', 'buffer' => false, 'before' => '$(".submit_loader").show();', 'success' => 'history.pushState(null, "MikroERP - List menu elements", this.url); $(document).attr("title", "MikroERP - List menu elements");')); ?></li>
    <li class="last"><a href="" onclick="return false">Add element</a></li>
</ul>
<div id='alert'><?php echo $this->Session->flash(); ?></div>

<div class="name_add_search">
    <div class="name_of_page"><h3>Add Menu Element</h3></div>
    <div class="add_and_search">
        
        <div class="search"><?php echo $this->Form->create('MenuElement', array('action' => 'search')); ?>
        <?php echo $this->Form->input('name', array('label' => false, 'class' => 'input_search', 'placeholder' => 'Enter search data'));?>
        <?php echo $this->Js->submit('Search', array(
                    'url' => array('action' => 'search'),
                    'update' => '#container',
                    'div' => false,
                    'class' => "button_search",
                    'buffer' => false,
                    'before' => '$(".submit_loader").show();',
                    'success' => 'history.pushState(null, "MikroERP - Search", "'.Router::url(array('controller' => 'MenuElements', 'action' => 'search')).'"); $(document).attr("title", "MikroERP - Search");'
                ));?>
        <?php echo $this->Form->end(); ?></div>
    </div>
</div>

<div class='content_data'>
<div class="formular">

<?php echo $this->Form->create('MenuElement'); ?>

	<div class="content_text_input">
		<?php 
		if(!empty($id)) $disabled = true; else $disabled = false; ?>
		<label for="MenuElementsMenu_id"> Select menu</label>
		<?php echo $this->Form->input('menu_id', array('label' => false, 'options' => $menus, 'disabled' => $disabled, 'empty' => '(Choose Menu)', 'class' => 'col_9')); 
			if($disabled)
				echo $this->Form->input('menu_id', array('type' => 'hidden', 'value' => $id)); ?>
	</div>

	<div class="content_text_input">
		<label for="MenuTitle"> Element title</label>
		<?php echo $this->Form->input('title', array('label' => false, 'class' => 'col_9', 'placeholder' => 'Menu\'s title' )); ?>
	</div>

		<?php foreach($controllers as $controller => $methods):        	
		$arrController[$controller] = $controller;
	       endforeach;    ?>

	<div class="content_text_input">
		<label for="MenuController"> Select controller</label>
		<?php echo $this->Form->input('controller', array('label' => false, 'options' => $arrController, 'empty' => '(Choose Controller)','class' => 'col_9')); ?>
	</div>

	<div class="content_text_input">
		<label for="MenuAction"> Select action</label>
	<?php echo $this->Form->input('action', array('label' => false, 'options' => 0, 'empty' => '(Choose Method)', 'class' => 'col_9', 'disabled' => 'disabled')); ?>
	</div>
	
	<div class="buttons_box">
		<div class="button_box">
			<?php echo $this->Js->submit('Add menu element', array(
				'update' => '#container',
				'div' => false,
				'class' => "button blue",
				'style' => "margin:20px 0 20px 0;",
				'buffer' => false,
				'before' => '$(".submit_loader").show();',
				'success' => 'history.pushState(null, "MikroERP - List menu elements", "'.Router::url(array('controller' => 'menuelements', 'action' => 'index')).'"); $(document).attr("title", "MikroERP - List menu elements");'
			));?>
		</div>
		<div class="button_box margin5_left">
		<?php
			echo $this->Js->link('Cancel', array('controller' => 'MenuElements', 'action' => 'index'), array('update' => '#container', 'buffer' => false,'htmlAttributes' => array('class' => 'button', 'style' => 'margin:20px 0 20px 0;'), 'before' => '$(".submit_loader").show();', 'success' => 'history.pushState(null, "MikroERP - Menus", this.url); $(document).attr("title", "MikroERP - Menus");'));
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

<script type="text/javascript">
function getActions(){
	$('#MenuElementAction').find('option').remove().end();
	$.ajax({
		dataType: "json",
		type: "POST",
		evalScripts: true,
		url: "<?php echo Router::url(array('controller' => 'MenuElements', 'action' => 'getByController'));?>",
		data: ({controller:$("#MenuElementController" ).val()}),
		success: function (data, textStatus){
			var result = $.map(data, function(k, v) {
				return [k];
			});
			var length = result.length,
				element = null;
			if(length > 0){
				$('#MenuElementAction').append($("<option>Choose Method</option>"));
				for (var i = 0; i < length; i++) {
					element = result[i];
					$('#MenuElementAction').append($("<option></option>").attr("value",element).text(element));                   
				}
				$('#MenuElementAction').prop('disabled', false);
			}else{
				$('#MenuElementAction').prop('disabled', true);
			}
		}
	});
}
$('#container').ready(function() {
	$(".submit_loader").hide();
	
	$("#MenuElementMenuId").val(<?php echo $id; ?>);

	$("#MenuElementController").change(function() {
		getActions();
	});		
	if($('#MenuElementController').val().length > 0){
		getActions();
	}
});
</script> 