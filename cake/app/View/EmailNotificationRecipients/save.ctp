<ul class="breadcrumbs">
	<li><?php echo $this->Html->link(__('Početna'), '/'); ?></li>
	<li><?php echo $this->Html->link(__('Primaoci određenih mejl notifikacija'), array('controller' => 'EmailNotificationRecipients', 'action' => 'index')); ?></li>
	<li class="last"><a href="" onclick="return false"><?php echo __('Dodaj primaoca'); ?></a></li>
</ul>
<div id='alert'><?php echo $this->Session->flash(); ?></div>

<div class="name_add_search">
	<div class="name_of_page"><h3><?php echo __('Dodaj primaoca'); ?></h3></div>
</div>

<div class="content_data">
	<div class="formular">

	<?php echo $this->Form->create('EmailNotificationRecipient'); ?>
		<!-- Tag funkcije -->
		<div class="content_text_input">
			<label for="EmailNotificationRecipientFunctionTag"> <?php echo __('Tag funkcije'); ?></label> <label class="red">*</label>
			<?php echo $this->Form->input('function_tag', array(
				'label' => false,
				'class' => 'col_9 inputborder',
				'required' => false,
				'autocomplete' => 'off',
				'placeholder' => __('Tag funkcije')
			)); ?>
		</div>

		<!-- Opis funkcije -->
		<div class="content_text_input">
			<label for="EmailNotificationRecipientDescription"> <?php echo __('Opis'); ?></label> <label class="red">*</label>
			<?php echo $this->Form->input('description', array(
				'label' => false,
				'class' => 'col_9 inputborder',
				'required' => false,
				'autocomplete' => 'off',
				'placeholder' => __('Opis')
			)); ?>
		</div>

		<!-- Primalac iz liste korisnika u sistemu -->
		<div class="content_text_input">
			<label for="EmailNotificationRecipientUserId"> <?php echo __('Primalac iz sistema'); ?></label>
			<?php echo $this->Form->input('user_id', array(
				'label' => false,
				'class' => 'col_9',
				'options' => $users,
				'empty' => __('(Izaberite korisnika)'),
				'required' => false,
				'autocomplete' => 'off'
			)); ?>
		</div>
		<div class="clear"></div>

		<!-- Mejl primaoca -->
		<div class="content_text_input">
			<label for="EmailNotificationRecipientEmail"> <?php echo __('Mejl primaoca'); ?></label>
			<?php echo $this->Form->input('email', array(
				'label' => false,
				'class' => 'col_9 inputborder',
				'required' => false,
				'autocomplete' => 'off',
				'placeholder' => __('Mejl primaoca')
			)); ?>
		</div>

		<div class="content_text_input">
			<div class="buttons_box">
				<div class="button_box">
				<?php echo $this->Form->submit(__('Dodaj primaoca'), array(
						'div' => false,
						'class' => "button blue",
						'style' => "margin:20px 0 20px 0;"
					));?>
				</div>
				<div class="button_box">
				<?php
					echo $this->Html->link(__('Odustani'), array('controller' => 'EmailNotificationRecipients', 'action' => 'index'), array('class' => 'button', 'style' => 'margin:20px 0 20px 0;'));
				?>
				<?php echo $this->Form->end(); ?>
				</div>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
	$("#EmailNotificationRecipientUserId").select2({ width: '370px' });
</script>