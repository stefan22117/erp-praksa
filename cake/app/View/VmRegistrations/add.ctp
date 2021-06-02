

<?php

echo '<p>'. $vehicle['VmVehicle']['brand_and_model'] .'</p>';

echo $this->Form->create('VmRegistration');

echo $this->Form->hidden('vm_vehicle_id', ['value' => $vehicle['VmVehicle']['id']]);

echo $this->Form->input(
    'registration_date',
    array(
        'type' => 'date',
        'dateFormat' => 'YMD',
        'minYear' => date('Y') - 5,
        'maxYear' => date('Y') + 5,
        // 'selected' => date('Y-m-d')) // today?
    )
);

echo $this->Form->input('spent_time');



echo $this->Form->input(//dropdown hr worker
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


echo $this->Form->input(
    'expiration_date',
    array(
        'type' => 'date',
        'dateFormat' => 'YMD',
        'minYear' => date('Y') - 5,
        'maxYear' => date('Y') + 5,
        // 'selected' => date('Y-m-d')) // today?
    )
);
echo $this->Form->input('amount');


echo $this->Form->input(//dropdown hr worker
    'vm_company_id',
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

echo $this->Html->link('Add new company', ['controller'=> 'vmcompanies', 'action'=>'add']);





echo $this->Form->end('dodaj');

?>