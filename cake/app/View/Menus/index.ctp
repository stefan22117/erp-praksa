<ul class="breadcrumbs">
    <li><?php echo $this->Html->link('Home', '/'); ?></li>
    <li class="last"><a href="" onclick="return false">Menus</a></li>
</ul>
<div id='alert'><?php echo $this->Session->flash(); ?></div>

<div class="name_add_search">
    <div class="name_of_page"><h3>Add Menu</h3></div>
    <div class="add_and_search">
        <?php echo $this->Js->link('<i class="icon-plus-sign"></i> Add menu', array('controller' => 'Menus', 'action' => 'add'), array('update' => '#container', 'htmlAttributes' => array('class' => 'button small blue adduser', 'escape' => false), 'buffer' => false, 'before' => '$(".submit_loader").show();', 'success' => 'history.pushState(null, "MikroERP - Add Menu", this.url); $(document).attr("title", "MikroERP - Add Menu");')) ;?>
        <div class="search"><?php echo $this->Form->create('Menu', array('action' => 'search')); ?>
            <?php echo $this->Form->input('name', array('label' => false, 'class' => 'input_search', 'placeholder' => 'Enter search data'));?>
            <?php echo $this->Js->submit('Search', array(
                        'url' => array('action' => 'search'),
                        'update' => '#container',
                        'div' => false,
                        'class' => "button_search",
                        'buffer' => false,
                        'before' => '$(".submit_loader").show();',
                        'success' => 'history.pushState(null, "MikroERP - Search", "'.Router::url(array('controller' => 'Menus', 'action' => 'search')).'"); $(document).attr("title", "MikroERP - Search");'
                    ));?>
            <?php echo $this->Form->end(); ?></div>
    </div>
</div>


<div class='content_data'>
    <table>
        <thead><tr><th>ID</th><th>Menu name</th><th>Parent</th><th>Group</th><th>User</th><th>Controller</th><th>Action</th><th>allowed</th><th></th></tr></thead>
        <tbody>
            <?php /*
            foreach($parents as $parent):
                $menu_parent['id'] = $parent['Menu']['id'];
                $menu_parent['title'] = $parent['Menu']['title'];
                echo $menu_parent['title'];
            endforeach; */?>

            <?php foreach($menus as $menu): ?>
            <tr>
            <td><?php echo $menu['Menu']['id']; ?></td>
            <td><?php echo $menu['Menu']['title']; ?></td>
            <td><?php echo $menu['Menu']['parent_id']; ?></td>
            <td><?php echo $menu['Group']['name']; ?></td>
            <td><?php echo $menu['User']['username']; ?></td>
            <td><?php echo $menu['Menu']['controller']; ?></td>
            <td><?php echo $menu['Menu']['action']; ?></td>
            <td><?php if($menu['Menu']['allowed'] == 1) echo 'allowed'; else echo 'deny'; ?></td>
            <td class="right">
                <ul class="button-bar">
                    <li class="first"><?php echo $this->Js->link('<i class="icon-edit"></i>Edit', array('action' => 'edit', $menu['Menu']['id']), array('update' => '#container', 'buffer' => false, 'escape' => false, 'before' => '$(".submit_loader").show();', 'success' => 'history.pushState(null, "MikroERP - Edit menu", this.url); $(document).attr("title", "MikroERP - Edit menu");')); ?></li>

                    <li><?php echo $this->Js->link('<i class="icon-plus-sign"></i>Add element', array('controller' => 'MenuElements', 'action' => 'add', $menu['Menu']['id']), array('update' => '#container', 'buffer' => false, 'escape' => false, 'before' => '$(".submit_loader").show();', 'success' => 'history.pushState(null, "MikroERP - Add menu element", this.url); $(document).attr("title", "MikroERP - Add menu element");')); ?></li>

                    <li class="last"><?php echo $this->Js->link('<i class="icon-trash"></i>Delete', array('action' => 'delete', $menu['Menu']['id']), array('update' => '#container', 'escape' => false, 'buffer' => false, 'confirm' => "Are you sure you want to delete this menu?", )); ?></li>
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