<ul class="breadcrumbs">
    <li><?php echo $this->Html->link(__('Početna'), array('controller' => 'pages', 'action' => 'display')); ?></li>
    <li class="last"><a href="" onclick="return false"><?php echo __('Slike'); ?></a></li>
</ul>
<div id='alert'><?php echo $this->Session->flash(); ?></div>


<div class="name_add_search">
    <div class="name_of_page">
        <h3><i class="icon-picture" style="color:cyan;"></i> <?php echo __('Slike'); ?></h3>
    </div>
    <div style="float:right; margin:20px 24px 0 0;">
        <ul class="button-bar">
            <li class="first">
                <?php echo $this->Html->link('<i class="icon-plus-sign" style="color :#669E00"></i> ' . __('Dodaj novu sliku'), array('action' => 'add'), array('escape' => false)); ?>
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

    <?php if (empty($vm_images)) : ?>
        <div class="notice warning">
            <i class="icon-warning-sign icon-large"></i>
            <?php echo __('Nema dodatih slika'); ?>
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
                <?php foreach ($vm_images as $vm_image) : ?>
                    <tr>
                        <td><?php echo $vm_image['VmImage']['title']; ?></td>
                        <td><?php echo $vm_image['VmImage']['created']; ?></td>
                        <td><?php echo $this->Html->link(
                                $vm_image['VmVehicle']['brand_and_model'],
                                array('controller' => 'vmVehicles', 'action' => 'view', $vm_image['VmVehicle']['id'])
                            );
                            ?>
                        </td>

                        <td>
                        <ul class="button-bar">
                                <li class="first">
                                    <?php echo $this->Html->link(
                                        '<i class="icon-eye-open" style="color :blue"></i>',
                                        array('controller' => 'vmImages', 'action' => 'view', $vm_image['VmImage']['id']),
                                        array('title' => __('Detalji'), 'escape' => false)
                                    ); ?>
                                </li>
                                <li>
                                    <?php echo $this->Html->link(
                                        '<i class="icon-download-alt" style="color :brown"></i>',
                                        array('controller' => 'vmImages', 'action' => 'download', $vm_image['VmImage']['id']),
                                        array('title' => __('Preuzimanje'), 'escape' => false)
                                    ); ?>
                                </li>
                                <li class="last">
                                    <?php echo $this->Form->postLink(
                                        '<i class="icon-trash" style="color :red"></i>',
                                        array('controller' => 'vmImages', 'action' => 'delete', $vm_image['VmImage']['id']),
                                        array(
                                            'title' => __('Brisanje'),
                                            'confirm' => 'Da li ste sigurni da želite da izbrišete sliku vozila?',
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