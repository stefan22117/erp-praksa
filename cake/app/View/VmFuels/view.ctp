<ul class="breadcrumbs">
    <li><?php echo $this->Html->link(__('Početna'), array('controller' => 'pages', 'action' => 'display')); ?></li>
    <li><?php echo $this->Html->link(__('Goriva'), array('action' => 'index')); ?></li>
    <li class="last"><a href="" onclick="return false"><?php echo __('Detalji'); ?></a></li>
</ul>

<div class="name_of_page">
    <h3><i class="icon-dashboard" style="color: yellow;"></i> <i class="icon-search"></i> <?php echo __('Detalji goriva'); ?></h3>
</div>




<div style="float:right; margin:20px 24px 0 0;">
    <ul class="button-bar">

        <li class="first">
            <?php
            echo $this->Html->link(
                '<i class="icon-eye-open" style="color:blue;"></i> <i class="icon-truck" style="color:black;"></i>' . __('Vozilo'),
                array(
                    'controller' => 'vmVehicles',
                    'action' => 'view',
                    $vm_fuel['VmFuel']['vm_vehicle_id']
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
            echo $this->Html->link(
                '<i class="icon-edit" style="color:orange;"></i> ' . __('Izmena'),
                array(
                    'action' => 'save',
                    $vm_fuel['VmFuel']['id']
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
                    $vm_fuel['VmFuel']['id']
                ),
                array(
                    'escape' => false,
                    'confirm' =>  __('Da li ste sigurni da želite da izbrišete gorivo?'),
                    'style' => ' font-size: 12px;'
                )
            );
            ?>
        </li>
    </ul>
</div>

<div class="content_data">
    <table cellspacing="0" cellpadding="0" class="striped">
        <tr>
            <th style="text-align:left"><?php echo __('Gorivo u litrima'); ?></th>
            <td style="width:85%;">
                <?php echo $vm_fuel['VmFuel']['liters']; ?>
            </td>
        </tr>
        <tr>
            <th style="text-align:left"><?php echo __('	Ukupna cena goriva'); ?></th>
            <td><?php echo $vm_fuel['VmFuel']['amount']; ?></td>
        </tr>
        <tr>
            <th style="text-align:left"><?php echo __('Radnik'); ?></th>
            <td><?php echo !empty($vm_fuel['VmCrossedKm']['HrWorker']['first_name']) ?
                    $vm_fuel['VmCrossedKm']['HrWorker']['first_name'] : null; ?></td>
        </tr>
        <tr>
            <th style="text-align:left"><?php echo __('Datum sipanja goriva'); ?></th>
            <td><?php echo $vm_fuel['VmCrossedKm']['report_datetime']; ?></td>
        </tr>
        <tr>
            <th style="text-align:left"><?php echo __('Pređena kilometraža pri sipanju'); ?></th>
            <td><?php echo $vm_fuel['VmCrossedKm']['total_kilometers']; ?></td>
        </tr>
        <tr>
            <th style="text-align:left"><?php echo __('Pređena kilometraža sada'); ?></th>
            <td><?php echo $vm_max_crossed_km; ?></td>
        </tr>
        <tr>
            <th style="text-align:left"><?php echo __('Vozilo'); ?></th>
            <td><?php echo $vm_fuel['VmVehicle']['brand_and_model']; ?></td>
        </tr>

    </table>
</div>