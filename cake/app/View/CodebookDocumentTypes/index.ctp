<div class="breadcrumbs_holder">
    <ul class="breadcrumbs">
        <li><?php echo $this->Html->link(__('Početna'), '/'); ?></li>
        <li><?php echo $this->Html->link(__('Finansijsko knjigovodstvo'), array('controller' => 'ErpModules', 'action' => 'start', 'financial')); ?></li>
        <li class="last"><a href="" onclick="return false"><?php echo __('Vrste dokumenata'); ?></a></li>
    </ul>
</div>
<div id='alert'><?php echo $this->Session->flash(); ?></div>

<div class="name_add_search">
    <div class="name_of_page">
        <h3><i class="icon-beaker"></i> <?php echo __('Vrste dokumenata'); ?></h3>        
    </div>
    <div style="float:right; margin:20px 24px 0 0;">
        <ul class="button-bar">
            <li class="first last"><?php echo $this->Html->link('<i class="icon-plus-sign"></i> '.__('Dodaj novu vrstu'), array('controller' => 'CodebookDocumentTypes', 'action' => 'save'), array('escape' => false)); ?></li>
        </ul>
    </div>
    <div class="clear"></div>
</div>

<div class="content_data meni">
    <fieldset style="margin-top:0;">
        <legend>Meni</legend>
        <?php echo $this->Form->create('CodebookDocumentType', array('type' => 'get', 'action' => 'index')); ?>
        <?php echo $this->Form->input('keywords', array('label' => __('Pretraga'), 'div' => false, 'style' => 'width:65%;', 'placeholder' => __('Unesite ključne reči'))); ?>
        <?php echo $this->Form->checkbox('show_inactive'); ?>
        <?php echo $this->Form->label('show_inactive', __('Prikaži neaktivne')); ?>        
        <?php echo $this->Form->button('Prikaži', array('type' => 'submit', 'class' => 'small green right', 'style' => 'margin-left:10px;')); ?>
        <?php echo $this->Form->end(); ?>
    </fieldset>
</div>

<!-- log list starts -->
<div class="center paginator">
    <?php echo $this->Paginator->first(__('prva strana'), array());?>
    <?php if($this->Paginator->hasPrev()){ ?>
    <?php echo $this->Paginator->prev('« '.__('prethodna'), array(), null, array('class' => 'disabled')); } ?>
    <?php echo $this->Paginator->numbers();?>
    <?php if($this->Paginator->hasNext()){ ?>
    <?php echo $this->Paginator->next(__('sledeća').' »', array(), null, array('class' => 'disabled')); } ?>
    <?php echo $this->Paginator->last(__('poslednja strana'), array()); ?>
</div>
<div class="content_data">
     <?php if(empty($types)){ ?>
        <div class="notice warning">
            <i class="icon-warning-sign icon-large"></i>
            <?php echo __("Nema podataka za zadati upit!"); ?>
        </div>
    <?php }else{ ?>
        <table>
            <thead>
                <tr>                   
                    <th class="left"><?php echo __('Šifra'); ?></th>
                    <th class="left"><?php echo __('Naziv'); ?></th>
                    <th class="center"><?php echo __('Aktivan'); ?></th>
                    <th class="center"><?php echo __('Kreiran'); ?></th>
                    <th class="center">&nbsp;</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($types as $type): ?>
                <tr>
                    <td class="left"><?php echo $type['CodebookDocumentType']['code']; ?></td>
                    <td class="left"><?php echo $type['CodebookDocumentType']['name']; ?></td>
                    <td class="center">
                        <?php if(!empty($type['CodebookDocumentType']['active'])){ ?>
                            <span style="color:green;"><?php echo __('DA'); ?></span>
                        <?php }else{ ?>
                            <span style="color:red;"><?php echo __('NE'); ?></span>
                        <?php } ?>
                    </td>              
                    <td class="center"><?php echo date('d.m.Y', strtotime($type['CodebookDocumentType']['created'])); ?></td>
                    <td class="right" style="white-space: nowrap;">
                        <?php if(!empty($type['CodebookDocumentType']['active'])){ ?>
                        <ul class="button-bar">
                            <li class="first last">
                                <?php echo $this->Html->link('<i class="icon-remove" style="color:red;"></i> Deaktiviranje vrste', array('controller' => 'CodebookDocumentTypes', 'action' => 'deactivate', $type['CodebookDocumentType']['id']), array('escape' => false), __("Da li ste sigurni da želite da deaktivirate vrstu ".$type['CodebookDocumentType']['code']."?")); ?>
                            </li>
                        </ul>
                        <?php }else{ ?>
                            &nbsp;
                        <?php } ?>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    <?php } ?>
    <!-- log list ends -->
</div>
<div class="center paginator">
    <?php echo $this->Paginator->first(__('prva strana'), array());?>
    <?php if($this->Paginator->hasPrev()){ ?>
    <?php echo $this->Paginator->prev('« '.__('prethodna'), array(), null, array('class' => 'disabled')); } ?>
    <?php echo $this->Paginator->numbers();?>
    <?php if($this->Paginator->hasNext()){ ?>
    <?php echo $this->Paginator->next(__('sledeća').' »', array(), null, array('class' => 'disabled')); } ?>
    <?php echo $this->Paginator->last(__('poslednja strana'), array()); ?>            
</div>
<div class="clear"></div>
<div class="submit_loader">
    <?php echo $this->Html->image('submit_loader.gif', array('alt' => 'Loader')); ?>
    <h2>Molimo sačekajte...</h2>
</div> 
<script type="text/javascript">
$('#container').ready(function(){
    //Hide ajax loader
    $(".submit_loader").hide();
});
</script>