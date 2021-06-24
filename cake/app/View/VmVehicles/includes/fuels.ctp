<!-- TAB_FUELS -->
<div id="tab_vm_fuels" class="tab-content">
    <div style="float:left; margin:0 0 24px 20px;">
        <ul class="button-bar">
            <li class="first">
                <?php echo $this->Html->link('<i class="icon-plus-sign" style="color :#669E00"></i> ' . __('Dodaj novo gorivo'),
                 'javascript:void(0);',
                  array('id' => 'addNewFuel', 'escape' => false,
                  'style' => isset($errors['Fuels']) ? 'display: none' : 'display: block'
                  )); ?>
            </li>
        </ul>
    </div>





    <div id="addNewFuelForm" <?php echo isset($errors['Fuels']) ? 'style="display: block"' : 'style="display: none"'; ?>>

        <div class="formular">
            <?php echo $this->Form->create('VmFuel', array('novalidate' => 'true', 'url' => array('controller' => 'vmFuels', 'action' => 'save'))); ?>

            <?php echo $this->Form->hidden('vm_vehicle_id', array('value' => $vm_vehicle['VmVehicle']['id'])); ?>

            <div class="col_9">
                <?php echo $this->Form->label('VmFuel.liters', __('Gorivo u litrima')); ?>
                <?php echo $this->Form->input('VmFuel.liters', array('type' => 'number', 'label' => false, 'style' => 'margin: 0; width: 100%;', 'required' => false, 'placeholder' => __('Unesite gorivo u litrima'))); ?>

            </div>
            <div class="col_9">
                <?php echo $this->Form->label('VmFuel.amount', __('Ukupna cena goriva')); ?>
                <?php echo $this->Form->input('VmFuel.amount', array('type' => 'number', 'label' => false, 'style' => 'margin: 0; width: 100%;', 'required' => false, 'placeholder' => __('Unesite ukupnu cenu goriva'))); ?>
            </div>
            <div class="col_9">
                <?php echo $this->Form->label('VmCrossedKm.total_kilometers', __('Trenutna kilometraža')); ?>
                <?php echo $this->Form->input('VmCrossedKm.total_kilometers', array('type' => 'number', 'label' => false, 'style' => 'margin: 0; width: 100%;', 'required' => false, 'placeholder' => __('Unesite pređenu kilometražu'))); ?>
            </div>

            <div class="col_9">
                <?php echo $this->Form->label('VmCrossedKm.report_datetime', __('Datum sipanja goriva')); ?>

                <?php echo $this->Form->date('VmCrossedKm.report_datetime', array('label' => false, 'class' => 'col_9', 'style' => 'margin: 0; width: 100%;', 'value' => date('Y-m-d'))); ?>
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
                        <?php echo $this->Html->link(__('Poništi'), 
                        'javascript:void(0)', 
                        array('id' => 'addNewFuelClose', 'class' => 'button', 'style' => 'margin:20px 0 20px 0;')); 
                        ?>
                        <?php echo $this->Form->end(); ?>
                    </div>
                </div>
            </div>



        </div>

    </div>





    <?php if (empty($vm_fuels)) : ?>
        <div class="notice warning">
            <i class="icon-warning-sign icon-large"></i>
            <?php echo __('Nema dodatih goriva'); ?>
            <a class="icon-remove" href="#close"></a>
        </div>
    <?php else : ?>
        <table style="table-layout: fixed;">

            <thead>
                <th><?php echo __('Gorivo u litrima'); ?></th>
                <th><?php echo __('Ukupna cena goriva'); ?></th>
                <th><?php echo __('Radnik'); ?></th>
                <th></th>
            </thead>
            <tbody>
                <?php foreach ($vm_fuels as $vm_fuel) : ?>
                    <tr>
                        <td><?php echo $vm_fuel['VmFuel']['liters']; ?></td>
                        <td><?php echo $vm_fuel['VmFuel']['amount']; ?></td>
                        <td><?php echo $vm_fuel['VmCrossedKm']['HrWorker']['first_name']; ?></td>

                        <td>
                            <ul class="button-bar">
                                <li class="first">
                                    <?php
                                    echo $this->Html->link(
                                        '<i class="icon-eye-open" style="color:blue;"></i>',
                                        array('controller' => 'vmFuels', 'action' => 'view', $vm_fuel['VmFuel']['id']),
                                        array('escape' => false, 'title' => __('Detalji'))
                                    );
                                    ?>
                                </li>
                                <li>
                                    <?php
                                    echo $this->Html->link(
                                        '<i class="icon-edit" style="color:orange;"></i>',
                                        array('controller' => 'vmFuels', 'action' => 'save', $vm_fuel['VmFuel']['id']),
                                        array('escape' => false, 'title' => __('Izmena'))
                                    );
                                    ?>
                                </li>
                                <li class="last">
                                    <?php
                                    echo $this->Html->link(
                                        '<i class="icon-trash" style="color:red;"></i>',
                                        array('controller' => 'vmFuels', 'action' => 'delete', $vm_fuel['VmFuel']['id']),
                                        array('escape' => false, 'title' => __('Detalji'))
                                    );
                                    ?>

                                </li>
                            </ul>

                        </td>
                    </tr>


                <?php endforeach; ?>

            </tbody>
        </table>


    <?php endif; ?>
</div>