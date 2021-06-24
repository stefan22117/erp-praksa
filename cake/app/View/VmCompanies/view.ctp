<ul class="breadcrumbs">
    <li><?php echo $this->Html->link(__('Početna'), array('controller' => 'pages', 'action' => 'display')); ?></li>
    <li><?php echo $this->Html->link(__('Firme'), array('action' => 'index')); ?></li>
    <li class="last"><a href="" onclick="return false"><?php echo __('Detalji'); ?></a></li>
</ul>
<div class="name_of_page">
    <h3><i class="icon-building" style="color: green;"></i> <i class="icon-search"></i> <?php echo __('Detalji firme: ' . $vm_company['VmCompany']['name']); ?></h3>

</div>

<div style="float:right; margin:20px 24px 0 0;">
    <ul class="button-bar">
        <li class="first">
            <?php
            echo $this->Html->link(
                '<i class="icon-edit" style="color:orange;"></i> ' . __('Izmena'),
                array(
                    'action' => 'save',
                    $vm_company['VmCompany']['id']
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
                    $vm_company['VmCompany']['id']
                ),
                array(
                    'escape' => false,
                    'confirm' =>  __('Da li ste sigurni da želite da izbrišete firmu ' . $vm_company['VmCompany']['name'] . '?'),
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
            <th style="text-align:left"><?php echo __('Naziv firme'); ?></th>
            <td style="width:85%;"><?php echo $vm_company['VmCompany']['name']; ?></td>
        </tr>
        <tr>
            <th style="text-align:left"><?php echo __('Adresa firme'); ?></th>
            <td style="width:85%;"><?php echo $vm_company['VmCompany']['address']; ?></td>
        </tr>
        <tr>
            <th style="text-align:left"><?php echo __('Sedište firme'); ?></th>
            <td style="width:85%;">
                <?php echo $vm_company['VmCompany']['zip_code'] . ', ' . $vm_company['VmCompany']['city']; ?>
            </td>
        </tr>
        <tr>
            <th style="text-align:left"><?php echo __('E-mail firme'); ?></th>
            <td style="width:85%;"><?php echo $vm_company['VmCompany']['email']; ?></td>
        </tr>
    </table>

</div>



<!-- TABS_LIST -->
<ul class="tabs left">
    <li><a href="#tab_vm_registrations"><i class="icon-tag" style="color :darkgreen"></i><?php echo __('Registracije'); ?></a></li>
    <li><a href="#tab_vm_repairs"><i class="icon-cogs" style="color :black"></i><?php echo __('Popravke'); ?></a></li>
    <li><a href="#tab_vm_maintenances"><i class="icon-tint" style="color :orange"></i><?php echo __('Održavanja'); ?></a></li>
    <li><a href="#tab_vm_external_workers"><i class="icon-group" style="color :blue"></i><?php echo __('Radnici'); ?></a></li>
</ul>


<!-- TAB_REGISTRATIONS -->
<?php include('includes/registrations.ctp'); ?>
<!-- TAB_FILES -->
<?php include('includes/repairs.ctp'); ?>

<!-- TAB_FUELS -->
<?php include('includes/maintenances.ctp'); ?>

<!-- TAB_FUELS -->
<?php include('includes/external_workers.ctp'); ?>


<script>
    $('#VmRegistrationSpentTimeDay').select2({
        minimumResultsForSearch: -1
    });
    $('#VmRegistrationSpentTimeHour').select2({
        minimumResultsForSearch: -1
    });
    $('#VmRegistrationSpentTimeMinute').select2({
        minimumResultsForSearch: -1
    });
    $('#VmRepairSpentTimeDay').select2({
        minimumResultsForSearch: -1
    });
    $('#VmRepairSpentTimeHour').select2({
        minimumResultsForSearch: -1
    });
    $('#VmRepairSpentTimeMinute').select2({
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

    $('#VmRegistrationHrWorkerId').select2({});
    $('#VmRegistrationVmVehicleId').select2({});

    $('#addNewRepair').click(function(e) {
        $('#addNewRepairForm').show();
        $(this).hide();

    })

    $('#addNewRepairFormClose').click(function() {
        $('#addNewRepairForm').hide();
        $('#addNewRepair').show();
    })

    $('#VmRepairVmVehicleId').select2({});
    $('#VmCrossedKmHrWorkerId').select2({});
    $('#VmRepairVmDamageId').select2({});



    $('#VmRepairVmVehicleId').change(function(e) {

        var vm_vehicle_id = $(this).val();

        $.ajax({
            url: '<?php echo $this->Html->url(array('controller' => 'vmRepairs', 'action' => 'save')); ?>',
            dataType: "json",
            type: "POST",
            evalScripts: true,
            data: {
                vm_vehicle_id: vm_vehicle_id
            },
            success: function(response) {
                console.log(response);
                $('#VmRepairVmDamageId').empty();
                Object.keys(response).sort().forEach(function(key, i) {
                    $('#VmRepairVmDamageId').append($("<option></option>").attr("value", key).text(response[key]));
                });
            }
        });
    })



    $('#addNewMaintenance').click(function(e) {
        $('#addNewMaintenanceForm').show();
        $(this).hide();

    })

    $('#addNewMaintenanceClose').click(function() {
        $('#addNewMaintenanceForm').hide();
        $('#addNewMaintenance').show();
    })

    $('#addNewExternalWorker').click(function(e) {
        $('#addNewExternalWorkerForm').show();
        $(this).hide();

    })

    $('#addNewExternalWorkerClose').click(function() {
        $('#addNewExternalWorkerForm').hide();
        $('#addNewExternalWorker').show();
    })

    $('#VmCrossedKmHrWorkerIdVmRepair').select2({}); 
    $('#VmMaintenanceVmVehicleId').select2({});
    $('#VmCrossedKmHrWorkerIdVmMaintenance').select2({}); 
</script>