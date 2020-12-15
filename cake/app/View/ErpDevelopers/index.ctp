<!-- Breadcrumbs -->
<div class="breadcrumbs_holder">
    <ul class="breadcrumbs">
        <li><?php echo $this->Html->link(__('Početna'), '/'); ?></li>
        <li>
            <?php
                echo $this->Html->link(
                    __('Organizacija ERP-a'),
                    array(
                        'controller' => 'ErpUnits',
                        'action' => 'index'
                    )
                );
            ?>
        </li>
        <li class="last"><a href="" onclick="return false"><?php echo __('ERP developeri'); ?></a></li>
    </ul>
</div>
<!-- Messages -->
<div id='alert'><?php echo $this->Session->flash(); ?></div>
<!-- Top bar -->
<div class="name_add_search">
    <!-- Page title -->
    <div class="name_of_page">
        <h3><i class="icon-laptop"></i> <?php echo __('ERP developeri'); ?></h3>
    </div>
    <!-- Buttons -->
    <div style="float:right; margin:20px 24px 0 0;">
        <ul class="button-bar">
            <li class="first last">
                <?php
                    echo $this->Html->link(
                        '<i class="icon-plus-sign" style="color:green;"></i> '.__('Dodaj developera'),
                        array(
                            'controller' => 'ErpDevelopers',
                            'action' => 'save'
                        ),
                        array(
                            'escape' => false
                        )
                    );
                ?>
            </li>
        </ul>
    </div>
    <div class="clear"></div>
</div>
<!-- Search and filters -->
<div class="content_data meni">
    <fieldset style="margin-top:0;">
        <?php
            echo $this->Form->create(
                'ErpDeveloper',
                array(
                    'type' => 'get',
                    'action' => 'index'
                )
            );
        ?>
        <?php
            echo $this->Form->input(
                'keywords',
                array(
                    'label' => 'Pretraga:',
                    'div' => false,
                    'style' => 'width: 250px;',
                    'placeholder' => __('Unesite reči za pretragu')
                )
            );
        ?>
        <?php
            echo $this->Form->button(
                'Prikaži',
                array(
                    'type' => 'submit',
                    'class' => 'small green right',
                    'style' => 'margin-left: 10px;'
                )
            );
        ?>
        <?php echo $this->Form->end(); ?>
    </fieldset>
</div>
<!-- Pagination -->
<div style="float:left; margin-left:20px; padding: 0px 10px;">
    <?php echo $this->Paginator->counter(__('Ukupno').': {:count}'); ?>
</div>
<?php echo $this->element('paginator'); ?>
<div class="clear"></div>
<!-- Main content -->
<div class="content_data">
     <?php if(empty($erpDevelopers)): ?>
        <div class="notice warning">
            <i class="icon-warning-sign icon-large"></i>
            Nema podataka u bazi!
        </div>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th><?php echo __('Korisnik'); ?></th>
                    <th><?php echo __('Aktivan'); ?></th>
                    <th width="100"></th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($erpDevelopers as $erpDeveloper): ?>
                <tr>
                    <td>
                        <?php
                            if(empty($erpDeveloper['User']['avatar_link'])) {
                                echo $this->Html->image(
                                    'company/male.jpg',
                                    array(
                                        'alt' => 'Default Avatar',
                                        'style' => 'width:30px; height:30px; margin-right: 15px;'
                                    )
                                );
                            } else {
                                echo $this->Html->image(
                                    'http://10.101.15.233:81/img/'.$erpDeveloper['User']['avatar_link'],
                                    array(
                                        'alt' => 'Avatar',
                                        'style' => 'width:30px; height:30px; margin-right: 15px;'
                                    )
                                );
                            }
                        ?>
                        <?php echo $erpDeveloper['User']['first_name'].' '.$erpDeveloper['User']['last_name']; ?>
                    </td>
                    <td>
                        <?php if ($erpDeveloper['ErpDeveloper']['active']): ?>
                            <span style="color:green;">DA</span>
                        <?php else: ?>
                            <span style="color:red;">NE</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <ul class="button-bar">
                            <li class="first">
                                <?php
                                    echo $this->Html->link(
                                        '<i class="icon-edit" style="color:orange;"></i>',
                                        array(
                                            'controller' => 'ErpDevelopers',
                                            'action' => 'save',
                                            $erpDeveloper['ErpDeveloper']['id']
                                        ),
                                        array(
                                            'title' => __('Izmena'),
                                            'escape' => false
                                        )
                                    );
                                ?>
                            </li>
                            <li class="last">
                                <?php
                                    echo $this->Html->link(
                                        '<i class="icon-trash" style="color:red;"></i>',
                                        array(
                                            'controller' => 'ErpDevelopers',
                                            'action' => 'delete',
                                            $erpDeveloper['ErpDeveloper']['id']
                                        ),
                                        array(
                                            'title' => __('Brisanje'),
                                            'escape' => false,
                                            'confirm' => 'Da li ste sigurni da želite da obrišete developera?'
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
</div>
<!-- Pagination -->
<?php echo $this->element('paginator'); ?>
<div class="clear"></div>
<!-- Loader -->
<div class="submit_loader">
    <?php echo $this->Html->image('submit_loader.gif', array('alt' => 'Loader')); ?>
    <h2>Molimo sačekajte...</h2>
</div> 
<!-- Custom script -->
<script type="text/javascript">
    $(document).ready(function() {
        $('.submit_loader').hide();
    });
</script>
