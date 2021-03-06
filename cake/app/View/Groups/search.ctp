<?php $this->Paginator->options(array(
    'update' => '#container', 
    'evalScripts' => true,
    'before' => '$(".submit_loader").show();',
    'success' => 'history.pushState(null, "MikroERP - Search groups", this.url); $(document).attr("title", "MikroERP - Search groups");'
    )); ?>
<ul class="breadcrumbs">
    <li><?php echo $this->Html->link('Home', '/'); ?></li>
     <li><?php echo $this->Js->link('Groups', array('controller' => 'Groups', 'action' => 'index'), array('update' => '#container', 'buffer' => false, 'before' => '$(".submit_loader").show();', 'success' => 'history.pushState(null, "MikroERP - List groups", this.url); $(document).attr("title", "MikroERP - List groups");')); ?></li>
    <li class="last"><a href="" onclick="return false">Search</a></li>
</ul>
<div id='alert'><?php echo $this->Session->flash(); ?></div>

<div class="name_add_search">
    <div class="name_of_page"><h3>Groups</h3></div>
    <div class="add_and_search">
        <?php echo $this->Js->link('<i class="icon-plus-sign"></i> Add group', array('controller' => 'Groups', 'action' => 'add'), array('update' => '#container', 'htmlAttributes' => array('class' => 'button small blue adduser', 'escape' => false), 'buffer' => false, 'before' => '$(".submit_loader").show();', 'success' => 'history.pushState(null, "MikroERP - Add Group", this.url); $(document).attr("title", "MikroERP - Add Group");')) ;?>
        <div class="search"><?php echo $this->Form->create('Group', array('action' => 'search')); ?>
        <?php echo $this->Form->input('nameSearch', array('label' => false, 'class' => 'input_search pictureInput', 'placeholder' =>'Podaci za pretragu'));?>
        <?php echo $this->Js->submit('Search', array(
                    'url' => array('action' => 'search'),
                    'update' => '#container',
                    'div' => false,
                    'class' => "button_search",
                    'buffer' => false,
                    'before' => '$(".submit_loader").show();',
                    'success' => 'history.pushState(null, "MikroERP - Search", "'.Router::url(array('controller' => 'users', 'action' => 'search')).'"); $(document).attr("title", "MikroERP - Search");'
                ));?>
        <?php echo $this->Form->end(); ?></div>
    </div>
</div>
<div class="center paginator">
    <?php echo $this->Paginator->first(__('prva strana'), array());?>
    <?php if($this->Paginator->hasPrev()){ ?>
    <?php echo $this->Paginator->prev('?? '.__('prethodna'), array(), null, array('class' => 'disabled')); } ?>
    <?php echo $this->Paginator->numbers();?>
    <?php if($this->Paginator->hasNext()){ ?>
    <?php echo $this->Paginator->next(__('slede??a').' ??', array(), null, array('class' => 'disabled')); } ?>
    <?php echo $this->Paginator->last(__('poslednja strana'), array()); ?>            
</div>
<div class='content_data'>
    <?php if(empty($groups)){
        ?>
        <div class="notice warning">
            <i class="icon-warning-sign icon-large"></i>
            No Data Match
            <a class="icon-remove" href="#close"></a>
        </div>
        <?php
    }
    else{
    ?>
    <table>
        <thead>
            <tr>
            <th>ID</th>
            <th class="left">Group Name</th>
            <th></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($groups as $group): ?>
            <tr>
                <td><?php echo $group['Group']['id']; ?></td>
                <td class="left"><?php echo $group['Group']['name']; ?></td>
                <td class="right">
                    <ul class="button-bar">
                        <li class="first">
                            <?php echo $this->Js->link('<i class="icon-user"></i> Set permission', array('controller' => 'Groups', 'action' => 'permissions', $group['Group']['id']), array('update' => '#container', 'buffer' => false, 'escape' => false, 'before' => '$(".submit_loader").show();', 'success' => 'history.pushState(null, "MikroERP - Set permission", this.url); $(document).attr("title", "MikroERP - Set permission");')); ?>
                        </li>
                        <li>
                            <?php echo $this->Js->link('<i class="icon-edit"></i> Edit', array('controller' => 'Groups', 'action' => 'edit', $group['Group']['id']), array('update' => '#container', 'buffer' => false, 'escape' => false, 'before' => '$(".submit_loader").show();', 'success' => 'history.pushState(null, "MikroERP - Edit group", this.url); $(document).attr("title", "MikroERP - Edit group");')); ?>
                          
                        </li>
                        <li class="last">
                              <?php echo $this->Js->link('<i class="icon-trash"></i>Delete', array('controller' => 'Groups', 'action' => 'delete', $group['Group']['id']), array('update' => '#container', 'escape' => false, 'buffer' => false, 'before' => '$(".submit_loader").show();', 'confirm' => "Are you sure you want to delete this group?", )); ?>
                        </li>
                    </ul>
                </td>
            </tr>
             <?php endforeach; ?>
        </tbody>
    </table>
    <?php } ?>
</div>
<div class="center paginator">
    <?php echo $this->Paginator->first(__('prva strana'), array());?>
    <?php if($this->Paginator->hasPrev()){ ?>
    <?php echo $this->Paginator->prev('?? '.__('prethodna'), array(), null, array('class' => 'disabled')); } ?>
    <?php echo $this->Paginator->numbers();?>
    <?php if($this->Paginator->hasNext()){ ?>
    <?php echo $this->Paginator->next(__('slede??a').' ??', array(), null, array('class' => 'disabled')); } ?>
    <?php echo $this->Paginator->last(__('poslednja strana'), array()); ?>            
</div>
<div class="clear"></div>
<div class="submit_loader">
    <?php echo $this->Html->image('submit_loader.gif', array('alt' => 'Loader')); ?>
    <h2>Molimo sa??ekajte...</h2>
</div>


<?php echo $this->Js->writeBuffer(); ?>


<script>
$('#container').ready(function(){
    $(".submit_loader").hide();
    <?php if(!empty($isAjax)){ ?>
        var url = "<?php echo Router::url(array('controller' => 'Groups', 'action' => 'search')); ?>";
        history.pushState(null, "MikroERP - Korisnici", url); $(document).attr("title", "MikroERP - Korisnici");
    <?php } ?>
});
</script>