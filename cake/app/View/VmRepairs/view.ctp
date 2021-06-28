<ul class="breadcrumbs">
    <li><?php echo $this->Html->link(__('Početna'), array('controller' => 'pages', 'action' => 'display')); ?></li>
    <li><?php echo $this->Html->link(__('Popravke'), array('action' => 'index')); ?></li>
    <li class="last"><a href="" onclick="return false"><?php echo __('Detalji'); ?></a></li>
</ul>

<div class="name_of_page">
    <h3><i class="icon-cogs" style="color: black;"></i> <i class="icon-search"></i> <?php echo __('Detalji popravke'); ?></h3>
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
                    $vm_repair['VmDamage']['vm_vehicle_id']
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
                '<i class="icon-eye-open" style="color:blue;"></i> <i class="icon-fire" style="color:red;"></i>' . __('Šteta'),
                array(
                    'controller' => 'vmDamages',
                    'action' => 'view',
                    $vm_repair['VmDamage']['id']
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
                '<i class="icon-eye-open" style="color:blue;"></i> <i class="icon-building" style="color:green;"></i>' . __('Firma'),
                array(
                    'controller' => 'vmCompanies',
                    'action' => 'view',
                    $vm_repair['VmCompany']['id']
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
                    $vm_repair['VmRepair']['id']
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
                    $vm_repair['VmRepair']['id']
                ),
                array(
                    'escape' => false,
                    'confirm' =>  __('Da li ste sigurni da želite da izbrišete popravku?'),
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
            <th style="text-align:left"><?php echo __('Odgovoran za štetu'); ?></th>
            <td style="width:85%;"><?php echo $vm_repair['VmDamage']['responsible']; ?></td>
        </tr>
        <tr>
            <th style="text-align:left"><?php echo __('Opis štete'); ?></th>
            <td><?php echo $vm_repair['VmDamage']['description']; ?></td>
        </tr>
        
        <tr>
            <th style="text-align:left"><?php echo __('Cena popravke'); ?></th>
            <td>
                <?php echo $vm_repair['VmRepair']['amount']; ?>
            </td>
        </tr>
        <tr>
            <th style="text-align:left"><?php echo __('Potrošeno vreme'); ?></th>
            <td>
                <?php echo $spent_time; ?>
            </td>
        </tr>

        <tr>
            <th style="text-align:left"><?php echo __('Opis popravke'); ?></th>
            <td><?php echo $vm_repair['VmRepair']['description']; ?></td>
        </tr>
        <tr>
            <th style="text-align:left"><?php echo __('Vreme popravke'); ?></th>
            <td><?php echo $vm_repair['VmCrossedKm']['report_datetime']; ?></td>
        </tr>
        <tr>
            <th style="text-align:left"><?php echo __('Radnik'); ?></th>
            <td><?php echo !empty($vm_repair['VmCrossedKm']['HrWorker']['first_name']) ?
                    $vm_repair['VmCrossedKm']['HrWorker']['first_name'] : null; ?></td>
        </tr>
        <tr>
            <th style="text-align:left"><?php echo __('Vozilo'); ?></th>
            <td><?php echo !empty($vm_repair['VmDamage']['VmVehicle']['brand_and_model']) ?
                    $vm_repair['VmDamage']['VmVehicle']['brand_and_model'] : null; ?></td>
        </tr>
        <tr>
            <th style="text-align:left"><?php echo __('Firma'); ?></th>
            <td><?php echo $vm_repair['VmCompany']['name']; ?></td>
        </tr>

    </table>
</div>