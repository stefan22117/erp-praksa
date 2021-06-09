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