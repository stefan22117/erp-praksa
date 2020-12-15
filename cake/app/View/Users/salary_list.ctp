<ul class="breadcrumbs">
    <li><?php echo $this->Html->link('Početna', '/'); ?></li>
    <li class="last"><a href="" onclick="return false">Zaposleni (zarade)</a></li>
</ul>
<div id='alert'><?php echo $this->Session->flash(); ?></div>

<div class="name_add_search">
    <div class="name_of_page"><h3>Zaposleni</h3></div>
    <div class="add_and_search right">
        <div class="search"><?php echo $this->Form->create('User', array('action' => 'salary_list_search')); ?>
        <?php echo $this->Form->input('keyWord', array('label' => false, 'class' => 'input_search pictureInput', 'placeholder' => 'Podaci za pretragu'));?>
        <?php echo $this->Form->submit('Pretraga', array(
                    'url' => array('action' => 'search'),
                    'div' => false,
                    'class' => "button_search",
                ));?>
        <?php echo $this->Form->end(); ?></div>
    </div>
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

<div class='content_data'>
<?php if(empty($users)){ ?>
        <div class="notice warning">
                <i class="icon-warning-sign icon-large"></i>
                    U sistemu nema zaposlenih.
            </div>
<?php }else{ ?>
<table>
    <tr>
        <th style="width:200px;">Zaposleni</th>
        <th style="width:200px;">Iznos zarade</th>
        <th style="width:200px;">Valuta</th>
        <th style="text-align:right;">&nbsp;</th>
    </tr>
    <?php foreach ($users as $user) { ?>
    <tr>
        <td><?php echo $user['User']['name']; ?></td>
        <td><?php echo number_format($user['User']['salary'],2,',','.'); ?></td>
        <td><?php echo $user['Currency']['currency']; ?></td>
        <td class="right">
            <ul class="button-bar">
                <li class="first last">
                    <?php echo $this->Html->link('<i class="icon-money"></i>', 
                        array(
                                'action' => 'salary', $user['User']['id']
                                ), 
                        array(
                            'title' => 'Pregled i izmena', 
                            'escape' => false, 
                            'style' =>'color:green;'
                            )
                    ); ?>
                </li> ?>
            </ul>
        </td>
    </tr>
    <?php } ?>
</table>   
<?php } ?>
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


<script>
$('#container').ready(function(){

});

</script>