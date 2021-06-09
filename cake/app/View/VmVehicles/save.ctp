<ul class="breadcrumbs">
    <li><?php echo $this->Html->link(__('Početna'), array('controller' => 'pages', 'action' => 'display')); ?></li>
    <li><?php echo $this->Html->link(__('Vozila'), array('controller' => 'vmVehicles', 'action' => 'index')); ?></li>
    <li class="last"><a href="" onclick="return false"><?php echo __('Dodavanje'); ?></a></li>
</ul>
<div id='alert'><?php echo $this->Session->flash(); ?></div>

<div class="name_add_search">
    <div class="name_of_page">
        <?php if ($action == 'add') : ?>
            <h3><i class="icon-save"></i> <?php echo __('Dodavanje novog vozila'); ?></h3>
        <?php endif; ?>
        <?php if ($action == 'edit') : ?>
            <h3><i class="icon-edit"></i> <?php echo __('Izmena postojećeg vozila'); ?></h3>
        <?php endif; ?>
    </div>
</div>

<div class="content_data">
    <?php echo $this->Form->create('VmVehicle'); ?>
    <?php
    if ($action == 'edit')
        echo $this->Form->input('id');
    ?>
    <div class="col_9">
        <?php echo $this->Form->label('brand_and_model', __('Marka i model')); ?>
        <?php echo $this->Form->input('brand_and_model', array('type' => 'text', 'label' => false, 'class' => 'col_9', 'style' => 'margin: 0; width: 100%;', 'required' => false, 'placeholder' => __('Unesite marku i model'))); ?>
    </div>
    <div class="clear"></div>
    <div class="col_9">
        <?php echo $this->Form->label('reg_number', __('Registarski broj')); ?>
        <?php echo $this->Form->input('reg_number', array('type' => 'text', 'label' => false, 'class' => 'col_9', 'style' => 'margin: 0; width: 100%;', 'required' => false, 'placeholder' => __('Unesite registarski broj'))); ?>
    </div>
    <div class="clear"></div>
    <div class="col_9">
        <?php echo $this->Form->label('active_from', __('Aktivno od')); ?>
        <?php echo $this->Form->date('active_from', array('label' => false, 'class' => 'col_9', 'style' => 'margin: 0; width: 100%;', 'required' => false, 'placeholder' => __('Unesite od kad je vozilo aktivno'))); ?>
        <?php if ($this->Form->isFieldError('active_from')) {
            echo $this->Form->error('active_from');
        }
        ?>
    </div>
    <div class="clear"></div>
    <div class="col_9">
        <?php echo $this->Form->label('active_to', __('Aktivno do')); ?>
        <?php echo $this->Form->date('active_to', array('label' => false, 'class' => 'col_9', 'style' => 'margin: 0; width: 100%;', 'required' => false, 'placeholder' => __('Unesite do kad je vozilo aktivno'))); ?>
        <?php if ($this->Form->isFieldError('active_to')) {
            echo $this->Form->error('active_to');
        }
        ?>
    </div>
    <div class="clear"></div>
    <div class="col_9">
        <?php echo $this->Form->label('horse_power', __('Konjskih snaga')); ?>
        <?php echo $this->Form->input('horse_power', array('type' => 'number', 'label' => false, 'class' => 'col_9', 'style' => 'margin: 0; width: 100%;', 'required' => false, 'placeholder' => __('Unesite konjske snage vozila'))); ?>
    </div>
    <div class="clear"></div>
    <div class="col_9">
        <?php echo $this->Form->label('engine_capacity_cm3', __('Kubikaža motora')); ?>
        <?php echo $this->Form->input('engine_capacity_cm3', array('type' => 'number', 'label' => false, 'class' => 'col_9', 'style' => 'margin: 0; width: 100%;', 'required' => false, 'placeholder' => __('Unesite kubikažu vozila'))); ?>
    </div>
    <div class="clear"></div>
    <div class="col_9">
        <?php echo $this->Form->label('year_of_production', __('Godina proizvodnje')); ?>
        <?php echo $this->Form->input('year_of_production', array('type' => 'number', 'label' => false, 'class' => 'col_9', 'style' => 'margin: 0; width: 100%;', 'required' => false, 'placeholder' => __('Unesite godinu proizvodnje'))); ?>
    </div>
    <div class="clear"></div>
    <div class="col_9">
        <?php echo $this->Form->label('color', __('Boja vozila')); ?>
        <?php echo $this->Form->input('color', array('type' => 'text', 'label' => false, 'class' => 'col_9', 'style' => 'margin: 0; width: 100%;', 'required' => false, 'placeholder' => __('Unesite boju vozila'))); ?>
    </div>
    <div class="clear"></div>

    <div class="col_9">
        <?php echo $this->Form->label('number_of_seats', __('Broj sedišta')); ?>
        <?php echo $this->Form->input('number_of_seats', array('type' => 'number', 'label' => false, 'class' => 'col_9', 'style' => 'margin: 0; width: 100%;', 'required' => false, 'placeholder' => __('Unesite broj sedišta'))); ?>
    </div>
    <div class="clear"></div>

    <div class="col_9">
        <?php echo $this->Form->label('chassis_number', __('Broj šasije')); ?>
        <?php echo $this->Form->input('chassis_number', array('type' => 'text', 'label' => false, 'class' => 'col_9', 'style' => 'margin: 0; width: 100%;', 'required' => false, 'placeholder' => __('Unesite broj šasije'))); ?>
    </div>
    <div class="clear"></div>

    <div class="col_9">
        <?php echo $this->Form->label('engine_number', __('Broj motora')); ?>
        <?php echo $this->Form->input('engine_number', array('type' => 'text', 'label' => false, 'class' => 'col_9', 'style' => 'margin: 0; width: 100%;', 'required' => false, 'placeholder' => __('Unesite broj motora'))); ?>
    </div>
    <div class="clear"></div>

    <div class="col_9">
        <?php echo $this->Form->label('date_of_purchase', __('Datum kupovine')); ?>
        <?php echo $this->Form->date('date_of_purchase', array(/*'type' => 'date',*/'label' => false, 'class' => 'col_9', 'style' => 'margin: 0; width: 100%;', 'required' => false, 'placeholder' => __('Unesite datum kupovine'))); ?>
        <?php if ($this->Form->isFieldError('date_of_purchase')) {
            echo $this->Form->error('date_of_purchase');
        }
        ?>
    </div>
    <div class="clear"></div>

    <div class="col_9">
        <?php echo $this->Form->label('price', __('Cena')); ?>
        <?php echo $this->Form->input('price', array('type' => 'number', 'label' => false, 'class' => 'col_9', 'style' => 'margin: 0; width: 100%;', 'required' => false, 'placeholder' => __('Unesite cenu vozila'))); ?>
    </div>
    <div class="clear"></div>

    <?php
    if ($action == 'edit') {
    ?>
        <div class="col_4 left inline">
            <?php echo $this->Form->label('in_use', __('U upotrebi')); ?>
            <?php echo $this->Form->input('in_use', array('type' => 'checkbox', 'label' => false, 'class' => 'check', 'style' => 'margin: 0;width:1em; height: 1em;', 'required' => false, 'placeholder' => __('Izaberite da li je u upotrebi'))); ?>
        </div>
        <!-- <div class="col_4 left">
        </div> -->
        <div class="clear"></div>
    <?php
    }
    ?>

    <div class="content_text_input">
        <div class="buttons_box">
            <div class="button_box">
                <?php
                echo $this->Form->submit(__('Snimi'), array(
                    'div' => false,
                    'class' => "button blue",
                    'style' => "margin:20px 0 20px 0;"
                ));
                ?>
            </div>
            <div class="button_box">
                <?php echo $this->Html->link(__('Nazad'), array('action' => 'index'), array('class' => 'button', 'style' => 'margin:20px 0 20px 0;')); ?>
                <?php echo $this->Form->end(); ?>
            </div>
        </div>
    </div>

</div>



<hr>
<hr>
<hr>
<hr>
<hr>