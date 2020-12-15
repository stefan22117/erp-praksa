<ul class="breadcrumbs">
	<li><?php echo $this->Html->link(__('Početna'), '/'); ?></li>
	<li><?php echo $this->Html->link(__('Finansijsko knjigovodstvo'), array('controller' => 'ErpModules', 'action' => 'start', 'financial')); ?></li>
	<li><?php echo $this->Html->link(__('Vrste dokumenata'), array('controller' => 'CodebookDocumentTypes', 'action' => 'index')); ?></li>
	<li class="last"><a href="" onclick="return false"><?php echo __('Snimanje'); ?></a></li>
</ul>
<div id='alert'><?php echo $this->Session->flash(); ?></div>
<div class="name_add_search">
	<div class="name_of_page"><h3><i class="icon-save"></i> <?php echo __('Snimanje vrste'); ?></h3></div>
</div>
<div class="content_data" style="width:60%;">
	<?php echo $this->Form->create('CodebookDocumentType'); ?>
		<div class="content_text_input">
			<?php echo $this->Form->label('code', __('Šifra vrste').' <span class="red">*</span>'); ?>
			<?php echo $this->Form->input('code', array('type' => 'text', 'label' => false, 'class' => 'col_9 inputborder', 'required' => false, 'placeholder' => __('Unesite šifru vrste'))); ?>
		</div>
		<div class="content_text_input">
			<?php echo $this->Form->label('name', __('Naziv vrste').' <span class="red">*</span>'); ?>
			<?php echo $this->Form->input('name', array('type' => 'text', 'label' => false, 'class' => 'col_9 inputborder', 'required' => false, 'placeholder' => __('Unesite naziv vrste'))); ?>
		</div>
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
					<?php echo $this->Html->link(__('Nazad'), array('controller' => 'CodebookDocumentTypes', 'action' => 'index'), array('class' => 'button', 'style' => 'margin:20px 0 20px 0;')); ?>
					<?php echo $this->Form->end(); ?>
				</div>
			</div>
		</div>
</div>
<div class="clear"></div>
<script>
$('#container').ready(function(){
	$(".submit_loader").hide(); //Hide ajax loader
});
</script>