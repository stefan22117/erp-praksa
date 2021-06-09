<ul class="breadcrumbs">
    <li><?= $this->Html->link('Home', array('controller' => 'pages', 'action' => 'display')); ?></li>
    <li><?= $this->Html->link('Companies', array('controller' => 'vmcompanies', 'action' => 'index')); ?></li>
    <li class="last"><a href="" onclick="return false">View</a></li>
</ul>

<h2><?= $vm_company['VmCompany']['name'] ?></h2>


<table>
    <tr>
        <td>
            <?= $vm_company['VmCompany']['name'] ?>
        </td>
        <td>
            <?= $vm_company['VmCompany']['address'] ?>
        </td>
        <td>
            <?= $vm_company['VmCompany']['city'] ?>
        </td>
        <td>
            <?= $vm_company['VmCompany']['email'] ?>
        </td>
        <td>
            <?= $vm_company['VmCompany']['zip_code'] ?>
        </td>

    </tr>
</table>


<ul class="tabs left">
    <li><a href="#tabr1">Registrations</a></li>
    <li><a href="#tabr2">Repairs</a></li>
    <li><a href="#tabr3">Maintenances</a></li>
</ul>


<div id="tabr1" class="tab-content">
    <?php
    if (isset($vm_company['VmRegistration']) && count($vm_company['VmRegistration']) > 0) {
    ?>

        <table>

            <tr>

            </tr>

            <tr>
                <th>
                    Registration date
                </th>
                <th>
                    Expitarion date
                </th>
            </tr>

            <?php

            foreach ($vm_company['VmRegistration'] as $registration) {
            ?>
                <tr>
                    <td><?= $registration['registration_date'] ?></td>
                    <td><?= $registration['expiration_date'] ?></td>
                    <!-- <td><?= $registration['VmHrWorker']['first_name'] ?></td> -->

                    <td>
                        <!-- <?= $this->Html->link(
                                    'View',
                                    [
                                        'controller' => 'vmregistrations',
                                        'action' => 'view', $registration['id']
                                    ]
                                ) ?> -->

                        <ul class="button-bar">
                            <li class=""><?php echo $this->Js->link('<i class="icon-eye-open"></i>View', array('controller' => 'vmregistrations', 'action' => 'view', $registration['id']), array('update' => '#container', 'buffer' => false, 'escape' => false, 'before' => '$(".submit_loader").show();', 'success' => 'history.pushState(null, "MikroERP - Edit menu", this.url); $(document).attr("title", "MikroERP - Edit menu");')); ?></li>

                        </ul>


                    </td>
                </tr>
            <?php
            }
        } else {

            ?>
            <h2>Nema dodatih registracija</h2>


        <?php
        }
        ?>
        </table>
</div>

