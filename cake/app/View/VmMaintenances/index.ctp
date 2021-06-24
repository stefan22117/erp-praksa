<ul class="breadcrumbs">
    <li><?php echo $this->Html->link(__('Početna'), array('controller' => 'pages', 'action' => 'display')); ?></li>
    <li class="last"><a href="" onclick="return false"><?php echo __('Održavanja'); ?></a></li>
</ul>
<div id='alert'><?php echo $this->Session->flash(); ?></div>


<div class="name_add_search">
    <div class="name_of_page">
        <h3><i class="icon-tint" style="color :orange;"></i> <?php echo __('Održavanja'); ?></h3>
    </div>
    <div style="float:right; margin:20px 24px 0 0;">
        <ul class="button-bar">
            <li class="first">
                <?php echo $this->Html->link('<i class="icon-plus-sign" style="color :#669E00"></i> ' . __('Dodaj novo održavanje'), array('action' => 'save'), array('escape' => false)); ?>
            </li>
        </ul>
    </div>
    <div class="clear"></div>
</div>






<!-- Search -->
<div class="content_data meni">
    <fieldset style="margin-top:0;">
        <legend>Filteri</legend>
        <?php echo $this->Form->create(false, array('type' => 'get', 'url' => array('controller' => 'vmFuels', 'action' => 'index'), 'novalidate' => true)); ?>



        <?php echo $this->Form->input('vm_vehicle_id', array('label' => 'Vozilo', 'type' => 'select', 'div' => false, 'hiddenField' => false, 'options' => $vm_vehicles, 'empty' => 'Sva vozila')); ?>


        <?php echo $this->Form->input('hr_worker_id', array('label' => 'Radnik', 'type' => 'select', 'div' => false, 'hiddenField' => false, 'options' => $hr_workers, 'empty' => 'Svi radnici')); ?>


        <?php echo $this->Form->button('Prikaži', array('type' => 'submit', 'class' => 'small green right', 'style' => 'margin-left:10px;')); ?>
        <?php echo $this->Form->end(); ?>
    </fieldset>
</div>

<div class="content_data">






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
                <th><?php echo __('Vozilo'); ?></th>
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
                                $vm_maintenance['VmVehicle']['brand_and_model'],
                                array(
                                    'controller' => 'VmVehicles',
                                    'action' => 'view',
                                    $vm_maintenance['VmVehicle']['id']
                                )
                            ); ?>
                        </td>
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
                                    <?php echo $this->Form->postLink('<i class="icon-trash" style="color :red"></i>', array('controller' => 'vmMaintenances', 'action' => 'delete', $vm_maintenance['VmMaintenance']['id']), array('title' => __('Brisanje'), 'escape' => false, 'confirm' => 'Da li ste sigurni da želite da izbrišete ovo održavanje?')); ?>
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