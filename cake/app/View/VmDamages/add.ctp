<h2>Add Damage</h2>


<?php
echo $this->Form->create('VmDamage');
echo $this->Form->input('responsible');
echo $this->Form->input('description');
echo $this->Form->input('date');


echo $this->Form->end('Add damage');
?>