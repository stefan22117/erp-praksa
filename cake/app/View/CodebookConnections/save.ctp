<ul class="breadcrumbs">
	<li><?php echo $this->Html->link(__('Početna'), '/'); ?></li>
	<li><?php echo $this->Html->link(__('Šifarnici'), array('controller' => 'Codebooks', 'action' => 'index')); ?></li>
	<li><?php echo $this->Html->link(__('Veze sa dokumentima'), array('controller' => 'CodebookConnections', 'action' => 'index')); ?></li>
	<li class="last"><a href="" onclick="return false"><?php echo __('Snimanje'); ?></a></li>
</ul>
<div id='alert'><?php echo $this->Session->flash(); ?></div>

<div class="name_add_search">
	<div class="name_of_page"><h3><i class="icon-save"></i> <?php echo __('Snimanje veze sa dokumentima'); ?></h3></div>
</div>

<div class="content_data">
	<div class="formular">
		<?php echo $this->Form->create('CodebookConnection'); ?>
		<div class="col_9">
        	<?php echo $this->Form->label('codebook_id', __('Šifarnik').': <span class="red">*</span>'); ?>
        	<?php echo $this->Form->input('codebook_id', array('label' => false, 'options' => $codebooks, 'empty' => __('(Odaberite šifarnik)'), 'required' => false, 'class' => 'dropdown', 'style' => 'width:100%')); ?>
        </div>				
        <div class="clear"></div>
		<div class="content_text_input">
			<?php echo $this->Form->label('name', __('Naziv').' <span class="red">*</span>'); ?>
			<?php echo $this->Form->input('name', array('label' => false, 'class' => 'col_9 inputborder', 'required' => false, 'placeholder' => __('Unesite naziv veze dokumenata'))); ?>
		</div>
		<div class="content_text_input">
			<?php echo $this->Form->label('code', __('Šifra').' <span class="red">*</span>'); ?>
			<?php echo $this->Form->input('code', array('label' => false, 'class' => 'col_9 inputborder', 'required' => false, 'placeholder' => __('Unesite šifru veze dokumenata'))); ?>
		</div>		
		<div class="content_text_input">
			<?php echo $this->Form->label('list_method', __('Funkcija modela za listanje').' <span class="red">*</span>'); ?>
			<?php echo $this->Form->input('list_method', array('label' => false, 'class' => 'col_9 inputborder', 'required' => false, 'placeholder' => __('Unesite naziv funkcije modela za listanje'))); ?>
		</div>
		<div class="content_text_input">
			<?php echo $this->Form->label('list_json_query', __('Parametri akcije').'(JSON format)'); ?>
			<?php echo $this->Form->input('list_json_query', array('label' => false, 'class' => 'col_9 inputborder', 'required' => false, 'placeholder' => __('Unesite parametre akcije za listanje'))); ?>
		</div>		
		<div class="clear"></div>
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
					<?php echo $this->Html->link(__('Nazad'), array('controller' => 'CodebookConnections', 'action' => 'index'), array('class' => 'button', 'style' => 'margin:20px 0 20px 0;')); ?>
					<?php echo $this->Form->end(); ?>
				</div>
			</div>
		</div>
	</div>
</div>
<script>
$('#container').ready(function(){
	$(".submit_loader").hide();	 
    $(".dropdown").select2();    //Load select2 input
});
</script>