<!-- TAB_FILES -->
<div id="tab_vm_vehicle_files" class="tab-content">
    <div style="float:left; margin:0 0 24px 20px;">
        <ul class="button-bar">
            <li class="first">
                <?php echo $this->Html->link('<i class="icon-plus-sign" style="color :#669E00"></i> ' . __('Dodaj novi fajl'), 
                'javascript:void(0);',
                 array('id' => 'addNewFile', 'escape' => false,
                 'style' => isset($errors['Files']) ? 'display: none' : 'display: block'
                 )); ?>
            </li>
        </ul>
    </div>

    <div id="addNewFileForm" <?php echo isset($errors['Files']) ? 'style="display: block"' : 'style="display: none"'; ?>>

        <div class="formular">
            <?php echo $this->Form->create('VmVehicleFile', array('type' => 'file', 'url' => array('controller' => 'vmVehicleFiles', 'action' => 'add', $vm_vehicle['VmVehicle']['id']))); ?>

            <?php echo $this->Form->hidden('vm_vehicle_id', array('value' => $vm_vehicle['VmVehicle']['id'])); ?>



            <div class="col_9">
                <?php echo $this->Form->label('title', __('Naslov fajla')); ?>
                <?php echo $this->Form->input('title', array('type' => 'text', 'label' => false, 'style' => 'margin: 0; width: 100%;', 'required' => false, 'placeholder' => __('Unesite naslov fajla'))); ?>
            </div>
            <div class="col_9">
                <?php echo $this->Form->input('file', array('type' => 'file', 'label' => false, 'style' => 'margin: 0; width: 100%;', 'required' => false, 'placeholder' => __('Izaberite fajl'))); ?>
                <?php
                if ($this->Form->isFieldError('VmVehicleFile.path')) {
                    echo $this->Form->error('VmVehicleFile.path');
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
                        <?php echo $this->Html->link(__('Poništi'), 
                        'javascript:void(0)', 
                        array('id' => 'addNewFileClose', 'class' => 'button', 'style' => 'margin:20px 0 20px 0;'));
                        ?>
                        <?php echo $this->Form->end(); ?>
                    </div>
                </div>
            </div>



        </div>

    </div>





    <?php if (empty($vm_vehicle_files)) : ?>
        <div class="notice warning">
            <i class="icon-warning-sign icon-large"></i>
            <?php echo __('Nema dodatih fajlova'); ?>
            <a class="icon-remove" href="#close"></a>
        </div>
    <?php else : ?>
        <table style="table-layout: fixed;">

            <thead>
                <th><?php echo __('Naslov'); ?></th>
                <th><?php echo __('Vreme unosa'); ?></th>
                <th></th>
            </thead>
            <tbody>
                <?php foreach ($vm_vehicle_files as $vm_vehicle_file) : ?>
                    <tr>
                        <td><?php echo $vm_vehicle_file['VmVehicleFile']['title']; ?></td>
                        <td><?php echo $vm_vehicle_file['VmVehicleFile']['created']; ?></td>
                        <td>
                            <ul class="button-bar">


                                <li class="first">
                                    <?php echo $this->Html->link(
                                        '<i class="icon-eye-open" style="color :blue"></i>',
                                        array('controller' => 'vmVehicleFiles', 'action' => 'view', $vm_vehicle_file['VmVehicleFile']['id']),
                                        array('title' => __('Detalji'), 'escape' => false)
                                    ); ?>
                                </li>
                                <li>
                                    <?php echo $this->Html->link(
                                        '<i class="icon-download-alt" style="color :brown"></i>',
                                        array('controller' => 'vmVehicleFiles', 'action' => 'download', $vm_vehicle_file['VmVehicleFile']['id']),
                                        array('title' => __('Preuzimanje'), 'escape' => false)
                                    ); ?>
                                </li>
                                <li class="last">
                                    <?php echo $this->Form->postLink(
                                        '<i class="icon-trash" style="color :red"></i>',
                                        array('controller' => 'vmVehicleFiles', 'action' => 'delete', $vm_vehicle_file['VmVehicleFile']['id']),
                                        array(
                                            'title' => __('Brisanje'),
                                            'confirm' => 'Da li ste sigurni da želite da obrišete određeni fajl?',
                                            'escape' => false
                                        )
                                    ); ?>
                                </li>
                            </ul>

                        </td>
                    </tr>


                <?php endforeach; ?>

            </tbody>
        </table>


    <?php endif; ?>


</div>