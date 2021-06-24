<ul class="breadcrumbs">
    <li><?php echo $this->Html->link(__('Početna'), array('controller' => 'pages', 'action' => 'display')); ?></li>
    <li><?php echo $this->Html->link(__('Goriva'), array('action' => 'index')); ?></li>
    <li class="last"><a href="" onclick="return false"><?php echo __('Dodavanje'); ?></a></li>
</ul>

<div class="name_add_search">
    <div class="name_of_page">
        <?php if ($action == 'add') : ?>
            <h3>
                <i class="icon-dashboard" style="color:yellow;"></i>
                <i class="icon-save" style="color:blue;"></i>
                <?php echo __('Novi unos goriva'); ?>
            </h3>
        <?php endif; ?>
        <?php if ($action == 'edit') : ?>
            <h3>
                <i class="icon-dashboard" style="color:yellow;"></i>
                <i class="icon-edit" style="color:orange;"></i>
                <?php echo __('Izmena postojećeg unosa goriva'); ?>
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
                            'confirm' =>  __('Da li ste sigurni da želite da izbrišete štetu?'),
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
        <?php echo $this->Form->create('VmFuel', array('novalidate' => 'true')); ?>

        <?php if ($action == 'add') : ?>

            <div class="col_9">
                <?php echo $this->Form->label('VmFuel.vm_vehicle_id', __('Vozilo')); ?>
                <?php echo $this->Form->input('VmFuel.vm_vehicle_id', array('label' => false, 'options' => $vm_vehicles, 'class' => 'col_12', 'style' => 'margin: 0; width: 100%;', 'empty' => __('Izaberite vozilo'))); ?>
            </div>

        <?php else : ?>
            <?php echo $this->Form->hidden('vm_vehicle_id', array('value' => $vm_fuel['VmFuel']['vm_vehicle_id'])); ?>
        <?php endif; ?>


        <div class="col_9">
            <?php echo $this->Form->label('VmFuel.liters', __('Gorivo u litrima')); ?>
            <?php echo $this->Form->input('VmFuel.liters', array('type' => 'text', 'label' => false, 'style' => 'margin: 0; width: 100%;', 'required' => false, 'placeholder' => __('Unesite gorivo u litrima'))); ?>
        </div>
        <div class="col_9">
            <?php echo $this->Form->label('VmFuel.amount', __('Ukupna cena goriva')); ?>
            <?php echo $this->Form->input('VmFuel.amount', array('type' => 'number', 'label' => false, 'style' => 'margin: 0; width: 100%;', 'required' => false, 'placeholder' => __('Unesite gorivo u litrima'))); ?>
        </div>
        <div class="col_9">
            <?php echo $this->Form->label('VmCrossedKm.total_kilometers', __('Trenutna kilometraža')); ?>
            <?php echo $this->Form->input('VmCrossedKm.total_kilometers', array('type' => 'text', 'label' => false, 'style' => 'margin: 0; width: 100%;', 'required' => false, 'placeholder' => __('Unesite gorivo u litrima'))); ?>
        </div>


        <div class="col_9">
            <?php echo $this->Form->label('VmCrossedKm.report_datetime', __('Datum sipanja goriva')); ?>
            <?php if ($action == 'add') : ?>
                <?php echo $this->Form->date('VmCrossedKm.report_datetime', array('label' => false, 'class' => 'col_9', 'style' => 'margin: 0; width: 100%;', 'value' => date('Y-m-d'))); ?>
            <?php else : ?>
                <?php echo $this->Form->date('VmCrossedKm.report_datetime', array('label' => false, 'class' => 'col_9', 'style' => 'margin: 0; width: 100%;', 'value' => substr($vm_fuel['VmCrossedKm']['report_datetime'], 0, 10))); ?>
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
                    <?php echo $this->Html->link(__('Poništi'), 'javascript:void(0)', array('id' => 'addNewFuelClose', 'class' => 'button', 'style' => 'margin:20px 0 20px 0;')); ?>
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

    try {
        $('#VmFuelVmVehicleId').select2();
    } catch (e) {}
    $('#VmCrossedKmHrWorkerId').select2();

    $('input[type="date"]').datepicker({
        changeYear: true,
        changeMonth: true
    });
</script>