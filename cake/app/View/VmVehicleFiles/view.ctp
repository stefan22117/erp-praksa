<ul class="breadcrumbs">
    <li><?php echo $this->Html->link(__('Početna'), array('controller' => 'pages', 'action' => 'display')); ?></li>
    <li><?php echo $this->Html->link(__('Fajlovi vozila'), array('action' => 'index')); ?></li>
    <li class="last"><a href="" onclick="return false"><?php echo __('Detalji'); ?></a></li>
</ul>


<div class="name_of_page">
    <h3><i class="icon-file" style="color: darkblue"></i> <?php echo __('Fajl vozila: ' . $vm_vehicle_file['VmVehicleFile']['title']); ?></h3>
</div>

<div style="float:right; margin:20px 24px 0 0;">
    <ul class="button-bar">
        <li class="first">
            <?php
            echo $this->Html->link(
                '<i class="icon-eye-open" style="color:blue;"></i><i class="icon-truck"></i> ' . __('Vozilo'),
                array(
                    'controller' => 'vmVehicles',
                    'action' => 'view',
                    $vm_vehicle_file['VmVehicle']['id']
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
                    $vm_vehicle_file['VmVehicleFile']['id']
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
                    $vm_vehicle_file['VmVehicleFile']['id']
                ),
                array(
                    'escape' => false,
                    'confirm' =>  __('Da li ste sigurni da želite da izbrišete fajl vozila?'),
                    'style' => ' font-size: 12px;'
                )
            );
            ?>
        </li>
    </ul>
</div>








<embed src="/img/<?= $vm_vehicle_file['VmVehicleFile']['path']; ?>" 
type="application/pdf" style="width:99.80%;height:120vh;">