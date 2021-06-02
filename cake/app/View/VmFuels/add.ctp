
<?php

echo $this->Form->create('VmFuel');


echo $this->Form->input('liters');

echo $this->Form->input('amount');


echo $this->Form->input('total_kilometers');


echo $this->Form->input(
    'hr_worker_id',
    array(
        'label'=>'Hr Worker',
        'type' => 'select',
        'options'=>
        [
            $hr_workers
        ]
    )
);

echo $this->Form->end('Fill');

?>
