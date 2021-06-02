<ul class="breadcrumbs">
<li><?= $this->Html->link('Home' , array('controller'=>'pages', 'action'=>'display')); ?></li>
<li><?= $this->Html->link('Vehicles', array('action'=>'index')); ?></li>
<li><?= $this->Html->link('View', array('action'=>'view', $vehicle['VmVehicle']['id']  )); ?></li>
</ul>

<table>
        <tr>
            <td>Brand and model</td>
            <td>
            <?= $vehicle['VmVehicle']['brand_and_model'] ?>
            </td>
        </tr>
        <tr>
        <td>Registration Number</td>
            <td>
            <?= $vehicle['VmVehicle']['reg_number'] ?>
            </td>
            
        </tr>
        <tr>

        <td>In use</td>
            <td>
            <?= $vehicle['VmVehicle']['in_use'] ?>
            </td>
        </tr>

        <tr>

        <td>Active from</td>
            <td>
            <?= $vehicle['VmVehicle']['active_from'] ?>
            </td>
        </tr>

        <tr>

        <td>Active to</td>
            <td>
            <?= $vehicle['VmVehicle']['active_to'] ?>
            </td>
        </tr>

        <tr>

        <td>Horse power</td>
            <td>
            <?= $vehicle['VmVehicle']['horse_power'] ?>
            </td>
        </tr>

        <tr>

        <td>Engine capacity</td>
            <td>
            <?= $vehicle['VmVehicle']['engine_capacity_cm3'] ?>
            </td>
        </tr>

        <tr>

        <td>Year of production</td>
            <td>
            <?= $vehicle['VmVehicle']['year_of_production'] ?>
            </td>
        </tr>

        <tr>

        <td>Color</td>
            <td>
            <?= $vehicle['VmVehicle']['color'] ?>
            </td>
        </tr>

        <tr>

        <td>Number of seats</td>
            <td>
            <?= $vehicle['VmVehicle']['number_of_seats'] ?>
            </td>
        </tr>

        <tr>

        <td>Chassis number</td>
            <td>
            <?= $vehicle['VmVehicle']['chassis_number'] ?>
            </td>
        </tr>

        <tr>

        <td>Engine number</td>
            <td>
            <?= $vehicle['VmVehicle']['engine_number'] ?>
            </td>
        </tr>

        <tr>

        <td>Date of purchase</td>
            <td>
            <?= $vehicle['VmVehicle']['date_of_purchase'] ?>
            </td>
        </tr>

        <tr>

        <td>Price</td>
            <td>
            <?= $vehicle['VmVehicle']['price'] ?>
            </td>
        </tr>

        <tr>

        <td>Passed kilometers</td>
        <td>
            <?php
            if($vehicle['VmCrossedKm'] && count($vehicle['VmCrossedKm'])>0){
                $crossed_km = $vehicle['VmCrossedKm'];

                usort($crossed_km, function($a, $b) {return $b['total_kilometers']-$a['total_kilometers'];});
                 echo $crossed_km[0]['total_kilometers'];
            }
            else{
                echo "0" ;
            }
            ?>
            </td>
        </tr>
        <tr>

        <td>Registration to: </td>
            <td>
            <?php
            if($vehicle['VmRegistration'] && count($vehicle['VmRegistration'])>0){
                // echo 'dsaasddsa';
                $registrations = $vehicle['VmRegistration'];

                usort($registrations, function($a, $b) {return 
                    strtotime($a['expiration_date']) < strtotime($b['expiration_date']) ? 1 : -1;
                });
                 echo $registrations[0]['expiration_date'];
                 if(date("Y-m-d") >= $registrations[0]['expiration_date']){
                     echo '(Istekla)';
                 }
            }
            else{
                echo "NIJE REGISTROVAN" ;
            }
            ?>
            </td>
        </tr>

</table>
<ul class="tabs left">
<li><a href="#tabr1">Registrations</a></li>
<li><a href="#tabr2">Files</a></li>
<li><a href="#tabr3">Fuel</a></li>
</ul>

<div id="tabr1" class="tab-content">
    <table>

    <tr>
        <?= $this->Html->link('Add new Registration', 
        ['controller'=>'vmregistrations', 'action' => 'add', $vehicle['VmVehicle']['id']]) ?>
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
        
    foreach($vehicle['VmRegistration'] as $registration){
    ?>
        <tr>
            <td><?= $registration['registration_date']?></td>
            <td><?= $registration['expiration_date']?></td>
            <!-- <td><?= $registration['VmHrWorker']['first_name'] ?></td> -->

            <td><?= $this->Html->link('View', 
            ['controller'=>'vmregistrations',
                'action'=>'view', $registration['id']])?></td>
        </tr>
        <?php
    }
    ?>
    </table>
</div>
<div id="tabr2" class="tab-content">


    <?php
    
foreach($vehicle['VmVehicleFile'] as $vehicle_file){
?>
    <tr>
        <td><?= $vehicle_file['title']?></td>
        <td><?= $vehicle_file['path']?></td>

        <td><?= $this->Html->link('View', 
        ['controller'=>'vmregistrations',
            'action'=>'view', $vehicle_file['id']])?></td>
    </tr>
    <?php
}
?>
</div>





<div id="tabr3" class="tab-content">

    <?=
    $this->Html->link('Fill fuel' , ['controller'=>'vmfuels', 'action'=>'add', $vehicle['VmVehicle']['id']])
    
    ?>


    <table>

    <tr>
        <th>
            Liters
        </th>
        <th>
            Amount
        </th>
        <th>
            Name of worker
        </th>
    </tr>
<?php
    // var_dump($kms);//die();
    
    foreach($kms as $km){
    $index = 0;
    if(isset($km['VmFuel']) && count($km['VmFuel']) > 0){
    ?>
        <tr>
            <td><?= $km['VmFuel'][0]['liters']?></td>
            <td><?= $km['VmFuel'][0]['amount']?></td>
            <td><?=$kms[$index]['HrWorker']['first_name']; ?></td>
            
        </tr>
        <?php
        $index++;
        }
    }
    ?>

</table>
</div>


