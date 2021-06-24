<ul class="breadcrumbs">
    <li><?php echo $this->Html->link(__('Početna'), array('controller' => 'pages', 'action' => 'display')); ?></li>
    <li class="last"><a href="" onclick="return false"><?php echo __('Vozila'); ?></a></li>
</ul>
<div id='alert'><?php echo $this->Session->flash(); ?></div>

<div class="name_add_search">
    <div class="name_of_page">
        <h3><i class="icon-truck"></i> <?php echo __('Vozila'); ?></h3>
    </div>
    <div style="float:right; margin:20px 24px 0 0;">
        <ul class="button-bar">
            <li class="first">
                <?php echo $this->Html->link('<i class="icon-plus-sign" style="color :#669E00"></i> ' . __('Dodaj novo vozilo'), array('action' => 'save'), array('escape' => false)); ?>
            </li>
        </ul>
    </div>
    <div class="clear"></div>
</div>


<!-- Search -->
<div class="content_data meni">
    <fieldset style="margin-top:0;">
        <legend>Filteri</legend>
        <?php echo $this->Form->create('VmVehicle', array('type' => 'get', 'action' => 'index')); ?>
        <?php echo $this->Form->input('keywords', array('label' => 'Pretraga', 'div' => false, 'style' => 'width:220px;', 'placeholder' => __('Unesite reči za pretragu'))); ?>




        <?php echo $this->Form->input('hr_worker_id', array('label' => 'Radnik', 'type' => 'select', 'div' => false, 'hiddenField' => false, 'options' => $hr_workers, 'empty' => 'Svi zaposleni')); ?>



        <?php echo $this->Form->input('in_use', array('label' => 'U upotrebi', 'type' => 'checkbox', 'div' => false, 'hiddenField' => false, 'value' => 'on')); ?>


        <?php echo $this->Form->input('registered', array('label' => 'Registrovana', 'type' => 'checkbox', 'div' => false, 'hiddenField' => false, 'value' => 'on')); ?>



        <?php echo $this->Form->button('Prikaži', array('type' => 'submit', 'class' => 'small green right', 'style' => 'margin-left:10px;')); ?>
        <?php echo $this->Form->end(); ?>
    </fieldset>
</div>


<!-- Vehicle content -->
<div class="content_data">
    <?php if (empty($vm_vehicles)) : ?>
        <div class="notice warning">
            <i class="icon-warning-sign icon-large"></i>
            <?php echo __('Nema dodatih vozila'); ?>
            <a class="icon-remove" href="#close"></a>
        </div>
    <?php else : ?>

        <table style="table-layout: fixed;" cellspacing="0" cellpadding="0">
            <thead>
                <tr>

                    <th><?php echo __('Marka i model'); ?></th>
                    <th><?php echo __('Registarski broj'); ?></th>
                    <th><?php echo __('Datum isteka registracije'); ?></th>
                    <th><?php echo __('Godina proizvodnje'); ?></th>
                    <th><?php echo __('U upotrebi'); ?></th>
                    <th><?php echo __('Boja'); ?></th>

                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($vm_vehicles as $vm_vehicle) : ?>
                    <tr>
                        <td><?php echo $vm_vehicle['VmVehicle']['brand_and_model']; ?></td>
                        <td><?php echo $vm_vehicle['VmVehicle']['reg_number']; ?></td>
                        <td><?php
                            if ($vm_vehicle['VmRegistration'] && count($vm_vehicle['VmRegistration']) > 0) {
                                $registrations = $vm_vehicle['VmRegistration'];

                                usort($registrations, function ($a, $b) {
                                    return
                                        strtotime($a['expiration_date']) < strtotime($b['expiration_date']) ? 1 : -1;
                                });
                                echo $registrations[0]['expiration_date'];
                                if (date("Y-m-d") >= $registrations[0]['expiration_date']) {
                                    echo __('(Istekla)');
                                }
                            } else {
                                echo __('Nije registrovan');
                            }




                            ?>
                        </td>
                        <td><?php echo $vm_vehicle['VmVehicle']['year_of_production']; ?></td>



                        <?php if ($vm_vehicle['VmVehicle']['in_use']) : ?>
                            <td style="background-color: #feffee;">
                            <?php echo __('U upotrebi'); ?>
                            </td>
                        <?php else : ?>
                            <td style="background-color: #eeffee;">
                                <?php echo __('Slobodno'); ?>
                            </td>

                        <?php endif; ?>


                        <td><?php echo $vm_vehicle['VmVehicle']['color']; ?></td>

                        <td class="right" style="white-space: nowrap;">
                            <ul class="button-bar">
                                <li class="first">
                                    <?php echo $this->Html->link('<i class="icon-eye-open" style="color :blue"></i>', array('action' => 'view', $vm_vehicle['VmVehicle']['id']), array('title' => __('Detalji'), 'escape' => false)); ?>
                                </li>
                                <li>
                                    <?php echo $this->Html->link('<i class="icon-edit" style="color :#FC730A"></i>', array('action' => 'save', $vm_vehicle['VmVehicle']['id']), array('title' => __('Izmena'), 'escape' => false)); ?>
                                </li>

                                <li class="last">
                                    <?php echo $this->Form->postLink('<i class="icon-trash" style="color :#B21203"></i>', array('action' => 'delete', $vm_vehicle['VmVehicle']['id']), array('title' => __('Brisanje'), 'confirm' => __('Da li ste sigurni da želite da izbrišete vozilo ' . $vm_vehicle['VmVehicle']['brand_and_model'] . '?'), 'escape' => false)); ?>
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
<div class="clear"></div>


<script>
    $('#VmVehicleHrWorkerId').select2();
</script>


