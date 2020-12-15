<div class="breadcrumbs_holder">
    <ul class="breadcrumbs">
        <li><?php echo $this->Html->link(__('Početna'), '/'); ?></li>        
        <li><?php echo $this->Html->link(__('Šifarnici'), array('controller' => 'Codebooks', 'action' => 'index')); ?></li>
        <li class="last"><a href="" onclick="return false"><?php echo __('Veze sa dokumentima'); ?></a></li>
    </ul>
</div>
<div id='alert'><?php echo $this->Session->flash(); ?></div>

<div class="name_add_search">
    <div class="name_of_page">
        <h3><i class="icon-credit-card"></i> <?php echo __('Veze sa dokumentima'); ?></h3>
    </div>
    <div style="float:right; margin:20px 24px 0 0;">
        <ul class="button-bar">
            <li class="last">
                <?php echo $this->Html->link('<i class="icon-plus-sign"></i> Dodaj vezu', array('controller' => 'CodebookConnections', 'action' => 'save'), array('escape' => false)); ?>
            </li>
        </ul>
    </div>
    <div class="clear"></div>
</div>

<div class="content_data meni">
    <fieldset style="margin-top:0;">
        <legend>Meni</legend>
        <?php echo $this->Form->create('CodebookConnection', array('type' => 'get', 'action' => 'index')); ?>
        <?php echo $this->Form->input('keywords', array('label' => 'Pretraga:', 'div' => false, 'style' => 'width:800px;', 'placeholder' => __('Unesite reči za pretragu'))); ?>
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
     <?php if(empty($connections)){ ?>
        <div class="notice warning">
            <i class="icon-warning-sign icon-large"></i>
            Nema podataka u bazi!
        </div>
    <?php }else{ ?>
        <table>
            <thead>
                <tr>
                    <th class="left"><?php echo __('Šifra'); ?></th>                   
                    <th class="left"><?php echo __('Naziv'); ?></th>
                    <th class="left"><?php echo __('Šifarnik'); ?></th>                    
                    <th class="left"><?php echo __('Funkcija modela za listanje'); ?></th>
                    <th class="left"><?php echo __('Parametri funkcije'); ?></th>
                    <th class="left">&nbsp;</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($connections as $connection): ?>
                <tr>
                    <td><?php echo $connection['CodebookConnection']['code']; ?></td>
                    <td><?php echo $connection['CodebookConnection']['name']; ?></td>
                    <td><?php echo $this->Html->link($connection['Codebook']['name'], array('controller' => $connection['Codebook']['controller_name'], 'action' => $connection['Codebook']['action_name'])); ?></td>                    
                    <td><?php echo $connection['CodebookConnection']['list_method']; ?></td>
                    <td><?php echo $connection['CodebookConnection']['list_json_query']; ?></td>
                    <td class="right">
                        <ul class="button-bar">
                            <li class="first last">
                                <?php echo $this->Html->link('<i class="icon-edit"></i>', array('controller' => 'CodebookConnections', 'action' => 'save', $connection['CodebookConnection']['id']), array('title' => __('Izmena'), 'escape' => false)); ?>
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
    $(".submit_loader").hide();
});
</script>