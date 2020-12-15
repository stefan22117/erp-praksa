<!-- Breadcrumbs -->
<div class="breadcrumbs_holder">
	<ul class="breadcrumbs">
		<li><?php echo $this->Html->link(__('Početna'), '/'); ?></li>
	    <li>
	        <?php
	            echo $this->Html->link(
	                __('Organizacija ERP-a'),
	                array(
	                    'controller' => 'ErpUnits',
	                    'action' => 'index'
	                )
	            );
	        ?>
	    </li>
		<li>
			<?php
				echo $this->Html->link(
					__('ERP developeri'),
					array(
						'controller' => 'ErpDevelopers',
						'action' => 'index'
					)
				);
			?>
		</li>
		<li class="last">
			<a href="" onclick="return false"><?php echo __('Snimanje developera'); ?></a>
		</li>
	</ul>
</div>
<!-- Messages -->
<div id='alert'><?php echo $this->Session->flash(); ?></div>
<!-- Top bar -->
<div class="name_add_search">
	<div class="name_of_page">
		<h3><i class="icon-save"></i> <?php echo __('Snimanje developera'); ?></h3>
	</div>
</div>
<!-- Main content -->
<div class="content_data">
	<fieldset style="padding-top: 15px;">
		<?php echo $this->Form->create('ErpDeveloper'); ?>
		<?php echo $this->Form->input('id'); ?>
		<div class="col_4"></div>
		<div class="col_4">
			<?php echo $this->Form->label('user_id', __('Korisnik').' <span class="red">*</span>'); ?>
			<?php
				echo $this->Form->input(
					'user_id',
					array(
						'type' => 'select',
						'label' => false,
						'empty' => __('Izaberite...'),
						'required' => false,
						'class' => 'dropdown',
						'style' => 'width:100%'
					)
				);
			?>
		</div>		
		<div class="col_4"></div>
		<div class="clear"></div>
		<div class="col_4"></div>
		<div class="holder col_4">
	        <?php
	        	echo $this->Form->checkbox(
	        		'active',
	        		array(
	        			'label' => false,
	        			'div' => false,
	        		)
	        	);
	        ?>
	        <?php echo $this->Form->label('active', __('Aktivan')); ?>
		</div>
		<div class="col_4"></div>
		<div class="clear"></div>
		<hr>
		<div class="col_4"></div>
		<div class="col_2">
			<?php
				echo $this->Html->link(
					'<i class="icon-arrow-left" style="margin-right: 5px;"></i>'.__('Nazad'),
					array(
						'controller' => 'ErpDevelopers',
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
	                    'style' => 'width: 100%; margin-top: 30px;'
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
    });
</script>
