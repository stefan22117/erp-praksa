<!-- TAB_DAMAGES -->
<div id="tab_vm_damages" class="tab-content">
    <div style="float:left; margin:0 0 24px 20px;">
        <ul class="button-bar">
            <li class="first">
                <?php echo $this->Html->link(
                    '<i class="icon-plus-sign" style="color :#669E00"></i> ' . __('Dodaj novu štetu'),
                    'javascript:void(0);',
                    array(
                        'id' => 'addNewDamage', 'escape' => false,
                        'style' => isset($errors['Damages']) ? 'display: none' : 'display: block'
                    )
                ); ?>
            </li>
        </ul>
    </div>

    <div id="addNewDamageForm" <?php echo isset($errors['Damages']) ? 'style="display: block"' : 'style="display: none"'; ?>>

        <div class="formular">
            <?php echo $this->Form->create('VmDamage', array('novalidate' => 'true', 'url' => array('controller' => 'vmDamages', 'action' => 'save'))); ?>



            <?php echo $this->Form->hidden('vm_vehicle_id', array('value' => $vm_vehicle['VmVehicle']['id'])); ?>



            <div class="col_9">
                <?php echo $this->Form->label('VmDamage.responsible', __('Odgovoran')); ?>
                <?php echo $this->Form->input('VmDamage.responsible', array('type' => 'text', 'label' => false, 'style' => 'margin: 0; width: 100%;', 'required' => false, 'placeholder' => __('Unesite odgovornog za štetu'))); ?>

            </div>
            <div class="col_9">
                <?php echo $this->Form->label('VmDamage.description', __('Opis')); ?>
                <?php echo $this->Form->input('VmDamage.description', array('type' => 'text', 'label' => false, 'style' => 'margin: 0; width: 100%;', 'required' => false, 'placeholder' => __('Unesite opis štete'))); ?>
            </div>
            <div class="col_9">
                <?php echo $this->Form->label('VmDamage.date', __('Datum nastanka štete')); ?>
                <?php echo $this->Form->date('VmDamage.date', array('label' => false, 'class' => 'col_9', 'style' => 'margin: 0; width: 100%;', 'value' => date('Y-m-d'))); ?>
                <?php
                if ($this->Form->isFieldError('VmDamage.date')) {
                    echo $this->Form->error('VmDamage.date');
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
                            array('id' => 'addNewDamageClose', 'class' => 'button', 'style' => 'margin:20px 0 20px 0;')
                        );
                        ?>
                        <?php echo $this->Form->end(); ?>
                    </div>
                </div>
            </div>



        </div>

    </div>







    <?php if (empty($vm_damages)) : ?>
        <div class="notice warning">
            <i class="icon-warning-sign icon-large"></i>
            <?php echo __('Nema dodatih šteta'); ?>
            <a class="icon-remove" href="#close"></a>
        </div>
    <?php else : ?>
        <table style="table-layout: fixed;">

            <thead>
                <th><?php echo __('Odgovoran'); ?></th>
                <th><?php echo __('Opis'); ?></th>
                <th><?php echo __('Datum nastanka štete'); ?></th>
                <th><?php echo __('Popravljena'); ?></th>
                <th></th>
            </thead>
            <tbody>
                <?php foreach ($vm_damages as $vm_damage) : ?>
                    <tr>
                        <td><?php echo $vm_damage['VmDamage']['responsible']; ?></td>
                        <td><?php echo $vm_damage['VmDamage']['description']; ?></td>
                        <td><?php echo $vm_damage['VmDamage']['date']; ?></td>

                        <?php if ($vm_damage['VmDamage']['repaired']) : ?>
                            <td style="background-color: #eeffee;">
                                <?php echo __('Popravljena'); ?>
                            </td>
                        <?php else : ?>
                            <td style="background-color: #feffee;">
                                <?php echo __('U kvaru'); ?>
                            </td>

                        <?php endif; ?>

                        <td>
                            <ul class="button-bar">

                                <li class="first">
                                    <?php echo $this->Html->link('<i class="icon-eye-open" style="color :blue"></i>', array('controller' => 'vmDamages', 'action' => 'view', $vm_damage['VmDamage']['id']), array('title' => __('Popravke'), 'escape' => false)); ?>
                                </li>

                                <li>
                                    <?php echo $this->Html->link('<i class="icon-edit" style="color:orange"></i>', array('controller' => 'vmDamages', 'action' => 'save', $vm_damage['VmDamage']['id']), array('title' => __('Brisanje'), 'escape' => false)); ?>
                                </li>

                                <li class="last">
                                    <?php echo $this->Html->link('<i class="icon-trash" style="color:red"></i>', array('controller' => 'vmDamages', 'action' => 'delete', $vm_damage['VmDamage']['id']), array('title' => __('Brisanje'), 'escape' => false)); ?>
                                </li>

                            </ul>

                        </td>
                    </tr>


                <?php endforeach; ?>

            </tbody>
        </table>


    <?php endif; ?>
</div>