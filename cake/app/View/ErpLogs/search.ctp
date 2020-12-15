<?php $this->Paginator->options(array(
    'update' => '#container', 
    'evalScripts' => true,
    'before' => '$(".submit_loader").show();'
    )); ?>
<div class="breadcrumbs_holder">
    <ul class="breadcrumbs">
        <li><?php echo $this->Html->link('Home', '/'); ?></li>
        <li><?php echo $this->Js->link('Logs', array('controller' => 'ErpLogs', 'action' => 'index'), array('update' => '#container', 'buffer' => false, 'before' => '$(".submit_loader").show();', 'success' => 'history.pushState(null, "MikroERP - Logs", this.url); $(document).attr("title", "MikroERP - Logs");')); ?></li>
        <li class="last"><a href="" onclick="return false">Search</a></li>
    </ul>
</div>
<div id='alert'><?php echo $this->Session->flash(); ?></div>

<div class="name_add_search">
    <div class="name_of_page">
        <h3>Logs</h3>
    </div>
    <div class="add_and_search">
        <div class="search">
        <?php echo $this->Form->create('ErpLog', array('action' => 'search')); ?>
        <?php echo $this->Form->input('name', array('label' => false, 'class' => 'input_search pictureInput', 'placeholder' =>'Podaci za pretragu'));?>
        <?php echo $this->Js->submit('Search', array(
                    'url' => array('action' => 'search'),
                    'update' => '#container',
                    'div' => false,
                    'class' => "button_search",
                    'buffer' => false,
                    'before' => '$(".submit_loader").show();',
                    'success' => 'history.pushState(null, "MikroERP - Search", "'.Router::url(array('controller' => 'ErpLogs', 'action' => 'index')).'"); $(document).attr("title", "MikroERP - Search");'
                ));?>
        <?php echo $this->Form->end(); ?>
        </div>
    </div>
</div>
<!-- heading, search ends -->

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
<div class="content_data" id="searchContent">
     <?php if(empty($logs)){ ?>
        <div class="notice warning">
            <i class="icon-warning-sign icon-large"></i>
            No Data Match
            <a class="icon-remove" href="#close"></a>
        </div>
    <?php }else{ ?>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Created</th>
                    <th>User</th>
                    <th>Controller</th>
                    <th>Action</th>
                    <th>Description</th>
                    <th>Input Data Source</th>
                    <th></th> 
                </tr>
            </thead>
            <tbody>
            <?php foreach ($logs as $log): ?>
                <tr class="tdTop">
                    <td><?php echo $log['ErpLog']['id']; ?></td>
                    <td><?php echo $log['ErpLog']['created'];  ?></td>
                    <td><?php echo $log['User']['username'];  ?></td>
                    <td><?php echo $log['ErpLog']['controller'];  ?></td>
                    <td><?php echo $log['ErpLog']['action'];  ?></td>
                    <td><?php echo $log['ErpLog']['description'];  ?></td>
                    <td><?php echo $log['ErpLog']['input_data_source'];  ?></td>
                    <td><?php echo $this->Js->link('<i class="icon-eye-open"></i> View Log', array('action' => 'view', $log['ErpLog']['id']), array('update' => '#container', 'buffer' => false, 'class' => 'button small' , 'escape' => false, 'before' => '$(".submit_loader").show();', 'success' => 'history.pushState(null, "MikroERP - View Log", this.url); $(document).attr("title", "MikroERP - View Log");')); ?></td>
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

<?php echo $this->Js->writeBuffer(); ?>