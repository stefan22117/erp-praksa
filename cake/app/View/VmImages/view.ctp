<ul class="breadcrumbs">
    <li><?php echo $this->Html->link(__('Početna'), array('controller' => 'pages', 'action' => 'display')); ?></li>
    <li><?= $this->Html->link(__('Slike'), array('controller' => 'vmImages', 'action' => 'index')); ?></li>
    <li class="last"><a href="" onclick="return false"><?php echo __('Detalji'); ?></a></li>
</ul>


<div class="name_of_page">
    <h3><i class="icon-picture" style="color: aqua;"></i> </i> <i class="icon-search"></i> <?php echo __('Slika: ' . $vm_image['VmImage']['title']); ?></h3>
</div>

<div style="float:right; margin:20px 24px 0 0;">
    <ul class="button-bar">
        <li class="first">
            <?php
            echo $this->Html->link(
                '<i class="icon-eye-open" style="color:blue;"></i><i class="icon-truck" style="color:black;"></i> ' . __('Vozilo'),
                array(
                    'controller' => 'vmVehicles',
                    'action' => 'view',
                    $vm_image['VmVehicle']['id']
                ),
                array(
                    'escape' => false,
                    'style' => ' font-size: 12px;'
                )
            );
            ?>
        </li>
        <li>
            <?php
            echo $this->Form->postLink(
                '<i class="icon-download-alt" style="color:brown;"></i> ' . __('Preuzimanje'),
                array(
                    'action' => 'download',
                    $vm_image['VmImage']['id']
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
            echo $this->Form->postLink(
                '<i class="icon-trash" style="color:red;"></i> ' . __('Brisanje'),
                array(
                    'action' => 'delete',
                    $vm_image['VmImage']['id']
                ),
                array(
                    'escape' => false,
                    'confirm' =>  __('Da li ste sigurni da želite da izbrišete sliku vozila?'),
                    'style' => ' font-size: 12px;'
                )
            );
            ?>
        </li>
    </ul>
</div>





<?php
echo $this->Html->image($vm_image['VmImage']['path'], array('style' => "width:99.80%;height:120vh;"));
?>