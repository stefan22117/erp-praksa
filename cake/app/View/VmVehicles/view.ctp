<ul class="breadcrumbs">
    <li><?php echo $this->Html->link(__('Početna'), array('controller' => 'pages', 'action' => 'display')); ?></li>
    <li><?php echo $this->Html->link(__('Vozila'), array('action' => 'index')); ?></li>
    <li class="last"><a href="" onclick="return false"><?php echo __('Detalji'); ?></a></li>
</ul>

<div class="name_of_page">
    <h3><i class="icon-truck"></i> <i class="icon-search"></i> <?php echo __('Detalji vozila: ' . $vm_vehicle['VmVehicle']['brand_and_model']); ?></h3>
</div>




<div style="float:right; margin:20px 24px 0 0;">
        <ul class="button-bar">
            <li class="first">
                <?php
                	echo $this->Html->link(
                		'<i class="icon-edit" style="color:orange;"></i> '.__('Izmena'),
                		array(
                			'action' => 'save',
                            $vm_vehicle['VmVehicle']['id']
                		),
                        array(
                            'escape' => false,
                            'style' => ' font-size: 12px;'
                        )
                	);
                ?>
            </li>
            <li class="last">
                <?php
                	echo $this->Form->postLink(
                		'<i class="icon-trash" style="color:red;"></i> '.__('Brisanje'),
                		array(
                			'action' => 'delete',
                            $vm_vehicle['VmVehicle']['id']
                		),
                		array(
                			'escape' => false,
                			// 'confirm' => 'Da li ste sigurni da želite da obrišete ovaj modul?',
                			'confirm' =>  __('Da li ste sigurni da želite da izbrišete vozilo ' . $vm_vehicle['VmVehicle']['brand_and_model'] . '?'),
                            'style' => ' font-size: 12px;'
                		)
                	);
                ?>
            </li>
        </ul>
    </div>





























































<div class="content_data">
    <table cellspacing="0" cellpadding="0" class="striped tight">
        <tr>
            <th><?php echo __('Marka i model'); ?></th>
            <td><?php echo $vm_vehicle['VmVehicle']['brand_and_model']; ?></td>
        </tr>
        <tr>
            <th><?php echo __('Registarski broj'); ?></th>
            <td><?php echo $vm_vehicle['VmVehicle']['reg_number']; ?></td>

        </tr>
        <tr>

            <th><?php echo __('U upotrebi') ?></th>
            <?php if ($vm_vehicle['VmVehicle']['in_use'] == 1) : ?>
                <td style="background-color: #feffee;">
                    <?php echo __('U upotrebi'); ?>
                </td>
            <?php else : ?>
                <td style="background-color: #eeffee;">
                    <?php echo __('Slobodno'); ?>
                </td>

            <?php endif; ?>
        </tr>

        <tr>

            <th><?php echo __('Aktivno od'); ?></th>
            <td><?php echo $vm_vehicle['VmVehicle']['active_from']; ?></td>
        </tr>

        <tr>

            <th><?php echo __('Aktivno do'); ?></th>
            <td><?php echo $vm_vehicle['VmVehicle']['active_to']; ?></td>
        </tr>

        <tr>

            <th><?php echo __('Konjskih snaga'); ?></th>
            <td><?php echo $vm_vehicle['VmVehicle']['horse_power']; ?></td>
        </tr>

        <tr>

            <th><?php echo __('Kubikaža motora'); ?></th>
            <td><?php echo $vm_vehicle['VmVehicle']['engine_capacity_cm3']; ?></td>
        </tr>

        <tr>

            <th><?php echo __('Godina proizvodnje'); ?></th>
            <td>
                <?php echo $vm_vehicle['VmVehicle']['year_of_production']; ?></td>
        </tr>

        <tr>

            <th><?php echo __('Boja'); ?></th>
            <td><?php echo $vm_vehicle['VmVehicle']['color']; ?></td>
        </tr>

        <tr>

            <th><?php echo __('Broj sedišta'); ?></th>
            <td><?php echo $vm_vehicle['VmVehicle']['number_of_seats']; ?></td>
        </tr>

        <tr>

            <th><?php echo __('Broj šasije'); ?></th>
            <td><?php echo $vm_vehicle['VmVehicle']['chassis_number']; ?></td>
        </tr>

        <tr>

            <th><?php echo __('Broj motora'); ?></th>
            <td><?php echo $vm_vehicle['VmVehicle']['engine_number']; ?></td>
        </tr>

        <tr>

            <th><?php echo __('Datum kupovine'); ?></th>
            <td><?php echo $vm_vehicle['VmVehicle']['date_of_purchase']; ?></td>
        </tr>

        <tr>

            <th><?php echo __('Cena'); ?></th>
            <td><?php echo $vm_vehicle['VmVehicle']['price']; ?></td>
        </tr>

        
        <tr>
            <th><?php echo __('Pređeno kilometara') ?></th>
            <td><?= $vm_max_crossed_km ?></td>
        </tr>

        <tr>
            <th><?php echo __('Registrovano do') ?></th>
            <td><?php echo $vm_max_registration_date; ?></td>
        </tr>
    </table>

