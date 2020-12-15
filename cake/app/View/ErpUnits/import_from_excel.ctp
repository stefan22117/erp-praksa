<!-- Breadcrumbs -->
<div class="breadcrumbs_holder">
    <ul class="breadcrumbs">
        <li><?php echo $this->Html->link(__('Početna'), '/'); ?></li>
        <li><?php echo $this->Html->link(__('Organizacija ERP-a'), array('controller' => 'ErpUnits', 'action' => 'index')); ?></li>
        <li class="last"><a href="" onclick="return false"><?php echo __('Učitavanje podataka iz Excel fajla'); ?></a></li>
    </ul>
</div>

<div id='alert'><?php echo $this->Session->flash(); ?></div>

<div class="name_add_search">
    <div class="name_of_page">
        <h3><i class="icon-save"></i> <?php echo __('Učitavanje podataka iz Excel fajla'); ?></h3>
    </div>
    <!-- Button bar -->
    <div style="float:right; margin:20px 24px 0 0;">
        <ul class="button-bar">        
            <li class="first">
                <?php echo $this->Html->link('<i class="icon-download" style="color :#669E00"></i> '.__('Download template'), Router::url('/', true).'app/webroot/files/ErpUnitsTemplate.xlsx', array('escape' => false, 'download' => 'mcusTemplate.xlsx')); ?>
            </li>
        </ul>
    </div>
</div>

<div class="content_data">
	<?php echo $this->Form->create('ErpUnit', array('type' => 'file')); ?>
	<?php echo $this->Form->label('upload', __('Upload Excel fajla (dozvoljeni formati: .xls, .xlsx)')); ?>
	<?php echo $this->Form->file('upload', array('label' => false, 'class' => 'col_12', 'style' => 'margin: 0; width: 100%;', 'required' => false)); ?>
	<div class="buttons_box">
		<div class="button_box">
		<?php 
			echo $this->Form->submit(__('Ucitaj'), array(
				'div' => false,
				'class' => "button blue",
				'style' => "margin:20px 0 20px 0;"
			));
		?>
		</div>
		<div class="button_box">
			<?php echo $this->Html->link(__('Nazad'), array('action' => 'index'), array('class' => 'button', 'style' => 'margin:20px 0 20px 0;')); ?>
			<?php echo $this->Form->end(); ?>
		</div>
	</div>
</div>
<div class="clear"></div>
