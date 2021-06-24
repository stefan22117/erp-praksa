
<?php
        var_dump($this->Session->read('errors.VmFuel'));
            ?>
<?php


echo $this->Form->create('VmFuel', ['novalidate'=>true]);


echo $this->Form->input('VmFuel.liters');

echo $this->Form->input('VmFuel.amount');


echo $this->Form->input('VmCrossedKm.total_kilometers');

echo $this->Form->date('VmCrossedKm.report_datetime');
if ($this->Form->isFieldError('VmCrossedKm.report_datetime')) {
    echo $this->Form->error('VmCrossedKm.report_datetime');
}

echo $this->Form->input(
    'VmCrossedKm.hr_worker_id',
    array(
        'label'=>'Hr Worker',
        'type' => 'select',
        'options'=>
        [
            $hr_workers
        ]
    )
);

echo $this->Form->end('Fill');

?>






<div id="tabr3" class="tab-content">


    <div style="float:left; margin:0 0 24px 20px;">
        <ul class="button-bar">
            <li class="first">
                <?php echo $this->Html->link('<i class="icon-plus-sign" style="color :#669E00"></i> ' . __('Dodaj novo gorivo'), 'javascript:void(0);', array('id' => 'addNewFuel', 'escape' => false)); ?>
            </li>
        </ul>
    </div>


    <div id="addNewFuelForm">

        <div class="formular">
            <?php echo $this->Form->create('VmFuel', array('novalidate'=>'true', 'url' => array('controller' => 'vmFuels', 'action' => 'add', $vm_vehicle['VmVehicle']['id']))); ?>
            <div class="col_9">
                <?php echo $this->Form->label('VmFuel.liters', __('Gorivo u litrima')); ?>
                <?php echo $this->Form->input('VmFuel.liters', array('type' => 'text', 'label' => false, 'style' => 'margin: 0; width: 100%;', 'required' => false, 'placeholder' => __('Unesite gorivo u litrima'))); ?>
            </div>
            <div class="col_9">
                <?php echo $this->Form->label('VmFuel.amount', __('Ukupna cena goriva')); ?>
                <?php echo $this->Form->input('VmFuel.amount', array('type' => 'text', 'label' => false, 'style' => 'margin: 0; width: 100%;', 'required' => false, 'placeholder' => __('Unesite gorivo u litrima'))); ?>
            </div>
            <div class="col_9">
                <?php echo $this->Form->label('VmCrossedKm.total_kilometers', __('Trenutna kilometraža')); ?>
                <?php echo $this->Form->input('VmCrossedKm.total_kilometers', array('type' => 'text', 'label' => false, 'style' => 'margin: 0; width: 100%;', 'required' => false, 'placeholder' => __('Unesite gorivo u litrima'))); ?>
            </div>

            <div class="col_9">
                <?php echo $this->Form->date('VmCrossedKm.report_datetime'); ?>
                <?php
                if ($this->Form->isFieldError('VmCrossedKm.report_datetime')) {
                    echo $this->Form->error('VmCrossedKm.report_datetime');
                }
                ?>

            </div>

            <div class="col_9">
                <?php
                echo $this->Form->input(
                    'hr_worker_id',
                    array(
                        'label' => 'Hr Worker',
                        'type' => 'select',
                        'options' =>
                        [
                            $hr_workers
                        ]
                    )
                );
                ?>
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


