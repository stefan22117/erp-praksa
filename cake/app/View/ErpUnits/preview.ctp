<!-- Custom style -->
<style type="text/css">
	.breadcrumbs_holder, .name_add_search {
		width: 95%;
		margin-left: 50px;
	}
	.content_data {
		width: 95%;
		margin-left: 50px;
	}
	.name_add_search {
		width: 95%;
	}
	.main {
		min-height: 1000px;
	}
	table {
		font-size: 11px;
	}
	table tr td {
		font-size: 11px;
		border: 1px solid #ccc;
	}
	table tr th {
		border: 1px solid #ccc;
		background: #fff;
		text-align: center;
	}
	table tr td:hover {
		background: #eee;
	}
</style>
<!-- Breadcrumbs -->
<div class="breadcrumbs_holder">
    <ul class="breadcrumbs">
        <li><?php echo $this->Html->link(__('Početna'), '/'); ?></li>
        <li class="last"><a href="" onclick="return false"><?php echo __('Organizacija ERP-a'); ?></a></li>
    </ul>
</div>
<!-- Messages -->
<div id='alert'><?php echo $this->Session->flash(); ?></div>
<!-- Top bar -->
<div class="name_add_search">
    <!-- Page title -->
    <div class="name_of_page" style="float: left;">
        <h3><i class="icon-sitemap"></i> <?php echo __('Organizacija ERP-a'); ?></h3>
    </div>
    <!-- Buttons -->
    <div style="float:right; margin:20px 24px 0 0;">
        <ul class="button-bar">
            <li class="first last">
                <?php
                    echo $this->Html->link(
                        '<i class="icon-file" style="color:green;"></i> '.__('Export u EXCEL'),
                        array(
                            'controller' => 'ErpUnits',
                            'action' => 'exportToExcel'
                        ),
                        array('escape' => false)
                    );
                ?>
            </li>
        </ul>
    </div>
    <div class="clear"></div>
