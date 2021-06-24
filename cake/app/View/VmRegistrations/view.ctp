<ul class="breadcrumbs">
    <li><?php echo $this->Html->link(__('Početna'), array('controller' => 'pages', 'action' => 'display')); ?></li>
    <li><?php echo $this->Html->link(__('Registracije'), array('action' => 'index')); ?></li>
    <li class="last"><a href="" onclick="return false"><?php echo __('Detalji'); ?></a></li>
</ul>

<div class="name_of_page">
    <h3><i class="icon-tag" style="color: green;"></i> <i class="icon-search"></i> <?php echo __('Registracija za: ' . $vm_registration['VmVehicle']['brand_and_model']); ?></h3>
</div>




<div style="float:right; margin:20px 24px 0 0;">
    <ul class="button-bar">
        <li class="first">
            <?php
            echo $this->Html->link(
                '<i class="icon-eye-open" style="color:blue;"></i> <i class="icon-truck" style="color:black;"></i>' . __('Vozilo'),
                array(
                    'controller'=>'vmVehicles',
                    'action' => 'view',
                    $vm_registration['VmVehicle']['id']
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
                '<i class="icon-eye-open" style="color:blue;"></i> <i class="icon-building" style="color:green;"></i>' . __('Firma'),
                array(
                    'controller'=>'vmCompanies',
                    'action' => 'view',
                    $vm_registration['VmCompany']['id']
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
                    $vm_registration['VmVehicle']['id']
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
                    $vm_registration['VmVehicle']['id']
                ),
                array(
                    'escape' => false,
                    'confirm' =>  __('Da li ste sigurni da želite da izbrišete registraciju?'),
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
            <th style="text-align:left"><?php echo __('Marka i model'); ?></th>
            <td style="width:85%;">
                <?php echo $vm_registration['VmVehicle']['brand_and_model']; ?>
            </td>
        </tr>
        <tr>
            <th style="text-align:left"><?php echo __('Datum registracije'); ?></th>
            <td><?php echo $vm_registration['VmRegistration']['registration_date']; ?></td>
        </tr>
        <tr>
            <th style="text-align:left"><?php echo __('Potrošeno vreme'); ?></th>
            <td><?php echo $spent_time ?></td>
        </tr>
        <tr>
            <th style="text-align:left"><?php echo __('Radnik'); ?></th>
            <td><?php echo $vm_registration['HrWorker']['first_name']; ?></td>
        </tr>
        <tr>
            <th style="text-align:left"><?php echo __('Datum isteka registracije'); ?></th>
            <td><?php echo $vm_registration['VmRegistration']['expiration_date']; ?></td>
        </tr>
        <tr>
            <th style="text-align:left"><?php echo __('Cena registracije'); ?></th>
            <td><?php echo $vm_registration['VmRegistration']['amount']; ?></td>
        </tr>
        <tr>
            <th style="text-align:left"><?php echo __('Firma'); ?></th>
            <td>

                <?php echo $vm_registration['VmCompany']['name']; ?>

            </td>
        </tr>
    </table>
</div>
<ul class="tabs left">
    <li><a href="#tab_vm_registration_files"><i class="icon-tag" style="color :darkgreen"></i><i class="icon-file" style="color :darkblue"></i><?php echo __('Registracioni fajlovi'); ?></a></li>
</ul>


<div id="tab_vm_registration_files" class="tab-content">
    <div style="float:left; margin:0 0 24px 20px;">
        <ul class="button-bar">
            <li class="first">
                <?php echo $this->Html->link(
                    '<i class="icon-upload-alt" style="color :#669E00"></i> ' . __('Dodaj novi registracioni fajl'),
                    'javascript:void(0);',
                    array('id' => 'AddNewRegFile', 'escape' => false)
                ); ?>
            </li>
        </ul>
    </div>

    <div id="addNewRegFileForm" <?php echo isset($errors['RegistrationFiles']) ? 'style="display: block"' : 'style="display: none"'; ?>>

        <div class="formular">
            <?php echo $this->Form->create('VmRegistrationFile', array('type' => 'file', 'url' => array('controller' => 'vmRegistrationFiles', 'action' => 'add', $vm_registration['VmRegistration']['id']))); ?>
            <div class="col_9">
                <?php echo $this->Form->label('VmRegistrationFile.title', __('Naslov fajla')); ?>
                <?php echo $this->Form->input('VmRegistrationFile.title', array('type' => 'text', 'label' => false, 'style' => 'margin: 0; width: 100%;', 'required' => false, 'placeholder' => __('Unesite naslov fajla'))); ?>
            </div>
            <div class="col_9">
                <?php echo $this->Form->input('file', array('type' => 'file', 'label' => false, 'style' => 'margin: 0; width: 100%;', 'required' => false, 'placeholder' => __('Izaberite fajl'))); ?>
                <?php
                if ($this->Form->isFieldError('VmRegistrationFile.path')) {
                    echo $this->Form->error('VmRegistrationFile.path');
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
                        <?php echo $this->Html->link(__('Poništi'), 'javascript:void(0)', array('id' => 'addNewRegFileFormClose', 'class' => 'button', 'style' => 'margin:20px 0 20px 0;')); ?>
                        <?php echo $this->Form->end(); ?>
                    </div>
                </div>
            </div>



        </div>

    </div>

</div>


<?php if (empty($vm_registration['VmRegistrationFile'])) : ?>
    <div class="notice warning">
        <i class="icon-warning-sign icon-large"></i>
        <?php echo __('Nema dodatih registracionih fajlova'); ?>
        <a class="icon-remove" href="#close"></a>
    </div>

<?php else : ?>
    <table style="table-layout: fixed;">
        <thead>
            <tr>
                <th><?php echo __('Naslov') ?></th>
                <th><?php echo __('Vreme unosa') ?></th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($vm_registration['VmRegistrationFile'] as $vm_registration_file) : ?>

                <tr>
                    <td><?php echo $vm_registration_file['title'] ?></td>
                    <td><?php echo $vm_registration_file['created'] ?></td>


                    <td>
                        <ul class="button-bar">
                            <li class="first">
                                <?php
                                echo $this->Html->link(
                                    '<i class="icon-eye-open" style="color:blue;"></i>',
                                    array(
                                        'controller' => 'vmRegistrationFiles',
                                        'action' => 'view',
                                        $vm_registration_file['id']
                                    ),
                                    array(
                                        'title' => __('Detalji'),
                                        'escape' => false,
                                        'style' => ' font-size: 12px;'
                                    )
                                );
                                ?>
                            </li>
                            <li>
                                <?php
                                echo $this->Html->link(
                                    '<i class="icon-download-alt" style="color:brown;"></i>',
                                    array(
                                        'controller' => 'vmRegistrationFiles',
                                        'action' => 'download',
                                        $vm_registration_file['id']
                                    ),
                                    array(
                                        'title' => __('Preuzimanje'),
                                        'escape' => false,
                                        'style' => ' font-size: 12px;'
                                    )
                                );
                                ?>
                            </li>
                            <li class="last">
                                <?php
                                echo $this->Form->postLink(
                                    '<i class="icon-trash" style="color:red;"></i>',
                                    array(
                                        'controller' => 'vmRegistrationFiles',
                                        'action' => 'delete',
                                        $vm_registration_file['id']
                                    ),
                                    array(
                                        'title' => __('Brisanje'),
                                        'confirm' => 'Da li ste sigurni da želite da obrišete određeni registracioni fajl?',
                                        'escape' => false,
                                        'style' => ' font-size: 12px;'
                                    )
                                );
                                ?>
                            </li>
                        </ul>



                    </td>


                </tr>





            <?php endforeach; ?>
        </tbody>
    </table>



<?php endif; ?>










<script>
    $('#AddNewRegFile').click(function(e) {
        // e.preventDefault();
        $('#addNewRegFileForm').show();
        $(this).hide();

    })

    $('#addNewRegFileFormClose').click(function() {
        $('#addNewRegFileForm').hide();
        $('#AddNewRegFile').show();
    })
</script>