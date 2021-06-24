<ul class="breadcrumbs">
    <li><?php echo $this->Html->link(__('Početna'), array('controller' => 'pages', 'action' => 'display')); ?></li>
    <li><?php echo $this->Html->link(__('Eksterni radnici'), array('action' => 'index')); ?></li>
    <li class="last"><a href="" onclick="return false"><?php echo __('Dodavanje'); ?></a></li>
</ul>

<div class="name_add_search">
    <div class="name_of_page">
        <?php if ($action == 'add') : ?>
            <h3>
                <i class="icon-group" style="color:blue;"></i>
                <i class="icon-save"style="color:blue;"></i>
                <?php echo __('Novi unos eksternog radnika'); ?>
            </h3>
        <?php endif; ?>
        <?php if ($action == 'edit') : ?>
            <h3>
            <i class="icon-group" style="color:blue;"></i>
                <i class="icon-edit"style="color:orange;"></i>
                <?php echo __('Izmena postojećeg eksternog radnika'); ?>
            </h3>
        <?php endif; ?>
    </div>

    <?php if ($action == 'edit') : ?>


        <div style="float:right; margin:20px 24px 0 0;">
            <ul class="button-bar">
                <li class="first">
                    <?php
                    echo $this->Html->link(
                        '<i class="icon-eye-open" style="color:blue;"></i> ' . __('Detalji'),
                        array(
                            'action' => 'view',
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

    <?php endif; ?>
</div>


<div class="content_data">
    <div class="formular">
        <?php echo $this->Form->create('VmExternalWorker', array('novalidate' => 'true')); ?>



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
            <?php if($action == 'add'): ?>
            <?php echo $this->Form->date('VmExternalWorker.date_of_birth', array('label' => false, 'class' => 'col_9', 'style' => 'margin: 0; width: 100%;', 'value' => date('Y-m-d'))); ?>
            <?php else: ?>
            <?php echo $this->Form->date('VmExternalWorker.date_of_birth', array('label' => false, 'class' => 'col_9', 'style' => 'margin: 0; width: 100%;', 'value' => $vm_external_worker['VmExternalWorker']['date_of_birth'])); ?>
            <?php endif; ?>
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

        <div class="col_9">
                <?php echo $this->Form->label('VmExternalWorker.vm_company_id', __('Firma')); ?>
                <?php echo $this->Form->input('VmExternalWorker.vm_company_id', array('label' => false, 'options' => $vm_companies, 'class' => 'col_12', 'style' => 'margin: 0; width: 100%;', 'empty' => __('Izaberite firmu'))); ?>
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
                    <?php echo $this->Html->link(__('Poništi'), 'javascript:void(0)', array('id' => 'backId', 'class' => 'button', 'style' => 'margin:20px 0 20px 0;')); ?>
                    <?php echo $this->Form->end(); ?>
                </div>
            </div>
        </div>



    </div>
</div>

<script>
   

    $('input[type="date"]').datepicker({
        changeMonth: true,
        changeYear: true,
        yearRange: "-100:+0"
    });

    $('#backId').click(function(e) {
        e.preventDefault();
        location.href = document.referrer
    });

    $('#VmExternalWorkerVmCompanyId').select2();
</script>