</div>
<div class="clear"></div>
<!-- Main content -->
<div class="content_data main">
	<?php if(empty($data)): ?>
    <div class="notice warning">
        <i class="icon-warning-sign icon-large"></i>
            <?php echo __('Nema podataka'); ?>
        <a class="icon-remove" href="#close"></a>
    </div>
	<?php else: ?>
	<table>
		<tr>
			<th colspan="13" style="background: #4af;"><?php echo __('Organizacija ERP-a'); ?></th>
		</tr>
		<tr>
			<th width="15%" colspan="2"><?php echo __('Modul'); ?></th>
			<th width="15%" colspan="2"><?php echo __('Grupa'); ?></th>
			<th width="15%" colspan="2"><?php echo __('Podgrupa'); ?></th>
			<th width="15%" colspan="2"><?php echo __('Vrsta'); ?></th>
			<th width="15%" colspan="2"><?php echo __('Podvrsta'); ?></th>
			<th width="10%"><?php echo __('Developer'); ?></th>
			<th width="10%"><?php echo __('Maintainer'); ?></th>
			<th width="5%"><?php echo __('Napomena'); ?></th>
		</tr>
		<tr>
			<?php
				echo $this->Form->create(
					'ErpUnit',
					array(
						'type' => 'get',
						'action' => 'preview'
					)
				);
			?>
			<td colspan="2">
			<?php
				echo $this->Form->input(
					'module_id',
					array(
						'label' => false,
						'div' => false,
						'style' => 'width: 100%; margin-right: 20px;',
						'class' => 'dropdown',
						'type' => 'select',
						'empty' => __('Svi')
					)
				);
			?>
			</td>
			<td colspan="2">
			<?php
				echo $this->Form->input(
					'group_id',
					array(
						'label' => false,
						'div' => false,
						'style' => 'width: 100%; margin-right: 20px;',
						'class' => 'dropdown',
						'type' => 'select',
						'empty' => __('Sve')
					)
				);
			?>
			</td>
			<td colspan="2">
			<?php
				echo $this->Form->input(
					'subgroup_id',
					array(
						'label' => false,
						'div' => false,
						'style' => 'width: 100%; margin-right: 20px;',
						'class' => 'dropdown',
						'type' => 'select',
						'empty' => __('Sve')
					)
				);
			?>
			</td>
			<td colspan="2">
			<?php
	            echo $this->Form->input(
					'type_id',
					array(
						'label' => false,
						'div' => false,
						'style' => 'width: 100%; margin-right: 20px;',
						'class' => 'dropdown',
						'type' => 'select',
						'empty' => __('Sve')
					)
				);
			?>
			</td>
			<td colspan="2">
			<?php
				echo $this->Form->input(
					'subtype_id',
					array(
						'label' => false,
						'div' => false,
						'style' => 'width: 100%; margin-right: 20px;',
						'class' => 'dropdown',
						'type' => 'select',
						'empty' => __('Sve')
					)
				);
			?>
			</td>
			<td>
			<?php
				echo $this->Form->input(
					'developer_id',
					array(
						'label' => false,
						'div' => false,
						'style' => 'width: 100%; margin-right: 20px;',
						'class' => 'dropdown',
						'type' => 'select',
						'empty' => __('Svi')
					)
				);
			?>
			</td>
			<td>
			<?php
				echo $this->Form->input(
					'maintainer_id',
					array(
						'label' => false,
						'div' => false,
						'style' => 'width: 100%; margin-right: 20px;',
						'class' => 'dropdown',
						'type' => 'select',
						'empty' => __('Svi')
					)
				);
			?>
			</td>
			<td>
			<?php
				echo $this->Form->button(
					'Prikaži',
					array(
						'type' => 'submit',
						'class' => 'small green',
						'style' => 'width: 100%;'
					)
				);
			?>
			</td>
			<?php echo $this->Form->end(); ?>
		</tr>
		<?php foreach ($data as $unit): ?>
			<?php $color = '#fff'; ?>
			<?php if(!empty($unit['module'])) $color = '#eea'; ?>
			<?php if(!empty($unit['group'])) $color = '#eee'; ?>
			<tr style="background: <?php echo $color; ?>;">
				<td style="text-align: center; width: 3%; color: #f80;"><?php echo $unit['module']['code']; ?></td>
				<td>
					<?php echo $this->Html->link($unit['module']['name'], array(
						'controller' => 'ErpUnits',
						'action' => 'view',
						$unit['id']));
					?>
					<?php
						if(!empty($unit['module']['name'])) {
							echo $this->Html->link(
								'('.$unit['module']['feedbacks'].')',
								array(
									'controller' => 'Feedbacks',
									'action' => 'index'.'?erp_unit_id='.$unit['id']
								),
								array(
									'style' => 'text-decoration:none; color:red;'
								)
							);
						}
					?>
				</td>
				<td style="text-align: center; width: 3%; color: #f80;"><?php echo $unit['group']['code']; ?></td>
				<td>
					<?php echo $this->Html->link($unit['group']['name'], array(
						'controller' => 'ErpUnits',
						'action' => 'view',
						$unit['id']));
					?>
					<?php
						if(!empty($unit['group']['name'])) {
							echo $this->Html->link(
								'('.$unit['group']['feedbacks'].')',
								array(
									'controller' => 'Feedbacks',
									'action' => 'index'.'?erp_unit_id='.$unit['id']
								),
								array(
									'style' => 'text-decoration:none; color:red;'
								)
							);
						}
					?>
				</td>
				<td style="text-align: center; width: 3%; color: #f80;"><?php echo $unit['subgroup']['code']; ?></td>
				<td>
					<?php echo $this->Html->link($unit['subgroup']['name'], array(
						'controller' => 'ErpUnits',
						'action' => 'view',
						$unit['id']));
					?>
					<?php
						if(!empty($unit['subgroup']['name'])) {
							echo $this->Html->link(
								'('.$unit['subgroup']['feedbacks'].')',
								array(
									'controller' => 'Feedbacks',
									'action' => 'index'.'?erp_unit_id='.$unit['id']
								),
								array(
									'style' => 'text-decoration:none; color:red;'
								)
							);
						}
					?>
				</td>
				<td style="text-align: center; width: 3%; color: #f80;"><?php echo $unit['type']['code']; ?></td>
				<td>
					<?php echo $this->Html->link($unit['type']['name'], array(
						'controller' => 'ErpUnits',
						'action' => 'view',
						$unit['id']));
					?>
					<?php
						if(!empty($unit['type']['name'])) {
							echo $this->Html->link(
								'('.$unit['type']['feedbacks'].')',
								array(
									'controller' => 'Feedbacks',
									'action' => 'index'.'?erp_unit_id='.$unit['id']
								),
								array(
									'style' => 'text-decoration:none; color:red;'
								)
							);
						}
					?>
				</td>
				<td style="text-align: center; width: 3%; color: #f80;"><?php echo $unit['subtype']['code']; ?></td>
				<td>
					<?php echo $this->Html->link($unit['subtype']['name'], array(
						'controller' => 'ErpUnits',
						'action' => 'view',
						$unit['id']));
					?>
					<?php
						if(!empty($unit['subtype']['name'])) {
							echo $this->Html->link(
								'('.$unit['subtype']['feedbacks'].')',
								array(
									'controller' => 'Feedbacks',
									'action' => 'index'.'?erp_unit_id='.$unit['id']
								),
								array(
									'style' => 'text-decoration:none; color:red;'
								)
							);
						}
					?>
				</td>
				<td style="color: green;"><?php echo $unit['developer']; ?></td>
				<td style="color: green;"><?php echo $unit['maintainer']; ?></td>
				<td><?php echo $unit['note']; ?></td>
			</tr>
		<?php endforeach; ?>
	</table>
	<?php endif; ?>
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
		$('.dropdown').select2();
		$('#ErpUnitGroupId').attr('disabled', 'disabled');
		$('#ErpUnitSubgroupId').attr('disabled', 'disabled');
		$('#ErpUnitTypeId').attr('disabled', 'disabled');
		$('#ErpUnitSubtypeId').attr('disabled', 'disabled');

		var moduleId = $('#ErpUnitModuleId').val();
		if (moduleId) {
			$('#ErpUnitGroupId').removeAttr('disabled');
            $('#ErpUnitGroupId option[value!=""]').remove();
            $("#ErpUnitGroupId").off("select2-selecting");
            $("#ErpUnitGroupId").select2();

            $.ajax({
                url: '<?php echo $this->Html->url(array('controller' => 'ErpUnits', 'action'=>'selectGroups')); ?>',
                type: "post",
                dataType: "json",
                evalScripts: true,
                data: {id: moduleId},
                success: function(response) {
                    Object.keys(response).sort().forEach(function(key, i) {
                        $('#ErpUnitGroupId').append($("<option></option>").attr("value",key).text(response[key]));
                    });
					$('#ErpUnitGroupId').val(
						<?php
							if (!empty($this->request->data['ErpUnit']['group_id']))
								echo $this->request->data['ErpUnit']['group_id'];
						?>
					);
					$('#ErpUnitGroupId').trigger('change.select2');

					var groupId = $('#ErpUnitGroupId').val();
					if (groupId) {
						$('#ErpUnitSubgroupId').removeAttr('disabled');
						$('#ErpUnitSubgroupId option[value!=""]').remove();
						$("#ErpUnitSubgroupId").off("select2-selecting");
						$("#ErpUnitSubgroupId").select2();
						$.ajax({
							url: '<?php echo $this->Html->url(array('controller' => 'ErpUnits', 'action'=>'selectGroups')); ?>',
							type: "post",
							dataType: "json",
							evalScripts: true,
							data: {id: groupId},
							success: function(response) {
								Object.keys(response).sort().forEach(function(key, i) {
									$('#ErpUnitSubgroupId').append($("<option></option>").attr("value",key).text(response[key]));
								});
								$('#ErpUnitSubgroupId').val(
									<?php
										if (!empty($this->request->data['ErpUnit']['subgroup_id']))
											echo $this->request->data['ErpUnit']['subgroup_id'];
									?>
								);
								$('#ErpUnitSubgroupId').trigger('change.select2');

								var subgroupId = <?php
										if (!empty($this->request->data['ErpUnit']['subgroup_id']))
										echo $this->request->data['ErpUnit']['subgroup_id'];
										else echo 0;
								?>;
								if (subgroupId) {
									$('#ErpUnitTypeId').removeAttr('disabled');
									$('#ErpUnitTypeId option[value!=""]').remove();
									$("#ErpUnitTypeId").off("select2-selecting");
									$("#ErpUnitTypeId").select2();
									$.ajax({
										url: '<?php echo $this->Html->url(array('controller' => 'ErpUnits', 'action'=>'selectGroups')); ?>',
										type: "post",
										dataType: "json",
										evalScripts: true,
										data: {id: subgroupId},
										success: function(response) {
											Object.keys(response).sort().forEach(function(key, i) {
												$('#ErpUnitTypeId').append($("<option></option>").attr("value",key).text(response[key]));
											});
											$('#ErpUnitTypeId').val(
												<?php
													if (!empty($this->request->data['ErpUnit']['type_id']))
														echo $this->request->data['ErpUnit']['type_id'];
												?>
											);
											$('#ErpUnitTypeId').trigger('change.select2');

											var typeId = <?php
													if (!empty($this->request->data['ErpUnit']['type_id']))
													echo $this->request->data['ErpUnit']['type_id'];
													else echo 0;
											?>;
											if (typeId) {
												$('#ErpUnitSubtypeId').removeAttr('disabled');
												$('#ErpUnitSubtypeId option[value!=""]').remove();
												$("#ErpUnitSubtypeId").off("select2-selecting");
												$("#ErpUnitSubtypeId").select2();
												$.ajax({
													url: '<?php echo $this->Html->url(array('controller' => 'ErpUnits', 'action'=>'selectGroups')); ?>',
													type: "post",
													dataType: "json",
													evalScripts: true,
													data: {id: typeId},
													success: function(response) {
														Object.keys(response).sort().forEach(function(key, i) {
															$('#ErpUnitSubtypeId').append($("<option></option>").attr("value",key).text(response[key]));
														});
														$('#ErpUnitSubtypeId').val(
															<?php
																if (!empty($this->request->data['ErpUnit']['subtype_id']))
																	echo $this->request->data['ErpUnit']['subtype_id'];
															?>
														);
														$('#ErpUnitSubtypeId').trigger('change.select2');
													}
												});
											} else {
												$('#ErpUnitSubtypeId').attr('disabled', 'disabled');
											}
										}
									});
								} else {
									$('#ErpUnitTypeId').attr('disabled', 'disabled');
								}

							}
						});
					} else {
						$('#ErpUnitSubgroupId').attr('disabled', 'disabled');
					}

                }
            });
		} else {
			$('#ErpUnitGroupId').attr('disabled', 'disabled');
		}
		$('#ErpUnitModuleId').change(function(){
            $('#ErpUnitGroupId option[value!=""]').remove();
            $("#ErpUnitGroupId").off("select2-selecting");
            $("#ErpUnitGroupId").select2();

            $('#ErpUnitSubgroupId option[value!=""]').remove();
            $("#ErpUnitSubgroupId").off("select2-selecting");
            $("#ErpUnitSubgroupId").select2();

            $('#ErpUnitTypeId option[value!=""]').remove();
            $("#ErpUnitTypeId").off("select2-selecting");
            $("#ErpUnitTypeId").select2();

            $('#ErpUnitSubtypeId option[value!=""]').remove();
            $("#ErpUnitSubtypeId").off("select2-selecting");
            $("#ErpUnitSubtypeId").select2();

			var id = $(this).val();
			if (id) $('#ErpUnitGroupId').removeAttr('disabled');
			else $('#ErpUnitGroupId').attr('disabled', 'disabled');

			$('#ErpUnitSubgroupId').attr('disabled', 'disabled');
			$('#ErpUnitTypeId').attr('disabled', 'disabled');
			$('#ErpUnitSubtypeId').attr('disabled', 'disabled');

            $.ajax({
                url: '<?php echo $this->Html->url(array('controller' => 'ErpUnits', 'action'=>'selectGroups')); ?>',
                type: "post",
                dataType: "json",
                evalScripts: true,
                data: {id: id},
                success: function(response) {
                    Object.keys(response).sort().forEach(function(key, i) {
                        $('#ErpUnitGroupId').append($("<option></option>").attr("value",key).text(response[key]));
                    });
					$('#ErpUnitPreviewForm').submit();
                }
            });

		});
		$('#ErpUnitGroupId').change(function(){
            $('#ErpUnitSubgroupId option[value!=""]').remove();
            $("#ErpUnitSubgroupId").off("select2-selecting");
            $("#ErpUnitSubgroupId").select2();

            $('#ErpUnitTypeId option[value!=""]').remove();
            $("#ErpUnitTypeId").off("select2-selecting");
            $("#ErpUnitTypeId").select2();

            $('#ErpUnitSubtypeId option[value!=""]').remove();
            $("#ErpUnitSubtypeId").off("select2-selecting");
            $("#ErpUnitSubtypeId").select2();

			var id = $(this).val();
			if (id) $('#ErpUnitSubgroupId').removeAttr('disabled');
			else $('#ErpUnitSubgroupId').attr('disabled', 'disabled');

			$('#ErpUnitTypeId').attr('disabled', 'disabled');
			$('#ErpUnitSubtypeId').attr('disabled', 'disabled');

            $.ajax({
                url: '<?php echo $this->Html->url(array('controller' => 'ErpUnits', 'action'=>'selectGroups')); ?>',
                type: "post",
                dataType: "json",
                evalScripts: true,
                data: {id: id},
                success: function(response) {
                    Object.keys(response).sort().forEach(function(key, i) {
                        $('#ErpUnitSubgroupId').append($("<option></option>").attr("value",key).text(response[key]));
                    });
					$('#ErpUnitPreviewForm').submit();
                }
            });
		});
		$('#ErpUnitSubgroupId').change(function(){
            $('#ErpUnitTypeId option[value!=""]').remove();
            $("#ErpUnitTypeId").off("select2-selecting");
            $("#ErpUnitTypeId").select2();

            $('#ErpUnitSubtypeId option[value!=""]').remove();
            $("#ErpUnitSubtypeId").off("select2-selecting");
            $("#ErpUnitSubtypeId").select2();

			var id = $(this).val();
			if (id) $('#ErpUnitTypeId').removeAttr('disabled');
			else $('#ErpUnitTypeId').attr('disabled', 'disabled');

			$('#ErpUnitSubtypeId').attr('disabled', 'disabled');

            $.ajax({
                url: '<?php echo $this->Html->url(array('controller' => 'ErpUnits', 'action'=>'selectGroups')); ?>',
                type: "post",
                dataType: "json",
                evalScripts: true,
                data: {id: id},
                success: function(response) {
                    Object.keys(response).sort().forEach(function(key, i) {
                        $('#ErpUnitTypeId').append($("<option></option>").attr("value",key).text(response[key]));
                    });
					$('#ErpUnitPreviewForm').submit();
                }
            });
		});
		$('#ErpUnitTypeId').change(function(){
            $('#ErpUnitSubtypeId option[value!=""]').remove();
            $("#ErpUnitSubtypeId").off("select2-selecting");
            $("#ErpUnitSubtypeId").select2();

			var id = $(this).val();
			if (id) $('#ErpUnitSubtypeId').removeAttr('disabled');
			else $('#ErpUnitSubtypeId').attr('disabled', 'disabled');

            $.ajax({
                url: '<?php echo $this->Html->url(array('controller' => 'ErpUnits', 'action'=>'selectGroups')); ?>',
                type: "post",
                dataType: "json",
                evalScripts: true,              
                data: {id: id},
                success: function(response) {
                    Object.keys(response).sort().forEach(function(key, i) {
                        $('#ErpUnitSubtypeId').append($("<option></option>").attr("value",key).text(response[key]));
                    });
					$('#ErpUnitPreviewForm').submit();
                }
            });
		});

    });
</script>
