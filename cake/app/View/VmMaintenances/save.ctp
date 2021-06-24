<ul class="breadcrumbs">
    <li><?php echo $this->Html->link(__('Početna'), array('controller' => 'pages', 'action' => 'display')); ?></li>
    <li><?php echo $this->Html->link(__('Održavanja'), array('action' => 'index')); ?></li>
    <li class="last"><a href="" onclick="return false"><?php echo __('Dodavanje'); ?></a></li>
</ul>

<div class="name_add_search">
    <div class="name_of_page">
        <?php if ($action == 'add') : ?>
            <h3>
                <i class="icon-tint" style="color:orange;"></i>
                <i class="icon-save" style="color:blue;"></i>
                <?php echo __('Novi unos održavanja'); ?>
            </h3>
        <?php endif; ?>
        <?php if ($action == 'edit') : ?>
            <h3>
                <i class="icon-tint" style="color:orange;"></i>
                <i class="icon-edit" style="color:orange;"></i>
                <?php echo __('Izmena postojećeg održavanja'); ?>
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
                            $vm_maintenance['VmMaintenance']['id']
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
                            $vm_maintenance['VmMaintenance']['id']
                        ),
                        array(
                            'escape' => false,
                            'confirm' =>  __('Da li ste sigurni da želite da izbrišete održavanje?'),
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
        <?php echo $this->Form->create('VmMaintenance', array('novalidate' => 'true')); ?>

        <?php if ($action == 'add') : ?>

            <div class="col_9">
                <?php echo $this->Form->label('VmMaintenance.vm_vehicle_id', __('Vozilo')); ?>
                <?php echo $this->Form->input('VmMaintenance.vm_vehicle_id', array('label' => false, 'options' => $vm_vehicles, 'class' => 'col_12', 'style' => 'margin: 0; width: 100%;', 'empty' => __('Izaberite vozilo'))); ?>
            </div>
        <?php else : ?>
            <?php echo $this->Form->hidden('vm_vehicle_id', array('value' => $vm_maintenance['VmMaintenance']['vm_vehicle_id'])); ?>
        <?php endif; ?>


        <div class="col_9">
            <?php echo $this->Form->label('VmMaintenance.amount', __('Ukupna cena održavanja')); ?>
            <?php echo $this->Form->input('VmMaintenance.amount', array('type' => 'number', 'label' => false, 'style' => 'margin: 0; width: 100%;', 'required' => false, 'placeholder' => __('Unesite cenu održavanja'))); ?>
        </div>

        <div class="col_9" style="display: flex;justify-content: space-between">


            <?php echo $this->Form->label('spent_time', __('Potrošeno vreme')); ?>
            <?php


            $days = range(0, 30);
            $days[0] = __('dani');
            echo $this->Form->input('spent_time', array(
                'type' => 'select', 'options' => $days,
                'name' => 'data[VmMaintenance][spent_time][day]', 'id' => 'VmMaintenanceSpentTimeDay', 'error' => false, 'label' => false,
                'selected' => isset($this->request->data['VmMaintenance']['spent_time']['day']) ? $this->request->data['VmMaintenance']['spent_time']['day'] : 0
            ));


            $hours = range(0, 23);
            $hours[0] = __('sati');
            echo $this->Form->input('spent_time', array(
                'type' => 'select', 'options' => $hours,
                'name' => 'data[VmMaintenance][spent_time][hour]', 'id' => 'VmMaintenanceSpentTimeHour', 'error' => false, 'label' => false,
                'selected' => isset($this->request->data['VmMaintenance']['spent_time']['hour']) ? $this->request->data['VmMaintenance']['spent_time']['hour'] : 0
            ));




            $minutes = range(0, 59);
            $minutes[0] = __('minuti');
            echo $this->Form->input('spent_time', array(
                'type' => 'select', 'options' => $minutes,
                'name' => 'data[VmMaintenance][spent_time][min]', 'id' => 'VmMaintenanceSpentTimeMinute', 'error' => false, 'label' => false,
                'selected' => isset($this->request->data['VmMaintenance']['spent_time']['min']) ? $this->request->data['VmMaintenance']['spent_time']['min'] : 0
            ));
            ?>

        </div>

        <?php if ($this->Form->isFieldError('spent_time')) : ?>
            <div class="col_9">
                <?php echo $this->Form->error('spent_time'); ?>
            </div>
        <?php endif; ?>


        <div class="col_9">
            <?php echo $this->Form->label('VmMaintenance.description', __('Opis održavanja')); ?>
            <?php echo $this->Form->input('VmMaintenance.description', array('type' => 'text', 'label' => false, 'style' => 'margin: 0; width: 100%;', 'required' => false, 'placeholder' => __('Unesite opis održavanja'))); ?>
        </div>
        <div class="col_9">
            <?php echo $this->Form->label('VmCrossedKm.total_kilometers', __('Trenutna kilometraža')); ?>
            <?php echo $this->Form->input('VmCrossedKm.total_kilometers', array('type' => 'number', 'label' => false, 'style' => 'margin: 0; width: 100%;', 'required' => false, 'placeholder' => __('Unesite pređenu kilometražu'))); ?>
        </div>


        <div class="col_9">
            <?php echo $this->Form->label('VmCrossedKm.report_datetime', __('Datum održavanja')); ?>
            <?php if ($action == 'add') : ?>
                <?php echo $this->Form->date('VmCrossedKm.report_datetime', array('label' => false, 'class' => 'col_9', 'style' => 'margin: 0; width: 100%;', 'value' => date('Y-m-d'))); ?>
            <?php else : ?>
                <?php echo $this->Form->date('VmCrossedKm.report_datetime', array('label' => false, 'class' => 'col_9', 'style' => 'margin: 0; width: 100%;', 'value' => substr($vm_maintenance['VmCrossedKm']['report_datetime'], 0, 10))); ?>
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
            <?php echo $this->Form->label('VmMaintenance.vm_company_id', __('Firma')); ?>
            <?php echo $this->Form->input('VmMaintenance.vm_company_id', array('label' => false, 'options' => $vm_companies, 'class' => 'col_12', 'style' => 'margin: 0; width: 100%;', 'empty' => __('Izaberite firmu'))); ?>
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
                    <?php echo $this->Html->link(__('Poništi'), 'javascript:void(0)', array('id' => 'backId', 'class' => 'button', 'style' => 'margin:20px 0 20px 0;')); ?>
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
        $('#VmMaintenanceVmVehicleId').select2();
    }
    catch(e){}
    $('#VmCrossedKmHrWorkerId').select2();
    $('#VmMaintenanceVmCompanyId').select2();
    $('#VmMaintenanceSpentTimeDay').select2({
        minimumResultsForSearch: -1
    });
    $('#VmMaintenanceSpentTimeHour').select2({
        minimumResultsForSearch: -1
    });
    $('#VmMaintenanceSpentTimeMinute').select2({
        minimumResultsForSearch: -1
    });

    $('input[type="date"]').datepicker({
        changeYear: true,
        changeMonth: true
    });
</script>