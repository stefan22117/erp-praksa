<ul class="breadcrumbs">
    <li><?php echo $this->Html->link(__('Početna'), array('controller' => 'pages', 'action' => 'display')); ?></li>
    <li class="last"><a href="" onclick="return false"><?php echo __('Beleške promena'); ?></a></li>
</ul>
<div id='alert'><?php echo $this->Session->flash(); ?></div>

<div class="name_add_search">
    <div class="name_of_page">
        <h3><i class="icon-book" style="color:blueviolet"></i> <?php echo __('Beleške promena'); ?></h3>
    </div>
    <div class="clear"></div>
</div>


<!-- Search -->
<div class="content_data meni">
    <fieldset style="margin-top:0;">
        <legend>Filteri</legend>
        <?php echo $this->Form->create('VmChangeLog', array('type' => 'get', 'action' => 'index')); ?>

        <?php echo $this->Form->input('vm_vehicle_id', array('label' => 'Vozila', 'type' => 'select', 'div' => false, 'hiddenField' => false, 'options' => $vm_vehicles, 'empty' => 'Sva vozila')); ?>

        <?php echo $this->Form->input('table_id', array('label' => 'Tabela', 'type' => 'select', 'div' => false, 'hiddenField' => false, 'options' => $tables, 'empty' => 'Sve tabele')); ?>


        <?php echo $this->Form->input('action', array('label' => 'Tabela', 'type' => 'radio', 'legend'=>false, 'div' => false, 'hiddenField' => false, 'options' => $actions)); ?>



        <?php echo $this->Form->button('Prikaži', array('type' => 'submit', 'class' => 'small green right', 'style' => 'margin-left:10px;')); ?>
        <?php echo $this->Form->end(); ?>
    </fieldset>
</div>


<!-- Vehicle content -->
<div class="content_data">
    <?php if (empty($vm_change_logs)) : ?>
        <div class="notice warning">
            <i class="icon-warning-sign icon-large"></i>
            <?php echo __('Nema dodatih beleški promena'); ?>
            <a class="icon-remove" href="#close"></a>
        </div>
    <?php else : ?>

        <table style="table-layout: fixed;" cellspacing="0" cellpadding="0">
            <thead>
                <tr>

                    <th><?php echo __('Opis'); ?></th>
                    <th><?php echo __('Vozilo'); ?></th>

                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($vm_change_logs as $vm_change_log) : ?>
                    <tr>
                        <td><?php echo $vm_change_log['VmChangeLog']['description']; ?></td>
                        <td>
                            <?php
                            echo
                            !empty($vm_change_log['VmVehicle']['brand_and_model']) &&
                                !empty($vm_change_log['VmVehicle']['id']) ?
                                $this->Html->link(
                                    $vm_change_log['VmVehicle']['brand_and_model'],
                                    array(
                                        'controller' => 'VmVehicles',
                                        'action' => 'view',
                                        $vm_change_log['VmVehicle']['id']
                                    )
                                ) : null;
                            ?>
                        </td>

                        <td class="right" style="white-space: nowrap;">
                            <ul class="button-bar">

                                <li class="last">
                                    <?php echo $this->Form->postLink('<i class="icon-trash" style="color :#B21203"></i>', array('action' => 'delete', $vm_change_log['VmChangeLog']['id']), array('title' => __('Brisanje'), 'confirm' => __('Da li ste sigurni da želite da izbrišete vozilo belešku promene?'), 'escape' => false)); ?>
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
    $('#VmChangeLogVmVehicleId').select2();
    $('#VmChangeLogTableId').select2();
</script>