<!-- Prepare data -->
<?php if(!empty($data)) foreach ($data as $key => $value) ${$key} = $value; ?>
<!-- Breadcrumbs -->
<div class="breadcrumbs_holder">
    <ul class="breadcrumbs">
        <li><?php echo $this->Html->link(__('Početna'), '/'); ?></li>
        <li class="last"><a href="" onclick="return false"><?php echo __('Organizacija ERP-a'); ?></a></li>
    </ul>
</div>
<!-- Messages -->
<div id='alert'><?php echo $this->Session->flash(); ?></div>
<!-- Top bar -->
<div class="name_add_search">
    <!-- Page title -->
    <div class="name_of_page">
        <h3><i class="icon-sitemap"></i> <?php echo $title; ?></h3>
    </div>
    <!-- Buttons -->
    <div style="float:right; margin:20px 24px 0 0;">
        <ul class="button-bar">
            <li class="first">
                <?php
                    echo $this->Html->link(
                        '<i class="icon-arrow-up" style="color:gray;"></i> '.__('Vrati se na vrh'),
                        array(
                            'controller' => 'ErpUnits',
                            'action' => 'index'
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
                        '<i class="icon-plus-sign" style="color:green;"></i> '.__('Dodaj modul'),
                        array(
                            'controller' => 'ErpUnits',
                            'action' => 'save',
                            $level,
                            $parentId
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
                        '<i class="icon-sitemap" style="color:blue;"></i> '.__('Hijerarhijski pregled'),
                        array(
                            'controller' => 'ErpUnits',
                            'action' => 'preview'
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
                    echo $this->Html->link(
                        '<i class="icon-file" style="color:green;"></i> '.__('Uvoz iz Excela'),
                        array(
                            'controller' => 'ErpUnits',
                            'action' => 'importFromExcel'
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
    <div class="clear"></div>
</div>
<!-- Search and filters -->
<div class="content_data meni">
    <fieldset style="margin-top:0;">
        <?php
            echo $this->Form->create(
                'ErpUnit',
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
     <?php if(empty($erpUnits)): ?>
        <div class="notice warning">
            <i class="icon-warning-sign icon-large"></i>
            Nema podataka u bazi!
        </div>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th><?php echo __('Šifra'); ?></th>
                    <th><?php echo __('Naziv'); ?></th>
                    <th><?php echo __('Developeri'); ?></th>
                    <th><?php echo __('Maintaineri'); ?></th>
                    <th><?php echo __('Napomena'); ?></th>
                    <th width="135"></th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($erpUnits as $erpUnit): ?>
                <tr>
                    <td style="font-weight: bold; color: #fa0">
                        <?php echo $erpUnit['ErpUnit']['code']; ?>
                    </td>
                    <td>
                        <?php
                        if($erpUnit['ErpUnit']['level'] != 'subtype') {
                            echo $this->Html->link(
                                $erpUnit['ErpUnit']['name'],
                                array(
                                    'controller' => 'ErpUnits',
                                    'action' => 'index',
                                    $sublevel,
                                    $erpUnit['ErpUnit']['id']
                                )
                            );
                        } else {
                            echo $erpUnit['ErpUnit']['name'];
                        }
                        ?>
                    </td>
                    <td>
                        <?php foreach ($erpUnit['developers'] as $developer): ?>
                            <p><?php echo $developer; ?></p>
                        <?php endforeach; ?>
                    </td>
                    <td>
                        <?php foreach ($erpUnit['maintainers'] as $maintainer): ?>
                            <p><?php echo $maintainer; ?></p>
                        <?php endforeach; ?>
                    </td>
                    <td><?php echo $erpUnit['ErpUnit']['note']; ?></td>
                    <td>
                        <ul class="button-bar">
                            <li class="first">
                                <?php
                                    echo $this->Html->link(
                                        '<i class="icon-eye-open" style="color:blue;"></i>',
                                        array(
                                            'controller' => 'ErpUnits',
                                            'action' => 'view',
                                            $erpUnit['ErpUnit']['id']
                                        ),
                                        array(
                                            'title' => __('Detalji'),
                                            'escape' => false,
                                            'style' => 'font-size: 12px;'
                                        )
                                    );
                                ?>
                            </li>
                            <li>
                                <?php
                                    if(empty($parentId)) $parentId = 'null';
                                    echo $this->Html->link(
                                        '<i class="icon-edit" style="color:orange;"></i>',
                                        array(
                                            'controller' => 'ErpUnits',
                                            'action' => 'save',
                                            $level,
                                            $parentId,
                                            $erpUnit['ErpUnit']['id']
                                        ),
                                        array(
                                            'title' => __('Izmena'),
                                            'escape' => false,
                                            'style' => 'font-size: 12px;'
                                        )
                                    );
                                ?>
                            </li>
                            <li class="last">
                                <?php
                                    echo $this->Html->link(
                                        '<i class="icon-trash" style="color:red;"></i>',
                                        array(
                                            'controller' => 'ErpUnits',
                                            'action' => 'delete',
                                            $level,
                                            $parentId,
                                            $erpUnit['ErpUnit']['id']
                                        ),
                                        array(
                                            'title' => __('Brisanje'),
                                            'escape' => false,
                                            'confirm' => 'Da li ste sigurni da želite da obrišete modul?',
                                            'style' => 'font-size: 12px;'
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
