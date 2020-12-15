<ul class="breadcrumbs">
	<li><?php echo $this->Html->link(__('Početna'), '/'); ?></li>
	<li><?php echo $this->Html->link(__('Šifarnici'), array('controller' => 'Codebooks', 'action' => 'index')); ?></li>
	<li class="last"><a href="" onclick="return false"><?php echo __('Snimanje'); ?></a></li>
</ul>
<div id='alert'><?php echo $this->Session->flash(); ?></div>

<div class="name_add_search">
	<div class="name_of_page"><h3><i class="icon-save"></i> <?php echo __('Snimanje šifarnika'); ?></h3></div>
</div>

<div class="content_data">
	<div class="formular">
		<?php echo $this->Form->create('Codebook'); ?>
		<div class="content_text_input">
			<?php echo $this->Form->label('codebook_document_type_id', __('Vrsta dokumenta')); ?>
			<?php echo $this->Form->input('codebook_document_type_id', array('label' => false, 'class' => 'col_9 dropdown', 'required' => false, 'options' => $codebook_document_types,  'empty' => __('Odaberite vrstu'))); ?>
		</div>
		<div class="clear"></div>		
		<div class="content_text_input">
			<?php echo $this->Form->label('name', __('Ime').' <span class="red">*</span>'); ?>
			<?php echo $this->Form->input('name', array('label' => false, 'class' => 'col_9 inputborder', 'required' => false, 'placeholder' => __('Unesite ime šifarnika'))); ?>
		</div>
		<div class="clear"></div>
		<div class="content_text_input">
			<?php echo $this->Form->label('table_name', __('Tabela').' <span class="red">*</span>'); ?>
			<?php echo $this->Form->input('table_name', array('label' => false, 'class' => 'col_9 inputborder', 'required' => false, 'placeholder' => __('Unesite tabelu šifarnika'))); ?>
		</div>
		<div class="clear"></div>
		<div class="content_text_input">
			<?php echo $this->Form->label('model_name', __('Model').' <span class="red">*</span>'); ?>
			<?php echo $this->Form->input('model_name', array('label' => false, 'class' => 'col_9 inputborder', 'required' => false, 'placeholder' => __('Unesite model šifarnika'))); ?>
		</div>
		<div class="clear"></div>		
		<div class="content_text_input">
			<?php echo $this->Form->label('controller_name', __('Kontroler').' <span class="red">*</span>'); ?>
			<?php echo $this->Form->input('controller_name', array('label' => false, 'class' => 'col_9 inputborder', 'required' => false, 'placeholder' => __('Unesite kontroler šifarnika'))); ?>
		</div>
		<div class="clear"></div>
		<div class="content_text_input">
			<?php echo $this->Form->label('action_name', __('Akcija').' <span class="red">*</span>'); ?>
			<?php echo $this->Form->input('action_name', array('label' => false, 'class' => 'col_9 inputborder', 'required' => false, 'placeholder' => __('Unesite akciju kontrolera šifarnika'))); ?>
		</div>
		<div class="clear"></div>
		<div class="content_text_input">
			<?php echo $this->Form->label('code_field', __('Polje za šifru')); ?>
			<?php echo $this->Form->input('code_field', array('label' => false, 'class' => 'col_9 inputborder', 'required' => false, 'placeholder' => __('Unesite polje za šifru'))); ?>
		</div>
		<div class="clear"></div>		
		<div class="content_text_input">
			<div class="buttons_box">
				<div class="button_box">
				<?php echo $this->Form->submit(__('Snimi'), array(
						'div' => false,
						'class' => "button blue",
						'style' => "margin:20px 0 20px 0;"
					));?>
				</div>
				<div class="button_box">
					<?php echo $this->Html->link(__('Nazad'), array('controller' => 'Codebooks', 'action' => 'index'), array('class' => 'button', 'style' => 'margin:20px 0 20px 0;')); ?>
					<?php echo $this->Form->end(); ?>
				</div>
			</div>
		</div>
	</div>
</div>
<script>
$('#container').ready(function(){
	$(".submit_loader").hide();
	$(".dropdown").select2();
});
</script>