<!-- <h2>Registration file</h2> -->
<ul class="breadcrumbs">
    <li><?= $this->Html->link('Home', array('controller' => 'pages', 'action' => 'display')); ?></li>
    <li><?= $this->Html->link('Registration files', array('controller'=>'vmregistrationfiles', 'action' => 'index')); ?></li>
    <!-- <li><?= $this->Html->link('View', array('action' => 'view', $vehicle['VmVehicle']['id'])); ?></li> -->
    <li class="last"><a href="" onclick="return false">View - <?= $vm_registration_file['VmRegistrationFile']['title']; ?></a></li>
</ul>
<embed src="/img/<?= $vm_registration_file['VmRegistrationFile']['path']; ?>"    type="application/pdf" style="width:99.80%;height:120vh;">
