<?php echo $this->Html->css('Script/financial.css?ver='.filemtime(WWW_ROOT . "/css/Script/financial.css")); ?>
<div class="breadcrumbs_holder">
    <ul class="breadcrumbs">
        <li><?php echo $this->Html->link(__('Početna'), '/'); ?></li>        
        <li class="last"><a href="" onclick="return false"><?php echo __('Konfiguracija'); ?></a></li>
    </ul>
</div>
<div id='alert'><?php echo $this->Session->flash(); ?></div>
<div class="name_add_search" style="margin-left:22px">
    <div class="name_of_page">
        <h3><i class="icon-wrench"></i> <?php echo __('Konfiguracija'); ?></h3>
    </div>
    <div style="float:right; margin:20px 24px 0 0;">
        <ul class="button-bar">
            <li class="first last">
                <?php 
                    echo $this->Html->link('<i class="icon-plus-sign"></i> '.__('Dodaj konfiguraciju'), array(
                        'controller' => 'Configurations', 'action' => 'save'), array('escape' => false)
                    );
                ?>
            </li>
        </ul>
    </div>
    <div class="clear"></div>
</div>

<div class="clear"></div>

<div class="content_data meni" style="margin:0 0 0 44px;">
    <fieldset style="margin:0;">
        <legend><?php echo __('Meni'); ?></legend>
        <?php 
            echo $this->Form->create('Configuration', array(
                'type' => 'get',
                'url' => array('controller' => 'Configurations', 'action' => 'index')
            ));
        ?>
        <?php 
            echo $this->Form->input('keywords', array(
                'label' => __('Pretraga'), 'div' => false, 'style' => 'width:200px;',
                'placeholder' => __('Unesite reči za pretragu')
            ));
        ?>     
        <?php echo $this->Form->label('type', __('po vrsti konfiguracionog taga'), array('style' => 'margin:0 10px 0 10px;')); ?>
        <?php
            echo $this->Form->input('type', array(
                'label' => false, 'options' => $types, 'div' => false, 'style' => 'width:400px;', 'empty' => '',
                'class' => 'dropdown', 'required' => false, 'empty' => __("Sve vrste")
            ));
        ?>        
        <?php 
            echo $this->Form->button('Prikaži', array(
                'type' => 'submit', 'class' => 'small green right', 'style' => 'margin-left:10px;'
            ));
        ?>
        <?php echo $this->Form->end(); ?>
    </fieldset>
</div>

<div class="clear"></div>