</div>


<!-- TABS_LIST -->
<ul class="tabs left">
    <li><a href="#tabr1"><i class="icon-tag" style="color :darkgreen"></i><?php echo __('Registracije'); ?></a></li>
    <li><a href="#tabr2"><i class="icon-file" style="color :darkblue"></i><?php echo __('Fajlovi'); ?></a></li>
    <li><a href="#tabr3"><i class="icon-dashboard" style="color :yellow"></i><?php echo __('Gorivo'); ?></a></li>
    <li><a href="#tabr4"><i class="icon-fire" style="color :red"></i><?php echo __('Štete'); ?></a></li>
    <li><a href="#tabr5"><i class="icon-cogs" style="color :black"></i><?php echo __('Popravke'); ?></a></li>
    <li><a href="#tabr6"><i class="icon-tint" style="color :orange"></i> <?php echo __('Održavanja'); ?></a></li>
</ul>

<!-- TAB_REGISTRATIONS -->
<div id="tabr1" class="tab-content">
    <div style="float:left; margin:0 0 24px 20px;">
        <ul class="button-bar">
            <li class="first">
                <?php echo $this->Html->link('<i class="icon-plus-sign" style="color :#669E00"></i> ' . __('Dodaj novu registraciju'), array('controller' => 'vmRegistrations', 'action' => 'add', $vm_vehicle['VmVehicle']['id']), array('escape' => false)); ?>
            </li>
        </ul>
    </div>

    <?php if (empty($vm_vehicle['VmRegistration'])) : ?>
        <div class="notice warning">
            <i class="icon-warning-sign icon-large"></i>
            <?php echo __('Nema dodatih registracija'); ?>
            <a class="icon-remove" href="#close"></a>
        </div>
    <?php else : ?>
        <table style="table-layout: fixed;">

            <thead>
                <th>Registration date</th>
                <th>Expiration date</th>
                <th></th>
            </thead>
            <tbody>
                <?php foreach ($vm_vehicle['VmRegistration'] as $vm_registration) : ?>
                    <tr>
                        <td><?php echo $vm_registration['registration_date']; ?></td>
                        <td><?php echo $vm_registration['expiration_date']; ?></td>

                        <td>
                            <ul class="button-bar">
                                <li class="first">
                                    <?php echo $this->Html->link('<i class="icon-eye-open" style="color :blue"></i>', array('controller' => 'vmRegistrations', 'action' => 'view', $vm_vehicle['VmVehicle']['id']), array('title' => __('Detalji'), 'escape' => false)); ?>
                                </li>
                                <li>
                                    <?php echo $this->Html->link('<i class="icon-edit" style="color :#FC730A"></i>', array('controller' => 'vmRegistrations', 'action' => 'edit', $vm_vehicle['VmVehicle']['id']), array('title' => __('Izmena'), 'escape' => false)); ?>
                                </li>

                                <li class="last">
                                    <?php echo $this->Form->postLink('<i class="icon-trash" style="color :#B21203"></i>', array('controller' => 'vmRegistrations', 'action' => 'delete', $vm_vehicle['VmVehicle']['id']), array('title' => __('Brisanje'), 'confirm' => __('Da li ste sigurni da želite da izbrišete ovu registraciju?', $vm_vehicle['VmVehicle']['id']), 'escape' => false)); ?>
                                </li>

                            </ul>

                        </td>
                    </tr>


                <?php endforeach; ?>

            </tbody>
        </table>


    <?php endif; ?>

