<!-- Custom style -->
<style type="text/css">
	.children {
		border: 1px solid #eee;
		padding: 10px;
	}
	.child {
		padding: 10px;
	}
	.controllers {
		border: 1px solid #eee;
		padding: 10px;
	}
	.controller {
		float: left;
		padding: 5px 10px;
		background: #eee;
		margin-right: 10px;
		margin-bottom: 10px;
		border: 1px solid #ccc;
	}
</style>
<!-- Breadcrumbs -->
<div class="breadcrumbs_holder">
    <ul class="breadcrumbs">
        <li><?php echo $this->Html->link(__('Početna'), '/'); ?></li>
        <li><?php echo $this->Html->link(__('Organizacija ERP-a'), array('controller' => 'ErpUnits', 'action' => 'index')); ?></li>
        <li class="last"><a href="" onclick="return false"><?php echo __('Detalji'); ?></a></li>
    </ul>
</div>
<!-- Messages -->
<div id='alert'><?php echo $this->Session->flash(); ?></div>
<!-- Top bar -->
<div class="name_add_search">
    <!-- Page title -->
    <div class="name_of_page">
        <h3><i class="icon-stop"></i> <?php echo $unit['ErpUnit']['code'].' - '.$unit['ErpUnit']['name']; ?></h3>
    </div>
    <!-- Buttons -->
    <div style="float:right; margin:20px 24px 0 0;">
        <ul class="button-bar">
            <li class="first">
                <?php
                	if(empty($unit['ErpUnit']['parent_id'])) $unit['ErpUnit']['parent_id'] = 'null';
                	echo $this->Html->link(
                		'<i class="icon-edit" style="color:orange;"></i> '.__('Izmena'),
                		array(
                			'controller' => 'ErpUnits',
                			'action' => 'save',
                			$unit['ErpUnit']['level'],
                			$unit['ErpUnit']['parent_id'],
                			$unit['ErpUnit']['id']
                		),
                        array(
                            'escape' => false,
                            'style' => ' font-size: 12px;'
                        )
                	);
                ?>
            </li>
            <li class="last">
                <?php
                	echo $this->Html->link(
                		'<i class="icon-trash" style="color:red;"></i> '.__('Brisanje'),
                		array(
                			'controller' => 'ErpUnits',
                			'action' => 'delete',
                			$unit['ErpUnit']['level'],
                			$unit['ErpUnit']['parent_id'],
                			$unit['ErpUnit']['id']
                		),
                		array(
                			'escape' => false,
                			'confirm' => 'Da li ste sigurni da želite da obrišete ovaj modul?',
                            'style' => ' font-size: 12px;'
                		)
                	);
                ?>
            </li>
        </ul>
    </div>
    <div class="clear"></div>
</div>
<!-- Main content -->
<div class="content_data">
	<table>
		<tr>
			<th width="200">Nadređeni modul</th>
			<td>
				<?php
					if(!empty($unit['ErpUnit']['parent_id']) && $unit['ErpUnit']['parent_id'] != 'null') {
						echo $this->Html->link(
							$unit['Parent']['code'].' - '.$unit['Parent']['name'],
							array(
								'controller' => 'ErpUnits',
								'action' => 'view',
								$unit['Parent']['id']
							)
						);
					} else {
						echo 'Nema';
					}
				?>
			</td>
		</tr>
		<tr>
			<th>Nivo</th>
			<td><?php echo $levels[$unit['ErpUnit']['level']]; ?></td>
		</tr>
		<tr>
			<th>Developeri</th>
			<td colspan="3">
				<?php foreach ($unit['developers'] as $developer): ?>
					<?php echo $developer; ?>
				<?php endforeach; ?>
			</td>
		</tr>
		<tr>
			<th>Maintaineri</th>
			<td colspan="3">
				<?php foreach ($unit['maintainers'] as $maintainer): ?>
					<?php echo $maintainer; ?>
				<?php  endforeach; ?>
			</td>
		</tr>
		<tr>
			<th>Feedbackovi</th>
			<td colspan="3">
				<?php
					echo $this->Html->link(
						'('.$unit['feedbacks'].')',
						array(
							'controller' => 'Feedbacks',
							'action' => 'index'.
							'?erp_unit_id='.$unit['ErpUnit']['id'].
							'&status=&status%5B%5D=open'
						)
					);
				?>
			</td>
		</tr>
		<tr>
			<th>Kontroleri</th>
		</tr>
		<tr>
			<td colspan="3">
				<div class="controllers">
					<?php if(isset($unit['controllers']['main']) && !empty($unit['controllers']['main'])): ?>
					<?php foreach ($unit['controllers']['main'] as $key => $controller): ?>
						<div class="controller">
							<?php // echo $controller; ?>
							<?php
								echo $this->Html->link(
									$controller,
									array(
										'controller' => 'ErpUnitControllers',
										'action' => 'view',
										$key
									),
									array()
								);
							?>
						</div>
					<?php endforeach; ?>
					<?php endif; ?>
					<div class="clear"></div>
				</div>
			</td>
		</tr>
		<?php if ($unit['ErpUnit']['level'] != 'subtype'): ?>
		<tr>
			<th colspan="2">Podređeni moduli</th>
		</tr>
		<tr>
			<td colspan="2">
				<div class="children">
					<?php foreach ($unit['children'] as $child): ?>
						<div class="child">
							<?php
								echo $this->Html->link(
									$child['ErpUnit']['code'].' - '.$child['ErpUnit']['name'],
									array(
										'controller' => 'ErpUnits',
										'action' => 'view',
										$child['ErpUnit']['id']
									)
								);
							?>
						</div>
					<?php endforeach; ?>
				</div>
			</td>
		</tr>
		<tr>
			<th colspan="2">Kontroleri podređenih modula</th>
		</tr>
		<tr>
			<td colspan="3">
				<div class="controllers">
					<?php if(isset($unit['controllers']['children']) && !empty($unit['controllers']['children'])): ?>
					<?php foreach ($unit['controllers']['children'] as $controller): ?>
						<div class="controller">
							<?php echo $controller; ?>
						</div>
					<?php endforeach; ?>
					<?php endif; ?>
					<div class="clear"></div>
				</div>
			</td>
		</tr>
		<?php endif; ?>
	</table>
</div>
<div class="clear"></div>
<!-- Loader -->
<div class="submit_loader">
    <?php echo $this->Html->image('submit_loader.gif', array('alt' => 'Loader')); ?>
    <h2>Molimo sačekajte...</h2>
</div> 
<!-- Custom script -->
<script type="text/javascript">
    $(document).ready(function() {
        $('.submit_loader').hide();
    });
</script>
