<!-- TAB_REPAIRS -->
<div id="tab_vm_repairs" class="tab-content">

    <div style="float:left; margin:0 0 24px 20px;">
        <ul class="button-bar">
            <li class="first">

                <?php echo $this->Html->link(
                    '<i class="icon-plus-sign" style="color :#669E00"></i> ' . __('Dodaj novu popravku'),
                    'javascript:void(0);',
                    array(
                        'escape' => false, 'id' => 'addNewRepair',
                        'style' => isset($errors['Repairs']) ? 'display: none' : 'display: block'
                    )
                ); ?>

            </li>
        </ul>
    </div>


    <div id="addNewRepairForm" <?php echo isset($errors['Repairs']) ? 'style="display: block"' : 'style="display: none"'; ?>>
        <?php
        if (empty($vm_damages_for_repairs)) :
        ?>
            <div class="notice warning">
                <i class="icon-warning-sign icon-large"></i>
                <?php echo __('Nema dodatih šteta za ovo vozilo'); ?>
                <a class="icon-remove" href="#close"></a>
            </div>
        <?php endif; ?>





        <div class="formular">
            <?php echo $this->Form->create('VmRepair', array('novalidate' => 'true', 'url' => array('controller' => 'VmRepairs', 'action' => 'save'))); ?>



            <?php echo $this->Form->hidden('vm_vehicle_id', array('value' => $vm_vehicle['VmVehicle']['id'])); ?>



            <div class="col_9">
                <?php echo $this->Form->label('VmRepair.vm_damage_id', __('Šteta')); ?>
                <?php echo $this->Form->input('VmRepair.vm_damage_id', array('label' => false, 'options' => $vm_damages_for_repairs, 'class' => 'col_12', 'style' => 'margin: 0; width: 100%;', 'empty' => __('Izaberite štetu'))); ?>
            </div>



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

                <?php echo $this->Form->date('VmCrossedKm.report_datetime', array('label' => false, 'class' => 'col_9', 'style' => 'margin: 0; width: 100%;', 'value' => date('Y-m-d'))); ?>

                <?php
                if ($this->Form->isFieldError('VmCrossedKm.report_datetime')) {
                    echo $this->Form->error('VmCrossedKm.report_datetime');
                }
                ?>

            </div>
            
            <div class="col_9">
                <?php echo $this->Form->label('VmCrossedKm.hr_worker_id', __('Radnik')); ?>
                <?php echo $this->Form->input('VmCrossedKm.hr_worker_id', array('id' => 'VmCrossedKmHrWorkerIdVmRepair', 'label' => false, 'options' => $hr_workers, 'class' => 'col_12', 'style' => 'margin: 0; width: 100%;', 'empty' => __('Izaberite radnika'))); ?>
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
                        <?php echo $this->Html->link(__('Poništi'), 'javascript:void(0)', array('id' => 'addNewRepairFormClose', 'class' => 'button', 'style' => 'margin:20px 0 20px 0;')); ?>
                        <?php echo $this->Form->end(); ?>
                    </div>
                </div>
            </div>

        </div>

    </div>












































    <?php if (empty($vm_repairs)) : ?>
        <div class="notice warning">
            <i class="icon-warning-sign icon-large"></i>
            <?php echo __('Nema dodatih popravki'); ?>
            <a class="icon-remove" href="#close"></a>
        </div>
    <?php else : ?>
        <table style="table-layout: fixed;">

            <thead>
                <th><?php echo __('Cena popravke'); ?></th>
                <th><?php echo __('Utrošeno vreme'); ?></th>
                <th><?php echo __('Opis popravke'); ?></th>
                <th><?php echo __('Vreme popravke'); ?></th>
                <th><?php echo __('Radnik'); ?></th>
                <th><?php echo __('Firma'); ?></th>
                <th><?php echo __('Šteta'); ?></th>
                <th></th>
            </thead>
            <tbody>
                <?php foreach ($vm_repairs as $vm_repair) : ?>
                    <tr>
                        <td><?php echo $vm_repair['VmRepair']['amount']; ?></td>
                        <td>

                            <?php
                            $spent_time = $vm_repair['VmRepair']['spent_time'];
                            $m = floor(($spent_time % 3600) / 60);
                            $h = floor(($spent_time % 86400) / 3600);
                            $d = floor($spent_time / 86400);

                            $spent_time = '';
                            $d ? $spent_time = ($d == 1 ?  __(' dan i ') : $d . __(' dana i ')) : null;
                            $spent_time .= $h . ':' . $m;
                            echo $spent_time;
                            ?>
                        </td>
                        <td><?php echo $vm_repair['VmRepair']['description']; ?></td>
                        <td><?php echo $vm_repair['VmCrossedKm']['report_datetime']; ?></td>
                        <td><?php echo $vm_repair['VmCrossedKm']['HrWorker']['first_name']; ?></td>
                        <td><?php echo $this->Html->link(
                                $vm_repair['VmCompany']['name'],
                                array(
                                    'controller' => 'vmCompanies',
                                    'action' => 'view',
                                    $vm_repair['VmCompany']['id']
                                )
                            ); ?>
                        </td>
                        <td><?php echo $this->Html->link(
                                strlen($vm_repair['VmDamage']['description']) < 15 ?
                                    $vm_repair['VmDamage']['description'] :
                                    substr($vm_repair['VmDamage']['description'], 0, 10) . '...',
                                array(
                                    'controller' => 'vmCompanies',
                                    'action' => 'view',
                                    $vm_repair['VmCompany']['id']
                                )
                            ); ?>
                        </td>
                        </td>
                        <td>
                            <ul class="button-bar">
                                <li class="first">
                                    <?php echo $this->Html->link('<i class="icon-eye-open" style="color :blue"></i>', array('controller' => 'vmRepairs', 'action' => 'view', $vm_repair['VmRepair']['id']), array('title' => __('Detalji'), 'escape' => false)); ?>
                                </li>
                                <li>
                                    <?php echo $this->Html->link('<i class="icon-edit" style="color :orange"></i>', array('controller' => 'vmRepairs', 'action' => 'save', $vm_repair['VmRepair']['id']), array('title' => __('Izmena'), 'escape' => false)); ?>
                                </li>
                                <li class="last">
                                    <?php echo $this->Html->link('<i class="icon-trash" style="color :red"></i>', array('controller' => 'vmRepairs', 'action' => 'delete', $vm_repair['VmRepair']['id']), array('title' => __('Brisanje'), 'escape' => false, 'confirm' => 'Da li ste sigurni da želite da izbrišete ovu popravku?')); ?>
                                </li>
                            </ul>

                        </td>
                    </tr>


                <?php endforeach; ?>

            </tbody>
        </table>


    <?php endif; ?>
</div>