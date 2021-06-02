<ul class="breadcrumbs">
    <li><?php echo $this->Html->link('Home', ['controller'=>'pages', 'action'=>'display']); ?></li>
    <li class="last"><a href="" onclick="return false">Vehicles</a></li>
</ul>
<div id='alert'><?php echo $this->Session->flash(); ?></div>
<div class="name_add_search">
    <div class="name_of_page">
        <h3>Vehicles</h3>
    </div>

    <div class="add_and_search">
        <?php echo $this->Js->link('<i class="icon-plus-sign"></i> Add vechile', array('controller' => 'VmVehicles', 'action' => 'add'), array('update' => '#container', 'htmlAttributes' => array('class' => 'button small blue adduser', 'escape' => false), 'buffer' => false, 'before' => '$(".submit_loader").show();', 'success' => 'history.pushState(null, "MikroERP - Add Vechile", this.url); $(document).attr("title", "MikroERP - Add Vechile");')); ?>
        <div class="search"><?php echo $this->Form->create('Vechile', array('action' => 'search')); ?>
            <?php echo $this->Form->input('marka_i_tip', array('label' => false, 'class' => 'input_search', 'placeholder' => 'Enter search data')); ?>
            <?php echo $this->Form->control('in_use', ['type' => 'checkbox']); ?>
            <?php echo $this->Js->submit('Search', array(
                        'url' => array('action' => 'search'),
                        'update' => '#container',
                        'div' => false,
                        'class' => "button_search",
                        'buffer' => false,
                        'before' => '$(".submit_loader").show();',
                        'success' => 'history.pushState(null, "MikroERP - Search", "'.Router::url(array('controller' => 'VmVehicles', 'action' => 'search')).'"); $(document).attr("title", "MikroERP - Search");'
                        // 'success' => '$(".submit_loader").hide();'
                    ));?>
            <?php echo $this->Form->end(); ?></div>
    </div>
</div>





<div class='content_data'>
    <table>
        <thead><tr><th>Brand and Model</th><th>Registration number</th><th>Registration exp. date</th><th>Year of production</th><th>In use</th><th>Color</th></tr></thead>
        <tbody>
            

            <?php foreach($vehicles as $vehicle): ?>
            <tr>
            <td><?php echo $vehicle['VmVehicle']['brand_and_model']; ?></td>
            <td><?php echo $vehicle['VmVehicle']['reg_number']; ?></td>
            <td><?php 
            if($vehicle['VmRegistration'] && count($vehicle['VmRegistration'])>0){
                // echo 'dsaasddsa';
                $registrations = $vehicle['VmRegistration'];

                usort($registrations, function($a, $b) {return 
                    strtotime($a['expiration_date']) < strtotime($b['expiration_date']) ? 1 : -1;
                });
                 echo $registrations[0]['expiration_date'];
                 if(date("Y-m-d") >= $registrations[0]['expiration_date']){
                     echo '(Istekla)';
                 }
            }
            
            
            
            
            ?></td>
            <td><?php echo $vehicle['VmVehicle']['year_of_production']; ?></td>
           <td><?php if($vehicle['VmVehicle']['in_use'] == 1) echo 'IN USE'; else echo 'FREE'; ?></td>
            <td><?php echo $vehicle['VmVehicle']['color'] ?></td>
            <td class="right">
                <ul class="button-bar">
                <li class="first"><?php echo $this->Html->link('View', array('action' => 'view', $vehicle['VmVehicle']['id'])); ?></li>

                    <li class="first"><?php echo $this->Js->link('<i class="icon-eye-open"></i>View', array('action' => 'view', $vehicle['VmVehicle']['id']), array('update' => '#container', 'buffer' => false, 'escape' => false, 'before' => '$(".submit_loader").show();', 'success' => 'history.pushState(null, "MikroERP - Edit menu", this.url); $(document).attr("title", "MikroERP - Edit menu");')); ?></li>

                    <li><?php echo $this->Js->link('<i class="icon-edit"></i>Edit', array('action' => 'edit', $vehicle['VmVehicle']['id']), array('update' => '#container', 'buffer' => false, 'escape' => false, 'before' => '$(".submit_loader").show();', 'success' => 'history.pushState(null, "MikroERP - Add menu element", this.url); $(document).attr("title", "MikroERP - Add menu element");')); ?></li>

                    <li class="last"><?php echo $this->Js->link('<i class="icon-trash"></i>Delete', array('action' => 'delete', $vehicle['VmVehicle']['id']), array('update' => '#container', 'escape' => false, 'buffer' => false, 'confirm' => "Are you sure you want to delete this vechile?", )); ?></li>
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






<!-- <div class="center paginator">
    <?php echo $this->Paginator->first(__('prva strana'), array());?>
    <?php if($this->Paginator->hasPrev()){ ?>
    <?php echo $this->Paginator->prev('« '.__('prethodna'), array(), null, array('class' => 'disabled')); } ?>
    <?php echo $this->Paginator->numbers();?>
    <?php if($this->Paginator->hasNext()){ ?>
    <?php echo $this->Paginator->next(__('sledeća').' »', array(), null, array('class' => 'disabled')); } ?>
    <?php echo $this->Paginator->last(__('poslednja strana'), array()); ?>            
</div> -->
