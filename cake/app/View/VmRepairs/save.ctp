<ul class="breadcrumbs">
    <li><?php echo $this->Html->link(__('Početna'), array('controller' => 'pages', 'action' => 'display')); ?></li>
    <li><?php echo $this->Html->link(__('Popravke'), array('action' => 'index')); ?></li>
    <li class="last"><a href="" onclick="return false"><?php echo __('Dodavanje'); ?></a></li>
</ul>

<div class="name_add_search">
    <div class="name_of_page">
        <?php if ($action == 'add') : ?>
            <h3>
                <i class="icon-cogs" style="color:black;"></i>
                <i class="icon-save" style="color:blue;"></i>
                <?php echo __('Novi unos popravke'); ?>
            </h3>
        <?php endif; ?>
        <?php if ($action == 'edit') : ?>
            <h3>
                <i class="icon-cogs" style="color:black;"></i>
                <i class="icon-edit" style="color:orange;"></i>
                <?php echo __('Izmena postojeće popravke'); ?>
            </h3>
        <?php endif; ?>
    </div>

    <?php if ($action == 'edit') : ?>


        <div style="float:right; margin:20px 24px 0 0;">
            <ul class="button-bar">
                <li class="first">
                    <?php
                    echo $this->Html->link(
                        '<i class="icon-eye-open" style="color:blue;"></i> ' . __('Detalji'),
                        array(
                            'action' => 'view',
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

    <?php endif; ?>
</div>


<div class="content_data">
    <div class="formular">
        <?php echo $this->Form->create('VmRepair', array('novalidate' => true)); ?>

        <?php if ($action == 'add') : ?>
            <div class="col_9">
                <?php echo $this->Form->label('VmRepair.vm_vehicle_id', __('Vozilo')); ?>
                <?php echo $this->Form->input('VmRepair.vm_vehicle_id', array('label' => false, 'options' => $vm_vehicles, 'class' => 'col_12', 'style' => 'margin: 0; width: 100%;', 'empty' => __('Izaberite vozilo'))); ?>
            </div>

            <div class="col_9">
                <?php echo $this->Form->label('VmRepair.vm_damage_id', __('Šteta')); ?>
                <?php echo $this->Form->input('VmRepair.vm_damage_id', array('label' => false, 'options' => $vm_damages, 'class' => 'col_12', 'style' => 'margin: 0; width: 100%;', 'empty' => __('Izaberite štetu'))); ?>
            </div>
        <?php else : ?>
            <?php echo $this->Form->hidden('VmRepair.vm_damage_id', array('label' => false, 'class' => 'col_9', 'style' => 'margin: 0; width: 100%;', 'value' => $vm_repair['VmRepair']['vm_damage_id'])); ?>

        <?php endif; ?>




        <div class="col_9">
            <?php echo $this->Form->label('VmRepair.amount', __('Cena popravke')); ?>
            <?php echo $this->Form->input('VmRepair.amount', array('type' => 'number', 'label' => false, 'style' => 'margin: 0; width: 100%;', 'required' => false, 'placeholder' => __('Unesite cenu popravke'))); ?>
        </div>



        <div class="col_9" style="display: flex;justify-content: space-between">


            <?php echo $this->Form->label('spent_time', __('Potrošeno vreme')); ?>
            <?php


            $days = range(0, 30);
            $days[0] = __('dani');
            echo $this->Form->input('spent_time', array(
                'type' => 'select', 'options' => $days,
                'name' => 'data[VmRepair][spent_time][day]', 'id' => 'VmRepairSpentTimeDay', 'error' => false, 'label' => false,
                'selected' => isset($this->request->data['VmRepair']['spent_time']['day']) ? $this->request->data['VmRepair']['spent_time']['day'] : 0
            ));


            $hours = range(0, 23);
            $hours[0] = __('sati');
            echo $this->Form->input('spent_time', array(
                'type' => 'select', 'options' => $hours,
                'name' => 'data[VmRepair][spent_time][hour]', 'id' => 'VmRepairSpentTimeHour', 'error' => false, 'label' => false,
                'selected' => isset($this->request->data['VmRepair']['spent_time']['hour']) ? $this->request->data['VmRepair']['spent_time']['hour'] : 0
            ));




            $minutes = range(0, 59);
            $minutes[0] = __('minuti');
            echo $this->Form->input('spent_time', array(
                'type' => 'select', 'options' => $minutes,
                'name' => 'data[VmRepair][spent_time][min]', 'id' => 'VmRepairSpentTimeMinute', 'error' => false, 'label' => false,
                'selected' => isset($this->request->data['VmRepair']['spent_time']['min']) ? $this->request->data['VmRepair']['spent_time']['min'] : 0
            ));
            ?>

        </div>

        <div class="col_9">
            <?php echo $this->Form->label('VmCrossedKm.total_kilometers', __('Pređeni kilometri')); ?>
            <?php echo $this->Form->input('VmCrossedKm.total_kilometers', array('type' => 'number', 'label' => false, 'style' => 'margin: 0; width: 100%;', 'required' => false, 'placeholder' => __('Unesite pređene kilometre'))); ?>
        </div>



        <div class="col_9">
            <?php echo $this->Form->label('VmRepair.description', __('Opis popravke')); ?>
            <?php echo $this->Form->input('VmRepair.description', array('type' => 'text', 'label' => false, 'style' => 'margin: 0; width: 100%;', 'required' => false, 'placeholder' => __('Unesite opis popravke'))); ?>
        </div>



        <div class="col_9">
            <?php echo $this->Form->label('VmCrossedKm.report_datetime', __('Datum popravke')); ?>
            <?php if ($action == 'add') : ?>
                <?php echo $this->Form->date('VmCrossedKm.report_datetime', array('label' => false, 'class' => 'col_9', 'style' => 'margin: 0; width: 100%;', 'value' => date('Y-m-d'))); ?>
            <?php else : ?>
                <?php echo $this->Form->date('VmCrossedKm.report_datetime', array('label' => false, 'class' => 'col_9', 'style' => 'margin: 0; width: 100%;', 'value' => substr($vm_repair['VmCrossedKm']['report_datetime'], 0, 10))); ?>
            <?php endif; ?>
            <?php
            if ($this->Form->isFieldError('VmCrossedKm.report_datetime')) {
                echo $this->Form->error('VmCrossedKm.report_datetime');
            }
            ?>

        </div>

        <div class="col_9">
            <?php echo $this->Form->label('VmCrossedKm.hr_worker_id', __('Radnik')); ?>
            <?php echo $this->Form->input('VmCrossedKm.hr_worker_id', array('label' => false, 'options' => $hr_workers, 'class' => 'col_12', 'style' => 'margin: 0; width: 100%;', 'empty' => __('Izaberite radnika'))); ?>
        </div>

        <div class="col_9">
            <?php echo $this->Form->label('VmRepair.vm_company_id', __('Firma')); ?>
            <?php echo $this->Form->input('VmRepair.vm_company_id', array('label' => false, 'options' => $vm_companies, 'class' => 'col_12', 'style' => 'margin: 0; width: 100%;', 'empty' => __('Izaberite firmu'))); ?>
        </div>

        <div class="content_text_input">
            <div class="buttons_box">
                <div class="button_box">
                    <?php
                    echo $this->Form->submit(
                        __('Snimi'),
                        array(
                            'div' => false,
                            'class' => "button blue",
                            'style' => "margin:20px 0 20px 0;"
                        )
                    );
                    ?>
                </div>
                <div class="button_box">
                    <?php echo $this->Html->link(__('Nazad'), 'javascript:void(0)', array('id' => 'backId', 'class' => 'button', 'style' => 'margin:20px 0 20px 0;')); ?>
                    <?php echo $this->Form->end(); ?>
                </div>
            </div>
        </div>

    </div>
</div>

<script>
    $('#backId').click(function(e) {
        location.href = document.referrer
    });

    try{
        $('#VmRepairVmVehicleId').select2();
        $('#VmRepairVmDamageId').select2();
    }
    catch(e){}

    $('#VmCrossedKmHrWorkerId').select2({});
    $('#VmRepairVmCompanyId').select2({});

    $('#backId').click(function(e) {
        location.href = document.referrer
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



    $('input[type="date"]').datepicker({
        changeYear: true,
        changeMonth: true
    });



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
</script>