<div class="breadcrumbs_holder">
    <ul class="breadcrumbs">
        <li><?php echo $this->Html->link(__('Početna'), '/'); ?></li>        
        <li class="last"><a href="" onclick="return false"><?php echo __('ERP Ikonice'); ?></a></li>
    </ul>
</div>
<div id='alert'><?php echo $this->Session->flash(); ?></div>

<div class="name_add_search">
    <div class="name_of_page">
        <h3><i class="icon-picture"></i> <?php echo __('ERP Ikonice'); ?></h3>        
    </div>
    <div style="float:right; margin:20px 24px 0 0;">
        <ul class="button-bar">
            <li class="first last"><?php echo $this->Html->link('<i class="icon-plus-sign"></i> '.__('Dodaj novu ikonicu'), array('controller' => 'ErpKickstartIcons', 'action' => 'save'), array('escape' => false)); ?></li>
        </ul>
    </div>
    <div class="clear"></div>
</div>

<div class="content_data meni">
    <fieldset style="margin-top:0;">
        <legend>Meni</legend>
        <?php echo $this->Form->create('ErpKickstartIcon', array('type' => 'get', 'action' => 'index')); ?>
        <?php echo $this->Form->input('keywords', array('label' => __('Pretraga'), 'div' => false, 'style' => 'width:80%;', 'placeholder' => __('Unesite ključne reči'))); ?>
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
     <?php if(empty($icons)){ ?>
        <div class="notice warning">
            <i class="icon-warning-sign icon-large"></i>
            <?php echo __("Nema podataka za zadati upit!"); ?>
        </div>
    <?php }else{ ?>
        <table>
            <thead>
                <tr>                   
                    <th class="center">&nbsp;</th>
                    <th class="left"><?php echo __('Naziv'); ?></th>
                    <th class="left"><?php echo __('Klasa'); ?></th>
                    <th class="center">&nbsp;</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($icons as $icon): ?>
                <tr>
                    <td class="center"><i class="<?php echo $icon['ErpKickstartIcon']['icon_class']; ?> icon-2x"></i></td>
                    <td class="left"><?php echo $icon['ErpKickstartIcon']['icon_name']; ?></td>
                    <td class="left"><?php echo $icon['ErpKickstartIcon']['icon_class']; ?></td>                                
                    <td class="right" style="white-space: nowrap;">
                        <ul class="button-bar">
                            <li class="first last">
                                <?php echo $this->Html->link('<i class="icon-edit"></i>', array('controller' => 'ErpKickstartIcons', 'action' => 'save', $icon['ErpKickstartIcon']['id']), array('title' => __('Izmena'), 'escape' => false)); ?>
                            </li>
                        </ul>
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