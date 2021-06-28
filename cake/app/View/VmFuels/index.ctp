
<ul class="breadcrumbs">
    <li><?php echo $this->Html->link(__('Početna'), array('controller' => 'pages', 'action' => 'display')); ?></li>
    <li class="last"><a href="" onclick="return false"><?php echo __('Goriva'); ?></a></li>
</ul>
<div id='alert'><?php echo $this->Session->flash(); ?></div>


<div class="name_add_search">
    <div class="name_of_page">
        <h3><i class="icon-dashboard" style="color:yellow;"></i> <?php echo __('Goriva'); ?></h3>
    </div>
    <div style="float:right; margin:20px 24px 0 0;">
        <ul class="button-bar">
            <li class="first">
                <?php echo $this->Html->link('<i class="icon-plus-sign" style="color :#669E00"></i> ' . __('Dodaj novo gorivo'), array('action' => 'save'), array('escape' => false)); ?>
            </li>
        </ul>
    </div>
    <div class="clear"></div>
</div>






<!-- Search -->
<div class="content_data meni">
    <fieldset style="margin-top:0;">
        <legend>Filteri</legend>
        <?php echo $this->Form->create(false, array('type' => 'get', 'novalidate'=>true)); ?>



        <?php echo $this->Form->input('vm_vehicle_id', array('label' => 'Vozilo', 'type' => 'select', 'div' => false, 'hiddenField' => false, 'options' => $vm_vehicles, 'empty' => 'Sva vozila')); ?>


        <?php echo $this->Form->input('hr_worker_id', array('label' => 'Radnik', 'type' => 'select', 'div' => false, 'hiddenField' => false, 'options' => $hr_workers, 'empty' => 'Svi radnici')); ?>


        <?php echo $this->Form->button('Prikaži', array('type' => 'submit', 'class' => 'small green right', 'style' => 'margin-left:10px;')); ?>
        <?php echo $this->Form->end(); ?>
    </fieldset>
</div>

<div class="content_data">






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
                <th><?php echo __('Vozilo'); ?></th>
                <th></th>
            </thead>
            <tbody>
                    <?php foreach ($vm_fuels as $vm_fuel) : ?>
                        <tr>
                            <td><?php echo $vm_fuel['VmFuel']['liters']; ?></td>
                            <td><?php echo $vm_fuel['VmFuel']['amount']; ?></td>
                            <td><?php echo !empty($vm_fuel['VmCrossedKm']['HrWorker']['first_name']) ?
                            $vm_fuel['VmCrossedKm']['HrWorker']['first_name'] : null ; ?></td>
                            <td><?php echo $this->Html->link(
                                    $vm_fuel['VmVehicle']['brand_and_model'],
                                    array('controller' => 'vmVehicles', 'action' => 'view', $vm_fuel['VmVehicle']['id'])
                                );
                                ?>
                            </td>

                            <td>
                                <ul class="button-bar">
                                    <li class="first">
                                        <?php
                                        echo $this->Html->link(
                                            '<i class="icon-eye-open" style="color:blue;"></i>',
                                            array('action' => 'view', $vm_fuel['VmFuel']['id']),
                                            array('escape' => false, 'title' => __('Detalji'))
                                        );
                                        ?>
                                    </li>
                                    <li>
                                        <?php
                                        echo $this->Html->link(
                                            '<i class="icon-edit" style="color:orange;"></i>',
                                            array('action' => 'save', $vm_fuel['VmFuel']['id']),
                                            array('escape' => false, 'title' => __('Izmena'))
                                        );
                                        ?>
                                    </li>
                                    <li class="last">
                                        <?php
                                        echo $this->Form->postLink(
                                            '<i class="icon-trash" style="color:red;"></i>',
                                            array('action' => 'delete', $vm_fuel['VmFuel']['id']),
                                            array('escape' => false,
                                            'title' => __('Brisanje'),
                                            'confirm' =>  __('Da li ste sigurni da želite da izbrišete gorivo?')
                                            )
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
    $('#vm_vehicle_id').select2({});
    $('#hr_worker_id').select2({});
</script>