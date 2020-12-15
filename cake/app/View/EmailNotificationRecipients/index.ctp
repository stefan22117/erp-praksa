<?php $this->Paginator->options(array('url' => array('controller' => 'EmailNotificationRecipients', 'action' => 'index', '?' => $this->request->query))); ?>
<ul class="breadcrumbs">
	<li><?php echo $this->Html->link(__('Početna'), '/'); ?></li>
	<?php if ( !empty( $this->request->query['term'] ) ){ ?>
		<li><?php echo $this->Html->link(__('Emailovi za notifikacije'), array('controller' => 'EmailNotificationRecipients', 'action' => 'index')); ?></li>
		<li class="last"><a href="" onclick="return false"><?php echo __('Pretraga'); ?></a></li>
	<?php }else{ ?>
		<li class="last"><a href="" onclick="return false"><?php echo __('Emailovi za notifikacije'); ?></a></li>
	<?php } ?>
</ul>
<div id='alert'><?php echo $this->Session->flash(); ?></div>

<div class="name_add_search">
	<div class="name_of_page"><h3><?php echo __('Emailovi za notifikacije'); ?></h3></div>
	<div class="add_and_search right">
	<?php echo $this->Html->link('<i class="icon-plus-sign"></i> '.__('Dodaj'), array('controller' => 'EmailNotificationRecipients', 'action' => 'save'), array('class' => 'button small blue', 'escape' => false)); ?>
	</div>
</div>

<div class="content_data">
	<fieldset style="margin: auto; width: 1100px;">
		<legend><?php echo __('Filter'); ?></legend>

		<?php echo $this->Form->create('EmailNotificationRecipient', array('controller' => 'PoDocuments', 'action' => 'index', 'type' => 'get')); ?>

        <div class="content_text_input">
            <!-- Function tag -->
            <?php echo $this->Form->input('function_tag', array(
                'label' => false,
                'empty' => __('(Odaberite tag funkcije)'),
                'autocomplete' => 'off',
                'required' => false,
                'div' => array('style' => array('display: inline-block'))
			)); ?>
			
            <!-- Description -->
            <?php echo $this->Form->input('description', array(
                'label' => false,
                'placeholder' => __('Opis funkcije'),
                'autocomplete' => 'off',
				'required' => false,
                'div' => array('style' => array('display: inline-block'))
            )); ?>

            <!-- Emails -->
            <?php echo $this->Form->input('email', array(
                'label' => false,
                'empty' => __('(Odaberite email)'),
				'options' => $emails,
                'autocomplete' => 'off',
				'required' => false,
                'div' => array('style' => array('display: inline-block'))
            )); ?>

            <!-- Search button -->
            <?php echo $this->Form->submit(__('Pretraga'), array('url' => array('action' => 'index'), 'div' => false, 'class' => "button_search"));?>
        </div>

		<?php echo $this->Form->end(); ?>
	</fieldset>

	<?php echo $this->element('paginator'); ?>
	<table style="font-size: 13px; margin-top: 40px;" class="tight">
		<thead>
			<tr>
				<th><?php echo __('Tag funkcije'); ?></th>
				<th><?php echo __('Opis funkcije'); ?></th>
				<th><?php echo __('Primalac u sistem'); ?></th>
				<th><?php echo __('Primalac dodat ručno'); ?></th>
				<th></th>
			</tr>
		</thead>

		<tbody>
			<?php foreach( $emailNotificationRecipients as $emailNotificationRecipient ): ?>
			<tr>
				<td><?php echo $emailNotificationRecipient['EmailNotificationRecipient']['function_tag']; ?></td>
				<td><?php echo $emailNotificationRecipient['EmailNotificationRecipient']['description']; ?></td>
				<td><?php echo $emailNotificationRecipient['User']['email']; ?></td>
				<td><?php echo $emailNotificationRecipient['EmailNotificationRecipient']['email']; ?></td>
				<td class="right"><?php echo $this->Html->link('<i class="icon-trash"></i>', array('controller' => 'EmailNotificationRecipients', 'action' => 'delete', $emailNotificationRecipient['EmailNotificationRecipient']['id']), array('confirm' => __('Da li ste sigurni?'), 'escape' => false)); ?></td>
			</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
	<?php echo $this->element('paginator'); ?>
</div>

<script>
	$("#EmailNotificationRecipientFunctionTag").select2({width: '350px'});
	$("#EmailNotificationRecipientEmail").select2({width: '350px'});
</script>