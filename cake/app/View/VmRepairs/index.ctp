<?php
// print("<pre>". print_r($vm_repairs, true) . "</pre>" );
// die();
?>

<ul class="breadcrumbs">
    <li><?php echo $this->Html->link(__('Početna'), array('controller' => 'pages', 'action' => 'display')); ?></li>
    <li class="last"><a href="" onclick="return false"><?php echo __('Popravke'); ?></a></li>
</ul>
<div id='alert'><?php echo $this->Session->flash(); ?></div>


<div class="name_add_search">
    <div class="name_of_page">
        <h3><i class="icon-cogs" style="color:black;"></i> <?php echo __('Popravke'); ?></h3>
    </div>
    <div style="float:right; margin:20px 24px 0 0;">
        <ul class="button-bar">
            <li class="first">
                <?php echo $this->Html->link('<i class="icon-plus-sign" style="color :#669E00"></i> ' . __('Dodaj novu popravku'), array('action' => 'save'), array('escape' => false)); ?>
            </li>
        </ul>
    </div>
    <div class="clear"></div>
</div>






<!-- Search -->
<div class="content_data meni">
    <fieldset style="margin-top:0;">
        <legend>Filteri</legend>
        <?php echo $this->Form->create(false, array('type' => 'get', 'novalidate' => true)); ?>

        <?php echo $this->Form->input('keywords', array('label' => 'Pretraga', 'div' => false, 'style' => 'width:220px;', 'placeholder' => __('Unesite reči za pretragu')));?>

        <?php echo $this->Form->input('vm_vehicle_id', array('label' => 'Vozilo', 'type' => 'select', 'div' => false, 'hiddenField' => false, 'options' => $vm_vehicles, 'empty' => 'Sva vozila')); ?>


        <?php echo $this->Form->input('hr_worker_id', array('label' => 'Radnik', 'type' => 'select', 'div' => false, 'hiddenField' => false, 'options' => $hr_workers, 'empty' => 'Svi radnici')); ?>

        <?php echo $this->Form->input('vm_company_id', array('label' => 'Firma', 'type' => 'select', 'div' => false, 'hiddenField' => false, 'options' => $vm_companies, 'empty' => 'Sve firme')); ?>


        <?php echo $this->Form->button('Prikaži', array('type' => 'submit', 'class' => 'small green right', 'style' => 'margin-left:10px;')); ?>
        <?php echo $this->Form->end(); ?>
    </fieldset>
</div>

<div class="content_data">






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
                <th><?php echo __('Opis popravke'); ?></th>
                <th><?php echo __('Radnik'); ?></th>
                <th><?php echo __('Vozilo'); ?></th>
                <th><?php echo __('Firma'); ?></th>
                <th></th>
            </thead>
            <tbody>
                <?php foreach ($vm_repairs as $vm_repair) : ?>
                    <tr>
                        <td><?php echo $vm_repair['VmRepair']['amount']; ?></td>
                        
                        <td>
                            <?php echo strlen($vm_repair['VmRepair']['description']) < 15 ?
                                $vm_repair['VmRepair']['description'] :
                                substr($vm_repair['VmRepair']['description'], 0, 10) . '...'
                            ?>
                        </td>







                        <td><?php echo $vm_repair['VmCrossedKm']['HrWorker']['first_name']; ?></td>
                        <td><?php echo
                            !empty($vm_repair['VmDamage']['VmVehicle']['brand_and_model']) && !empty($vm_repair['VmDamage']['VmVehicle']['id']) ?
                                $this->Html->link(
                                    $vm_repair['VmDamage']['VmVehicle']['brand_and_model'],
                                    array(
                                        'controller' => 'vmVehicles',
                                        'action' => 'view',
                                        $vm_repair['VmDamage']['VmVehicle']['id']
                                    )
                                ) : null; ?>
                        </td>
                        <td><?php echo
                            !empty($vm_repair['VmCompany']['name']) && !empty($vm_repair['VmCompany']['id']) ?
                                $this->Html->link(
                                    $vm_repair['VmCompany']['name'],
                                    array(
                                        'controller' => 'vmCompanies',
                                        'action' => 'view',
                                        $vm_repair['VmCompany']['id']
                                    )
                                ) : null; ?>
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
                                    <?php echo $this->Form->postLink('<i class="icon-trash" style="color :red"></i>', array('controller' => 'vmRepairs', 'action' => 'delete', $vm_repair['VmRepair']['id']), array('title' => __('Brisanje'), 'escape' => false, 'confirm' => 'Da li ste sigurni da želite da izbrišete popravku?')); ?>
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
    $('#vm_company_id').select2({});
</script>