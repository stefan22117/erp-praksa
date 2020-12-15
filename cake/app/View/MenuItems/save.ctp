<div class="breadcrumbs_holder">
    <ul class="breadcrumbs">
        <li><?php echo $this->Html->link(__('Početna'), '/'); ?></li>
        <li><?php echo $this->Html->link(__('Meni'), array('controller' => 'MenuItems', 'action' => 'index')); ?></li>
        <li class="last"><a href="" onclick="return false"><?php echo __('Snimanje'); ?></a></li>
    </ul>
</div>

<div id='alert'><?php echo $this->Session->flash(); ?></div>

<div class="name_add_search">
    <div class="name_of_page">
	<?php if($action == 'add'): ?>
        <h3><i class="icon-save"></i> <?php echo __('Dodavanje nove stavke u meniju'); ?></h3>
    <?php endif; ?>
	<?php if($action == 'edit'): ?>
        <h3><i class="icon-edit"></i> <?php echo __('Izmena postojece stavke u meniju'); ?></h3>
    <?php endif; ?>
    </div>
</div>

<div class="content_data">
	<?php echo $this->Form->create('MenuItem'); ?>
	<?php
		if($action == 'edit')
		echo $this->Form->input('id');
	?>
	<div class="col_9">
		<?php echo $this->Form->label('parent_id', __('Roditelj')); ?>
		<?php echo $this->Form->input('parent_id', array('label' => false, 'class' => 'dropdown', 'style' => 'width:100%', 'required' => false, 'empty' => __('Izaberite roditelja...'))); ?>
	</div>
	<div class="clear"></div>
	<div class="col_9">
		<?php echo $this->Form->label('name', __('Naziv stavke u meniju')); ?>
		<?php echo $this->Form->input('name', array('type' => 'text', 'label' => false, 'class' => 'col_9', 'style' => 'margin: 0; width: 100%;', 'required' => false, 'placeholder' => __('Unesite naziv stavke u meniju'))); ?>
	</div>
	<div class="clear"></div>
	<div class="col_9">
		<?php echo $this->Form->label('erp_kickstart_icon_id', __('Ikonica')); ?>
		<?php echo $this->Form->input('erp_kickstart_icon_id', array('label' => false, 'class' => 'dropdown', 'style' => 'width:100%', 'required' => false, 'empty' => __('Izaberite ikonicu...'))); ?>
	</div>
	<div class="clear"></div>
	<div class="col_9">
		<?php echo $this->Form->label('aco_parent_id', __('Kontroler')); ?>
		<?php echo $this->Form->input('aco_parent_id', array('label' => false, 'class' => 'dropdown', 'options' => $acoParents, 'style' => 'width:100%', 'required' => false, 'empty' => __('Izaberite kontroler...'))); ?>
	</div>
	<div class="clear"></div>
	<div class="col_9">
		<?php echo $this->Form->label('aco_id', __('Akcija')); ?>
		<?php echo $this->Form->input('aco_id', array('label' => false, 'class' => 'dropdown', 'style' => 'width:100%', 'required' => false, 'empty' => __('Izaberite akciju...'))); ?>
	</div>
	<div class="clear"></div>
	<div class="col_9">
		<?php echo $this->Form->label('params', __('Parametri za akciju')); ?>
		<?php echo $this->Form->input('params', array('type' => 'text', 'label' => false, 'class' => 'col_9', 'style' => 'margin: 0; width: 100%;', 'required' => false, 'placeholder' => __('Unesite parametre za akciju'))); ?>
	</div>
	<div class="clear"></div>
	<div class="content_text_input">
		<div class="buttons_box">
			<div class="button_box">
			<?php 
				echo $this->Form->submit(__('Snimi'), array(
					'div' => false,
					'class' => "button blue",
					'style' => "margin:20px 0 20px 0;"
				));
			?>
			</div>
			<div class="button_box">
				<?php echo $this->Html->link(__('Nazad'), array('action' => 'index'), array('class' => 'button', 'style' => 'margin:20px 0 20px 0;')); ?>
				<?php echo $this->Form->end(); ?>
			</div>
		</div>
	</div>
</div>
<div class="clear"></div>

<!-- Custom javascript -->
<script type="text/javascript">
	/* Function for formatting icons */
	function format(icon) {	
		return icon.text;
	}//~!
	$(document).ready(function(){

		//Event handler for mcu vendor change
		$('#MenuItemAcoParentId').change(function(){
	        $('#MenuItemAcoId option[value!=""]').remove();
	        $("#MenuItemAcoId").off("select2-selecting");
	        $("#MenuItemAcoId").select2();
			var id = $(this).val();
			$.ajax({
				url: '<?php echo $this->Html->url(array('controller' => 'MenuItems', 'action'=>'selectActions')); ?>',
	            type: "post",
	            dataType: "json",
	            evalScripts: true,				
				data: {id: id},
				success: function(response) {
	                Object.keys(response).sort().forEach(function(key, i) {
	                    $('#MenuItemAcoId').append($("<option></option>").attr("value",key).text(response[key]));
	                });
				}
			});
		});

		//Select2 dropdown with icons
		$('.dropdown').select2({
		    formatResult: format,
		    formatSelection: format
		});	
	});	
</script>
