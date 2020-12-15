<ul class="breadcrumbs">
	<li><?php echo $this->Html->link(__('Početna'), '/'); ?></li>
	<li><a href=""><?php echo $page_title; ?></a></li>
</ul>

<div id="alert"><?php echo $this->Session->flash(); ?></div>

<div class="name_and_search">
	<div class="name_of_page"><h3><?php echo $page_title; ?></h3></div>
</div>

<div class="content_data">
	<?php if (empty($notifications)){ ?>
	<div class="notice warning">
		<i class="icon-warning-sign icon-large"></i>
			<?php echo __('Nema notifikacija'); ?>
		<a class="icon-remove" href="#close"></a>
	</div>
	<?php }else{ ?>
	<?php echo $this->element('paginator'); ?>
	<fieldset class="col_12">
		<table>
			<thead>
				<tr>
					<th style="width: 30px;">&nbsp;</th>
					<th style="width: 80px;"><?php echo __('Datum'); ?></th>
					<th style="width: 60px;"><?php echo __('Vreme'); ?></th>
					<th><?php echo __('Naslov'); ?></th>
					<th><?php echo __('Opis'); ?></th>
					<th><?php echo __('Dokument'); ?></th>
					<th></th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($notifications as $notification) { ?>
					<tr>
						<td>
							<?php if ($notification['Notification']['read']){ ?>
								<i class="icon-envelope blue" title="<?php echo __('Pročitano'); ?>"></i>
							<?php }else{ ?>
								<i class="icon-envelope-alt red" title="<?php echo __('Nepročitano'); ?>"></i>
							<?php } ?>
						</td>
						<td><?php echo $this->Time->format('d.m.Y.', $notification['Notification']['created']); ?></td>
						<td><?php echo $this->Time->format('H:i', $notification['Notification']['created']); ?></td>
						<td><?php echo $notification['Notification']['title']; ?></td>
						<td><?php echo $notification['Notification']['description']; ?></td>
						<td><?php 
							$str = '';
			                if (!empty($notification['Notification']['document_code'])) $str .=  $notification['Notification']['document_code'].' - ';
			                $str .= $notification['Notification']['document_title'];
							echo $this->Html->link($str, array('controller' => $notification['Notification']['link_controller'], 'action' => $notification['Notification']['link_action'], $notification['Notification']['document_id'])); ?></td>
						<td></td>
					</tr>	
				<?php } ?>
			</tbody>
		</table>
	</fieldset>
	<?php echo $this->element('paginator'); ?>
	<?php } ?>
</div>