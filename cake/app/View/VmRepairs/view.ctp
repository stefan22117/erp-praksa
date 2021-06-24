<ul class="breadcrumbs">
    <li><?php echo $this->Html->link(__('Početna'), array('controller' => 'pages', 'action' => 'display')); ?></li>
    <li><?php echo $this->Html->link(__('Popravke'), array('controller' => 'vmRepairs', 'action' => 'index')); ?></li>
    <li class="last"><a href="" onclick="return false"><?php echo __('Detalji'); ?></a></li>
</ul>
<div class="name_of_page">
    <h3><i class="icon-wrench"></i> <?php echo __('Popravke'); ?></h3>

</div>


<div class="content_data">
    <table cellspacing="0" cellpadding="0" class="striped tight">
        <tr>
            <th><?php echo __('Marka i model'); ?></th>
            <td><?php echo $vm_damage['VmVehicle']['brand_and_model']; ?></td>
        </tr>


        <tr>
            <th><?php echo __('Odgovoran'); ?></th>
            <td><?php echo $vm_damage['VmDamage']['responsible']; ?></td>
        </tr>


        <tr>
            <th><?php echo __('Opis'); ?></th>
            <td><?php echo $vm_damage['VmDamage']['description']; ?></td>
        </tr>

        <tr>
            <th><?php echo __('Datum nastanka štete'); ?></th>
            <td><?php echo $vm_damage['VmDamage']['date']; ?></td>
        </tr>

        <tr>
            <th><?php echo __('Popravljena'); ?></th>
            <td><?php echo $vm_damage['VmDamage']['repaired'] ? 'Da' : 'Ne'; ?></td>
        </tr>

    </table>
    <div style="float:left; margin:0 0 24px 20px;">
        <ul class="button-bar">
            <li class="first">
                <?php echo $this->Html->link('<i class="icon-plus-sign" style="color :#669E00"></i> ' . __('Dodaj novu popravku'), 'javascript:void(0);', array('id' => 'addNewRepair', 'escape' => false)); ?>
            </li>
        </ul>
    </div>

    <div id="addNewRepairForm" <?php echo isset($errors['Repairs']) ? 'style="display: block"' : 'style="display: none"'; ?>>
    

        <div class="formular">

            <?php echo $this->Form->create('VmRepair', array('url' => array('controller' => 'vmRepairs', 'action' => 'repair', $vm_damage['VmDamage']['id']))); ?>






            <div class="col_9">
                <?php echo $this->Form->label('VmRepair.amount', __('Cena popravke')); ?>
                <?php echo $this->Form->input('VmRepair.amount', array('type' => 'text', 'label' => false, 'style' => 'margin: 0; width: 100%;', 'required' => false, 'placeholder' => __('Unesite cenu popravke'))); ?>
            </div>

            <div class="col_9" style="display: flex;justify-content: space-between">
                <?php echo $this->Form->label('VmRepair.spent_time', __('Potrošeno vreme')); ?>

                <?php $days = range(0, 30);
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
            <?php if ($this->Form->isFieldError('VmRepair.spent_time')) : ?>
            <div class="col_9">
                <?php echo $this->Form->error('VmRepair.spent_time'); ?>
            </div>
        <?php endif; ?>
            

            <div class="col_9">
                <?php echo $this->Form->label('VmRepair.description', __('Opis')); ?>
                <?php echo $this->Form->input('VmRepair.description', array('type' => 'text', 'label' => false, 'style' => 'margin: 0; width: 100%;', 'required' => false, 'placeholder' => __('Unesite opis'))); ?>
            </div>


            <div class="col_9">
                <?php echo $this->Form->label('VmCrossedKm.report_datetime', __('Datum popravke')); ?>
                <?php echo $this->Form->date('VmCrossedKm.report_datetime', array('name'=>'data[VmRepair][report_datetime][date]', 'label' => false, 'class' => 'col_9', 'style' => 'margin: 0; width: 100%;', 'required' => false, 'value' => date('Y-m-d'))); ?>

            </div>

            <div class="col_9">
                <?php echo $this->Form->label('VmRepair.report_datetime', __('Vreme popravke')); ?>
                <?php echo $this->Form->time('VmRepair.report_datetime', array('name'=>'data[VmRepair][report_datetime][time]', 'label' => false, 'class' => 'col_9', 'style' => 'margin: 0; width: 100%;', 'required' => false, 'value' => date('Y-m-d'))); ?>

            </div>

            <div class="col_9">
                <?php echo $this->Form->label('VmRepair.hr_worker_id', __('Radnik')); ?>
                <?php

                echo $this->Form->input(
                    'hr_worker_id',
                    array(
                        'label' => false,
                        'class' => 'col_9',
                        'style' => 'margin: 0; width: 100%;',
                        'type' => 'select',
                        'empty' => 'Izaberite ranika',
                        'options' =>
                        array(
                            $hr_workers
                        )

                    )
                );

                ?>
            </div>


            <div class="col_9">
                <?php echo $this->Form->label('VmRepair.vm_company_id', __('Firma')); ?>
                <?php

                echo $this->Form->input(
                    'vm_company_id',
                    array(
                        'label' => false,
                        'class' => 'col_9',
                        'style' => 'margin: 0; width: 100%;',
                        'type' => 'select',
                        'empty' => 'Izaberite firmu',
                        'options' =>
                        array(
                            $vm_companies
                        )

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
                        <?php echo $this->Html->link(__('Poništi'), 'javascript:void(0)', array('id' => 'addNewRepairFormClose', 'class' => 'button', 'style' => 'margin:20px 0 20px 0;')); ?>
                        <?php echo $this->Form->end(); ?>
                    </div>
                </div>
            </div>
















        </div>
    </div>

    <?php
    if (!empty($vm_repairs)) :
    ?>
        <table>
            <tr>
                <td><?php echo __('Cena popravke'); ?></td>
                <td><?php echo __('Potrošeno vreme'); ?></td>
                <td><?php echo __('Opis'); ?></td>
                <td><?php echo __('Vreme popravke'); ?></td>
                <td><?php echo __('Firma'); ?></td>
            </tr>
            <tr>
                <?php
                foreach ($vm_repairs as $vm_repair) {
                ?>
                    <td>
                        <?php echo $vm_repair['VmRepair']['amount']; ?>
                    </td>
                    <td>
                        <?php echo $vm_repair['VmRepair']['spent_time']; ?>
                    </td>
                    <td>
                        <?php echo $vm_repair['VmRepair']['description']; ?>
                    </td>
                    <td>
                        <?php echo $vm_repair['VmCrossedKm']['report_datetime']; ?>
                    </td>
                    <td>
                        <ul class="button-bar">
                            <li class="first"><?php echo $this->Js->link(
                                                    '<i class="icon-eye-open" style="color: blue;"></i> ' . $vm_repair['VmCompany']['name'],
                                                    array('controller' => 'vmCompanies', 'action' => 'view', $vm_repair['VmCompany']['id']),
                                                    array('title' => __('Detalji firme'), 'escape' => false, 'style' => ' font-size: 12px;')
                                                ); ?></li>
                        </ul>
                    </td>
            </tr>
        <?php
                }
        ?>
        </table>


    <?php
    else :
    ?>



    <?php
    endif;
    ?>
</div>


<script>
    $('#VmRepairHrWorkerId').select2({});
    $('#VmRepairVmCompanyId').select2({});


    $('#VmRepairSpentTimeDay').select2({
        minimumResultsForSearch: -1
    });
    $('#VmRepairSpentTimeHour').select2({
        minimumResultsForSearch: -1
    });
    $('#VmRepairSpentTimeMinute').select2({
        minimumResultsForSearch: -1
    });



    $('#addNewRepair').click(function(e) {
        $('#addNewRepairForm').show();
        $(this).hide();

    })

    $('#addNewRepairFormClose').click(function() {
        $('#addNewRepairForm').hide();
        $('#addNewRepair').show();
    })
</script>