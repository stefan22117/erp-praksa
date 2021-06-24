

<?php


echo $this->Form->create('VmVehicleFile', ['type'=>'file', 'novalidate'=>true]);
 ?>

<?php
if (!empty($vm_vehicles)) :
?>

    <div class="col_9">
        <?php echo $this->Form->label('vm_vehicle_id', __('Vozilo')); ?>
        <?php echo $this->Form->input('vm_vehicle_id', array('label' => false, 'options' => $vm_vehicles, 'class' => 'col_12', 'style' => 'margin: 0; width: 100%;', 'empty' => __('Izaberite vozilo'))); ?>
    </div>
    <div class="clear"></div>

<?php elseif (isset($vm_vehicle['VmVehicle']['id'])) : ?>
    <?php echo $this->Form->hidden('vm_vehicle_id', array('value' => $vm_vehicle['VmVehicle']['id'])); ?>



<?php endif; ?>
<?php

echo $this->Form->input('VmVehicleFile.title');

 echo $this->Form->input('file', 
array('type' => 'file', 'label' => false)
);




echo $this->Form->end('Upload');

?>