<ul class="breadcrumbs">
    <li><?php echo $this->Html->link(__('Početna'), array('controller' => 'pages', 'action' => 'display')); ?></li>
    <li class="last"><a href="" onclick="return false"><?php echo __('Firme'); ?></a></li>
</ul>
<div id='alert'><?php echo $this->Session->flash(); ?></div>


<div class="name_add_search">
    <div class="name_of_page">
        <h3><i class="icon-building" style="color:green;"></i> <?php echo __('Firme'); ?></h3>
    </div>
    <div style="float:right; margin:20px 24px 0 0;">
        <ul class="button-bar">
            <li class="first">
                <?php echo $this->Html->link('<i class="icon-plus-sign" style="color :#669E00"></i> ' . __('Dodaj novu firmu'), array('action' => 'save'), array('escape' => false)); ?>
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

        <?php echo $this->Form->input('keywords', array('label' => 'Pretraga', 'div' => false, 'style' => 'width:220px;', 'placeholder' => __('Unesite reči za pretragu')));
        ?>

        <?php echo $this->Form->button('Prikaži', array('type' => 'submit', 'class' => 'small green right', 'style' => 'margin-left:10px;')); ?>
        <?php echo $this->Form->end(); ?>
    </fieldset>
</div>

<div class="content_data">
    <?php if (empty($vm_companies)) : ?>
        <div class="notice warning">
            <i class="icon-warning-sign icon-large"></i>
            <?php echo __('Nema dodatih firmi'); ?>
            <a class="icon-remove" href="#close"></a>
        </div>
    <?php else : ?>
        <table style="table-layout: fixed;">

            <thead>
                <th><?php echo __('Naziv'); ?></th>
                <th><?php echo __('Adresa'); ?></th>
                <th><?php echo __('Grad'); ?></th>
                <th></th>
            </thead>
            <tbody>
                <?php foreach ($vm_companies as $vm_company) : ?>
                    <tr>

                        <td><?php echo $vm_company['VmCompany']['name']; ?></td>
                        <td><?php echo $vm_company['VmCompany']['address']; ?></td>
                        <td><?php echo $vm_company['VmCompany']['city']; ?></td>
                        

                        <td>
                            <ul class="button-bar">
                                <li class="first">
                                    <?php
                                    echo $this->Html->link(
                                        '<i class="icon-eye-open" style="color:blue;"></i>',
                                        array('action' => 'view', $vm_company['VmCompany']['id']),
                                        array('escape' => false, 'title' => __('Detalji'))
                                    );
                                    ?>
                                </li>
                                <li>
                                    <?php
                                    echo $this->Html->link(
                                        '<i class="icon-edit" style="color:orange;"></i>',
                                        array('action' => 'save', $vm_company['VmCompany']['id']),
                                        array('escape' => false, 'title' => __('Izmena'))
                                    );
                                    ?>
                                </li>
                                <li class="last">
                                    <?php
                                    echo $this->Form->postLink(
                                        '<i class="icon-trash" style="color:red;"></i>',
                                        array('action' => 'delete', $vm_company['VmCompany']['id']),
                                        array('escape' => false, 'title' => __('Brisanje'), 'confirm' => 'Da li ste sigurni da želite da izbrišete firmu?')
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