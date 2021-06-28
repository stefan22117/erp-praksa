<ul class="breadcrumbs">
    <li><?php echo $this->Html->link(__('Početna'), array('controller' => 'pages', 'action' => 'display')); ?></li>
    <li><?php echo $this->Html->link(__('Registracioni fajlovi'), array('action' => 'index')); ?></li>
    <li class="last"><a href="" onclick="return false"><?php echo __('Dodavanje'); ?></a></li>
</ul>

<div class="name_add_search">
    <div class="name_of_page">
        <h3>
            <i class="icon-tag" style="color:green;"></i>
            <i class="icon-save" style="color:blue;"></i>
            <?php echo __('Novi unos registracionog fajla'); ?>
        </h3>
    </div>

</div>


<div class="content_data">
    <div class="formular">
        <?php echo $this->Form->create('VmRegistrationFile', array('novalidate' => true, 'type' => 'file')); ?>

        <?php if (!empty($vm_vehicles)) : ?>
            <div class="col_9">
                <?php echo $this->Form->label('VmRegistrationFile.vm_vehicle_id', __('Vozilo')); ?>
                <?php echo $this->Form->input('VmRegistrationFile.vm_vehicle_id', array('label' => false, 'options' => $vm_vehicles, 'class' => 'col_12', 'style' => 'margin: 0; width: 100%;', 'empty' => __('Izaberite vozilo'))); ?>

            </div>
        <?php endif; ?>

        <?php if (!empty($vm_registrations)) : ?>
            <div class="col_9">
                <?php echo $this->Form->label('VmRegistrationFile.vm_registration_id', __('Registracija')); ?>
                <?php echo $this->Form->input('VmRegistrationFile.vm_registration_id', array('label' => false, 'options' => $vm_registrations, 'class' => 'col_12', 'style' => 'margin: 0; width: 100%;', 'empty' => __('Izaberite registraciju'))); ?>

            </div>
        <?php endif; ?>




        <div class="col_9">
            <?php echo $this->Form->label('VmRegistrationFile.title', __('Naslov')); ?>
            <?php echo $this->Form->input('VmRegistrationFile.title', array('type' => 'text', 'label' => false, 'style' => 'margin: 0; width: 100%;', 'required' => false, 'placeholder' => __('Unesite naslov registracionog fajla'))); ?>
        </div>


        <div class="col_9">
            <?php echo $this->Form->label('file', __('Izaberite registracioni fajl')); ?>
            <?php echo $this->Form->input(
                'file',
                array('type' => 'file', 'label' => false)
            ); ?>

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
                    <?php echo $this->Html->link(__('Nazad'), 'javascript:void(0)', array('id' => 'backId', 'class' => 'button', 'style' => 'margin:20px 0 20px 0;')); ?>
                    <?php echo $this->Form->end(); ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $('#backId').click(function(e) {
        location.href = document.referrer
    });
    $('#VmRegistrationFileVmVehicleId').select2();

    $('#VmRegistrationFileVmRegistrationId').select2({
        minimumResultsForSearch: -1
    });

    $('#VmRegistrationFileVmVehicleId').change(function(e) {
        
        var vm_vehicle_id = $(this).val();

        $.ajax({
            url: '<?php echo $this->Html->url(array('controller' => 'vmRegistrationFiles', 'action' => 'reloadRegistrations')); ?>',
            dataType: "json",
            type: "POST",
            evalScripts: true,
            data: {
                vm_vehicle_id: vm_vehicle_id
            },
            success: function(response) {
                console.log(response);
                $('#VmRegistrationFileVmRegistrationId').empty();
                Object.keys(response).sort().forEach(function(key, i) {
                    $('#VmRegistrationFileVmRegistrationId').append($("<option></option>").attr("value", key).text(response[key]));
                });
            }
        });
    });
</script>