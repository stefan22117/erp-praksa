
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
</table>



<?php
echo $this->Form->create('VmRegistrationFile', ['type'=>'file', 'url'=>['controller'=>'vmregistrationfiles', 'action' => 'add', $vm_registration['VmRegistration']['id']]]);
echo $this->Form->input('title');
echo $this->Form->input('file', ['type'=>'file']);
echo $this->Form->hidden('vm_registration_id', ['value'=>$vm_registration['VmRegistration']['id']]);
echo $this->Form->end('dodaj reg. dokument');





if(isset($vm_registration['VmRegistrationFile']) && count($vm_registration['VmRegistrationFile']) > 0){
?>
<h2>Documents</h2>

<?php
foreach($vm_registration['VmRegistrationFile'] as $vm_vehicle_file){
?>
<h3><?= $vm_vehicle_file['title'] ?></h3>
<?= $this->Html->image($vm_vehicle_file['path'], ['alt'=>$vm_vehicle_file['title']]); ?>
<?php
}
?>





<?php
}else{
?>


<h2>Nema dokumenata</h2>




<?php
}
?>