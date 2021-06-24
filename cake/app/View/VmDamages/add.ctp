<div class="content_data" id="addNewRepairForm" <?php echo isset($errors['Repairs']) ? 'style="display: block"' : 'style="display: none"' ?>>
    <div class="formular">
        <?php echo $this->Form->create('VmDamage', array('novalidate' => true)); ?>
        <?php echo $this->Form->hidden('VmCrossedKm.vm_vehicle_id', ['value' => $vm_damage['VmVehicle']['id']]); ?>
        <?php echo $this->Form->hidden('VmRepair.vm_damage_id', ['value' => $vm_damage['VmDamage']['id']]); ?>

        <div class="col_9">
            <?php echo $this->Form->label('VmDamage.responsible', __('Odgovoran')); ?>
            <?php echo $this->Form->input('VmDamage.responsible', array('type' => 'number', 'label' => false, 'class' => 'col_9', 'style' => 'margin: 0; width: 100%;', 'required' => false, 'placeholder' => __('Unesite odgovornog za štetu'))); ?>
        </div>
        <div class="clear"></div>

    </div>
</div>