<!-- TAB_EXTERNAL_WORKERS -->
<div id="tab_vm_external_workers" class="tab-content">
    <div style="float:left; margin:0 0 24px 20px;">
        <ul class="button-bar">
            <li class="first">
                <?php echo $this->Html->link(
                    '<i class="icon-plus-sign" style="color :#669E00"></i> ' . __('Dodaj novog eksternog radnika'),
                    'javascript:void(0);',
                    array(
                        'id' => 'addNewExternalWorker', 'escape' => false,
                        'style' => isset($errors['ExternalWorkers']) ? 'display: none' : 'display: block'
                    )
                ); ?>
            </li>
        </ul>
    </div>

    <div id="addNewExternalWorkerForm" <?php echo isset($errors['ExternalWorkers']) ? 'style="display: block"' : 'style="display: none"'; ?>>

        <div class="formular">
            <?php echo $this->Form->create('VmExternalWorker', array('novalidate' => 'true', 'url' => array('controller' => 'vmExternalWorkers', 'action' => 'save'))); ?>

            <?php echo $this->Form->hidden('VmExternalWorker.vm_company_id', array('value' => $vm_company['VmCompany']['id'])); ?>

            <div class="col_9">
                <?php echo $this->Form->label('VmExternalWorker.first_name', __('Ime radnika')); ?>
                <?php echo $this->Form->input('VmExternalWorker.first_name', array('type' => 'text', 'label' => false, 'style' => 'margin: 0; width: 100%;', 'required' => false, 'placeholder' => __('Unesite ime eksternog radnika'))); ?>
            </div>
            <div class="col_9">
                <?php echo $this->Form->label('VmExternalWorker.last_name', __('Prezime radnika')); ?>
                <?php echo $this->Form->input('VmExternalWorker.last_name', array('type' => 'text', 'label' => false, 'style' => 'margin: 0; width: 100%;', 'required' => false, 'placeholder' => __('Unesite prezime eksternog radnika'))); ?>
            </div>


            <div class="col_9">
                <?php echo $this->Form->label('VmExternalWorker.date_of_birth', __('Datum rođenja radnika')); ?>

                <?php echo $this->Form->date('VmExternalWorker.date_of_birth', array('label' => false, 'class' => 'col_9', 'style' => 'margin: 0; width: 100%;', 'value' => date('Y-m-d'))); ?>

                <?php
                if ($this->Form->isFieldError('VmExternalWorker.date_of_birth')) {
                    echo $this->Form->error('VmExternalWorker.date_of_birth');
                }
                ?>

            </div>

            <div class="col_9">
                <?php echo $this->Form->label('VmExternalWorker.phone_number', __('Broj telefona')); ?>
                <?php echo $this->Form->input('VmExternalWorker.phone_number', array('type' => 'text', 'label' => false, 'style' => 'margin: 0; width: 100%;', 'required' => false, 'placeholder' => __('Unesite broj telefona eksternog radnika'))); ?>
            </div>

            <div class="col_9">
                <?php echo $this->Form->label('VmExternalWorker.email', __('E-mail radnika')); ?>
                <?php echo $this->Form->input('VmExternalWorker.email', array('type' => 'text', 'label' => false, 'style' => 'margin: 0; width: 100%;', 'required' => false, 'placeholder' => __('Unesite e-mail eksternog radnika'))); ?>
            </div>

            <div class="content_text_input">
                <div class="buttons_box">
                    <div class="button_box">
                        <?php
                        echo $this->Form->submit(
                            __('Snimi'),
                            array(
                                'div' => false,
                                'class' => "button blue",
                                'style' => "margin:20px 0 20px 0;"
                            )
                        );
                        ?>
                    </div>
                    <div class="button_box">
                        <?php echo $this->Html->link(
                            __('Poništi'),
                            'javascript:void(0)',
                            array('id' => 'addNewExternalWorkerClose', 'class' => 'button', 'style' => 'margin:20px 0 20px 0;')
                        );
                        ?>
                        <?php echo $this->Form->end(); ?>
                    </div>
                </div>
            </div>


        </div>

    </div>





















































    <?php if (empty($vm_external_workers)) : ?>
        <div class="notice warning">
            <i class="icon-warning-sign icon-large"></i>
            <?php echo __('Nema dodatih eksternih radnika'); ?>
            <a class="icon-remove" href="#close"></a>
        </div>
    <?php else : ?>
        <table style="table-layout: fixed;">

            <thead>
                <th><?php echo __('Ime'); ?></th>
                <th><?php echo __('Prezime'); ?></th>
                <th><?php echo __('Broj telefona'); ?></th>
                <th></th>
            </thead>
            <tbody>
                <?php foreach ($vm_external_workers as $vm_external_worker) : ?>
                    <tr>
                        <td><?php echo $vm_external_worker['VmExternalWorker']['first_name']; ?></td>
                        <td><?php echo $vm_external_worker['VmExternalWorker']['last_name']; ?></td>
                        <td><?php echo $vm_external_worker['VmExternalWorker']['phone_number']; ?></td>

                        <td>
                            <ul class="button-bar">
                                <li class="first">
                                    <?php echo $this->Html->link('<i class="icon-eye-open" style="color :blue"></i>', array('controller' => 'vmExternalWorkers', 'action' => 'view', $vm_external_worker['VmExternalWorker']['id']), array('title' => __('Detalji'), 'escape' => false)); ?>
                                </li>
                                <li>
                                    <?php echo $this->Html->link('<i class="icon-edit" style="color :orange"></i>', array('controller' => 'vmExternalWorkers', 'action' => 'save', $vm_external_worker['VmExternalWorker']['id']), array('title' => __('Izmena'), 'escape' => false)); ?>
                                </li>
                                <li class="last">
                                    <?php echo $this->Html->link('<i class="icon-trash" style="color :red"></i>', array('controller' => 'vmExternalWorkers', 'action' => 'delete', $vm_external_worker['VmExternalWorker']['id']), array('title' => __('Brisanje'), 'escape' => false, 'confirm' => 'Da li ste sigurni da želite da izbrišete eksternog radnika?')); ?>
                                </li>
                            </ul>

                        </td>
                    </tr>


                <?php endforeach; ?>

            </tbody>
        </table>


    <?php endif; ?>
</div>