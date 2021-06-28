<ul class="breadcrumbs">
    <li><?php echo $this->Html->link(__('Početna'), array('controller' => 'pages', 'action' => 'display')); ?></li>
    <li><?php echo $this->Html->link(__('Firme'), array('action' => 'index')); ?></li>
    <li class="last"><a href="" onclick="return false"><?php echo __('Dodavanje'); ?></a></li>
</ul>

<div class="name_add_search">
    <div class="name_of_page">
        <?php if ($action == 'add') : ?>
            <h3>
                <i class="icon-building" style="color:green;"></i>
                <i class="icon-save" style="color:blue;"></i>
                <?php echo __('Novi unos firme'); ?>
            </h3>
        <?php endif; ?>
        <?php if ($action == 'edit') : ?>
            <h3>
                <i class="icon-building" style="color:green;"></i>
                <i class="icon-edit" style="color:orange;"></i>
                <?php echo __('Izmena postojeće firme'); ?>
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
                            $vm_company['VmCompany']['id']
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
                            $vm_company['VmCompany']['id']
                        ),
                        array(
                            'escape' => false,
                            'confirm' =>  __('Da li ste sigurni da želite da izbrišete firmu?'),
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
        <?php echo $this->Form->create('VmCompany', array('novalidate' => 'true')); ?>



        <div class="col_9">
            <?php echo $this->Form->label('VmCompany.name', __('Ime firme')); ?>
            <?php echo $this->Form->input('VmCompany.name', array('type' => 'text', 'label' => false, 'style' => 'margin: 0; width: 100%;', 'required' => false, 'placeholder' => __('Unesite ime eksternog radnika'))); ?>
        </div>
        <div class="col_9">
            <?php echo $this->Form->label('VmCompany.address', __('Adresa')); ?>
            <?php echo $this->Form->input('VmCompany.address', array('type' => 'text', 'label' => false, 'style' => 'margin: 0; width: 100%;', 'required' => false, 'placeholder' => __('Unesite prezime eksternog radnika'))); ?>
        </div>

        <div class="col_9">
            <?php echo $this->Form->label('VmCompany.city', __('Grad')); ?>
            <?php echo $this->Form->input('VmCompany.city', array('type' => 'text', 'label' => false, 'style' => 'margin: 0; width: 100%;', 'required' => false, 'placeholder' => __('Unesite broj telefona eksternog radnika'))); ?>
        </div>

        <div class="col_9">
            <?php echo $this->Form->label('VmCompany.email', __('E-mail firme')); ?>
            <?php echo $this->Form->input('VmCompany.email', array('type' => 'text', 'label' => false, 'style' => 'margin: 0; width: 100%;', 'required' => false, 'placeholder' => __('Unesite e-mail eksternog radnika'))); ?>
        </div>
        <div class="col_9">
            <?php echo $this->Form->label('VmCompany.zip_code', __('Poštanski broj')); ?>
            <?php echo $this->Form->input('VmCompany.zip_code', array('type' => 'number', 'label' => false, 'style' => 'margin: 0; width: 100%;', 'required' => false, 'placeholder' => __('Unesite e-mail eksternog radnika'))); ?>
        </div>


        <div class="col_9">
            <?php

            echo $this->Form->label('VmExternalWorker.id', __('Radnici u firmi'));
            echo $this->Form->input('VmExternalWorker.id', array(
                'type' => 'select', 'options' => $vm_external_workers,
                'error' => false, 'label' => false,
                'multiple' => true,
                'style' => 'width:100%;',
                'default' => $action == 'edit' ? $vm_external_workers_selected : 0

            ));
            ?>




            <?php if ($this->Form->isFieldError('VmVehicle.vm_external_worker_id')) {
                echo $this->Form->error('VmVehicle.vm_external_worker_id');
            }
            ?>

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
                    <?php echo $this->Html->link(__('Nazad'), 'javascript:void(0)', array('id' => 'backId', 'class' => 'button', 'style' => 'margin:20px 0 20px 0;')); ?>
                    <?php echo $this->Form->end(); ?>
                </div>
            </div>
        </div>



    </div>
</div>

<script>
    $('#backId').click(function(e) {
        e.preventDefault();
        location.href = document.referrer
    });

    $('#VmExternalWorkerId').select2();
</script>