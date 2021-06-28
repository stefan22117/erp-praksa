<ul class="breadcrumbs">
    <li><?php echo $this->Html->link(__('Početna'), array('controller' => 'pages', 'action' => 'display')); ?></li>
    <li><?= $this->Html->link(__('Slike'), array('controller' => 'vmImages', 'action' => 'index')); ?></li>
    <li class="last"><a href="" onclick="return false"><?php echo __('Galerija'); ?></a></li>
</ul>
<div id='alert'><?php echo $this->Session->flash(); ?></div>




<div class="name_add_search">
    <div class="name_of_page">
        <h3><i class="icon-picture" style="color:cyan;"></i> <i class="icon-truck" style="color:black;"></i>
            <?php echo __('Galerija vozila: ' . $vm_vehicle['VmVehicle']['brand_and_model']); ?></h3>
    </div>

    <div style="float:right; margin:20px 24px 0 0;">
        <ul class="button-bar">
            <li>
                <?php
                echo $this->Html->link(
                    '<i class="icon-eye-open" style="color:blue;"></i> <i class="icon-truck" style="color:black;"></i> ' . __('Vozilo'),
                    array(
                        'controller' => 'VmVehicles',
                        'action' => 'view',
                        $vm_vehicle['VmVehicle']['id']
                    ),
                    array(
                        'escape' => false,
                        'style' => ' font-size: 12px;'
                    )
                );
                ?>
            </li>

        </ul>
    </div>

    <div style="float:right; margin:20px 24px 0 0;">
        <ul class="button-bar">
            <li class="first">
                <?php echo $this->Html->link('<i class="icon-plus-sign" style="color :#669E00"></i> ' . __('Dodaj novu sliku'), 'javascript:void(0);', array('id' => 'addNewImage', 'escape' => false)); ?>
            </li>
        </ul>
    </div>
    <div class="clear"></div>
</div>



<div id="addNewImageForm" <?php echo isset($errors['Images']) ? 'style="display: block"' : 'style="display: none"'; ?>>

    <div class="formular">
        <?php echo $this->Form->create('VmImage', array('type' => 'file', 'url' => array('controller' => 'vmImages', 'action' => 'add', $vm_vehicle['VmVehicle']['id']))); ?>

        <?php echo $this->Form->hidden('vm_vehicle_id', array('value' => $vm_vehicle['VmVehicle']['id'])); ?>

        <div class="col_9">
            <?php echo $this->Form->label('title', __('Naslov fajla')); ?>
            <?php echo $this->Form->input('title', array('type' => 'text', 'label' => false, 'style' => 'margin: 0; width: 100%;', 'required' => false, 'placeholder' => __('Unesite naslov fajla'))); ?>
        </div>
        <div class="col_9">
            <?php echo $this->Form->input('file', array('type' => 'file', 'label' => false, 'style' => 'margin: 0; width: 100%;', 'required' => false, 'placeholder' => __('Izaberite fajl'))); ?>
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
                    <?php echo $this->Html->link(
                        __('Poništi'),
                        'javascript:void(0)',
                        array('id' => 'addNewImageFormClose', 'class' => 'button', 'style' => 'margin:20px 0 20px 0;')
                    );
                    ?>
                    <?php echo $this->Form->end(); ?>
                </div>
            </div>
        </div>



    </div>

</div>



<div class="content_data">

    <?php if (empty($vm_vehicle['VmImage'])) : ?>

        <div class="notice warning">
            <i class="icon-warning-sign icon-large"></i>
            <?php echo __('Nema dodatih slika za ovo vozilo'); ?>
            <a class="icon-remove" href="#close"></a>
        </div>

    <?php else : ?>

        <!-- Slideshow -->
        <!-- <ul> -->
        <ul class="slideshow" style="width:99.80%;height:100vh;">
            <?php
            foreach ($vm_vehicle['VmImage'] as $vm_image) :
            ?>

                <li style="position:relative;">

                    <?php
                    echo $this->Html->image($vm_image['path'], array('style' => "width:99.80%;height:100vh;"));
                    ?>


                    <div style="margin:20px 24px 0 0; position:absolute;top: 20px;right: 24px;">
                        <ul class="button-bar">
                            <li class="first">
                                <?php
                                echo $this->Html->link(
                                    '<i class="icon-eye-open" style="color:blue;"></i> ',
                                    array(
                                        'controller' => 'VmImages',
                                        'action' => 'view',
                                        $vm_image['id']
                                    ),
                                    array(
                                        'id'=> 'VmImageView' . $vm_image['id'],
                                        'name'=> 'VmImageView' . $vm_image['id'],
                                        'escape' => false,
                                        'style' => ' font-size: 12px;'
                                    )
                                );
                                ?>
                            </li>
                           


                        </ul>
                    </div>

                </li>
            <?php endforeach; ?>

        </ul>
    <?php endif; ?>
</div>

<script>
    $('#addNewImage').click(function(e) {
        $('#addNewImageForm').show();
        $(this).hide();

    })

    $('#addNewImageFormClose').click(function() {
        $('#addNewImageForm').hide();
        $('#addNewImage').show();
    })
</script>