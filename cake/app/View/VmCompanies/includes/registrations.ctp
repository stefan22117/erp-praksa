<!-- TAB_REGISTRATIONS -->
<div id="tab_vm_registrations" class="tab-content">
    <div style="float:left; margin:0 0 24px 20px;">
        <ul class="button-bar">
            <li class="first">
                <?php echo $this->Html->link('<i class="icon-plus-sign" style="color :#669E00"></i> ' . __('Dodaj novu registraciju'),
                 'javascript:void(0);', 
                 array('escape' => false, 'id' => 'addNewRegistration',
                 'style' => isset($errors['Registrations']) ? 'display: none' : 'display: block'
                 )); ?>
            </li>
        </ul>
    </div>

    <div id="addNewRegistrationForm" <?php echo isset($errors['Registrations']) ? 'style="display: block"' : 'style="display: none"'; ?>>


        <div class="formular">
            <?php echo $this->Form->create('VmRegistration', array('novalidate' => true, 'url' => array('controller' => 'VmRegistrations', 'action' => 'save'))); ?>

            <?php echo $this->Form->hidden('vm_company_id', array('value' => $vm_company['VmCompany']['id'])); ?>

            <div class="col_9">
                <?php echo $this->Form->label('vm_vehicle_id', __('Vozilo')); ?>
                <?php echo $this->Form->input('vm_vehicle_id', array('label' => false, 'options' => $vm_vehicles, 'class' => 'col_12', 'style' => 'margin: 0; width: 100%;', 'empty' => __('Izaberite vozilo'))); ?>
            </div>

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
            <div class="clear"></div>
            <div class="col_9">
                <?php echo $this->Form->label('amount', __('Cena registracije')); ?>
                <?php echo $this->Form->input('amount', array('type' => 'number', 'label' => false, 'class' => 'col_9', 'style' => 'margin: 0; width: 100%;', 'required' => false, 'placeholder' => __('Unesite cenu registracije'))); ?>
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
                            __('Poništi'),
                            'javascript:void(0)',
                            array(
                                'id' => 'addNewRegistrationFormClose', 'class' => 'button', 'style' => 'margin:20px 0 20px 0;'
                            )
                        ); ?>
                        <?php echo $this->Form->end(); ?>
                    </div>
                </div>
            </div>


        </div>

    </div>



































    <?php if (empty($vm_registrations)) : ?>
        <div class="notice warning">
            <i class="icon-warning-sign icon-large"></i>
            <?php echo __('Nema dodatih registracija'); ?>
            <a class="icon-remove" href="#close"></a>
        </div>
    <?php else : ?>
        <table style="table-layout: fixed;">

            <thead>
                <th><?php echo __('Datum registracije'); ?></th>
                <th><?php echo __('Potrošeno vreme'); ?></th>
                <th><?php echo __('Radnik'); ?></th>
                <th><?php echo __('Datum isteka'); ?></th>
                <th><?php echo __('Cena registracije'); ?></th>
                <th><?php echo __('Vozilo'); ?></th>
                <th></th>
            </thead>
            <tbody>
                <?php foreach ($vm_registrations as $vm_registration) : ?>
                    <tr>
                        <td><?php echo $vm_registration['VmRegistration']['registration_date']; ?></td>
                        <td>
                            <?php
                            $spent_time = $vm_registration['VmRegistration']['spent_time'];
                            $m = floor(($spent_time % 3600) / 60);
                            $h = floor(($spent_time % 86400) / 3600);
                            $d = floor($spent_time / 86400);

                            $spent_time = '';
                            $d ? $spent_time = ($d == 1 ?  __(' dan i ') : $d . __(' dana i ')) : null;
                            $spent_time .= $h . ':' . $m;
                            echo $spent_time;
                            ?>
                        </td>

                        <td><?php echo $vm_registration['HrWorker']['first_name']; ?></td>
                        <td><?php echo $vm_registration['VmRegistration']['expiration_date']; ?></td>
                        <td><?php echo $vm_registration['VmRegistration']['amount']; ?></td>


                        <td>
                            <?php
                            echo $this->Html->link(
                                $vm_registration['VmVehicle']['brand_and_model'],
                                array(
                                    'controller' => 'vmVehicles',
                                    'action' => 'view',
                                    $vm_registration['VmVehicle']['id']
                                )
                            )
                            ?>
                        </td>


                        <td>
                            <ul class="button-bar">
                                <li class="first">
                                    <?php
                                    echo $this->Html->link(
                                        '<i class="icon-eye-open" style="color:blue;"></i>',
                                        array('controller' => 'vmRegistrations', 'action' => 'view', $vm_registration['VmRegistration']['id']),
                                        array('escape' => false, 'title' => __('Detalji'))
                                    );
                                    ?>
                                </li>
                                <li>
                                    <?php
                                    echo $this->Html->link(
                                        '<i class="icon-edit" style="color:orange;"></i>',
                                        array('controller' => 'vmRegistrations', 'action' => 'save', $vm_registration['VmRegistration']['id']),
                                        array('escape' => false, 'title' => __('Izmena'))
                                    );
                                    ?>
                                </li>
                                <li class="last">
                                    <?php
                                    echo $this->Html->link(
                                        '<i class="icon-trash" style="color:red;"></i>',
                                        array('controller' => 'vmRegistrations', 'action' => 'delete', $vm_registration['VmRegistration']['id']),
                                        array('escape' => false, 'title' => __('Brisanje'), 'confirm' => 'Da li ste sigurni da želite da izbrišete registraciju?')
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


<script>
    $('#AddNewFile').click(function(e) {
        $('#addNewFileForm').show();
        $(this).hide();

    })

    $('#addNewFileFormClose').click(function() {
        $('#addNewFileForm').hide();
        $('#AddNewFile').show();
    })


    $('#addNewFuel').click(function(e) {
        $('#addNewFuelForm').show();
        $(this).hide();

    })

    $('#addNewFuelClose').click(function() {
        $('#addNewFuelForm').hide();
        $('#addNewFuel').show();
    })
</script>