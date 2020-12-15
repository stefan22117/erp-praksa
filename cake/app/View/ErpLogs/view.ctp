<div class="breadcrumbs_holder">
    <ul class="breadcrumbs">
        <li><?php echo $this->Html->link('Home', '/'); ?></li>
        <li><?php echo $this->Js->link('Logs', array('controller' => 'erplogs', 'action' => 'index'), array('update' => '#container', 'buffer' => false, 'before' => '$(".submit_loader").show();', 'success' => 'history.pushState(null, "MikroERP - Logs", this.url); $(document).attr("title", "MikroERP - Logs ");')); ?></li>
        <li class="last"><a href="" onclick="return false">Log</a></li>
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
                        'update' => '#container',
                        'div' => false,
                        'class' => "button_search",
                        'buffer' => false,
                        'before' => '$(".submit_loader").show();',
                        'success' => 'history.pushState(null, "MikroERP - Search", "'.Router::url(array('controller' => 'erplogs', 'action' => 'search')).'"); $(document).attr("title", "MikroERP - Search");'
                    ));?>
            <?php echo $this->Form->end(); ?>
        </div>
    </div>
</div>
<!-- heading, search ends -->
<div class="center"></div>
<!-- log list starts -->
<div class='content_data'>
    <table>
        <tr><td class="bold">ID</td><td><?php echo $view['ErpLog']['id']; ?></td></tr>
        <tr><td class="bold">IP address</td><td><?php echo $view['ErpLog']['ip_address']; ?></td></tr>
        <tr><td class="bold">Created</td><td><?php echo $view['ErpLog']['created']; ?></td></tr>
        <tr><td class="bold">User</td><td><?php echo $view['User']['username']; ?></td></tr>
        <tr><td class="bold">Controller</td><td><?php echo $view['ErpLog']['controller']; ?></td></tr>
        <tr><td class="bold">Action</td><td><?php echo $view['ErpLog']['action']; ?></td></tr>
        <tr><td class="bold">Description</td><td><?php echo $view['ErpLog']['description']; ?></td></tr>
        <tr><td class="bold">Input data</td><td><?php echo $view['ErpLog']['input_data_source']; ?></td></tr>
        <tr><td class="bold">Input data source</td><td><?php echo wordwrap($view['ErpLog']['input_data'], 150, "<br />\n", true); ?></td></tr>
    </table>
<!-- log list ends -->        
</div>

<div class="clear"></div>
<div class="submit_loader">
    <?php echo $this->Html->image('submit_loader.gif', array('alt' => 'Loader')); ?>
    <h2>Molimo sačekajte...</h2>
</div> 
<?php echo $this->Js->writeBuffer(); ?>

<script type="text/javascript">
$('#container').ready(function(){
    $(".submit_loader").hide();
});

</script>