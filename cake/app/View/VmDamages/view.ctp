<ul class="breadcrumbs">
    <li><?php echo $this->Html->link(__('Početna'), array('controller' => 'pages', 'action' => 'display')); ?></li>
    <li><?php echo $this->Html->link(__('Štete'), array('controller' => 'vmDamages', 'action' => 'index')); ?></li>
    <li class="last"><a href="" onclick="return false"><?php echo __('Detalji'); ?></a></li>
</ul>
<div class="name_of_page">
    <h3><i class="icon-fire" style="color: red;"></i> <i class="icon-search"></i> <?php echo __('Detalji štete'); ?></h3>

</div>

<div style="float:right; margin:20px 24px 0 0;">
    <ul class="button-bar">

        <?php if (!empty($vm_damage['VmRepair'])) : ?>
            <li class="first">
                <?php
                echo $this->Html->link(
                    '<i class="icon-wrench" style="color:gray;"></i> ' .
                        ($vm_damage['VmDamage']['repaired'] ?
                            '<i class="icon-check" style="color:green;"></i> '  . __('Popravljeno') :
                            '<i class="icon-remove" style="color:red;"></i> '  . __('U kvaru')),
                    array(
                        'controller' => 'vmDamages',
                        'action' => 'repair',
                        $vm_damage['VmDamage']['id']
                    ),
                    array(
                        'escape' => false,
                        'style' => ' font-size: 12px;'
                    )
                );
                ?>
            </li>
        <?php endif; ?>


        <li class="<?php echo empty($vm_damage['VmRepair']) ?  'first' : ''; ?>">
            <?php
            echo $this->Html->link(
                '<i class="icon-edit" style="color:orange;"></i> ' . __('Izmena'),
                array(
                    'controller' => 'vmDamages',
                    'action' => 'save',
                    $vm_damage['VmDamage']['id']
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
                    'controller' => 'vmDamages',
                    'action' => 'delete',
                    $vm_damage['VmDamage']['id']
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


<div class="content_data">
    <table cellspacing="0" cellpadding="0" class="striped">
        <tr>
            <th style="text-align:left"><?php echo __('Marka i model'); ?></th>
            <td style="width:85%;"><?php echo $vm_damage['VmVehicle']['brand_and_model']; ?></td>
        </tr>
        <tr>
            <th style="text-align:left"><?php echo __('Registarski broj'); ?></th>
            <td style="width:85%;"><?php echo $vm_damage['VmVehicle']['reg_number']; ?></td>
        </tr>
        <tr>
            <th style="text-align:left"><?php echo __('Odgovoran'); ?></th>
            <td style="width:85%;"><?php echo $vm_damage['VmDamage']['responsible']; ?></td>
        </tr>
        <tr>
            <th style="text-align:left"><?php echo __('Opis'); ?></th>
            <td style="width:85%;"><?php echo $vm_damage['VmDamage']['description']; ?></td>
        </tr>
        <tr>
            <th style="text-align:left"><?php echo __('Datum nastanka štete'); ?></th>
            <td style="width:85%;"><?php echo $vm_damage['VmDamage']['date']; ?></td>
        </tr>
    </table>
</div>

<div style="float:left; margin:0 0 24px 20px;">
    <ul class="button-bar">
        <li class="first">
            <?php echo $this->Html->link('<i class="icon-plus-sign" style="color :#669E00"></i> ' . __('Dodaj novu popravku'), 'javascript:void(0);', array('id' => 'addNewRepair', 'escape' => false)); ?>
        </li>
    </ul>
</div>


<div class="content_data" id="addNewRepairForm" <?php echo isset($errors['Repairs']) ? 'style="display: block"' : 'style="display: none"' ?>>
    <div class="formular">
        <?php echo $this->Form->create('VmRepair', array('novalidate' => true, 'url' => array('controller' => 'vmRepairs', 'action' => 'add', $vm_damage['VmDamage']['id']))); ?>
        <?php echo $this->Form->hidden('VmCrossedKm.vm_vehicle_id', ['value' => $vm_damage['VmVehicle']['id']]); ?>
        <?php echo $this->Form->hidden('VmRepair.vm_damage_id', ['value' => $vm_damage['VmDamage']['id']]); ?>

        <div class="col_9">
            <?php echo $this->Form->label('VmRepair.amount', __('Cena popravke')); ?>
            <?php echo $this->Form->input('VmRepair.amount', array('type' => 'number', 'label' => false, 'class' => 'col_9', 'style' => 'margin: 0; width: 100%;', 'required' => false, 'placeholder' => __('Unesite cenu popravke'))); ?>
        </div>
        <div class="clear"></div>

        <div class="col_9" style="display: flex;justify-content: space-between">


            <?php echo $this->Form->label('VmRepair.spent_time', __('Potrošeno vreme')); ?>
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
            echo $this->Form->input('VmRepair.spent_time', array(
                'type' => 'select', 'options' => $minutes,
                'name' => 'data[VmRepair][spent_time][min]', 'id' => 'VmRepairSpentTimeMinute', 'error' => false, 'label' => false,
                'selected' => isset($this->request->data['VmRepair']['spent_time']['min']) ? $this->request->data['VmRepair']['spent_time']['min'] : 0
            ));
            ?>

        </div>
        <div class="clear"></div>
        <?php if ($this->Form->isFieldError('VmRepair.spent_time')) : ?>
            <div class="col_9">
                <?php echo $this->Form->error('VmRepair.spent_time'); ?>
                <div class="clear"></div>
            </div>
        <?php endif; ?>





        <div class="col_9">
            <?php echo $this->Form->label('VmRepair.description', __('Opis popravke')); ?>
            <?php echo $this->Form->input('VmRepair.description', array('type' => 'text', 'label' => false, 'class' => 'col_9', 'style' => 'margin: 0; width: 100%;', 'required' => false, 'placeholder' => __('Unesite opis popravke'))); ?>
        </div>
        <div class="clear"></div>

        <div class="col_9">
            <?php echo $this->Form->label('VmCrossedKm.total_kilometers', __('Trenutna kilometraža')); ?>
            <?php echo $this->Form->input('VmCrossedKm.total_kilometers', array('type' => 'number', 'label' => false, 'style' => 'margin: 0; width: 100%;', 'required' => false, 'placeholder' => __('Unesite pređenu kilometražu'))); ?>
        </div>

        <div class="clear"></div>












        <div class="col_9">
            <?php echo $this->Form->label('VmCrossedKm.report_datetime', __('Datum popravke')); ?>

            <?php echo $this->Form->date('VmCrossedKm.report_datetime', array('label' => false, 'class' => 'col_9', 'style' => 'margin: 0; width: 100%;', 'value' => date('Y-m-d'))); ?>

        </div>

        <?php if ($this->Form->isFieldError('VmCrossedKm.report_datetime')) : ?>
            <div class="col_9">
                <?php echo $this->Form->error('VmCrossedKm.report_datetime'); ?>
                <div class="clear"></div>
            </div>
        <?php endif; ?>



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
                    <?php echo $this->Html->link(__('Poništi'), 'javascript:void(0)', array('id' => 'addNewRepairFormClose', 'class' => 'button', 'style' => 'margin:20px 0 20px 0;')); ?>
                    <?php echo $this->Form->end(); ?>
                </div>
            </div>
        </div>











    </div>
</div>
<div class="content_data">
    <?php if (empty($vm_damage['VmRepair'])) : ?>
        <div class="notice warning">
            <i class="icon-warning-sign icon-large"></i>
            <?php echo __('Nema dodatih popravki'); ?>
            <a class="icon-remove" href="#close"></a>
        </div>
    <?php else : ?>
        <table style="table-layout: fixed;">

            <thead>
                <th><?php echo __('Cena popravke'); ?></th>
                <th><?php echo __('Potrošeno vreme'); ?></th>
                <th><?php echo __('Opis'); ?></th>
                <th><?php echo __('Firma'); ?></th>
            </thead>
            <tbody>
                <?php foreach ($vm_damage['VmRepair'] as $vm_repair) : ?>
                    <tr>
                        <td><?php echo $vm_repair['amount']; ?></td>
                        <td>
                            <?php
                            $spent_time = $vm_repair['spent_time'];
                            $m = floor(($spent_time % 3600) / 60);
                            $h = floor(($spent_time % 86400) / 3600);
                            $d = floor($spent_time / 86400);

                            $spent_time = '';
                            $d ? $spent_time = ($d == 1 ?  __(' dan i ') : $d . __(' dana i ')) : null;
                            $spent_time .= $h . ':' . $m;
                            echo $spent_time;
                            ?>
                        </td>
                        <td><?php echo $vm_repair['description']; ?></td>
                        <td><?php echo
                            $this->Html->link(
                                $vm_repair['VmCompany']['name'],
                                array(
                                    'controller' => 'vmCompanies',
                                    'action' => 'view',
                                    $vm_repair['VmCompany']['id']
                                )

                            );

                            ?>

                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>

</div>




<script>
    $('#addNewRepair').click(function(e) {
        $('#addNewRepairForm').show();
        $(this).hide();

    })

    $('#addNewRepairFormClose').click(function() {
        $('#addNewRepairForm').hide();
        $('#addNewRepair').show();
    })



    $('#VmRepairSpentTimeDay').select2({
        minimumResultsForSearch: -1
    });
    $('#VmRepairSpentTimeHour').select2({
        minimumResultsForSearch: -1
    });
    $('#VmRepairSpentTimeMinute').select2({
        minimumResultsForSearch: -1
    });

    $('#VmCrossedKmHrWorkerId').select2({});
    $('#VmRepairVmCompanyId').select2({});

    $('input[type="date"]').datepicker({
        changeYear: true,
        changeMonth: true
    });
</script>