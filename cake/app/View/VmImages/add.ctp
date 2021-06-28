<ul class="breadcrumbs">
    <li><?php echo $this->Html->link(__('Početna'), array('controller' => 'pages', 'action' => 'display')); ?></li>
    <li><?php echo $this->Html->link(__('Slike vozila'), array('action' => 'index')); ?></li>
    <li class="last"><a href="" onclick="return false"><?php echo __('Dodavanje'); ?></a></li>
</ul>

<div class="name_add_search">
    <div class="name_of_page">
        <h3>
            <i class="icon-picture" style="color:cyan;"></i>
            <i class="icon-save" style="color:blue;"></i>
            <?php echo __('Novi unos slike vozila'); ?>
        </h3>
    </div>

</div>


<div class="content_data">
    <div class="formular">
        <?php echo $this->Form->create('VmImage', array('novalidate' => true, 'type'=>'file')); ?>

        <?php if (!empty($vm_vehicles)) : ?>
            <div class="col_9">
                <?php echo $this->Form->label('VmImage.vm_vehicle_id', __('Vozilo')); ?>
                <?php echo $this->Form->input('VmImage.vm_vehicle_id', array('label' => false, 'options' => $vm_vehicles, 'class' => 'col_12', 'style' => 'margin: 0; width: 100%;', 'empty' => __('Izaberite vozilo'))); ?>

            </div>

        <?php endif; ?>




        <div class="col_9">
            <?php echo $this->Form->label('VmImage.title', __('Naslov')); ?>
            <?php echo $this->Form->input('VmImage.title', array('type' => 'text', 'label' => false, 'style' => 'margin: 0; width: 100%;', 'required' => false, 'placeholder' => __('Unesite naslov fajla vozila'))); ?>
        </div>


        <div class="col_9">
            <?php echo $this->Form->label('file', __('Izaberite sliku vozila')); ?>
            <?php echo $this->Form->input(
                'file',
                array('type' => 'file', 'label' => false)
            ); ?>

            <?php
            if ($this->Form->isFieldError('VmImage.path')) {
                echo $this->Form->error('VmImage.path');
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

    $('#VmImageVmVehicleId').select2();
</script>
