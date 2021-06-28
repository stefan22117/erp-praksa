<ul class="breadcrumbs">
    <li><?php echo $this->Html->link(__('Početna'), array('controller' => 'pages', 'action' => 'display')); ?></li>
    <li class="last"><a href="" onclick="return false"><?php echo __('Fajlovi vozila'); ?></a></li>
</ul>
<div id='alert'><?php echo $this->Session->flash(); ?></div>


<div class="name_add_search">
    <div class="name_of_page">
        <h3><i class="icon-file" style="color:darkblue;"></i> <?php echo __('Fajlovi'); ?></h3>
    </div>
    <div style="float:right; margin:20px 24px 0 0;">
        <ul class="button-bar">
            <li class="first">
                <?php echo $this->Html->link('<i class="icon-plus-sign" style="color :#669E00"></i> ' . __('Dodaj novi fajl'), array('action' => 'add'), array('escape' => false)); ?>
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

        <?php echo $this->Form->input('keywords', array('label' => 'Pretraga', 'div' => false, 'style' => 'width:220px;', 'placeholder' => __('Unesite reči za pretragu'))); ?>


        <?php echo $this->Form->input('vm_vehicle_id', array('label' => 'Vozilo', 'type' => 'select', 'div' => false, 'hiddenField' => false, 'options' => $vm_vehicles, 'empty' => 'Sva vozila')); ?>

        <?php echo $this->Form->button('Prikaži', array('type' => 'submit', 'class' => 'small green right', 'style' => 'margin-left:10px;')); ?>
        <?php echo $this->Form->end(); ?>
    </fieldset>
</div>

<div class="content_data">






    <?php if (empty($vm_vehicle_files)) : ?>
        <div class="notice warning">
            <i class="icon-warning-sign icon-large"></i>
            <?php echo __('Nema dodatih fajlova'); ?>
            <a class="icon-remove" href="#close"></a>
        </div>
    <?php else : ?>

        <table style="table-layout: fixed;">

            <thead>
                <th><?php echo __('Naslov'); ?></th>
                <th><?php echo __('Vreme unosa'); ?></th>
                <th><?php echo __('Vozilo'); ?></th>
                <th></th>
            </thead>
            <tbody>
                <?php foreach ($vm_vehicle_files as $vm_vehicle_file) : ?>
                    <tr>
                        <td><?php echo $vm_vehicle_file['VmVehicleFile']['title']; ?></td>
                        <td><?php echo $vm_vehicle_file['VmVehicleFile']['created']; ?></td>
                        <td><?php echo $this->Html->link(
                                $vm_vehicle_file['VmVehicle']['brand_and_model'],
                                array('controller' => 'vmVehicles', 'action' => 'view', $vm_vehicle_file['VmVehicle']['id'])
                            );
                            ?>
                        </td>

                        <td>
                        <ul class="button-bar">
                                <li class="first">
                                    <?php echo $this->Html->link(
                                        '<i class="icon-eye-open" style="color :blue"></i>',
                                        array('controller' => 'vmVehicleFiles', 'action' => 'view', $vm_vehicle_file['VmVehicleFile']['id']),
                                        array('title' => __('Detalji'), 'escape' => false)
                                    ); ?>
                                </li>
                                <li>
                                    <?php echo $this->Html->link(
                                        '<i class="icon-download-alt" style="color :brown"></i>',
                                        array('controller' => 'vmVehicleFiles', 'action' => 'download', $vm_vehicle_file['VmVehicleFile']['id']),
                                        array('title' => __('Preuzimanje'), 'escape' => false)
                                    ); ?>
                                </li>
                                <li class="last">
                                    <?php echo $this->Form->postLink(
                                        '<i class="icon-trash" style="color :red"></i>',
                                        array('controller' => 'vmVehicleFiles', 'action' => 'delete', $vm_vehicle_file['VmVehicleFile']['id']),
                                        array(
                                            'title' => __('Brisanje'),
                                            'confirm' => 'Da li ste sigurni da želite da izbrišete fajl vozila?',
                                            'escape' => false
                                        )
                                    ); ?>
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
</script>