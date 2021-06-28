<ul class="breadcrumbs">
    <li><?php echo $this->Html->link(__('Početna'), array('controller' => 'pages', 'action' => 'display')); ?></li>
    <li><?php echo $this->Html->link(__('Vozila'), array('action' => 'index')); ?></li>
    <li class="last"><a href="" onclick="return false"><?php echo __('Detalji'); ?></a></li>
</ul>
<div class="name_of_page">
    <h3><i class="icon-truck"></i> <i class="icon-search"></i> <?php echo __('Detalji vozila: ' . $vm_vehicle['VmVehicle']['brand_and_model']); ?></h3>

</div>

<div style="float:right; margin:20px 24px 0 0;">
    <ul class="button-bar">
    <li class="first">
            <?php
            echo $this->Html->link(
                '<i class="icon-picture" style="color:aqua;"></i> <i class="icon-truck" style="color:black;"></i> ' . __('Galerija'),
                array(
                    'controller'=>'VmImages',
                    'action' => 'galery',
                    $vm_vehicle['VmVehicle']['id']
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
                    $vm_vehicle['VmVehicle']['id']
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
                    $vm_vehicle['VmVehicle']['id']
                ),
                array(
                    'escape' => false,
                    'confirm' =>  __('Da li ste sigurni da želite da izbrišete vozilo ' . $vm_vehicle['VmVehicle']['brand_and_model'] . '?'),
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
            <th style="text-align:left"><?php echo __('Marka i model'); ?></th>
            <td style="width:85%;"><?php echo $vm_vehicle['VmVehicle']['brand_and_model']; ?></td>
        </tr>
        <tr>
            <th style="text-align:left"><?php echo __('Registarski broj'); ?></th>
            <td><?php echo $vm_vehicle['VmVehicle']['reg_number']; ?></td>

        </tr>
        <tr>

            <th style="text-align:left"><?php echo __('U upotrebi') ?></th>
            <?php if ($vm_vehicle['VmVehicle']['in_use'] == 1) : ?>
                <td style="background-color: #feffee;">
                    <?php echo __('U upotrebi'); ?>
                </td>
            <?php else : ?>
                <td style="background-color: #eeffee;">
                    <?php echo __('Slobodno'); ?>
                </td>

            <?php endif; ?>
        </tr>

        <tr>

            <th style="text-align:left"><?php echo __('Aktivno od'); ?></th>
            <td><?php echo $vm_vehicle['VmVehicle']['active_from']; ?></td>
        </tr>

        <tr>

            <th style="text-align:left"><?php echo __('Aktivno do'); ?></th>
            <td><?php echo $vm_vehicle['VmVehicle']['active_to']; ?></td>
        </tr>

        <tr>

            <th style="text-align:left"><?php echo __('Konjskih snaga'); ?></th>
            <td><?php echo $vm_vehicle['VmVehicle']['horse_power']; ?></td>
        </tr>

        <tr>

            <th style="text-align:left"><?php echo __('Kubikaža motora'); ?></th>
            <td><?php echo $vm_vehicle['VmVehicle']['engine_capacity_cm3']; ?></td>
        </tr>

        <tr>

            <th style="text-align:left"><?php echo __('Godina proizvodnje'); ?></th>
            <td>
                <?php echo $vm_vehicle['VmVehicle']['year_of_production']; ?></td>
        </tr>

        <tr>

            <th style="text-align:left"><?php echo __('Boja'); ?></th>
            <td><?php echo $vm_vehicle['VmVehicle']['color']; ?></td>
        </tr>

        <tr>

            <th style="text-align:left"><?php echo __('Broj sedišta'); ?></th>
            <td><?php echo $vm_vehicle['VmVehicle']['number_of_seats']; ?></td>
        </tr>

        <tr>

            <th style="text-align:left"><?php echo __('Broj šasije'); ?></th>
            <td><?php echo $vm_vehicle['VmVehicle']['chassis_number']; ?></td>
        </tr>

        <tr>

            <th style="text-align:left"><?php echo __('Broj motora'); ?></th>
            <td><?php echo $vm_vehicle['VmVehicle']['engine_number']; ?></td>
        </tr>

        <tr>

            <th style="text-align:left"><?php echo __('Datum kupovine'); ?></th>
            <td><?php echo $vm_vehicle['VmVehicle']['date_of_purchase']; ?></td>
        </tr>

        <tr>

            <th style="text-align:left"><?php echo __('Cena'); ?></th>
            <td><?php echo $vm_vehicle['VmVehicle']['price']; ?></td>
        </tr>


        <tr>
            <th style="text-align:left"><?php echo __('Pređeno kilometara') ?></th>
            <td><?= $vm_max_crossed_km ?></td>
        </tr>

        <tr>
            <th style="text-align:left"><?php echo __('Registrovano do') ?></th>
            <td><?php echo $vm_max_registration_date; ?></td>
        </tr>
    </table>

</div>


<!-- TABS_LIST -->
<ul class="tabs left">
    <li><a href="#tab_vm_registrations"><i class="icon-tag" style="color :darkgreen"></i><?php echo __('Registracije'); ?></a></li>
    <li><a href="#tab_vm_vehicle_files"><i class="icon-file" style="color :darkblue"></i><?php echo __('Fajlovi'); ?></a></li>
    <li><a href="#tab_vm_fuels"><i class="icon-dashboard" style="color :yellow"></i><?php echo __('Gorivo'); ?></a></li>
    <li><a href="#tab_vm_damages"><i class="icon-fire" style="color :red"></i><?php echo __('Štete'); ?></a></li>
    <li><a href="#tab_vm_repairs"><i class="icon-cogs" style="color :black"></i><?php echo __('Popravke'); ?></a></li>
    <li><a href="#tab_vm_maintenances"><i class="icon-tint" style="color :orange"></i> <?php echo __('Održavanja'); ?></a></li>
</ul>

<!-- TAB_REGISTRATIONS -->
<?php include('includes/registrations.ctp'); ?>
<!-- TAB_FILES -->
<?php include('includes/files.ctp'); ?>

<!-- TAB_FUELS -->
<?php include('includes/fuels.ctp'); ?>

<!-- TAB_DAMAGES -->
<?php include('includes/damages.ctp'); ?>

<!-- TAB_REPAIRS -->
<?php include('includes/repairs.ctp'); ?>

<!-- TAB_MAINTENANCES -->
<?php include('includes/maintenances.ctp'); ?>







<script>
    $('#VmRepairSpentTimeDay').select2({
        minimumResultsForSearch: -1
    });
    $('#VmRepairSpentTimeHour').select2({
        minimumResultsForSearch: -1
    });
    $('#VmRepairSpentTimeMinute').select2({
        minimumResultsForSearch: -1
    });

    $('#VmRegistrationSpentTimeDay').select2({
        minimumResultsForSearch: -1
    });
    $('#VmRegistrationSpentTimeHour').select2({
        minimumResultsForSearch: -1
    });
    $('#VmRegistrationSpentTimeMinute').select2({
        minimumResultsForSearch: -1
    });

    $('#VmMaintenanceSpentTimeDay').select2({
        minimumResultsForSearch: -1
    });
    $('#VmMaintenanceSpentTimeHour').select2({
        minimumResultsForSearch: -1
    });
    $('#VmMaintenanceSpentTimeMinute').select2({
        minimumResultsForSearch: -1
    });

    $('#addNewRegistration').click(function(e) {
        $('#addNewRegistrationForm').show();
        $(this).hide();

    })

    $('#addNewRegistrationFormClose').click(function() {
        $('#addNewRegistrationForm').hide();
        $('#addNewRegistration').show();
    })


    $('#addNewFile').click(function(e) {
        $('#addNewFileForm').show();
        $(this).hide();

    })

    $('#addNewFileFormClose').click(function() {
        $('#addNewFileForm').hide();
        $('#addNewFile').show();
    })


    $('#addNewFuel').click(function(e) {
        $('#addNewFuelForm').show();
        $(this).hide();

    })

    $('#addNewFuelClose').click(function() {
        $('#addNewFuelForm').hide();
        $('#addNewFuel').show();
    })



    $('#VmRegistrationHrWorkerId').select2({});
    $('#VmRegistrationVmCompanyId').select2({});


    $('#VmCrossedKmHrWorkerId').select2({});
    $('#VmCrossedKmHrWorkerIdVmRepair').select2({});

    $('#VmCrossedKmHrWorkerIdVmMaintenance').select2({});
    $('#VmCrossedKmVmCompanyIdVmMaintenance').select2({});

    $('#VmRepairVmDamageId').select2({});
    $('#VmRepairVmCompanyId').select2({});


    $('#addNewFile').click(function(e) {
        $('#addNewFileForm').show();
        $(this).hide();

    })



    $('#addNewFileClose').click(function() {
        $('#addNewFileForm').hide();
        $('#addNewFile').show();
    })


    $('#addNewFuel').click(function(e) {
        $('#addNewFuelForm').show();
        $(this).hide();

    })

    $('#addNewFuelClose').click(function() {
        $('#addNewFuelForm').hide();
        $('#addNewFuel').show();
    })


    $('#addNewDamage').click(function(e) {
        $('#addNewDamageForm').show();
        $(this).hide();

    })

    $('#addNewDamageClose').click(function() {
        $('#addNewDamageForm').hide();
        $('#addNewDamage').show();
    })


    $('#addNewRepair').click(function(e) {
        $('#addNewRepairForm').show();
        $(this).hide();

    })

    $('#addNewRepairFormClose').click(function() {
        $('#addNewRepairForm').hide();
        $('#addNewRepair').show();
    })


    $('#addNewMaintenance').click(function(e) {
        $('#addNewMaintenanceForm').show();
        $(this).hide();

    })

    $('#addNewMaintenanceClose').click(function() {
        $('#addNewMaintenanceForm').hide();
        $('#addNewMaintenance').show();
    })

    $('input[type="date"]').datepicker({
        changeYear: true,
        changeMonth: true
    });
</script>