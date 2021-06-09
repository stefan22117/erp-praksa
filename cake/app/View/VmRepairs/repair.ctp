<h1><i class="icon-wrench"></i> Repair</h1>

<h2>Vehicle</h2>

<table>
    <tr>
        <th>Brand and model</th>
        <th>Registration number</th>
    </tr>
    <tr>
        <td>
            <?= $vm_damage['VmVehicle']['brand_and_model']; ?>
        </td>
        <td>
            <?= $vm_damage['VmVehicle']['reg_number']; ?>
        
        </td>

        <td>
            <ul class="button-bar">
                <li class="first"><?php echo $this->Js->link('<i class="icon-eye-open"></i>View', array('controller' => 'vmvehicles', 'action' => 'view', $vm_damage['VmVehicle']['id']), array('update' => '#container', 'buffer' => false, 'escape' => false, 'before' => '$(".submit_loader").show();', 'success' => 'history.pushState(null, "MikroERP - Edit menu", this.url); $(document).attr("title", "MikroERP - Edit menu");')); ?></li>
            </ul>

        </td>



    </tr>

</table>


<h2>Damage</h2>


<table>
    <tr>
        <th>Responsible</th>
        <th>Description</th>
        <th>Date</th>
    </tr>
    <tr>
        <td>
            <?= $vm_damage['VmDamage']['responsible']; ?>
        </td>
        <td>
            <?= $vm_damage['VmDamage']['description']; ?>
        
        </td>
        <td>
        
            <?= $vm_damage['VmDamage']['date']; ?>
        </td>

        <td>
            <ul class="button-bar">
                <li class="first"><?php echo $this->Js->link('<i class="icon-eye-open"></i>View', array('controller' => 'vmdamages', 'action' => 'view', $vm_damage['VmDamage']['id']), array('update' => '#container', 'buffer' => false, 'escape' => false, 'before' => '$(".submit_loader").show();', 'success' => 'history.pushState(null, "MikroERP - Edit menu", this.url); $(document).attr("title", "MikroERP - Edit menu");')); ?></li>
            </ul>

        </td>



    </tr>

</table>



<?php
echo $this->Form->create('VmRepair');

echo $this->Form->input('amount');
echo $this->Form->input('spent_time');
echo $this->Form->input('description');

echo $this->Form->input('total_kilometers', ['type'=>'number']);


//staviti select2 u jquery-ju
echo $this->Form->input(
    'hr_worker_id',
    array(
        'label'=>'Hr Worker',
        'type' => 'select',
        'empty'=>'choose HR worker',
        'options'=>
        [
            $hr_workers
        ]
    )
);


echo $this->Form->input(
    'vm_company_id',
    array(
        'label'=>'Company',
        'type' => 'select',
        'empty'=>'choose company',
        'options'=>
        [
            $vm_companies
        ]
    )
);





echo $this->Form->hidden('vm_vehicle_id', ['value'=>$vm_damage['VmVehicle']['id']]);
echo $this->Form->button('<i class="icon-wrench"></i> Repair',
 ['escape' => false,'type' => 'submit']);

 
?>












<div class="clear"></div>
<div class="submit_loader">
    <?php echo $this->Html->image('submit_loader.gif', array('alt' => 'Loader')); ?>
    <h2>Molimo sačekajte...</h2>
</div>

<script>
$('#container').ready(function(){
    $(".submit_loader").hide();
});
</script>