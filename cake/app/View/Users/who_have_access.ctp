<ul class="breadcrumbs">
	<li><?php echo $this->Html->link(__('Početna'), '/'); ?></li>
	<li><?php echo $this->Html->link(__('Korisnici'), array('controller' => 'Users', 'action' => 'index')); ?></li>
	<li class="last"><a href="" onclick="return false"><?php echo __('Pregled'); ?></a></li>
</ul>
<div id='alert'><?php echo $this->Session->flash(); ?></div>
<div class="name_add_search">
	<div class="name_of_page"><h3><?php echo __('Ko ima dozvolu'); ?></h3></div>
	<div class="add_and_search">
		
	</div>
</div>

<div class="content_data" style="margin-left:250px">
		<?php echo $this->Form->create('User', array('type' => 'get')); ?>
		<div class="content_text_input">
			<label for="UserController"> <?php echo __('Kontroller'); ?></label> <label class="red">*</label>
			<?php echo $this->Form->input('controller', array('label' => false, 'options' => $controllers, 'value' => $controller.'Controller', 'required' => false, 'empty' => '(Choose controller)', 'autocomplete' => 'off')); ?>
		</div>

		<div class="content_text_input">
			<label for="UserController"> <?php echo __('Akcija'); ?></label> <label class="red">*</label>
			<?php echo $this->Form->input('action', array('label' => false, 'options' => array(), 'required' => false, 'empty' => '(Choose action)', 'autocomplete' => 'off')); ?>
		</div>

		<div class="content_text_input">
			<div class="buttons_box">
				<div class="button_box">
					<?php echo $this->Form->submit(__('Prikaz'), array('div' => false, 'class' => "button blue", 'style' => "margin:20px 0 20px 0;")); ?>
				</div>
			</div>
		</div>
		<?php echo $this->Form->end(); ?>

		<div class="clear"></div>

		<?php if(!empty($haveAccesses)){ ?>
		<div class="content_text_input">
			<table class="tight" style="width:300px">
				<tbody>
				<tr>
					<td width="100px"><?php echo __('Kontroller'); ?></td>
					<td class="bold"><?php echo $controller; ?></td>
				</tr>
				<tr>
					<td><?php echo __('Akcija'); ?></td>
					<td class="bold"><?php echo $action; ?></td>
				</tr>
			</table>
		</div>


		<div class="content_text_input">
			<input type="text" id="search" size="35" placeholder="Filter"><br /><br />

			<table id="tableSearch" class="tight sortable" style="width:600px">
				<thead>
					<tr>
						<th width="300px"><?php echo __('Korisnik'); ?></th>
						<th><?php echo __('Grupa'); ?></th>
					</tr>
				</thead>
				<tbody>
					<?php foreach($haveAccesses as $haveAccess){ ?>
					<tr id="filterSearch">
						<td><?php echo $this->Html->link($haveAccess['User']['first_name'] . ' ' . $haveAccess['User']['last_name']. ' (' . $haveAccess['User']['username'] . ')', array('controller' => 'Users', 'action' => 'userpermissions', $haveAccess['User']['id']), array('target' => '_blank')); ?></td>
						<td><?php echo $this->Html->link($haveAccess['Group']['name'], array('controller' => 'Groups', 'action' => 'grouppermissions', $haveAccess['Group']['id']), array('target' => '_blank')); ?></td>
					</tr>
					<?php } ?>
				</tbody>
			</table>
		</div>
		<?php } ?>
</div>

<script>
$('#container').ready(function(){
	$('#UserController').select2( { width: '300px'} );
	$('#UserAction').select2( { width: '300px'} );

	controller = $('#UserController').val();
	if(controller) {
		getControllerActions(controller);
	}

	$("#UserController").change(function(){
		getControllerActions( $('#UserController').val() );
	});
});

function getControllerActions(controller) {
	//alert($('#UserController').val());
	//controller = $('#UserController').val();
	$.ajax({
		dataType: "json",
		type: "POST",
		evalScripts: true,
		data: ({ controller: controller }),
		url: "<?php echo Router::url(array('controller' => 'Users', 'action' => 'getControllerActions')); ?>",
		success: function (data){
			var result = $.map(data, function(k, v) {
				return [k,v];
			});
			var length = result.length,
				element = null, value = null;
			if(length > 0){
				for (var i = 0; i < length; i++) {
					element = result[i]; i++;
					value = result[i];
					$('#UserAction').append($("<option></option>").attr("value",element).text(element));
				}
			}
		},
		error:function(xhr){
			var error_msg = "An error occured: " + xhr.status + " " + xhr.statusText;
			alert(error_msg);
		}
	});
}

/* pretraga polja u tabeli */
var $rows = $('#tableSearch tr#filterSearch');
$('#search').keyup(function() {
	var val = '^(?=.*\\b' + $.trim($(this).val()).split(/\s+/).join('\\b)(?=.*\\b') + ').*$',
		reg = RegExp(val, 'i'),
		text;

	$rows.show().filter(function() {
		text = $(this).text().replace(/\s+/g, ' ');
		return !reg.test(text);
	}).hide();
});
</script>