</div>
<!-- TAB_FILES -->
<div id="tabr2" class="tab-content">
    <div style="float:left; margin:0 0 24px 20px;">
        <ul class="button-bar">
            <li class="first">
                <?php echo $this->Html->link('<i class="icon-plus-sign" style="color :#669E00"></i> ' . __('Dodaj novi fajl'), array('controller' => 'vmRegistrations', 'action' => 'add', $vm_vehicle['VmVehicle']['id']), array('escape' => false)); ?>
            </li>
        </ul>
    </div>

    <?php if (empty($vm_vehicle['VmVehicleFile'])) : ?>
        <div class="notice warning">
            <i class="icon-warning-sign icon-large"></i>
            <?php echo __('Nema dodatih fajlova'); ?>
            <a class="icon-remove" href="#close"></a>
        </div>
    <?php else : ?>
        <table style="table-layout: fixed;">

            <thead>
                <th><?php echo __('Naslov'); ?></th>
                <th></th>
            </thead>
            <tbody>
                <?php foreach ($vm_vehicle['VmVehicleFile'] as $vm_vehicle_file) : ?>
                    <tr>
                        <td><?php echo $vm_vehicle_file['title']; ?></td>

                        <td>
                            <ul class="button-bar">
                                <li class="first">
                                    <?php echo $this->Html->link('<i class="icon-eye-open" style="color :blue"></i>', array('controller' => 'vmRegistrations', 'action' => 'view', $vm_vehicle['VmVehicle']['id']), array('title' => __('Detalji'), 'escape' => false)); ?>
                                </li>
                                <li>
                                    <?php echo $this->Html->link('<i class="icon-download-alt" style="color :green"></i>', array('controller' => 'vmRegistrations', 'action' => 'view', $vm_vehicle['VmVehicle']['id']), array('title' => __('Preuzmi'), 'escape' => false)); ?>
                                </li>
                                <li class="last">
                                    <?php echo $this->Html->link('<i class="icon-trash" style="color :#B21203"></i>', array('controller' => 'vmRegistrations', 'action' => 'view', $vm_vehicle['VmVehicle']['id']), array('title' => __('Detalji'), 'escape' => false)); ?>
                                </li>
                            </ul>

                        </td>
                    </tr>


                <?php endforeach; ?>

            </tbody>
        </table>


    <?php endif; ?>


</div>
<!-- TAB_FUELS -->
<div id="tabr3" class="tab-content">


    <div style="float:left; margin:0 0 24px 20px;">
        <ul class="button-bar">
            <li class="first">
                <?php echo $this->Html->link('<i class="icon-plus-sign" style="color :#669E00"></i> ' . __('Dodaj novo gorivo'), array('controller' => 'vmFuels', 'action' => 'add', $vm_vehicle['VmVehicle']['id']), array('escape' => false)); ?>
            </li>
        </ul>
    </div>
    <?php
    $emptyFuels = true;
    foreach ($vm_crossed_kms as $vm_crossed_km) {
        if (!empty($vm_crossed_km['VmFuel'])) {
            $emptyFuels = false;
            break;
        }
    }

    ?>

    <?php if ($emptyFuels) : ?>
        <div class="notice warning">
            <i class="icon-warning-sign icon-large"></i>
            <?php echo __('Nema dodatih goriva'); ?>
            <a class="icon-remove" href="#close"></a>
        </div>
    <?php else : ?>
        <table style="table-layout: fixed;">

            <thead>
                <th>Amount</th>
                <th>Liters</th>
                <th>Worker name</th>
                <th></th>
            </thead>
            <tbody>
                <?php foreach ($vm_crossed_kms as $vm_crossed_km) : ?>
                    <?php foreach ($vm_crossed_km['VmFuel'] as $vm_fuel) : ?>
                        <tr>
                            <td><?php echo $vm_fuel['amount']; ?></td>
                            <td><?php echo $vm_fuel['liters']; ?></td>
                            <td><?php echo $vm_crossed_km['HrWorker']['first_name']; ?></td>

                            <td>
                                <ul class="button-bar">
                                    <li class="first">
                                        <?php echo $this->Html->link('<i class="icon-eye-open" style="color :blue"></i>', array('controller' => 'vmFuels', 'action' => 'view', $vm_fuel['id']), array('title' => __('Detalji'), 'escape' => false)); ?>
                                    </li>
                                </ul>

                            </td>
                        </tr>


                    <?php endforeach; ?>
                <?php endforeach; ?>

            </tbody>
        </table>


    <?php endif; ?>
</div>


