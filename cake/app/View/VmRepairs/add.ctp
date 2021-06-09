<h2>Add new repair</h2>

<?php
echo $this->Form->create('VmRepair');
echo $this->Form->input('amount');
echo $this->Form->input('spent_time');
echo $this->Form->input('description');

echo $this->Form->input('total_kilometers');

// echo $this->Form->input('date'); posle

echo $this->Form->input(//dropdown hr worker //za crossed kms
    'hr_worker_id',
    array(
        'label'=>'Hr Worker',
        'type' => 'select',
        'options'=>
        [
            $hr_workers
        ]
        // 'selected' => date('Y-m-d'))
    )
);

echo $this->Form->input(//dropdown damages
    'hr_worker_id',
    array(
        'label'=>'Damage',
        'type' => 'select',
        'options'=>
        [
            $vm_damages
        ]
        // 'selected' => date('Y-m-d'))
    )
);

echo $this->Form->input(//dropdown companies// trebalo bi da moze da se upise nova kompanija
    'hr_worker_id',
    array(
        'label'=>'Company',
        'type' => 'select',
        'options'=>
        [
            $vm_companies
        ]
        // 'selected' => date('Y-m-d'))
    )
);



echo $this->Form->end('Add repair');



?>