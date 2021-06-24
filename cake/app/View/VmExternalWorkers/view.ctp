<ul class="breadcrumbs">
    <li><?php echo $this->Html->link(__('Početna'), array('controller' => 'pages', 'action' => 'display')); ?></li>
    <li><?php echo $this->Html->link(__('Eksterni radnici'), array('action' => 'index')); ?></li>
    <li class="last"><a href="" onclick="return false"><?php echo __('Detalji'); ?></a></li>
</ul>

<div class="name_of_page">
    <h3><i class="icon-group" style="color: blue;"></i> <i class="icon-search"></i> <?php echo __('Detalji eksternog radnika'); ?></h3>
</div>




<div style="float:right; margin:20px 24px 0 0;">
    <ul class="button-bar">
        <li class="first">
            <?php
            echo $this->Html->link(
                '<i class="icon-eye-open" style="color:blue;"></i> <i class="icon-building" style="color:green;"></i>' . __('Firma'),
                array(
                    'controller'=>'vmCompanies',
                    'action' => 'view',
                    $vm_external_worker['VmCompany']['id']
                ),
                array(
                    'escape' => false,
                    'style' => ' font-size: 12px;'
                )
            );
            ?>
        </li>
        <li>
            <?php
            echo $this->Html->link(
                '<i class="icon-edit" style="color:orange;"></i> ' . __('Izmena'),
                array(
                    'action' => 'save',
                    $vm_external_worker['VmExternalWorker']['id']
                ),
                array(
                    'escape' => false,
                    'style' => ' font-size: 12px;'
                )
            );
            ?>
        </li>
        <li class="last">
            <?php
            echo $this->Form->postLink(
                '<i class="icon-trash" style="color:red;"></i> ' . __('Brisanje'),
                array(
                    'action' => 'delete',
                    $vm_external_worker['VmExternalWorker']['id']
                ),
                array(
                    'escape' => false,
                    'confirm' =>  __('Da li ste sigurni da želite da izbrišete eksternog radnika?'),
                    'style' => ' font-size: 12px;'
                )
            );
            ?>
        </li>
    </ul>
</div>



<div class="content_data">
    <table cellspacing="0" cellpadding="0" class="striped">
        <tr>
            <th style="text-align:left"><?php echo __('Ime'); ?></th>
            <td style="width:85%;">
                <?php echo $vm_external_worker['VmExternalWorker']['first_name']; ?>
            </td>
        </tr>
        <tr>
            <th style="text-align:left"><?php echo __('Prezime'); ?></th>
            <td><?php echo $vm_external_worker['VmExternalWorker']['last_name']; ?></td>
        </tr>
        <tr>
            <th style="text-align:left"><?php echo __('Datum rođenja'); ?></th>
            <td><?php echo $vm_external_worker['VmExternalWorker']['date_of_birth']; ?></td>
        </tr>
        <tr>
            <th style="text-align:left"><?php echo __('Broj telefona'); ?></th>
            <td><?php echo $vm_external_worker['VmExternalWorker']['phone_number']; ?></td>
        </tr>
        <tr>
            <th style="text-align:left"><?php echo __('E-mail'); ?></th>
            <td><?php echo $vm_external_worker['VmExternalWorker']['email']; ?></td>
        </tr>
        <tr>
            <th style="text-align:left"><?php echo __('Firma'); ?></th>
            <td><?php echo $vm_external_worker['VmCompany']['name']; ?></td>
        </tr>
    </table>
</div>