<div style="margin: 10px 12px 0 44px;">
    <div class="center paginator">
        <?php echo $this->Paginator->first(__('prva strana'), array());?>
        <?php if($this->Paginator->hasPrev()){ ?>
        <?php echo $this->Paginator->prev('« '.__('prethodna'), array(), null, array('class' => 'disabled')); } ?>
        <?php echo $this->Paginator->numbers();?>
        <?php if($this->Paginator->hasNext()){ ?>
        <?php echo $this->Paginator->next(__('sledeća').' »', array(), null, array('class' => 'disabled')); } ?>
        <?php echo $this->Paginator->last(__('poslednja strana'), array()); ?>            
    </div>        
     <?php if(empty($configurations)){ ?>
        <div class="notice warning center">
            <i class="icon-warning-sign icon-large"></i>
            <?php echo __("Za ovaj upit nema podataka u bazi!"); ?>
        </div>
    <?php }else{ ?>
        <table class="records">
            <thead>
                <tr class="column_numbers">
                    <th class="center" rowspan="2"><?php echo __('Naziv'); ?></th>
                    <th class="center" rowspan="2"><?php echo __('Model'); ?></th>
                    <th class="center" rowspan="2"><?php echo __('Tag'); ?></th>
                    <th class="center" rowspan="2"><?php echo __('Vrednost'); ?></th>
                    <th class="center" rowspan="2"><?php echo __('Vrsta'); ?></th>
                    <th class="center" rowspan="2"><?php echo __('Pojedinačni podatak iz baze'); ?></th>
                    <th class="center" colspan="4"><?php echo __('Više podataka iz baze'); ?></th>
                    <th class="left" rowspan="2" style="width:90px;">&nbsp;</th>
                </tr>
                <tr class="column_numbers">
                    <th class="center"><?php echo __('Model'); ?></th>
                    <th class="center"><?php echo __('Polje modela'); ?></th>
                    <th class="center"><?php echo __('Uslov'); ?></th>
                    <th class="center"><?php echo __('Vrednost uslova'); ?></th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($configurations as $configuration): ?>
                <tr class="nowrap">      
                    <td style="min-width: 200px;"><?php echo $configuration['Configuration']['name']; ?></td>
                    <td><?php echo $configuration['Configuration']['model']; ?></td>
                    <td><?php echo $configuration['Configuration']['tag']; ?></td>
                    <td class="<?php echo ($configuration['Configuration']['type'] == 'json') ? 'json-pretty' : ''; ?>"><?php echo $configuration['Configuration']['value']; ?></td>
                    <td><?php echo $types[$configuration['Configuration']['type']]; ?></td>
                    <td>
                        <?php if(!empty($configuration['Configuration']['foreign_title'])){ ?>
                            <?php echo $configuration['Configuration']['foreign_title']; ?>
                        <?php } ?>
                    </td>
                    <td><?php echo $configuration['Configuration']['multiple_model']; ?></td>
                    <td><?php echo $configuration['Configuration']['multiple_field']; ?></td>
                    <td class="center">
                        <?php if(!empty($configuration['Configuration']['multiple_condition'])){ ?>
                            <?php echo $multiple_conditions[$configuration['Configuration']['multiple_condition']]; ?>
                        <?php } ?>
                    </td>
                    <td><?php echo $configuration['Configuration']['multiple_value']; ?></td>
                    <td class="right">
                        <?php 
                            echo $this->Html->link('<i class="icon-edit"></i>', 
                                array(
                                    'controller' => 'Configurations', 'action' => 'save',
                                    $configuration['Configuration']['id']
                                ),
                                array('title' => __('Izmena'), 'escape' => false, 'class' => 'button small')
                            ); 
                        ?>                        
                        <?php 
                            echo $this->Html->link('<i class="icon-trash" style="color:red;"></i>', 
                                array(
                                    'controller' => 'Configurations', 'action' => 'delete',
                                    $configuration['Configuration']['id']
                                ),
                                array('title' => __('Izmena'), 'escape' => false, 'class' => 'button small'),
                                __("Da li ste sigurni da želite da obrišete konfiguraciju?")
                            );
                        ?>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    <?php } ?>
    <div class="center paginator">
        <?php echo $this->Paginator->first(__('prva strana'), array());?>
        <?php if($this->Paginator->hasPrev()){ ?>
        <?php echo $this->Paginator->prev('« '.__('prethodna'), array(), null, array('class' => 'disabled')); } ?>
        <?php echo $this->Paginator->numbers();?>
        <?php if($this->Paginator->hasNext()){ ?>
        <?php echo $this->Paginator->next(__('sledeća').' »', array(), null, array('class' => 'disabled')); } ?>
        <?php echo $this->Paginator->last(__('poslednja strana'), array()); ?>            
    </div>
</div>
<div class="clear"></div>
<div class="submit_loader">
    <?php echo $this->Html->image('submit_loader.gif', array('alt' => 'Loader')); ?>
    <h2><?php echo __("Molimo sačekajte..."); ?></h2>
</div> 
<script type="text/javascript">
//Init container
$('#container').ready(function(){
    //Hide ajax loader
    $(".submit_loader").hide();
    //Load select2 library
    $(".dropdown").select2();

    $('.json-pretty').each(function () {
        $(this).html('<pre>' + syntaxHighlight(JSON.parse($(this).text())) + '</pre>');
    });

    /**
     * Function for highlighting JSON syntax
     *
     * @todo Make global.js file and move this function and other helpful functions to that file
     *
     * @see https://stackoverflow.com/questions/4810841/pretty-print-json-using-javascript
     * @param  {json}     JSON object or string
     * @return {string}   Html
     */
    function syntaxHighlight(json) {
        if (typeof json != 'string') {
             json = JSON.stringify(json, undefined, 2);
        }
        json = json.replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;');
        return json.replace(/("(\\u[a-zA-Z0-9]{4}|\\[^u]|[^\\"])*"(\s*:)?|\b(true|false|null)\b|-?\d+(?:\.\d*)?(?:[eE][+\-]?\d+)?)/g, function (match) {
            var cls = 'number';
            if (/^"/.test(match)) {
                if (/:$/.test(match)) {
                    cls = 'key';
                } else {
                    cls = 'string';
                }
            } else if (/true|false/.test(match)) {
                cls = 'boolean';
            } else if (/null/.test(match)) {
                cls = 'null';
            }
            return '<span class="' + cls + '">' + match + '</span>';
        });
    }
});
</script>