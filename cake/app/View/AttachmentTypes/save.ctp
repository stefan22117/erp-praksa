<ul class="breadcrumbs">
	<li><?php echo $this->Html->link(__('Početna'), '/'); ?></li>
	<li><?php echo $this->Html->link(__('Tipovi priloga'), array('controller' => 'AttachmentTypes', 'action' => 'index')); ?></li>
	<li class="last"><a href="" onclick="return false"><?php echo __('Tip priloga'); ?></a></li>
</ul>
<div id='alert'><?php echo $this->Session->flash(); ?></div>

<div class="name_add_search">
	<div class="name_of_page"><h3><?php echo __('Tip priloga'); ?></h3></div>
</div>

<div class="content_data">
	<div class="formular">
		<?php echo $this->Form->create('AttachmentType'); ?>
		<?php echo $this->Form->input('id', array('type' => 'hidden')); ?>
		<div class="content_text_input">
			<?php echo $this->Form->label('type', __('Tip priloga').' <span class="red">*</span>'); ?>
			<?php echo $this->Form->input('type', array('label' => false, 'class' => 'col_9 inputborder', 'required' => false, 'placeholder' => __('Unesite tip priloga'))); ?>
		</div>
		<div class="content_text_input">
			<div class="buttons_box">
				<div class="button_box"><?php echo $this->Form->submit(__('Snimi'), array('div' => false, 'class' => 'button blue', 'style' => array('margin:20px 0 20px 0;'))); ?></div>
				<div class="button_box">
					<?php echo $this->Html->link(__('Odustani'), array('controller' => 'AttachmentTypes', 'action' => 'index'), array('class' => 'button', 'style' => 'margin:20px 0 20px 0;')); ?>
					<?php echo $this->Form->end(); ?>
				</div>
			</div>
		</div>
	</div>
</div>