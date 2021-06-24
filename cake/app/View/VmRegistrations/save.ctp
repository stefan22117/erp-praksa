<ul class="breadcrumbs">
    <li><?php echo $this->Html->link(__('Početna'), array('controller' => 'pages', 'action' => 'display')); ?></li>
    <li><?php echo $this->Html->link(__('Registracije'), array('action' => 'index')); ?></li>
    <li class="last"><a href="" onclick="return false"><?php echo __('Dodavanje'); ?></a></li>
</ul>

<div class="name_add_search">
    <div class="name_of_page">
        <?php if ($action == 'add') : ?>
            <h3>
                <i class="icon-tag" style="color:green;"></i>
                <i class="icon-save" style="color:blue;"></i>
                <?php echo __('Novi unos registracije'); ?>
            </h3>
        <?php endif; ?>
        <?php if ($action == 'edit') : ?>
            <h3>
                <i class="icon-tag" style="color:green;"></i>
                <i class="icon-edit" style="color:orange;"></i>
                <?php echo __('Izmena postojeće registracije'); ?>
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
                            $vm_registration['VmRegistration']['id']
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
                            $vm_registration['VmRegistration']['id']
                        ),
                        array(
                            'escape' => false,
                            'confirm' =>  __('Da li ste sigurni da želite da izbrišete registraciju?'),
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
        <?php echo $this->Form->create('VmRegistration', array('novalidate' => 'true')); ?>

        <?php if ($action == 'add') : ?>

            <div class="col_9">
                <?php echo $this->Form->label('VmRegistration.vm_vehicle_id', __('Vozilo')); ?>
                <?php echo $this->Form->input('VmRegistration.vm_vehicle_id', array('label' => false, 'options' => $vm_vehicles, 'class' => 'col_12', 'style' => 'margin: 0; width: 100%;', 'empty' => __('Izaberite vozilo'))); ?>
            </div>

        <?php endif; ?>


        <div class="col_9">
            <?php echo $this->Form->label('registration_date', __('Datum registracije')); ?>
            <?php echo $this->Form->date('registration_date', array('label' => false, 'class' => 'col_9', 'style' => 'margin: 0; width: 100%;', 'required' => false, 'value' => date('Y-m-d'), 'placeholder' => __('Unesite kad je vozilo registrovano'))); ?>
            <?php if ($this->Form->isFieldError('registration_date')) {
                echo $this->Form->error('registration_date');
            }
            ?>

        </div>

        <div class="col_9" style="display: flex;justify-content: space-between">


            <?php echo $this->Form->label('spent_time', __('Potrošeno vreme')); ?>
            <?php


            $days = range(0, 30);
            $days[0] = __('dani');
            echo $this->Form->input('spent_time', array(
                'type' => 'select', 'options' => $days,
                'name' => 'data[VmRegistration][spent_time][day]', 'id' => 'VmRegistrationSpentTimeDay', 'error' => false, 'label' => false,
                'selected' => isset($this->request->data['VmRegistration']['spent_time']['day']) ? $this->request->data['VmRegistration']['spent_time']['day'] : 0
            ));


            $hours = range(0, 23);
            $hours[0] = __('sati');
            echo $this->Form->input('spent_time', array(
                'type' => 'select', 'options' => $hours,
                'name' => 'data[VmRegistration][spent_time][hour]', 'id' => 'VmRegistrationSpentTimeHour', 'error' => false, 'label' => false,
                'selected' => isset($this->request->data['VmRegistration']['spent_time']['hour']) ? $this->request->data['VmRegistration']['spent_time']['hour'] : 0
            ));




            $minutes = range(0, 59);
            $minutes[0] = __('minuti');
            echo $this->Form->input('spent_time', array(
                'type' => 'select', 'options' => $minutes,
                'name' => 'data[VmRegistration][spent_time][min]', 'id' => 'VmRegistrationSpentTimeMinute', 'error' => false, 'label' => false,
                'selected' => isset($this->request->data['VmRegistration']['spent_time']['min']) ? $this->request->data['VmRegistration']['spent_time']['min'] : 0
            ));
            ?>

        </div>

        <?php if ($this->Form->isFieldError('spent_time')) : ?>
            <div class="col_9">
                <?php echo $this->Form->error('spent_time'); ?>
            </div>
        <?php endif; ?>

        <div class="col_9">
            <?php echo $this->Form->label('hr_worker_id', __('Radnik')); ?>
            <?php echo $this->Form->input('hr_worker_id', array('label' => false, 'options' => $hr_workers, 'class' => 'col_12', 'style' => 'margin: 0; width: 100%;', 'empty' => __('Izaberite radnika'))); ?>
        </div>

        <div class="col_9">
            <?php echo $this->Form->label('expiration_date', __('Datum isteka registracije')); ?>
            <?php echo $this->Form->date('expiration_date', array('label' => false, 'class' => 'col_9', 'style' => 'margin: 0; width: 100%;', 'required' => false, 'value' => date("Y-m-d", strtotime('+1 year')), 'placeholder' => __('Unesite kad je vozilo registrovano'))); ?>
            <?php if ($this->Form->isFieldError('expiration_date')) {
                echo $this->Form->error('expiration_date');
            }
            ?>
        </div>

        <div class="col_9">
            <?php echo $this->Form->label('amount', __('Cena registracije')); ?>
            <?php echo $this->Form->input('amount', array('type' => 'number', 'label' => false, 'class' => 'col_9', 'style' => 'margin: 0; width: 100%;', 'required' => false, 'placeholder' => __('Unesite cenu registracije'))); ?>
        </div>



        <div class="col_9">
            <?php echo $this->Form->label('vm_company_id', __('Firma')); ?>
            <?php echo $this->Form->input('vm_company_id', array('label' => false, 'options' => $vm_companies, 'class' => 'col_12', 'style' => 'margin: 0; width: 100%;', 'empty' => __('Izaberite firmu'))); ?>
        </div>



        <div class="content_text_input">
            <div class="buttons_box">
                <div class="button_box">
                    <?php
                    echo $this->Form->submit(__('Snimi'), array(
                        'div' => false,
                        'class' => "button blue",
                        'style' => "margin:20px 0 20px 0;"
                    ));
                    ?>
                </div>
                <div class="button_box">
                    <?php echo $this->Html->link(
                        __('Nazad'),
                        'javascript:void(0)',
                        array(
                            'id' => 'backId', 'class' => 'button', 'style' => 'margin:20px 0 20px 0;'
                        )
                    ); ?>
                    <?php echo $this->Form->end(); ?>
                </div>
            </div>
        </div>


    </div>
</div>

<script>
    $('#backId').click(function(e) {
        e.preventDefault();
        location.href = document.referrer
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

    $('#VmRegistrationHrWorkerId').select2({});
    $('#VmRegistrationVmCompanyId').select2({});
    $('#VmRegistrationVmVehicleId').select2({});

    $('input[type="date"]').datepicker({
        changeYear: true,
        changeMonth: true
    });
</script>