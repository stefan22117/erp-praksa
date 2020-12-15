<ul class="breadcrumbs">
	<li><?php echo $this->Html->link(__('Početna'), '/'); ?></li>
	<li class="last"><a href="" onclick="return false"><?php echo __('Tipovi priloga'); ?></a></li>
</ul>
<div id='alert'><?php echo $this->Session->flash(); ?></div>

<div class="name_add_search">
	<div class="name_of_page"><h3><?php echo __('Tipovi priloga'); ?></h3></div>
	<div class="add_and_search right">
		<?php echo $this->Html->link('<i class="icon-plus-sign"></i> '.__('Dodaj tip priloga'), array('controller' => 'AttachmentTypes', 'action' => 'save'), array('class' => 'button small blue adduser', 'escape' => false)) ;?>
	</div>
</div>

<?php echo $this->element('paginator'); ?>
<div class="content_data">
<?php if ( !empty( $attachmentTypes ) ){ ?>
	<table>
		<thead>
			<tr>
				<th width="200px"><?php echo __('Tip priloga'); ?></th>
				<th></th>
			</tr>
		</thead>
		<tbody>
			<?php foreach( $attachmentTypes as $attachmentType ){ ?>
			<tr>
				<td><?php echo $attachmentType['AttachmentType']['type']; ?></td>
				<td></td>
			</tr>
			<?php } ?>
		</tbody>
	</table>
<?php }else{ ?>
    <div class="notice warning">
        <i class="icon-warning-sign icon-large"></i>
        <?php echo __("Nema podataka u bazi!"); ?>
    </div>	
<?php } ?>
</div>
<?php echo $this->element('paginator'); ?>