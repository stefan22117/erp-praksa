<!-- TAB_MAINTENANCES -->
<div id="tab_vm_maintenances" class="tab-content">
    <div style="float:left; margin:0 0 24px 20px;">
        <ul class="button-bar">
            <li class="first">
                <?php echo $this->Html->link(
                    '<i class="icon-plus-sign" style="color :#669E00"></i> ' . __('Dodaj novo održavanje'),
                    'javascript:void(0);',
                    array(
                        'id' => 'addNewMaintenance', 'escape' => false,
                        'style' => isset($errors['Maintenances']) ? 'display: none' : 'display: block'
                    )
                ); ?>
            </li>
        </ul>
    </div>

    <div id="addNewMaintenanceForm" <?php echo isset($errors['Maintenances']) ? 'style="display: block"' : 'style="display: none"'; ?>>

        <div class="formular">
            <?php echo $this->Form->create('VmMaintenance', array('novalidate' => 'true', 'url' => array('controller' => 'vmMaintenances', 'action' => 'save'))); ?>

            <?php echo $this->Form->hidden('vm_company_id', array('value' => $vm_company['VmCompany']['id'])); ?>


            <div class="col_9">
                <?php echo $this->Form->label('VmMaintenance.vm_vehicle_id', __('Vozilo')); ?>
                <?php echo $this->Form->input('VmMaintenance.vm_vehicle_id', array('label' => false, 'options' => $vm_vehicles, 'class' => 'col_12', 'style' => 'margin: 0; width: 100%;', 'empty' => __('Izaberite vozilo'))); ?>
            </div>


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

                <?php echo $this->Form->date('VmCrossedKm.report_datetime', array('label' => false, 'class' => 'col_9', 'style' => 'margin: 0; width: 100%;', 'value' => date('Y-m-d'))); ?>

                <?php
                if ($this->Form->isFieldError('VmCrossedKm.report_datetime')) {
                    echo $this->Form->error('VmCrossedKm.report_datetime');
                }
                ?>

            </div>

            <div class="col_9">
                <?php echo $this->Form->label('VmCrossedKm.hr_worker_id', __('Radnik')); ?>
                <?php echo $this->Form->input('VmCrossedKm.hr_worker_id', array('id' => 'VmCrossedKmHrWorkerIdVmMaintenance', 'label' => false, 'options' => $hr_workers, 'class' => 'col_12', 'style' => 'margin: 0; width: 100%;', 'empty' => __('Izaberite radnika'))); ?>

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
                        <?php echo $this->Html->link(
                            __('Poništi'),
                            'javascript:void(0)',
                            array('id' => 'addNewMaintenanceClose', 'class' => 'button', 'style' => 'margin:20px 0 20px 0;')
                        );
                        ?>
                        <?php echo $this->Form->end(); ?>
                    </div>
                </div>
            </div>


        </div>

    </div>




    <?php if (empty($vm_maintenances)) : ?>
        <div class="notice warning">
            <i class="icon-warning-sign icon-large"></i>
            <?php echo __('Nema dodatih održavanja'); ?>
            <a class="icon-remove" href="#close"></a>
        </div>
    <?php else : ?>
        <table style="table-layout: fixed;">

            <thead>
                <th><?php echo __('Cena održavanja'); ?></th>
                <th><?php echo __('Utrošeno vreme'); ?></th>
                <th><?php echo __('Opis održavanja'); ?></th>
                <th><?php echo __('Vreme održavanja'); ?></th>
                <th><?php echo __('Radnik'); ?></th>
                <th><?php echo __('Firma'); ?></th>
                <th></th>
            </thead>
            <tbody>
                <?php foreach ($vm_maintenances as $vm_maintenance) : ?>
                    <tr>
                        <td><?php echo $vm_maintenance['VmMaintenance']['amount']; ?></td>
                        <td>

                            <?php
                            $spent_time = $vm_maintenance['VmMaintenance']['spent_time'];
                            $m = floor(($spent_time % 3600) / 60);
                            $h = floor(($spent_time % 86400) / 3600);
                            $d = floor($spent_time / 86400);

                            $spent_time = '';
                            $d ? $spent_time = ($d == 1 ?  __(' dan i ') : $d . __(' dana i ')) : null;
                            $spent_time .= $h . ':' . $m;
                            echo $spent_time;
                            ?>
                        </td>
                        <td>
                            <?php echo strlen($vm_maintenance['VmMaintenance']['description']) < 15 ?
                                $vm_maintenance['VmMaintenance']['description'] :
                                substr($vm_maintenance['VmMaintenance']['description'], 0, 10) . '...'
                            ?>
                        </td>



                        <td><?php echo $vm_maintenance['VmCrossedKm']['report_datetime']; ?></td>
                        <td><?php echo $vm_maintenance['VmCrossedKm']['HrWorker']['first_name']; ?></td>

                        <td>
                            <?php echo $this->Html->link(
                                $vm_maintenance['VmCompany']['name'],
                                array(
                                    'controller' => 'vmCompanies',
                                    'action' => 'view',
                                    $vm_maintenance['VmCompany']['id']
                                )
                            ); ?>
                        </td>

                        <td>
                            <ul class="button-bar">
                                <li class="first">
                                    <?php echo $this->Html->link('<i class="icon-eye-open" style="color :blue"></i>', array('controller' => 'vmMaintenances', 'action' => 'view', $vm_maintenance['VmMaintenance']['id']), array('title' => __('Detalji'), 'escape' => false)); ?>
                                </li>
                                <li>
                                    <?php echo $this->Html->link('<i class="icon-edit" style="color :orange"></i>', array('controller' => 'vmMaintenances', 'action' => 'save', $vm_maintenance['VmMaintenance']['id']), array('title' => __('Izmena'), 'escape' => false)); ?>
                                </li>
                                <li class="last">
                                    <?php echo $this->Html->link('<i class="icon-trash" style="color :red"></i>', array('controller' => 'vmMaintenances', 'action' => 'delete', $vm_maintenance['VmMaintenance']['id']), array('title' => __('Brisanje'), 'escape' => false, 'confirm' => 'Da li ste sigurni da želite da izbrišete ovo održavanje?')); ?>
                                </li>
                            </ul>

                        </td>
                    </tr>


                <?php endforeach; ?>

            </tbody>
        </table>


    <?php endif; ?>
</div>