<!-- TAB_DAMAGES -->
<div id="tabr4" class="tab-content">
    <div style="float:left; margin:0 0 24px 20px;">
        <ul class="button-bar">
            <li class="first">
                <?php echo $this->Html->link('<i class="icon-plus-sign" style="color :#669E00"></i> ' . __('Dodaj novu štetu'), array('controller' => 'vmRegistrations', 'action' => 'add', $vm_vehicle['VmVehicle']['id']), array('escape' => false)); ?>
            </li>
        </ul>
    </div>

    <?php if (empty($vm_vehicle['VmDamage'])) : ?>
        <div class="notice warning">
            <i class="icon-warning-sign icon-large"></i>
            <?php echo __('Nema dodatih šteta'); ?>
            <a class="icon-remove" href="#close"></a>
        </div>
    <?php else : ?>
        <table style="table-layout: fixed;">

            <thead>
                <th>Responsible</th>
                <th>Description</th>
                <th>Date</th>
                <th>Repaired</th>
                <th></th>
            </thead>
            <tbody>
                <?php foreach ($vm_vehicle['VmDamage'] as $vm_damage) : ?>
                    <tr>
                        <td><?php echo $vm_damage['responsible']; ?></td>
                        <td><?php echo $vm_damage['description']; ?></td>
                        <td><?php echo $vm_damage['date']; ?></td>
                        <td><?php echo $vm_damage['repaired'] ? 'Da' : 'Ne'; ?></td>

                        <td>
                            <ul class="button-bar">
                                <li class="first">
                                    <?php echo $this->Html->link('<i class="icon-eye-open" style="color :blue"></i>', array('controller' => 'vmDamages', 'action' => 'view', $vm_damage['id']), array('title' => __('Detalji'), 'escape' => false)); ?>
                                </li>
                                <li>
                                    <?php echo $this->Html->link('<i class="icon-eye-open" style="color :blue"></i><i class="icon-wrench" style="color :red"></i>', array('controller' => 'vmRepairs', 'action' => 'viewByVmDamageId', $vm_damage['id']), array('title' => __('Popravke'), 'escape' => false)); ?>
                                </li>

                                <li class="last">
                                    <?php echo $this->Html->link('<i class="icon-wrench" style="color:green"></i>', array('controller' => 'vmrepairs', 'action' => 'repair', $vm_damage['id']), array('title' => __('Popravi'), 'escape' => false)); ?></li>

                            </ul>

                        </td>
                    </tr>


                <?php endforeach; ?>

            </tbody>
        </table>


    <?php endif; ?>
</div>
<!-- TAB_REPAIRS -->
<div id="tabr5" class="tab-content">

    <div style="float:left; margin:0 0 24px 20px;">
        <ul class="button-bar">
            <li class="first">
                <?php echo $this->Html->link('<i class="icon-plus-sign" style="color :#669E00"></i> ' . __('Dodaj novu popravku'), array('controller' => 'vmRegistrations', 'action' => 'add', $vm_vehicle['VmVehicle']['id']), array('escape' => false)); ?>
            </li>
        </ul>
    </div>
    <?php
    $emptyRepairs = true;
    foreach ($vm_crossed_kms as $vm_crossed_km) {
        if (!empty($vm_crossed_km['VmRepair'])) {
            $emptyRepairs = false;
            break;
        }
    }
    ?>

    <?php if ($emptyRepairs) : ?>
        <div class="notice warning">
            <i class="icon-warning-sign icon-large"></i>
            <?php echo __('Nema dodatih popravki'); ?>
            <a class="icon-remove" href="#close"></a>
        </div>
    <?php else : ?>
        <table style="table-layout: fixed;">

            <thead>
                <th>Amount</th>
                <th>Spent Time</th>
                <th>Description</th>
                <th>Date</th>
                <th>Worker name</th>
                <th></th>
            </thead>
            <tbody>
                <?php foreach ($vm_crossed_kms as $vm_crossed_km) : ?>
                    <?php foreach ($vm_crossed_km['VmRepair'] as $vm_repair) : ?>
                        <tr>
                            <td><?php echo $vm_repair['amount']; ?></td>
                            <td><?php echo gmdate("H:i:s", $vm_repair['spent_time']); ?></td>
                            <td><?php echo $vm_repair['description']; ?></td>
                            <td><?php echo $vm_crossed_km['VmCrossedKm']['report_datetime']; ?></td>
                            <td><?php echo $vm_crossed_km['HrWorker']['first_name']; ?></td>
                            <td>
                                <ul class="button-bar">
                                    <li class="first">
                                        <?php echo $this->Html->link('<i class="icon-eye-open" style="color :blue"></i>', array('controller' => 'vmRepairs', 'action' => 'add', $vm_repair['id']), array('title' => __('Detalji'), 'escape' => false)); ?>
                                    </li>
                                    <li>
                                        <?php echo $this->Html->link('<i class="icon-eye-open" style="color :blue"></i><i class="icon-fire" style="color :red"></i>', array('controller' => 'vmDamages', 'action' => 'view', $vm_repair['VmDamage']['id']), array('title' => __('Vidi štetu'), 'escape' => false)); ?>
                                    </li>
                                    <li class="last">
                                        <?php echo $this->Html->link('<i class="icon-eye-open" style="color :blue"></i><i class="icon-building" style="color :green"></i>', array('controller' => 'vmCompanies', 'action' => 'view', $vm_repair['VmDamage']['id']), array('title' => __('Vidi kompaniju'), 'escape' => false)); ?>
                                    </li>
                                </ul>

                            </td>
                        </tr>


                    <?php endforeach; ?>
                <?php endforeach; ?>

            </tbody>
        </table>


    <?php endif; ?>
