<!-- Custom style -->
<style type="text/css">
	.error {
		color: red;
	}
	.requiredAsterisk {
		color:red;
		margin-left: 5px;
	}
</style>
<!-- Breadcrumbs -->
<div class="breadcrumbs_holder">
	<ul class="breadcrumbs">
		<li><?php echo $this->Html->link(__('Početna'), '/'); ?></li>
		<li><?php echo $this->Html->link(__('Organizacija ERP-a'), array('controller' => 'ErpUnits', 'action' => 'index')); ?></li>
		<li class="last"><a href="" onclick="return false"><?php echo __('Snimanje modula'); ?></a></li>
	</ul>
</div>
<!-- Messages -->
<div id='alert'><?php echo $this->Session->flash(); ?></div>
<!-- Top bar -->
<div class="name_add_search">
    <!-- Page title -->
	<div class="name_of_page">
		<h3><i class="icon-save"></i> <?php echo __('Snimanje modula'); ?></h3>
	</div>
</div>
<!-- Main content -->
<div class="content_data">
	<fieldset style="padding-top: 15px;">
		<?php echo $this->Form->create('ErpUnit'); ?>
		<?php echo $this->Form->input('id'); ?>
		<div class="col_2">
			<?php echo $this->Form->label('level', __('Nivo').'<span class="requiredAsterisk">*</span>'); ?>
			<?php
				echo $this->Form->input('level', array(
					'type' => 'select',
					'label' => false,
					'required' => false,
					'class' => 'dropdown',
					'style' => 'width: 100%;',
					'empty' => __('Izaberi...'),
					'default' => $level
				));
			?>
			<div id="ErpUnitLevelError" class="error"></div>
		</div>
		<div class="col_4">
			<div id="parent">
				<?php echo $this->Form->label('parent_id', __('Roditelj').'<span class="requiredAsterisk">*</span>'); ?>
				<?php
					echo $this->Form->input('parent_id', array(
						'type' => 'select',
						'label' => false,
						'required' => false,
						'class' => 'dropdown',
						'style' => 'width: 100%;',
						'empty' => __('Izaberi...'),
						'default' => $parentId
					));
				?>
				<div id="ErpUnitParentIdError" class="error"></div>
			</div>
		</div>
		<div class="col_6">
			<?php echo $this->Form->label('name', __('Naziv').'<span class="requiredAsterisk">*</span>'); ?>
			<?php
				echo $this->Form->input('name', array(
					'type' => 'text',
					'label' => false,
					'required' => false,
					'style' => 'width: 100%;',
					'placeholder' => __('Unesi naziv')
				));
			?>
			<div id="ErpUnitNameError" class="error"></div>
		</div>
		<div class="clear"></div>
		<hr>
		<div id="devs">
			<div class="col_5">
				<?php echo $this->Form->label('developer_id', __('Developeri').'<span class="requiredAsterisk">*</span>'); ?>
				<?php
					echo $this->Form->input('developer_id', array(
						'type' => 'select',
						'multiple' => true,
						'label' => false,
						'required' => false,
						'class' => 'dropdown',
						'style' => 'width: 100%;',
						'empty' => __('Izaberi...')
					));
				?>
				<div id="ErpUnitDeveloperIdError" class="error"></div>
			</div>
			<div class="col_2">
				<?php
					echo $this->Html->link(
						'<i class="icon-plus-sign" style="margin-right: 10px; color: green;"></i>'.__('Dodaj developera'),
						array(
							'controller' => 'ErpDevelopers',
							'action' => 'save'
						),
						array(
							'escape' => false,
							'class' => 'button',
							'style' => 'width: 100%; text-align: center; margin-top: 17px;'
						)
					);
				?>
			</div>
			<div class="col_5">
				<?php echo $this->Form->label('maintainer_id', __('Maintaineri').'<span class="requiredAsterisk">*</span>'); ?>
				<?php
					echo $this->Form->input('maintainer_id', array(
						'type' => 'select',
						'multiple' => true,
						'label' => false,
						'required' => false,
						'class' => 'dropdown',
						'style' => 'width: 100%;',
						'empty' => __('Izaberi...')
					));
				?>
				<div id="ErpUnitMaintainerIdError" class="error"></div>
			</div>
			<hr>
		</div>
		<div class="clear"></div>
		<div class="col_4">
			<?php echo $this->Form->label('controller_id', __('Kontroler')); ?>
			<?php
				echo $this->Form->input('controllers', array(
					'type' => 'select',
					'multiple' => false,
					'label' => false,
					'required' => false,
					'class' => 'dropdown',
					'style' => 'width: 100%;',
					'empty' => __('Izaberi...')
				));
			?>
			<div id="ErpUnitControllerError" class="error"></div>
		</div>
		<div class="col_4">
			<?php echo $this->Form->label('action_id', __('Akcija')); ?>
			<?php
				echo $this->Form->input('actions', array(
					'type' => 'select',
					'multiple' => false,
					'label' => false,
					'required' => false,
					'class' => 'dropdown',
					'style' => 'width: 100%;',
					'empty' => __('Izaberi...')
				));
			?>
			<div id="ErpUnitControllerError" class="error"></div>
		</div>
		<div class="col_3">
			<?php
				echo $this->Html->link(
					'<i class="icon-plus-sign" style="margin-right: 5px;"></i>'.__('Dodaj'),
					array(),
					array(
						'escape' => false,
						'class' => 'btn small green',
						'style' => 'margin-top: 23px;',
						'onclick' => 'return false',
						'id' => 'AddControllerAction'
					)
				);
			?>
		</div>
		<div class="clear"></div>
		<div class="col_12">
			<?php
				echo $this->Form->input('aco_id', array(
					'type' => 'select',
					'multiple' => true,
					'label' => false,
					'required' => false,
					'class' => 'dropdown',
					'style' => 'width: 100%;'
				));
			?>
			<div id="ErpUnitControllerError" class="error"></div>
		</div>
		<div class="clear"></div>
		<hr>
		<div class="col_12">
			<?php echo $this->Form->label('note', __('Napomena')); ?>
			<?php
				echo $this->Form->input('note', array(
					'type' => 'text',
					'label' => false,
					'required' => false,
					'style' => 'width: 100%;',
					'placeholder' => __('')
				));
			?>
			<div id="ErpUnitNoteError" class="error"></div>
		</div>
		<div class="clear"></div>
		<hr>
		<div class="col_4"></div>
		<div class="col_2">
			<?php
				echo $this->Html->link(
					'<i class="icon-arrow-left" style="margin-right: 5px;"></i>'.__('Nazad'),
					array(
						'controller' => 'ErpUnits',
						'action' => 'index'
					),
					array(
						'escape' => false,
						'class' => 'button',
						'style' => 'width: 100% ;margin-top: 30px;'
					)
				);
			?>
		</div>
		<div class="col_2">
			<?php
                echo $this->Form->submit(
                	__('Snimi'),
                	array(
	                    'div' => false,
	                    'class' => 'button blue',
	                    'style' => 'width: 100%; margin-top: 30px;',
	                    'onclick' => 'return false',
	                    'id' => 'SaveUnitButton'
                	)
                );
			?>
		</div>
		<div class="col_4"></div>
		<?php echo $this->Form->end(); ?>
		<div class="clear"></div>
	</fieldset>
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
		$('#parent').hide();
		$('#SaveUnitButton').click(function(){
			var error = 0;
			if(!error) {
				$('#ErpUnitSaveForm').submit();
			}
		});
		if($('#ErpUnitLevel').val() != '' && $('#ErpUnitLevel').val() != 'module') {
			$('#parent').show();
			$('#devs').show();
		} else {
			$('#parent').hide();
			$('#devs').hide();
		}
		$('#ErpUnitLevel').change(function(){
			if($(this).val() != '' && $(this).val() != 'module') {
				$('#parent').show();
				$('#devs').show();
			} else {
				$('#parent').hide();
				$('#devs').hide();
			}
			$('#ErpUnitParentId option[value!=""]').remove();
			$("#ErpUnitParentId").off("select2-selecting");
			$("#ErpUnitParentId").select2();
			var id = $(this).val();
			var level = $(this).val();
			$.ajax({
				url: '<?php echo $this->Html->url(array('controller' => 'ErpUnits', 'action'=>'selectParents')); ?>',
				type: "post",
				dataType: "json",
				evalScripts: true,
				data: {level: level},
				success: function(response) {
					Object.keys(response).sort().forEach(function(key, i) {
						$('#ErpUnitParentId').append($("<option></option>").attr("value",key).text(response[key]));
					});
				}
			});
		});

		$('#ErpUnitControllers').change(function(){
			$('#ErpUnitActions option[value!=""]').remove();
			$("#ErpUnitActions").off("select2-selecting");
			$("#ErpUnitActions").select2();
			var id = $(this).val();
			$.ajax({
				url: '<?php echo $this->Html->url(array('controller' => 'MenuItems', 'action'=>'selectActions')); ?>',
				type: "post",
				dataType: "json",
				evalScripts: true,
				data: {id: id},
				success: function(response) {
					Object.keys(response).sort().forEach(function(key, i) {
						$('#ErpUnitActions').append($("<option></option>").attr("value",key).text(response[key]));
					});
				}
			});
		});

		var a = [];
		if ($('#ErpUnitAcoId').val()) {
			a = $('#ErpUnitAcoId').val();
		}

		$('#AddControllerAction').click(function(){
			var controllerId = $('#ErpUnitControllers').val();
			var actionId = $('#ErpUnitActions').val();
			var acoId = controllerId;
			if (actionId != '')
				acoId = actionId;
			$.ajax({
				url: '<?php echo $this->Html->url(array('controller' => 'ErpUnits', 'action'=>'selectAco')); ?>',
				type: "post",
				dataType: "json",
				evalScripts: true,
				data: {acoId: acoId},
				success: function(response) {
					Object.keys(response).sort().forEach(function(key, i) {
						$('#ErpUnitAcoId').append($("<option></option>").attr("value",key).text(response[key]));
						a.push(key);
						$('#ErpUnitAcoId').val(a).trigger('change');
					});
				}
			});
		});

		//$('#ErpUnitAcoId').prop('selected', 'selected');

	});
</script>
