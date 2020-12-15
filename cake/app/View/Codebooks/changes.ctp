<div class="breadcrumbs_holder">
    <ul class="breadcrumbs">
        <li><?php echo $this->Html->link(__('Početna'), '/'); ?></li>        
        <li><?php echo $this->Html->link(__('Šifarnici'), array('controller' => 'Codebooks', 'action' => 'index')); ?></li>
        <li><?php echo $this->Html->link($codebook['Codebook']['name'], array('controller' => $codebook['Codebook']['controller_name'], 'action' => $codebook['Codebook']['action_name'])); ?></li>
        <li class="last"><a href="" onclick="return false"><?php echo __('Istorija promena'); ?></a></li>
    </ul>
</div>
<div id='alert'><?php echo $this->Session->flash(); ?></div>

<div class="name_add_search">
    <div class="name_of_page">
        <h3><i class="icon-time"></i> <?php echo __('Istorija promena'); ?></h3>
    </div>
    <div class="clear"></div>
</div>
<div class="content_data">
    <table>
        <thead>
            <tr>
                <th colspan="4">&nbsp;</th>
            </tr>
        </thead>
        <tbody>
            <tr>
               <td><strong><?php echo __("Šifarnik"); ?></strong></td>
               <td><?php echo $codebook['Codebook']['name']; ?></td>
            </tr>
            <tr>
               <td><strong><?php echo __("Naziv"); ?></strong></td>
               <td><?php echo $model_name; ?></td>               
            </tr>
        </tbody>
    </table>
</div>
<div class="content_data meni">
    <fieldset style="margin-top:0;">
        <legend>Filter</legend>        
        <?php 
            echo $this->Form->create('Codebook', array(
                'type' => 'get',
                'url' => array('controller' => 'Codebooks', 'action' => 'changes', $codebook['Codebook']['controller_name'], $codebook_record_id)
            ));
        ?>        
        <?php echo $this->Form->input('keywords', array('label' => 'Pretraga:', 'div' => false, 'style' => 'width:100px;', 'placeholder' => __('Unos pretrage'))); ?>
        <?php echo $this->Form->input('date', array('label' => __('za dan').':', 'div' => false, 'style' => 'width:85px;', 'class' => 'date', 'placeholder' => __('Datum'))); ?>
        <?php echo $this->Form->input('key_changed', array('label' => __('za polje').':', 'options' => $field_names, 'empty' => __("Za sva polja"), 'div' => false, 'style' => 'width:130px; margin-right:10px;','required' => false, 'class' => 'dropdown')); ?>
        <?php 
            $sorting = array(
                'date' => __("datumu"),
                'user_id' => __("operateru"),
                'key_changed' => __("polju šifre"),
                'code_count_change' => __("Red. br. promene šifre"),
                'field_count_change' => __("Red. br. promene polja"),
            );
        ?>
        <?php echo $this->Form->input('sorting', array('label' => __('sortiraj po').':', 'options' => $sorting, 'div' => false, 'style' => 'width:180px; margin-right:10px;','required' => false, 'class' => 'dropdown')); ?>
        <?php 
            $sorting_directions = array(
                'desc' => __("opadajući"),
                'asc' => __("rastući")             
            );
        ?>
        <?php echo $this->Form->input('sorting_direction', array('label' => false, 'options' => $sorting_directions, 'div' => false, 'style' => 'margin-right:10px;','required' => false, 'class' => 'dropdown')); ?>
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
     <?php if(empty($changes)){ ?>
        <div class="notice warning">
            <i class="icon-warning-sign icon-large"></i>
            <?php echo __("Nema promena za ovaj šifarnik!"); ?>
        </div>
    <?php }else{ ?>
        <table>
            <thead>
                <tr>                   
                    <th class="left"><?php echo __('Vreme promene'); ?></th>
                    <th class="left"><?php echo __('Operater'); ?></th>                    
                    <th class="left"><?php echo __('Polje šifre'); ?></th>
                    <th class="left"><?php echo __('Prethodni podatak'); ?></th>
                    <th class="center"><?php echo __('Red. br. promene šifre'); ?></th>
                    <th class="center"><?php echo __('Red. br. promene polja'); ?></th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($changes as $change): ?>
                <tr>
                    <td><?php echo date('d.m.Y H:i:s', strtotime($change['CodebookChange']['created'])); ?></td>
                    <td>
                        <?php if(empty($change['User']['avatar_link'])){ ?>
                            <?php echo $this->Html->image('company/avatar_default.png', array('alt' => 'Default Avatar', 'style' => 'width:30px; height:30px; margin-right:8px; vertical-align:middle;'));?>
                        <?php }else{ ?>
                            <?php echo $this->Html->image(str_replace("\\", "/", $change['User']['avatar_link']), array('alt' => $change['User']['username'], 'style' => 'width:40px; height:40px; margin-right:8px; vertical-align:middle;'));?>
                        <?php } ?>                    
                        <?php echo $change['User']['first_name'].' '.$change['User']['last_name']; ?>
                    </td>
                    <td><?php echo $change['CodebookChange']['name_changed']; ?></td>
                    <td><?php echo $change['CodebookChange']['field_previous_data']; ?></td>
                    <td class="center"><?php echo $change['CodebookChange']['codebook_change_count']; ?></td>
                    <td class="center"><?php echo $change['CodebookChange']['field_change_count']; ?></td>
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
    $(".date").datepicker({ dateFormat: "yy-mm-dd" });
    $(".dropdown").select2();
});
</script>