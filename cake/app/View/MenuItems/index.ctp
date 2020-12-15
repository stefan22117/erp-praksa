<!-- Custom styles -->
<?php echo $this->Html->css('Script/McuSpecifications/style'); ?>

<!-- Breadcrumbs -->
<div class="breadcrumbs_holder">
    <ul class="breadcrumbs">
        <li><?php echo $this->Html->link(__('Početna'), '/'); ?></li>
        <li class="last"><a href="" onclick="return false"><?php echo __('Meni'); ?></a></li>
    </ul>
</div>

<div id='alert'><?php echo $this->Session->flash(); ?></div>

<div class="name_add_search">
    <div class="name_of_page">
        <h3><i class="icon-list-ul"></i> <?php echo __('Meni'); ?></h3>
    </div>
    <div style="float:right; margin:20px 24px 0 0;">
        <ul class="button-bar">        
            <li class="first">
                <?php echo $this->Html->link('<i class="icon-plus-sign" style="color :#669E00"></i> '.__('Dodaj novu stavku'), array('action' => 'save'), array('escape' => false)); ?>
            </li>
        </ul>
    </div>
    <div class="clear"></div>
</div>

<!-- Search -->
<div class="content_data meni">
    <fieldset style="margin-top:0;">
        <legend>Filteri</legend>
        <?php echo $this->Form->create('MenuItem', array('type' => 'get', 'action' => 'index')); ?>
        <?php echo $this->Form->input('keywords', array('label' => 'Pretraga', 'div' => false, 'style' => 'width:220px;', 'placeholder' => __('Unesite reči za pretragu'))); ?>
        <?php echo $this->Form->button('Prikaži', array('type' => 'submit', 'class' => 'small green right', 'style' => 'margin-left:10px;')); ?>
        <?php echo $this->Form->end(); ?>
    </fieldset>
</div>

<!-- Paginator -->
<div style="float:left; margin-left:20px;">
        <?php echo $this->Paginator->counter(__("Ukupno").': {:count}'); ?>
</div>
<?php echo $this->element('paginator'); ?>
<div class="clear"></div>

<!-- Main content -->
<div class="content_data">
    <?php if (empty($menuItems)): ?>
    <div class="notice warning">
        <i class="icon-warning-sign icon-large"></i>
            <?php echo __('Nema stavki u meniju'); ?>
        <a class="icon-remove" href="#close"></a>
    </div>
    <?php else: ?>

	<table style="table-layout: fixed;">
	<thead>
	<tr>
        <th width="40"></th>
        <th width="40"></th>
		<th><?php echo __('Stavka u meniju'); ?></th>
		<th width="100"></th>
	</tr>
	</thead>
	<tbody>
    <?php $counter = $this->Paginator->counter('{:start}'); ?>
	<?php foreach ($menuItems as $menuItem): ?>
	<tr>
        <td><?php echo $counter++.'.'; ?></td>
        <td><?php echo '<i class="' . $menuItem['ErpKickstartIcon']['icon_class'] . '"></i>'; ?></td>
        <td><?php echo $this->Html->link($menuItem['MenuItem']['name'], array('controller' => 'MenuItems', 'action' => 'index/menuItemId:'.$menuItem['MenuItem']['id'])); ?>
		<td class="right" style="white-space: nowrap;">
			<ul class="button-bar">
				<li class="first">
                    <?php echo $this->Html->link('<i class="icon-edit" style="color :#FC730A"></i>', array('action' => 'save', $menuItem['MenuItem']['id']), array('title' => __('Izmena'), 'escape' => false)); ?>
                </li>
                <li class="last">
                	<?php echo $this->Form->postLink('<i class="icon-trash" style="color :#B21203"></i>', array('action' => 'delete', $menuItem['MenuItem']['id']), array('title' => __('Brisanje'), 'confirm' => __('Da li ste sigurni da zelite da izbrisete stavku u meniju '.$menuItem['MenuItem']['name'].'?', $menuItem['MenuItem']['id']), 'escape'=>false)); ?>
                </li>
            </ul>			
		</td>
	</tr>
<?php endforeach; ?>
	</tbody>
	</table>
    <?php endif; ?>
</div>

<!-- Bottom paginator -->
<?php echo $this->element('paginator'); ?>
<div class="clear"></div>

<div class="submit_loader">
    <?php echo $this->Html->image('submit_loader.gif', array('alt' => 'Loader')); ?>
    <h2>Molimo sačekajte...</h2>
</div> 

<!-- Custom javascript -->
<script type="text/javascript">
    $(document).ready(function(){
        $(".submit_loader").hide();
    });
</script>

<?php echo $this->Js->writeBuffer(); ?>
