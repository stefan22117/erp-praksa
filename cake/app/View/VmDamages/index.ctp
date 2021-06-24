<ul class="breadcrumbs">
    <li><?php echo $this->Html->link(__('Početna'), array('controller' => 'pages', 'action' => 'display')); ?></li>
    <li class="last"><a href="" onclick="return false"><?php echo __('Štete'); ?></a></li>
</ul>
<div id='alert'><?php echo $this->Session->flash(); ?></div>


<div class="name_add_search">
    <div class="name_of_page">
        <h3><i class="icon-fire" style="color:red;"></i> <?php echo __('Štete'); ?></h3>
    </div>
    <div style="float:right; margin:20px 24px 0 0;">
        <ul class="button-bar">
            <li class="first">
                <?php echo $this->Html->link('<i class="icon-plus-sign" style="color :#669E00"></i> ' . __('Dodaj novu štetu'), array('action' => 'save'), array('escape' => false)); ?>
            </li>
        </ul>
    </div>
    <div class="clear"></div>
</div>





<!-- Search -->
<div class="content_data meni">
    <fieldset style="margin-top:0;">
        <legend>Filteri</legend>
        <?php echo $this->Form->create('VmDamage', array('novalidate'=>true, 'type' => 'get', 'action' => 'index')); ?>

        <?php echo $this->Form->input('keywords', array('label' => 'Pretraga', 'div' => false, 'style' => 'width:220px;', 'placeholder' => __('Unesite reči za pretragu'))); ?>


        <?php echo $this->Form->input('vm_vehicle_id', array('label' => 'Vozilo', 'type' => 'select', 'div' => false, 'hiddenField' => false, 'options' => $vm_vehicles, 'empty' => 'Sva vozila')); ?>


        <?php echo $this->Form->input('repaired', array('label' => 'Popravljena', 'type' => 'checkbox', 'div' => false, 'hiddenField' => false, 'value' => 'on')); ?>





        <?php echo $this->Form->button('Prikaži', array('type' => 'submit', 'class' => 'small green right', 'style' => 'margin-left:10px;')); ?>
        <?php echo $this->Form->end(); ?>
    </fieldset>
</div>


<div class="content_data">
    <?php if (empty($vm_damages)) : ?>
        <div class="notice warning">
            <i class="icon-warning-sign icon-large"></i>
            <?php echo __('Nema dodatih popravki'); ?>
            <a class="icon-remove" href="#close"></a>
        </div>
    <?php else : ?>

        <table style="table-layout: fixed;">

            <thead>
                <th><?php echo __('Odgovoran'); ?></th>
                <th><?php echo __('Opis'); ?></th>
                <th><?php echo __('Datum nastanka štete'); ?></th>
                <th><?php echo __('Popravljena'); ?></th>
                <th><?php echo __('Vozilo'); ?></th>
                <th></th>
            </thead>
            <tbody>
                <?php foreach ($vm_damages as $vm_damage) : ?>
                    <tr>
                        <td><?php echo $vm_damage['VmDamage']['responsible']; ?></td>
                        <td><?php echo $vm_damage['VmDamage']['description']; ?></td>

                        <td><?php echo $vm_damage['VmDamage']['date']; ?></td>

                        <?php if ($vm_damage['VmDamage']['repaired']) : ?>
                            <td style="background-color: #eeffee;">
                                <?php echo __('Popravljena'); ?>
                            </td>
                        <?php else : ?>
                            <td style="background-color: #feffee;">
                                <?php echo __('U kvaru'); ?>
                            </td>

                        <?php endif; ?>
                        <td><?php echo $this->Html->link($vm_damage['VmVehicle']['brand_and_model'],
                        array('controller'=>'vmVehicles', 'action'=>'view', $vm_damage['VmVehicle']['id']));
                        ?>
                        </td>
                        <td>
                            <ul class="button-bar">
                                <li class="first">
                                    <?php
                                    echo $this->Html->link(
                                        '<i class="icon-eye-open" style="color:blue;"></i>',
                                        array('action' => 'view', $vm_damage['VmDamage']['id']),
                                        array('escape' => false, 'title' => __('Detalji'))
                                    );
                                    ?>
                                </li>
                                <li>
                                    <?php
                                    echo $this->Html->link(
                                        '<i class="icon-edit" style="color:orange;"></i>',
                                        array('action' => 'save', $vm_damage['VmDamage']['id']),
                                        array('escape' => false, 'title' => __('Izmena'))
                                    );
                                    ?>
                                </li>
                                <li class="last">
                                    <?php
                                    echo $this->Html->link(
                                        '<i class="icon-trash" style="color:red;"></i>',
                                        array('action' => 'delete', $vm_damage['VmDamage']['id']),
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

<?php echo $this->element('paginator'); ?>



<script>
    $('#VmDamageVmVehicleId').select2({});
</script>