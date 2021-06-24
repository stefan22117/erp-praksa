<ul class="breadcrumbs">
    <li><?php echo $this->Html->link(__('Početna'), array('controller' => 'pages', 'action' => 'display')); ?></li>
    <li class="last"><a href="" onclick="return false"><?php echo __('Registracije'); ?></a></li>
</ul>
<div id='alert'><?php echo $this->Session->flash(); ?></div>


<div class="name_add_search">
    <div class="name_of_page">
        <h3><i class="icon-tag" style="color:green;"></i> <?php echo __('Registracije'); ?></h3>
    </div>
    <div style="float:right; margin:20px 24px 0 0;">
        <ul class="button-bar">
            <li class="first">
                <?php echo $this->Html->link('<i class="icon-plus-sign" style="color :#669E00"></i> ' . __('Dodaj novu registraciju'), array('action' => 'save'), array('escape' => false)); ?>
            </li>
        </ul>
    </div>
    <div class="clear"></div>
</div>





<!-- Search -->
<div class="content_data meni">
    <fieldset style="margin-top:0;">
        <legend>Filteri</legend>
        <?php echo $this->Form->create(false, array('type' => 'get', 'action' => 'index')); ?>

        <?php //echo $this->Form->input('keywords', array('label' => 'Pretraga', 'div' => false, 'style' => 'width:220px;', 'placeholder' => __('Unesite reči za pretragu'))); 
        ?>


        <?php echo $this->Form->input('vm_vehicle_id', array('label' => 'Vozilo', 'type' => 'select', 'div' => false, 'hiddenField' => false, 'options' => $vm_vehicles, 'empty' => 'Sva vozila')); ?>
        <?php echo $this->Form->input('hr_worker_id', array('label' => 'Radnik', 'type' => 'select', 'div' => false, 'hiddenField' => false, 'options' => $hr_workers, 'empty' => 'Svi radnici')); ?>
        <?php echo $this->Form->input('vm_company_id', array('label' => 'Firma', 'type' => 'select', 'div' => false, 'hiddenField' => false, 'options' => $vm_companies, 'empty' => 'Sve firme')); ?>


        <?php echo $this->Form->input('valid', array('label' => 'Važeća', 'type' => 'checkbox', 'div' => false, 'hiddenField' => false, 'value' => 'on')); ?>






        <?php echo $this->Form->button('Prikaži', array('type' => 'submit', 'class' => 'small green right', 'style' => 'margin-left:10px;')); ?>
        <?php echo $this->Form->end(); ?>
    </fieldset>
</div>


<div class="content_data">
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
                <th><?php echo __('Firma'); ?></th>
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
                                    'controller' => 'VmVehicles',
                                    'action' => 'view',
                                    $vm_registration['VmVehicle']['id']
                                )
                            )
                            ?>
                        </td>

                        <td>
                            <?php
                            echo $this->Html->link(
                                $vm_registration['VmCompany']['name'],
                                array(
                                    'controller' => 'VmCompanies',
                                    'action' => 'view',
                                    $vm_registration['VmCompany']['id']
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
                                        array('action' => 'view', $vm_registration['VmRegistration']['id']),
                                        array('escape' => false, 'title' => __('Detalji'))
                                    );
                                    ?>
                                </li>
                                <li>
                                    <?php
                                    echo $this->Html->link(
                                        '<i class="icon-edit" style="color:orange;"></i>',
                                        array('action' => 'save', $vm_registration['VmRegistration']['id']),
                                        array('escape' => false, 'title' => __('Izmena'))
                                    );
                                    ?>
                                </li>
                                <li class="last">
                                    <?php
                                    echo $this->Form->postLink(
                                        '<i class="icon-trash" style="color:red;"></i>',
                                        array('action' => 'delete', $vm_registration['VmRegistration']['id']),
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

<?php echo $this->element('paginator'); ?>



<script>
    $('#VmDamageVmVehicleId').select2({});

    $('#vm_vehicle_id').select2();
    $('#hr_worker_id').select2();
    $('#vm_company_id').select2();
</script>