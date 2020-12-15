<ul class="breadcrumbs">
	<li><?php echo $this->Html->link(__('Početna'), '/'); ?></li>
	<li><?php echo $this->Html->link(__('ERP Ikonice'), array('controller' => 'ErpKickstartIcons', 'action' => 'index')); ?></li>
	<li class="last"><a href="" onclick="return false"><?php echo __('Snimanje'); ?></a></li>
</ul>
<div id='alert'><?php echo $this->Session->flash(); ?></div>

<div class="name_add_search">
	<div class="name_of_page"><h3><i class="icon-save"></i> <?php echo __('Snimanje ikonice'); ?></h3></div>
</div>

<div class="content_data" style="width:60%;">
	<?php echo $this->Form->create('ErpKickstartIcon'); ?>
		<div class="content_text_input">
			<?php echo $this->Form->label('icon_name', __('Naziv ikonice').' <span class="red">*</span>'); ?>
			<?php echo $this->Form->input('icon_name', array('type' => 'text', 'label' => false, 'class' => 'col_9 inputborder', 'required' => false, 'placeholder' => __('Unesite naziv ikonice'))); ?>
		</div>
		<div class="content_text_input">
			<?php echo $this->Form->label('icon_class', __('Klasa ikonice').' <span class="red">*</span>'); ?>
			<?php echo $this->Form->input('icon_class', array('type' => 'text', 'label' => false, 'class' => 'col_9 inputborder', 'required' => false, 'placeholder' => __('Unesite klasu ikonice'))); ?>
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
					<?php echo $this->Html->link(__('Nazad'), array('controller' => 'ErpKickstartIcons', 'action' => 'index'), array('class' => 'button', 'style' => 'margin:20px 0 20px 0;')); ?>
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