</div>
<!-- TAB_MAINTENANCES -->
<div id="tabr6" class="tab-content">
    <div style="float:left; margin:0 0 24px 20px;">
        <ul class="button-bar">
            <li class="first">
                <?php echo $this->Html->link('<i class="icon-plus-sign" style="color :#669E00"></i> ' . __('Dodaj novo održavanje'), array('controller' => 'vmMaintenances', 'action' => 'add', $vm_vehicle['VmVehicle']['id']), array('escape' => false)); ?>
            </li>
        </ul>
    </div>
    <?php
    $emptyMaintenances = true;
    foreach ($vm_crossed_kms as $vm_crossed_km) {
        if (!empty($vm_crossed_km['VmMaintenance'])) {
            $emptyMaintenances = false;
            break;
        }
    }

    ?>

    <?php if ($emptyMaintenances) : ?>
        <div class="notice warning">
            <i class="icon-warning-sign icon-large"></i>
            <?php echo __('Nema dodatih održavanja'); ?>
            <a class="icon-remove" href="#close"></a>
        </div>
    <?php else : ?>
        <table style="table-layout: fixed;">

            <thead>
                <th>Amount</th>
                <th>Description</th>
                <th>Date</th>
                <th>Worker name</th>
                <th></th>
            </thead>
            <tbody>
                <?php foreach ($vm_crossed_kms as $vm_crossed_km) : ?>
                    <?php foreach ($vm_crossed_km['VmMaintenance'] as $vm_maintenance) : ?>
                        <tr>
                            <td><?php echo $vm_maintenance['amount']; ?></td>
                            <td><?php echo $vm_maintenance['description']; ?></td>
                            <td><?php echo $vm_crossed_km['VmCrossedKm']['report_datetime']; ?></td>
                            <td><?php echo $vm_crossed_km['HrWorker']['first_name']; ?></td>

                            <td>
                                <ul class="button-bar">
                                    <li class="first">
                                        <?php echo $this->Html->link('<i class="icon-eye-open" style="color :blue"></i>', array('controller' => 'vmFuels', 'action' => 'view', $vm_maintenance['id']), array('title' => __('Detalji'), 'escape' => false)); ?>
                                    </li>
                                    <li class="last">
                                        <!-- <?php echo $this->Html->link('<i class="icon-eye-open" style="color :blue"></i>' . $vm_maintenance['VmCompany']['name'], array('controller' => 'vmCompanies', 'action' => 'view', $vm_maintenance['vm_company_id']), array('title' => __('Vidi kompaniju'), 'escape' => false)); ?> -->
                                        <?php echo $this->Html->link('<i class="icon-eye-open" style="color :blue"></i><i class="icon-building" style="color :green"></i>', array('controller' => 'vmCompanies', 'action' => 'view', $vm_maintenance['VmCompany']['id']), array('title' => __('Vidi kompaniju'), 'escape' => false)); ?>
                                    </li>
                                </ul>

                            </td>
                        </tr>


                    <?php endforeach; ?>
                <?php endforeach; ?>

            </tbody>
        </table>


    <?php endif; ?>
</div>








<div class="clear"></div>
<div class="submit_loader">
    <?php echo $this->Html->image('submit_loader.gif', array('alt' => 'Loader')); ?>
    <h2>Molimo sačekajte...</h2>
</div>

<script>
    $('#container').ready(function() {
        $(".submit_loader").hide();
    });
</script>