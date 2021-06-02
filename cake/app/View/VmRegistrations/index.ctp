INDEX



<table>
    <tr>
        <th>
            registration_date
        </th>
        <th>
            spent_time
        </th>
        <th>
            hr_worker
        </th>
        <th>
            expiration_date
        </th>
        <th>
            amount
        </th>
        <th>
            vm_company
        </th>
        <th></th>
    </tr>

    <?php
    foreach ($vm_registrations as $vm_registration) {
    ?>

        <tr>
        <td>
            <?= $vm_registration['VmRegistration']['registration_date'] ?>
        </td>
        <td>
        <?= $vm_registration['VmRegistration']['spent_time'] ?>
            
        </td>
        <td>
        <?= $vm_registration['HrWorker']['first_name'] ?>
        </td>
        <td>
        <?= $vm_registration['VmRegistration']['expiration_date'] ?>
            
        </td>
        <td>
        <?= $vm_registration['VmRegistration']['amount'] ?>
            
        </td>
        <td>
        <?= $vm_registration['VmCompany']['name'] ?>
        </td>
        </td>
        <td>
        <?= $this->Html->link('view', ['action'=>'view', $vm_registration['VmRegistration']['id']]) ?>
        </td>

    <?php
    }
    ?>
</table>
<?php

?>