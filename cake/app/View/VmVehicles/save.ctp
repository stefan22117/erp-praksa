<ul class="breadcrumbs">
    <li><?php echo $this->Html->link(__('Početna'), array('controller' => 'pages', 'action' => 'display')); ?></li>
    <li><?php echo $this->Html->link(__('Vozila'), array('controller' => 'vmVehicles', 'action' => 'index')); ?></li>
    <li class="last"><a href="" onclick="return false"><?php echo __('Dodavanje'); ?></a></li>
</ul>
<div id='alert'><?php echo $this->Session->flash(); ?></div>

<div class="name_add_search">
    <div class="name_of_page">

        <?php if ($action == 'add') : ?>
            <h3>
            <i class="icon-truck" style="color:black;"></i>
                <i class="icon-save"></i>
                <?php echo __('Dodavanje novog vozila'); ?>
            </h3>
        <?php endif; ?>
        <?php if ($action == 'edit') : ?>
            <h3>
            <i class="icon-truck" style="color:black;"></i>
                <i class="icon-edit"></i>
                <?php echo __('Izmena postojećeg vozila'); ?>
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
                            $this->request->data['VmVehicle']['id']
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
                            $this->request->data['VmVehicle']['id']
                        ),
                        array(
                            'escape' => false,
                            'confirm' =>  __('Da li ste sigurni da želite da izbrišete vozilo ' . $this->request->data['VmVehicle']['brand_and_model'] . '?'),
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

        <?php echo $this->Form->create('VmVehicle', array('novalidate' => true)); ?>
        <?php
        if ($action == 'edit')
            echo $this->Form->hidden('id');
        ?>
        <div class="col_9">
            <?php echo $this->Form->label('brand_and_model', __('Marka i model')); ?>
            <?php echo $this->Form->input('brand_and_model', array('type' => 'text', 'label' => false, 'class' => 'col_9', 'style' => 'margin: 0; width: 100%;', 'required' => false, 'placeholder' => __('Unesite marku i model'))); ?>
        </div>
        <div class="clear"></div>
        <div class="col_9">
            <?php echo $this->Form->label('reg_number', __('Registarski broj')); ?>
            <?php echo $this->Form->input('reg_number', array('type' => 'text', 'label' => false, 'class' => 'col_9', 'style' => 'margin: 0; width: 100%;', 'required' => false, 'placeholder' => __('Unesite registarski broj'))); ?>
        </div>
        <div class="clear"></div>
        <div class="col_9">
            <?php echo $this->Form->label('active_from', __('Aktivno od')); ?>

            <?php if ($action == 'add') : ?>
                <?php echo $this->Form->date('active_from', array('label' => false, 'class' => 'col_9', 'style' => 'margin: 0; width: 100%;', 'required' => false, 'value' => date('Y-m-d'), 'placeholder' => __('Unesite od kad je vozilo aktivno'))); ?>
            <?php else : ?>
                <?php echo $this->Form->date('active_from', array('label' => false, 'class' => 'col_9', 'style' => 'margin: 0; width: 100%;', 'required' => false, 'value' => $vm_vehicle['VmVehicle']['active_from'], 'placeholder' => __('Unesite od kad je vozilo aktivno'))); ?>
            <?php endif; ?>

            <?php if ($this->Form->isFieldError('active_from')) {
                echo $this->Form->error('active_from');
            }
            ?>
        </div>
        <div class="clear"></div>
        <div class="col_9">
            <?php echo $this->Form->label('active_to', __('Aktivno do')); ?>
            <?php if ($action == 'add') : ?>
                <?php echo $this->Form->date('active_to', array('label' => false, 'class' => 'col_9', 'style' => 'margin: 0; width: 100%;', 'required' => false, 'placeholder' => __('Unesite do kad je vozilo aktivno'))); ?>
            <?php else : ?>
                <?php echo $this->Form->date('active_to', array('label' => false, 'class' => 'col_9', 'style' => 'margin: 0; width: 100%;', 'required' => false, 'value' => $vm_vehicle['VmVehicle']['active_to'], 'placeholder' => __('Unesite do kad je vozilo aktivno'))); ?>

            <?php endif; ?>

            <?php if ($this->Form->isFieldError('active_to')) {
                echo $this->Form->error('active_to');
            }
            ?>


        </div>
        <div class="clear"></div>
        <div class="col_9">
            <?php echo $this->Form->label('horse_power', __('Konjskih snaga')); ?>
            <?php echo $this->Form->input('horse_power', array('type' => 'number', 'label' => false, 'class' => 'col_9', 'style' => 'margin: 0; width: 100%;', 'required' => false, 'placeholder' => __('Unesite konjske snage vozila'))); ?>
        </div>
        <div class="clear"></div>
        <div class="col_9">
            <?php echo $this->Form->label('engine_capacity_cm3', __('Kubikaža motora')); ?>
            <?php echo $this->Form->input('engine_capacity_cm3', array('type' => 'number', 'label' => false, 'class' => 'col_9', 'style' => 'margin: 0; width: 100%;', 'required' => false, 'placeholder' => __('Unesite kubikažu vozila'))); ?>
        </div>
        <div class="clear"></div>
        <div class="col_9">
            <?php echo $this->Form->label('year_of_production', __('Godina proizvodnje')); ?>
            <?php echo $this->Form->input('year_of_production', array('type' => 'number', 'label' => false, 'class' => 'col_9', 'style' => 'margin: 0; width: 100%;', 'required' => false, 'placeholder' => __('Unesite godinu proizvodnje'))); ?>
        </div>
        <div class="clear"></div>
        <div class="col_9">
            <?php echo $this->Form->label('color', __('Boja vozila')); ?>
            <?php echo $this->Form->input('color', array('type' => 'text', 'label' => false, 'class' => 'col_9', 'style' => 'margin: 0; width: 100%;', 'required' => false, 'placeholder' => __('Unesite boju vozila'))); ?>
        </div>
        <div class="clear"></div>

        <div class="col_9">
            <?php echo $this->Form->label('number_of_seats', __('Broj sedišta')); ?>
            <?php echo $this->Form->input('number_of_seats', array('type' => 'number', 'label' => false, 'class' => 'col_9', 'style' => 'margin: 0; width: 100%;', 'required' => false, 'placeholder' => __('Unesite broj sedišta'))); ?>
        </div>
        <div class="clear"></div>

        <div class="col_9">
            <?php echo $this->Form->label('chassis_number', __('Broj šasije')); ?>
            <?php echo $this->Form->input('chassis_number', array('type' => 'text', 'label' => false, 'class' => 'col_9', 'style' => 'margin: 0; width: 100%;', 'required' => false, 'placeholder' => __('Unesite broj šasije'))); ?>
        </div>
        <div class="clear"></div>

        <div class="col_9">
            <?php echo $this->Form->label('engine_number', __('Broj motora')); ?>
            <?php echo $this->Form->input('engine_number', array('type' => 'text', 'label' => false, 'class' => 'col_9', 'style' => 'margin: 0; width: 100%;', 'required' => false, 'placeholder' => __('Unesite broj motora'))); ?>
        </div>
        <div class="clear"></div>

        <div class="col_9">
            <?php echo $this->Form->label('date_of_purchase', __('Datum kupovine')); ?>
            <?php echo $this->Form->date('date_of_purchase', array('label' => false, 'class' => 'col_9', 'style' => 'margin: 0; width: 100%;', 'required' => false, 'value' => date('Y-m-d'), 'placeholder' => __('Unesite datum kupovine'))); ?>
            <?php if ($this->Form->isFieldError('date_of_purchase')) {
                echo $this->Form->error('date_of_purchase');
            }
            ?>
        </div>
        <div class="clear"></div>

        <div class="col_9">
            <?php echo $this->Form->label('price', __('Cena')); ?>
            <?php echo $this->Form->input('price', array('type' => 'number', 'label' => false, 'class' => 'col_9', 'style' => 'margin: 0; width: 100%;', 'required' => false, 'placeholder' => __('Unesite cenu vozila'))); ?>
        </div>
        <div class="clear"></div>

        <div class="col_9">



            <?php echo $this->Form->label('VmInternalWorkerVehicle.hr_worker_id', __('Zaposleni'));
            echo $this->Form->input('VmInternalWorkerVehicle.hr_worker_id', array(
                'type' => 'select', 'options' => $hr_workers,
                'id' => 'radnikId', 'error' => false, 'label' => false,
                'multiple' => true,
                'style' => 'width:100%;',
                'default' => $action == 'edit' ? $vm_hr_array : 0
            ));
            ?>
            <?php if ($this->Form->isFieldError('VmVehicle.hr_worker_id')) {
                echo $this->Form->error('VmVehicle.hr_worker_id');
            }
            ?>


        </div>
        <div class="clear"></div>


        <div class="col_9">
            <?php

            echo $this->Form->label('VmExternalWorkerVehicle.vm_external_worker_id', __('Eksterni radnici'));
            echo $this->Form->input('VmExternalWorkerVehicle.vm_external_worker_id', array(
                'type' => 'select', 'options' => $vm_external_workers,
                'id' => 'radnikEksterniId', 'error' => false, 'label' => false,
                'multiple' => true,
                'style' => 'width:100%;',
                'default' => $action == 'edit' ? $vm_ext_array : 0

            ));
            ?>




            <?php if ($this->Form->isFieldError('VmVehicle.vm_external_worker_id')) {
                echo $this->Form->error('VmVehicle.vm_external_worker_id');
            }
            ?>

        </div>


        <div class="col_9 row">
            <ul class="button-bar">
                <li class="first">
                    <?php echo $this->Html->link('<i class="icon-plus-sign" style="color :#669E00"></i> ' . __('Novi'), array('controller' => 'vmExternalWorkers', 'action' => 'save'), array('escape' => false)); ?>
                </li>
            </ul>
        </div>
        <div class="clear"></div>

        <script>
            $('#radnikId').select2();
            $('#radnikEksterniId').select2();
            // $("option:selected").prop("selected", false)
            // $('select').select2('val', ['1', '3'])
        </script>







        <?php
        if ($action == 'edit') {
        ?>
            <div class="col_4 left inline">
                <?php
                echo $this->Form->checkbox('in_use', array('on' => 'U upotrebi', 'style' => 'margin: 0;width:1em; height: 1em;'), array());
                ?>
                <?php echo $this->Form->label('in_use', __('U upotrebi')); ?>

            </div>
            <div class="clear"></div>
        <?php
        }
        ?>

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
                    <?php echo $this->Html->link(__('Nazad'), array('action' => 'index'), array('class' => 'button', 'style' => 'margin:20px 0 20px 0;')); ?>
                    <?php echo $this->Form->end(); ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $('input[type="date"]').datepicker({
        changeYear: true,
        changeMonth: true
    });
</script>