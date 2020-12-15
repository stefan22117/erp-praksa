<ul class="breadcrumbs">
    <li><?php echo $this->Html->link('Home', '/'); ?></li>
    <li><?php echo $this->Js->link('Menus', array('controller' => 'Menus', 'action' => 'index'), array('update' => '#container', 'buffer' => false, 'before' => '$(".submit_loader").show();', 'success' => 'history.pushState(null, "MikroERP - List menus", this.url); $(document).attr("title", "MikroERP - List menus");')); ?></li>
    <li class="last"><a href="" onclick="return false">Menu Elements</a></li>
</ul>
<div id='alert'><?php echo $this->Session->flash(); ?></div>
<div class="name_add_search">
    <div class="name_of_page"><h3>Add Menu Element</h3></div>
    <div class="add_and_search">
        <?php echo $this->Js->link('<i class="icon-plus-sign"></i> Add menu element', array('controller' => 'MenuElements', 'action' => 'add'), array('update' => '#container', 'htmlAttributes' => array('class' => 'button small blue adduser', 'escape' => false), 'buffer' => false, 'before' => '$(".submit_loader").show();', 'success' => 'history.pushState(null, "MikroERP - Add Menu element", this.url); $(document).attr("title", "MikroERP - Add Menu element");')) ;?>
        <div class="search"><?php echo $this->Form->create('MenuElement', array('action' => 'search')); ?>
        <?php echo $this->Form->input('name', array('label' => false, 'class' => 'input_search', 'placeholder' => 'Enter search data'));?>
        <?php echo $this->Js->submit('Search', array(
                    'url' => array('action' => 'search'),
                    'update' => '#container',
                    'div' => false,
                    'class' => "button_search",
                    'buffer' => false,
                    'before' => '$(".submit_loader").show();',
                    'success' => 'history.pushState(null, "MikroERP - Search", "'.Router::url(array('controller' => 'MenuElements', 'action' => 'search')).'"); $(document).attr("title", "MikroERP - Search");'
                ));?>
        <?php echo $this->Form->end(); ?></div>
    </div>
</div>

<div class='content_data'>
    <table>
    <thead><tr><th>ID</th><th>Title</th><th>Menu</th><th>Controller</th><th>Action</th><th></th></tr></thead>
        <tbody>
            <?php foreach($menuelements as $menuelement): ?>
            <tr>
            <td><?php echo $menuelement['MenuElement']['id']; ?></td>
            <td><?php echo $menuelement['MenuElement']['title']; ?></td>
            <td><?php echo $menuelement['Menu']['title']; ?></td>
            <td><?php echo $menuelement['MenuElement']['controller']; ?></td>
            <td><?php echo $menuelement['MenuElement']['action']; ?></td>
            <td class="right">
            <ul class="button-bar">
            <li class="first"><?php echo $this->Js->link('<i class="icon-edit"></i>Edit', array('action' => 'edit', $menuelement['MenuElement']['id']), array('update' => '#container', 'buffer' => false, 'escape' => false, 'before' => '$(".submit_loader").show();', 'success' => 'history.pushState(null, "MikroERP - Edit menu element", this.url); $(document).attr("title", "MikroERP - Edit menu element");')); ?></li>

            <li class="last"><?php echo $this->Js->link('<i class="icon-trash"></i>Delete', array('action' => 'delete', $menuelement['MenuElement']['id']), array('update' => '#container', 'escape' => false, 'buffer' => false, 'before' => '$(".submit_loader").show();', 'confirm' => "Are you sure you want to delete this menu element?", )); ?></li>
            </ul>
            </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<div class="clear"></div>
<div class="submit_loader">
    <?php echo $this->Html->image('submit_loader.gif', array('alt' => 'Loader')); ?>
    <h2>Molimo sačekajte...</h2>
</div>

<script>
$('#container').ready(function(){
    $(".submit_loader").hide();
});
</script>