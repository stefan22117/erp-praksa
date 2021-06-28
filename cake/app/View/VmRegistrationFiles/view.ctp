<ul class="breadcrumbs">
<li><?php echo $this->Html->link(__('Početna'), array('controller' => 'pages', 'action' => 'display')); ?></li>
    <li><?= $this->Html->link(__('Registracioni fajlovi'), array('controller' => 'vmRegistrationFiles', 'action' => 'index')); ?></li>
    <li class="last"><a href="" onclick="return false"><?php echo __('Detalji'); ?></a></li>
</ul>


<div class="name_of_page">
    <h3><i class="icon-tag" style="color: green;"></i> <i class="icon-file" style="color: darkblue;"></i> <i class="icon-search"></i> <?php echo __('Registracioni fajl: ' . $vm_registration_file['VmRegistrationFile']['title']); ?></h3>
</div>

<div style="float:right; margin:20px 24px 0 0;">
    <ul class="button-bar">
        <li class="first">
            <?php
            echo $this->Html->link(
                '<i class="icon-eye-open" style="color:blue;"></i><i class="icon-tag" style="color:green;"></i> ' . __('Registracija'),
                array(
                    'controller' => 'vmRegistrations',
                    'action' => 'view',
                    $vm_registration_file['VmRegistration']['id']
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
            echo $this->Form->postLink(
                '<i class="icon-download-alt" style="color:brown;"></i> ' . __('Preuzimanje'),
                array(
                    'action' => 'download',
                    $vm_registration_file['VmRegistrationFile']['id']
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
                    $vm_registration_file['VmRegistrationFile']['id']
                ),
                array(
                    'escape' => false,
                    'confirm' =>  __('Da li ste sigurni da želite da izbrišete registracioni fajl?'),
                    'style' => ' font-size: 12px;'
                )
            );
            ?>
        </li>
    </ul>
</div>








<embed src="/img/<?= $vm_registration_file['VmRegistrationFile']['path']; ?>" type="application/pdf" style="width:99.80%;height:120vh;">