<div id="tabr2" class="tab-content">

    repairs2

    <?=
    $this->Html->link('Add repair', ['controller' => 'vmmaintenances', 'action' => 'addByCompany', $vm_company['VmCompany']['id']])

    ?>
    <h4>Pogledaj ovo iznad /addByCompany</h4>



    <?php
    if (isset($vm_repairs) && count($vm_repairs) > 0) {
    ?>

        <table>

            <tr>
                <th>
                    Amount
                </th>
                <th>
                    Description
                </th>
                <th>
                    Date
                </th>
                <th>
                    Vehicle
                </th>
                <th>
                    Spent time
                </th>
            </tr>


            <?php

            foreach ($vm_repairs as $vm_repair) {
            ?>
                <tr>
                    <td><?= $vm_repair['VmRepair']['amount'] ?></td>
                    <td><?= $vm_repair['VmRepair']['description'] ?></td>
                    <td><?= 'datum koji cu dodati u vm_repairs' ?></td>
                    <td><?= $vm_repair['VmRepair']['spent_time'] ?></td>
                    <!-- <td><?= $vm_repair['VmCrossedKm']['date'] ?></td> -->


                    <td>
                        <ul class="button-bar">
                            <?php
                            if (isset($vm_repair['VmVehicle']) && isset($vm_repair['VmVehicle']['brand_and_model'])) {
                            ?>
                                <li class="first"><?php echo $this->Js->link('<i class="icon-eye-open"></i>' . $vm_repair['VmVehicle']['brand_and_model'], array('controller' => 'vmcompanies', 'action' => 'view', $vm_repair['VmVehicle']['id']), array('update' => '#container', 'buffer' => false, 'escape' => false, 'before' => '$(".submit_loader").show();', 'success' => 'history.pushState(null, "MikroERP - Edit menu", this.url); $(document).attr("title", "MikroERP - Edit menu");')); ?></li>
                            <?php
                            } else {
                            ?>

                                <li class="first"><?php echo $this->Js->link('<i class="icon-eye-close"></i>' . 'UNKNOWN', array('controller' => 'vmcompanies', 'action' => 'view', $vm_repair['VmVehicle']['id'], 'disabled'=>true), array('update' => '#container', 'buffer' => false, 'escape' => false, 'before' => 'return false;$(".submit_loader").show();', 'success' => 'history.pushState(null, "MikroERP - Edit menu", this.url); $(document).attr("title", "MikroERP - Edit menu");')); ?></li>
                            <?php
                            }
                            ?>
                        </ul>
                    </td>

                    <td>
                        <!-- bukv samo treba da pokazem amount description i datum, nema potrebe da imam view za maintenances -->
                        <ul class="button-bar">
                            <li class="first"><?php echo $this->Js->link('<i class="icon-eye-open"></i>View', array('controller' => 'vmmaintenances', 'action' => 'view', $vm_repair['VmMaintenance']['id']), array('update' => '#container', 'buffer' => false, 'escape' => false, 'before' => '$(".submit_loader").show();', 'success' => 'history.pushState(null, "MikroERP - Edit menu", this.url); $(document).attr("title", "MikroERP - Edit menu");')); ?></li>
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
        <h2>Niste uneli popravku vozila</h2>

    <?php
    }

    var_dump($vm_repairs);
    ?>


</div>

<div id="tabr3" class="tab-content">



    <?=
    $this->Html->link('Add maintenance', ['controller' => 'vmmaintenances', 'action' => 'addByCompany', $vm_company['VmCompany']['id']])

    ?>



    <?php
    if (isset($vm_maintenances) && count($vm_maintenances) > 0) {
    ?>

        <table>

            <tr>
                <th>
                    Amount
                </th>
                <th>
                    Description
                </th>
                <th>
                    Date
                </th>
                <th>
                    Vehicle
                </th>
            </tr>


            <?php

            foreach ($vm_maintenances as $vm_maintenance) {
            ?>
                <tr>
                    <td><?= $vm_maintenance['VmMaintenance']['amount'] ?></td>
                    <td><?= $vm_maintenance['VmMaintenance']['description'] ?></td>
                    <!-- <td><?= $vm_maintenance['VmCrossedKm']['date'] ?></td> -->
                    <td><?= 'datum koji cu dodati u vm_maintenances' ?></td>


                    <td>
                        <ul class="button-bar">
                            <li class="first"><?php echo $this->Js->link('<i class="icon-eye-open"></i>' . $vm_maintenance['VmVehicle']['brand_and_model'], array('controller' => 'vmcompanies', 'action' => 'view', $vm_maintenance['VmCompany']['id']), array('update' => '#container', 'buffer' => false, 'escape' => false, 'before' => '$(".submit_loader").show();', 'success' => 'history.pushState(null, "MikroERP - Edit menu", this.url); $(document).attr("title", "MikroERP - Edit menu");')); ?></li>
                        </ul>
                    </td>

                    <td>
                        <!-- bukv samo treba da pokazem amount description i datum, nema potrebe da imam view za maintenances -->
                        <ul class="button-bar">
                            <li class="first"><?php echo $this->Js->link('<i class="icon-eye-open"></i>View', array('controller' => 'vmmaintenances', 'action' => 'view', $vm_maintenance['VmMaintenance']['id']), array('update' => '#container', 'buffer' => false, 'escape' => false, 'before' => '$(".submit_loader").show();', 'success' => 'history.pushState(null, "MikroERP - Edit menu", this.url); $(document).attr("title", "MikroERP - Edit menu");')); ?></li>
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
        <h2>Niste ostetili vozilo</h2>

    <?php
    }
    ?>


</div>