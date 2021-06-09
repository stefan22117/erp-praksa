<h1>
    Registracije <?= $vm_registration['VmVehicle']['brand_and_model'] ?>
</h1>
<table>
    <tr>
        <td>registration_date</td>
        <td><?= $vm_registration['VmRegistration']['registration_date'] ?></td>
    </tr>
    <tr>
        <td>spent_time</td>
        <td><?= $vm_registration['VmRegistration']['spent_time'] ?></td>
    </tr>
    <tr>
        <td>hr_worker</td>
        <td><?= $vm_registration['HrWorker']['first_name'] ?></td>
    </tr>
    <tr>
        <td>expiration_date</td>
        <td><?= $vm_registration['VmRegistration']['expiration_date'] ?></td>
    </tr>
    <tr>
        <td>amount</td>
        <td><?= $vm_registration['VmRegistration']['amount'] ?></td>
    </tr>
    <tr>
        <td>vm_company</td>
        <td><?= $vm_registration['VmCompany']['name'] ?></td>
    </tr>
    <tr>
        <td>status</td>
        <td><?= strtotime($vm_registration['VmRegistration']['expiration_date']) > time() ? 'ACTIVE' : 'EXPIRED' ?></td>
    </tr>
</table>



<?php
echo $this->Form->create('VmRegistrationFile', ['type' => 'file', 'url' => ['controller' => 'vmregistrationfiles', 'action' => 'add', $vm_registration['VmRegistration']['id']]]);
echo $this->Form->input('title');
echo $this->Form->input('file', ['type' => 'file']);
echo $this->Form->hidden('vm_registration_id', ['value' => $vm_registration['VmRegistration']['id']]);
echo $this->Form->end('dodaj reg. dokument');





if (isset($vm_registration['VmRegistrationFile']) && count($vm_registration['VmRegistrationFile']) > 0) {
?>
    <h2>Documents</h2>
    
    <!-- <div class="gallery">

    <?php
    foreach ($vm_registration['VmRegistrationFile'] as $vm_registration_file) {
    ?>
<div class="col_3">
    <?= $this->Form->postLink('<i class="icon-remove"></i>', ['controller' => 'vmregistrationfiles', 'action' => 'delete', $vm_registration_file['id'], $vm_registration['VmVehicle']['id']], ['escape' => false, 'confirm' => 'Are you sure you want to delete this doc?']) ?>

    <h3><?= $vm_registration_file['title'] ?></h3>
    <?= $this->Html->image($vm_registration_file['path'], ['alt' => $vm_registration_file['title'], 'width' => "100%", 'height' => "100%"]); ?>
</div>
<div class="col_12">

<embed src="/app/webroot/img/registration_files/1622881962.pdf"  type="application/pdf" width="100%" height="100%">
<embed src="/img/registration_files/1622881962.pdf"  type="application/pdf" width="100%" height="100%">
</div>
<?php
    }
?>

</div> -->

    <?php
    if (isset($vm_registration_files) && count($vm_registration_files) > 0) {
    ?>
        <table>
            <tr>
                <th>title</th>
                <th>registration_date</th>
                <!-- <th>See registration</th> -->
                <!-- <th>See document</th> -->
                <th>See company</th>
                <th>See vehicle</th>
            </tr>
            
            <?php
            foreach ($vm_registration_files as $vm_registration_file) {
            ?>
                <tr>
                    <td>
                        <ul class="button-bar">
                            <li class="first"><?php echo $this->Js->link('<i class="icon-eye-open"></i>' . $vm_registration_file['VmRegistrationFile']['title'], array('controller' => 'vmregistrationfiles', 'action' => 'view', $vm_registration_file['VmRegistration']['id']), array('update' => '#container', 'buffer' => false, 'escape' => false, 'before' => '$(".submit_loader").show();', 'success' => 'history.pushState(null, "MikroERP - Edit menu", this.url); $(document).attr("title", "MikroERP - Edit menu");')); ?></li>
                        </ul>


                    </td>
                    <td><?= $vm_registration_file['VmRegistration']['registration_date'] ?></td>



                    <!-- <td>
                        <ul class="button-bar">
                            ako je u pitanju ova, napisi nesto drugo
                            <?php
                            if ($vm_registration_file['VmRegistration']['id'] == $vm_registration['VmRegistration']['id']) {
                            ?>
                                <li class="first"><?php echo $this->Js->link('<i class="icon-eye-close"></i><i>(Same)</i> ', array('controller' => 'vmregistrations', 'action' => 'view', $vm_registration_file['VmRegistration']['id']), array('update' => '#container', 'buffer' => false, 'escape' => false, 'before' => 'return false;$(".submit_loader").show();', 'success' => 'history.pushState(null, "MikroERP - Edit menu", this.url); $(document).attr("title", "MikroERP - Edit menu");')); ?></li>
                            <?php
                            } else {
                            ?>
                                <li class="first"><?php echo $this->Js->link('<i class="icon-eye-open"></i> See registration', array('controller' => 'vmregistrations', 'action' => 'view', $vm_registration_file['VmRegistration']['id']), array('update' => '#container', 'buffer' => false, 'escape' => false, 'before' => '$(".submit_loader").show();', 'success' => 'history.pushState(null, "MikroERP - Edit menu", this.url); $(document).attr("title", "MikroERP - Edit menu");')); ?></li>

                            <?php
                            }
                            ?>
                        </ul>
                    </td> -->


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
        <h3>Nema fajlova za ovu registraciju</h3>
    <?php
    }
    ?>



<?php
} else {
?>


    <h2>Nema dokumenata</h2>




<?php
}
?>