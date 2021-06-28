<ul class="breadcrumbs">
    <li><?php echo $this->Html->link(__('Početna'), array('controller' => 'pages', 'action' => 'display')); ?></li>
    <li class="last"><a href="" onclick="return false"><?php echo __('Registracioni fajlovi'); ?></a></li>
</ul>
<div id='alert'><?php echo $this->Session->flash(); ?></div>


<div class="name_add_search">
    <div class="name_of_page">
        <h3><i class="icon-tag" style="color:green;"></i> <i class="icon-file" style="color:darkblue;"></i> <?php echo __('Registracioni fajlovi'); ?></h3>
    </div>
    <div style="float:right; margin:20px 24px 0 0;">
        <ul class="button-bar">
            <li class="first">
                <?php echo $this->Html->link('<i class="icon-plus-sign" style="color :#669E00"></i> ' . __('Dodaj novi registracioni fajl'), array('action' => 'add'), array('escape' => false)); ?>
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






    <?php if (empty($vm_registration_files)) : ?>
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
                <?php foreach ($vm_registration_files as $vm_registration_file) : ?>
                    <tr>
                        <td><?php echo $vm_registration_file['VmRegistrationFile']['title']; ?></td>
                        <td><?php echo $vm_registration_file['VmRegistrationFile']['created']; ?></td>
                        <td><?php echo
                            !empty($vm_registration_file['VmRegistration']['VmVehicle']['brand_and_model']) &&
                                !empty($vm_registration_file['VmRegistration']['VmVehicle']['id']) ?
                                $this->Html->link(
                                    $vm_registration_file['VmRegistration']['VmVehicle']['brand_and_model'],
                                    array('controller' => 'vmVehicles', 'action' => 'view', $vm_registration_file['VmRegistration']['VmVehicle']['id'])
                                ) : null;
                            ?>
                        </td>

                        <td>
                            <ul class="button-bar">
                                <li class="first">
                                    <?php echo $this->Html->link(
                                        '<i class="icon-eye-open" style="color :blue"></i>',
                                        array('controller' => 'vmRegistrationFiles', 'action' => 'view', $vm_registration_file['VmRegistrationFile']['id']),
                                        array('title' => __('Detalji'), 'escape' => false)
                                    ); ?>
                                </li>
                                <li>
                                    <?php echo $this->Html->link(
                                        '<i class="icon-download-alt" style="color :brown"></i>',
                                        array('controller' => 'vmRegistrationFiles', 'action' => 'download', $vm_registration_file['VmRegistrationFile']['id']),
                                        array('title' => __('Preuzimanje'), 'escape' => false)
                                    ); ?>
                                </li>
                                <li class="last">
                                    <?php echo $this->Form->postLink(
                                        '<i class="icon-trash" style="color :red"></i>',
                                        array('controller' => 'vmRegistrationFiles', 'action' => 'delete', $vm_registration_file['VmRegistrationFile']['id']),
                                        array(
                                            'title' => __('Brisanje'),
                                            'confirm' => 'Da li ste sigurni da želite da izbrišete registracioni fajl?',
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























































































<hr>
<hr>
<hr>
<hr>
<hr>








<ul class="breadcrumbs">
    <li><?= $this->Html->link('Home', array('controller' => 'pages', 'action' => 'display')); ?></li>
    <li class="last"><a href="" onclick="return false">Registration files</a></li>
</ul>

<h2>Registration files da li odvojiti u grupe po registracijama?</h2>
<?php
if (isset($vm_registration_files) && count($vm_registration_files) > 0) {
?>
    <table>
        <tr>
            <th>title</th>
            <th>registration_date</th>
            <th>See registration</th>
            <th>See company</th>
            <th>See Vehicle</th>
        </tr>

        <?php
        foreach ($vm_registration_files as $vm_registration_file) {
        ?>
            <tr>
                <td><?= $vm_registration_file['VmRegistrationFile']['title'] ?></td>
                <td><?= $vm_registration_file['VmRegistration']['registration_date'] ?></td>

                <td>
                    <ul class="button-bar">
                        <li class="first"><?php echo $this->Js->link('<i class="icon-eye-open"></i> See registration', array('controller' => 'vmregistrations', 'action' => 'view', $vm_registration_file['VmRegistration']['id']), array('update' => '#container', 'buffer' => false, 'escape' => false, 'before' => '$(".submit_loader").show();', 'success' => 'history.pushState(null, "MikroERP - Edit menu", this.url); $(document).attr("title", "MikroERP - Edit menu");')); ?></li>
                    </ul>
                </td>


                <td>
                    <ul class="button-bar">
                        <li class="first"><?php echo $this->Js->link('<i class="icon-eye-open"></i>' . $vm_registration_file['VmCompany']['name'], array('controller' => 'vmcompanies', 'action' => 'view', $vm_registration_file['VmCompany']['id']), array('update' => '#container', 'buffer' => false, 'escape' => false, 'before' => '$(".submit_loader").show();', 'success' => 'history.pushState(null, "MikroERP - Edit menu", this.url); $(document).attr("title", "MikroERP - Edit menu");')); ?></li>
                    </ul>
                </td>

                <td>
                    <ul class="button-bar">
                        <li class="first"><?php echo $this->Js->link('<i class="icon-eye-open"></i>' . $vm_registration_file['VmVehicle']['brand_and_model'], array('controller' => 'vmvehicles', 'action' => 'view', $vm_registration_file['VmVehicle']['id']), array('update' => '#container', 'buffer' => false, 'escape' => false, 'before' => '$(".submit_loader").show();', 'success' => 'history.pushState(null, "MikroERP - Edit menu", this.url); $(document).attr("title", "MikroERP - Edit menu");')); ?></li>
                    </ul>
                </td>





            </tr>

        <?php
        }
        ?>

    </table>
<?php
} else {
?>

<?php
}
?>



<?php
var_dump($vm_registration_files);
?>