<?php echo $this->Html->css('Script/financial.css?ver='.filemtime(WWW_ROOT . "/css/Script/financial.css")); ?>
<?php echo $this->Html->css('jquery-confirm.min.css?ver='.filemtime(WWW_ROOT . "/css/jquery-confirm.min.css")); ?>
<?php echo $this->Html->script('jquery-confirm.min.js?ver='.filemtime(WWW_ROOT . "/js/jquery-confirm.min.js")); ?>
<div class="breadcrumbs_holder">
    <ul class="breadcrumbs">
        <li><?php echo $this->Html->link(__('Početna'), '/'); ?></li>        
        <li>
            <?php 
                echo $this->Html->link(__('Konfiguracija'), array(
                    'controller' => 'Configurations', 'action' => 'index'
                ));
            ?>
        </li>
        <li class="last">
            <a href="" onclick="return false"><?php echo __('Snimanje'); ?></a>
        </li>
    </ul>
</div>
<!-- Messages -->
<div id='alert'><?php echo $this->Session->flash(); ?></div>
<!-- Main content -->
<div style="margin: 0 24px 0 44px;">
    <!-- Top bar -->
    <div style="float:left;">
        <h3 style="margin-top:0px;">
            <i class="icon-save"></i> <?php echo __("Snimanje konfiguracije"); ?>
        </h3>
    </div>
    <div class="clear"></div>
    <?php 
        //Set save form url
        $url = array('controller' => 'Configurations', 'action' => 'save');

        //Init row count
        $row_count = 0;
        //Set form submitted flag
        $form_submitted = !empty($this->request->data);
        //Check for submitted form
        if($form_submitted){
            //Set row count
            $row_count = count($this->request->data['Configuration']);
        }else{
            //Check for forwarded configurations
            if($forwarded_configs){
                $row_count = count($forwarded_configs['Configuration']);
            }else{
                //Increment row count
                $row_count++;                    
            }
        }
        //Check for configuration id
        if(!empty($configuration['Configuration']['id'])){
            //Set configuration id
            $url[] = $configuration['Configuration']['id'];
        }
        //Add forwarded configs to URL
        $url['?'] = $forwarded_configs;
        //Init form
        echo $this->Form->create('Configuration', array('url' => $url, 'id' => 'configuration_form'));
    ?>
    <table class="records">
        <thead>
            <tr class="column_numbers">
                <th class="center" rowspan="2"><?php echo __('Naziv'); ?></th>
                <th class="center" rowspan="2"><?php echo __('Model'); ?></th>
                <th class="center" rowspan="2"><?php echo __('Tag'); ?></th>
                <th class="center" rowspan="2"><?php echo __('Vrednost'); ?></th>
                <th class="center" rowspan="2"><?php echo __('Vrsta'); ?></th>
                <th class="center" colspan="2"><?php echo __('Pojedinačni podatak iz baze'); ?></th>
                <th class="center" colspan="4"><?php echo __('Više podataka iz baze'); ?></th>
            </tr>
            <tr class="column_numbers">
                <th class="center"><?php echo __('Veza'); ?></th>
                <th class="center"><?php echo __('Vrednost'); ?></th>
                <th class="center"><?php echo __('Model'); ?></th>
                <th class="center"><?php echo __('Polje modela'); ?></th>
                <th class="center"><?php echo __('Uslov'); ?></th>
                <th class="center"><?php echo __('Vrednost uslova'); ?></th>
            </tr>
        </thead>        
        <?php
            //Check for referer
            if(!empty($referer)){
                $this->request->data['Main']['referer'] = $referer;
            }
            //Set hidden field with referer
            echo $this->Form->input("Main.referer", array('type' => 'hidden', 'required' => false, 'div' => false));
        ?>
        <?php for ($row=0; $row < $row_count; $row++): ?>
        <tr>
            <td>
                <?php
                    //Init readonly for forwarded configs
                    $forwarded_read_only = false;
                    //If not submitted form check for formatted data
                    if(!empty($forwarded_configs)){
                        //Set readonly for formatted
                        $forwarded_read_only = true;
                        //Check if form is submitted
                        if(!$form_submitted){
                            //Set forwarded configs form data
                            $this->request->data['Configuration'][$row] = $forwarded_configs['Configuration'][$row];
                        }
                    }
                    //Check id for editing
                    if(!empty($this->request->data['Configuration'][$row]['id'])){
                        //Set hidden field with id
                        echo $this->Form->input("Configuration.{$row}.id", array(
                            'type' => 'hidden', 'required' => false, 'div' => false, 
                            'value' => $this->request->data['Configuration'][$row]['id']
                        ));
                    }
                    //Set error if exists
                    $error = $this->Form->error("Configuration.{$row}.name", null, array('wrap' => false));                    
                    //Set input
                    echo $this->Form->input("Configuration.{$row}.name", array(
                        'type' => 'text', 'label' => false, 'required' => false, 'autocomplete' => 'off',
                        'style' => 'width:100%', 'placeholder' => __("Unesite naziv"), 'data-row' => $row,
                        'errorMessage' => false, 'title' => $error, 'class' => 'tooltip', 'data-delay' => '0',
                        'div' => false, 'readonly' => $forwarded_read_only
                    ));                        
                ?>                 
            </td>
            <td>
                <?php
                    //Set error if exists
                    $error = $this->Form->error("Configuration.{$row}.model", null, array('wrap' => false));                    
                    //Set input
                    echo $this->Form->input("Configuration.{$row}.model", array(
                        'type' => 'text', 'label' => false, 'required' => false, 'autocomplete' => 'off',
                        'style' => 'width:100%', 'placeholder' => __("Unesite model"), 'data-row' => $row,
                        'errorMessage' => false, 'title' => $error, 'class' => 'tooltip', 'data-delay' => '0',
                        'div' => false, 'readonly' => $forwarded_read_only
                    ));                        
                ?>                
            </td>
            <td>
                <?php
                    //Set error if exists
                    $error = $this->Form->error("Configuration.{$row}.tag", null, array('wrap' => false));                    
                    //Set input
                    echo $this->Form->input("Configuration.{$row}.tag", array(
                        'type' => 'text', 'label' => false, 'required' => false, 'autocomplete' => 'off',
                        'style' => 'width:100%', 'placeholder' => __("Unesite tag"), 'data-row' => $row,
                        'errorMessage' => false, 'title' => $error, 'class' => 'tooltip', 'data-delay' => '0',
                        'div' => false, 'readonly' => $forwarded_read_only
                    ));                        
                ?>                
            </td>
            <td>
                <?php
                    //Set error if exists
                    $error = $this->Form->error("Configuration.{$row}.value", null, array('wrap' => false));                    
                    //Set input
                    echo $this->Form->input("Configuration.{$row}.value", array(
                        'type' => 'text', 'label' => false, 'required' => false, 'autocomplete' => 'off',
                        'style' => 'width:100%', 'placeholder' => __("Unesite vrednost"), 'data-row' => $row,
                        'errorMessage' => false, 'title' => $error, 'class' => 'tooltip', 'data-delay' => '0',
                        'div' => false
                    ));                        
                ?>                  
            </td>
            <td>
                <?php
                    //Set error if exists
                    $error = $this->Form->error("Configuration.{$row}.type", null, array('wrap' => false));
                    //Set input
                    echo $this->Form->input("Configuration.{$row}.type", array(
                        'label' => false, 'required' => false, 'options' => $types,
                        'style' => 'width:100%; min-width:200px;', 'empty' => " ", 'data-row' => $row,
                        'errorMessage' => false, 'title' => $error, 'class' => 'typeDropdown tooltip', 'data-delay' => '0',
                        'div' => false
                    ));
                ?>                
            </td>
            <td>
                <?php
                    //Set connection inupt
                    echo $this->Form->input("Configuration.{$row}.connection", array(
                        'type' => 'hidden', 'label' => false, 'required' => false, 'div' => false,
                        'class' => 'connection', 'style' => 'width:100%;', 'data-row' => $row
                    ));
                    echo $this->Form->input("Configuration.{$row}.connection_title", array(
                        'type' => 'hidden', 'label' => false, 'required' => false, 'div' => false
                    ));
                ?>                
            </td>
            <td>
                <?php
                    //Set record input
                    echo $this->Form->input("Configuration.{$row}.record", array(
                        'type' => 'hidden', 'label' => false, 'required' => false, 'div' => false,
                        'class' => 'record', 'style' => 'width:100%;', 'data-row' => $row
                    ));
                    echo $this->Form->input("Configuration.{$row}.record_title", array(
                        'type' => 'hidden', 'label' => false, 'required' => false, 'div' => false
                    ));
                ?>                
            </td>
            <td>
                <?php
                    //Set multiple variables
                    $multiple_class = 'tooltip disabled';
                    $multiple_readonly = true;
                    if(
                        !empty($this->request->data['Configuration'][$row]['type']) &&
                        $this->request->data['Configuration'][$row]['type'] == 'multiple'
                    ){
                        $multiple_class = 'tooltip';
                        $multiple_readonly = false;
                    }
                    //Set error if exists
                    $error = $this->Form->error("Configuration.{$row}.multiple_model", null, array('wrap' => false));                    
                    //Set input
                    echo $this->Form->input("Configuration.{$row}.multiple_model", array(
                        'type' => 'text', 'label' => false, 'required' => false, 'autocomplete' => 'off',
                        'style' => 'width:100%', 'placeholder' => __("Unesite model"), 'data-row' => $row,
                        'errorMessage' => false, 'title' => $error, 'class' => $multiple_class, 'data-delay' => '0',
                        'readonly' => $multiple_readonly, 'div' => false
                    ));
                ?>                
            </td>
            <td>
                <?php
                    //Set error if exists
                    $error = $this->Form->error("Configuration.{$row}.multiple_field", null, array('wrap' => false));                    
                    //Set input
                    echo $this->Form->input("Configuration.{$row}.multiple_field", array(
                        'type' => 'text', 'label' => false, 'required' => false, 'autocomplete' => 'off',
                        'style' => 'width:100%', 'placeholder' => __("Unesite naziv polja"), 'data-row' => $row,
                        'errorMessage' => false, 'title' => $error, 'class' => $multiple_class, 'data-delay' => '0',
                        'readonly' => $multiple_readonly, 'div' => false
                    ));
                ?>                    
            </td>
            <td class="center">
                <?php
                    //Set error if exists
                    $error = $this->Form->error("Configuration.{$row}.multiple_condition", null, array('wrap' => false));
                    //Set input
                    echo $this->Form->input("Configuration.{$row}.multiple_condition", array(
                        'label' => false, 'required' => false, 'options' => $multiple_conditions,
                        'style' => 'width:100%; min-width:50px;', 'empty' => " ", 'div' => false, 'data-row' => $row,
                        'errorMessage' => false, 'title' => $error, 'class' => 'conditionDropdown', 'data-delay' => '0' 
                    ));                        
                ?> 
            </td> 
            <td>
                <?php
                    //Set error if exists
                    $error = $this->Form->error("Configuration.{$row}.multiple_value", null, array('wrap' => false));                    
                    //Set input
                    echo $this->Form->input("Configuration.{$row}.multiple_value", array(
                        'type' => 'text', 'label' => false, 'required' => false, 'autocomplete' => 'off',
                        'style' => 'width:100%', 'placeholder' => __("Unesite vrednost"), 'data-row' => $row,
                        'errorMessage' => false, 'title' => $error, 'class' => $multiple_class, 'data-delay' => '0',
                        'readonly' => $multiple_readonly, 'div' => false
                    ));
                ?>                    
            </td>               
        </tr>
        <?php endfor; ?>
    </table>
    <div class="clear"></div>    
    <div style="float:left;">
        <?php 
            //Create back button
            echo $this->Html->link('<i class="icon-arrow-left"></i> '.__("Napusti formu za unos"),
            array('controller' => 'Configurations', 'action' => 'index'),
            array(
                'escape' => false, 'class' => 'button green confirm', 'style' => 'margin-right:10px;',
                'data-content' => __('Napustanjem forme podaci koji ste uneli neće biti sačuvani. Da li zelite da napustite formu?'),
                'data-title' => __('Potvrda o napuštanju')
            ));
        ?>
    </div>
    <div style="float:right;">
    <?php 
        //Loader spinner
        echo $this->Html->image('small_loader.gif', array(
            'alt' => 'Loader', 'id' => 'submit_loader', 'class' => 'hide',
            'style' => 'margin-right:5px; vertical-align:middle;'
        ));
        //Create submit button
        echo $this->Form->button('<i class="icon-save"></i> '.__("Snimi podatke"), array(
            'type' => 'submit', 'escape' => false, 'class' => 'blue confirm', 'id' => 'submit_button',
            'data-content' => __('Da li ste sigurni da želite da snimite podatke o konfiguraciji modula?'),
            'data-title' => __('Potvrda o snimanju')
        ));
    ?>                        
    </div>
    <?php echo $this->Form->end(); ?>
    <div class="clear"></div>
</div>
<div class="submit_loader">
    <?php echo $this->Html->image('submit_loader.gif', array('alt' => 'Loader')); ?>
    <h2><?php echo __("Molimo sačekajte..."); ?></h2>
</div>
<script type="text/javascript">
/* Function that enables foreign */
function enableForeign(row){
    //Enable connection selection
    $('#Configuration'+row+'Connection').select2("readonly", false);
    //Get connection id
    var connection_id = $('#Configuration'+row+'Connection').val();
    //If connection is set enable record selection
    if(connection_id){
        loadRecordValueSelector(row, connection_id);
    }
}//~!
/* Function that disables foreign */
function disableForeign(row){
    //Unload record values
    unloadRecordValueSelector(row);
    //Disable connection selection
    $('#Configuration'+row+'Connection').select2("readonly", true);
    $('#Configuration'+row+'Connection').select2("val", null);
}//~!
/* Function that enables multiple */
function enableMultiple(row){
    //Remove readonly
    $("#Configuration"+row+"MultipleModel").prop('readonly', false);
    $("#Configuration"+row+"MultipleField").prop('readonly', false);
    $("#Configuration"+row+"MultipleCondition").select2("readonly", false);
    $("#Configuration"+row+"MultipleValue").prop('readonly', false);
    //Remove disabled class
    $("#Configuration"+row+"MultipleModel").removeClass('disabled');
    $("#Configuration"+row+"MultipleField").removeClass('disabled');
    $("#Configuration"+row+"MultipleValue").removeClass('disabled');
}//~!
/* Function that disables multiple */
function disableMultiple(row){
    //Reset values
    $("#Configuration"+row+"MultipleModel").val('');
    $("#Configuration"+row+"MultipleField").val('');
    $("#Configuration"+row+"MultipleCondition").select2("val", null);
    $("#Configuration"+row+"MultipleValue").val('');
    //Set as readonly
    $("#Configuration"+row+"MultipleModel").prop('readonly', true);
    $("#Configuration"+row+"MultipleField").prop('readonly', true);
    $("#Configuration"+row+"MultipleCondition").select2("readonly", true);
    $("#Configuration"+row+"MultipleValue").prop('readonly', true);
    //Add disabled class
    $("#Configuration"+row+"MultipleModel").addClass('disabled');
    $("#Configuration"+row+"MultipleField").addClass('disabled');
    $("#Configuration"+row+"MultipleValue").addClass('disabled');
}//~!
/* Function that enables value */
function enableValue(row){
    //Remove readonly
    $("#Configuration"+row+"Value").prop('readonly', false);
    //Remove disabled class
    $("#Configuration"+row+"Value").removeClass('disabled');       
}//~!
/* Function that disables value */
function disableValue(row){
    //Reset values
    $("#Configuration"+row+"Value").val('');
    //Set as readonly
    $("#Configuration"+row+"Value").prop('readonly', true);
    //Add disabled class
    $("#Configuration"+row+"Value").addClass('disabled');
}//~!
/* Function for processing selected type */
function processType(type, row){
    //Check for type
    switch (type) {
        case 'foreign_key':
            enableForeign(row);
            disableMultiple(row);
            disableValue(row);
            break;
        case 'multiple':                
            enableMultiple(row);
            disableForeign(row);
            disableValue(row);
            break;        
        default:
            disableForeign(row);
            disableMultiple(row);
            enableValue(row);
            break;
    }
}//~!
/* Load record value selector based on codebook connection */
function loadRecordValueSelector(row, query_codebook_connection_id){
    //Enable record dropdown
    $('#Configuration'+row+'Record').select2("readonly", false);
    //Codebook connection ajax loader
    $('#Configuration'+row+'Record').select2({
        minimumInputLength: 0,
        placeholder: "(Odaberite vrednost)",
        allowClear: true,
        query: function (query) {
            //Set init search data
            var process = {results: []};
            //Call search
            $.ajax({
                dataType: "json",
                type: "POST",
                evalScripts: true,
                data: ({ term: query.term, codebook_connection_id: query_codebook_connection_id }),
                url: '/CodebookConnections/getConnectionData/',
                success: function (data){                            
                    if(data){
                        for (var key in data) {
                            if (data.hasOwnProperty(key)) {
                            process.results.push({id: key, text: data[key] });
                            }
                        }
                    }
                    query.callback(process);

                    //Check data selection
                    $('#Configuration'+row+'Record').on("select2-selecting", function(e) {
                        $('#Configuration'+row+'RecordTitle').val(e.object.text);
                    });
                },
                error:function(xhr){
                    //Set error message
                    var error_msg = "An error occured: " + xhr.status + " " + xhr.statusText;
                    //Show message
                    $.ambiance({
                        timeout: 10,
                        message: error_msg,
                        type: "error"
                    });                         
                }
            });
        },
        initSelection : function (element, callback) {
            var data = {
                id: $('#Configuration'+row+'Record').val(),
                text: $('#Configuration'+row+'RecordTitle').val()
            };
            callback(data);
        }
    });
}//~!
/* Unload record value selector */
function unloadRecordValueSelector(row){
    //Reset record data
    $('#Configuration'+row+'Record').select2("val", null);
    $('#Configuration'+row+'RecordTitle').val('');
    //Disable record selection
    $('#Configuration'+row+'Record').select2("readonly", true);
}//~!
//Init container
$('#container').ready(function(){
    //Hide ajax loader
    $(".submit_loader").hide();
    //Load select2
    $(".typeDropdown").select2({ allowClear: true });
    //Load select2 for condition
    $(".conditionDropdown").select2({ allowClear: true });    
    //Check for type change
    $(".typeDropdown").on("select2-selecting", function(e) {
        //Set type value
        var type = e.val;
        var row = $(this).data('row');
        //Process selected type
        processType(type, row);
    });
    //Codebook connection ajax loader
    $('.connection').select2({
        minimumInputLength: 0,
        placeholder: "(Odaberite vezu)",
        allowClear: true,
        query: function (query) {
            //Set init search data
            var process = {results: []};
            //Call search
            $.ajax({
                dataType: "json",
                type: "POST",
                evalScripts: true,
                data: ({ term: query.term }),
                url: '/CodebookConnections/searchCodebookConnections/',
                success: function (data){
                    if(data){
                        for (var key in data) {
                            if (data.hasOwnProperty(key)) {
                                process.results.push({id: key, text: data[key] });
                            }
                        }
                    }
                    query.callback(process);                
                },
                error:function(xhr){
                    var error_msg = "An error occured: " + xhr.status + " " + xhr.statusText;
                    alert(error_msg);
                    $(".submit_loader").hide();
                }
            });				
        },
        initSelection : function (element, callback) {
            //Set row
            var row = element.data('row');
            //Set data
            var data = {
                id: $('#Configuration'+row+'Connection').val(),
                text: $('#Configuration'+row+'ConnectionTitle').val()
            };
            callback(data);
        }
    });  
    //Disable connection selection
    $(".connection").select2("readonly", true);
    //Fill-up form based on connection selection
    $('.connection').on("select2-selecting", function(e) {
        //Set id
        var row = $(this).data('row');
        //Set current codebook connection id
        var current_codebook_connection_id = $(this).val();
        //Set connection title
        $('#Configuration'+row+'ConnectionTitle').val(e.object.text);
        //Load record value loader
        var codebook_connection_id = e.object.id;
        //Unload record selection first if different codebook
        if(current_codebook_connection_id != codebook_connection_id){
            unloadRecordValueSelector(row);
        }        
        //Load record selector
        loadRecordValueSelector(row, codebook_connection_id);
    });
    //Clear connection selection
    $('.connection').on("select2-clearing", function(e) {
        //Set id
        var row = $(this).data('row');
        //Clear connection title
        $('#Configuration'+row+'ConnectionTitle').val('');
        //Unload record value selector
        unloadRecordValueSelector(row);
    });
    //Load select2 for condition
    $('.record').select2({
        minimumInputLength: 0,
        placeholder: "(Odaberite vrednost)",
        allowClear: true,
        query: function (query) {},
        initSelection : function (element, callback) {
            //Set row
            var row = element.data('row');
            //Set data
            var data = {
                id: $('#Configuration'+row+'Record').val(),
                text: $('#Configuration'+row+'RecordTitle').val()
            };
            callback(data);
        }
    });
    //Disable record selection
    $(".record").select2("readonly", true);
    //Check for all selected dropdowns
    $(".typeDropdown").each( function( index, element ){
        //Set tagname
        var tagname = $(this).prop("tagName").toLowerCase();
        //Check for tagname
        if(tagname == 'select'){
            //Process selected type
            processType($(this).val(), $(this).data('row'));
        }
    });
    //Confirm dialog for button
    $('button.confirm').confirm({
        boxWidth: '300px',
        useBootstrap: false,
        buttons: {
            da: function () {
                //Show loader
                $('#submit_loader').removeClass('hide');
                //Disable submit button
                $('#submit_button').prop('disabled', true);
                $('#submit_button').removeClass('blue');
                $('#submit_button').addClass('disabled');
                //Submit form
                $('#configuration_form').submit();
            },
            ne: function () {
                //function holder
            }
        }
    });
    //Set confirm link
    $('a.confirm').confirm({
        boxWidth: '300px',
        useBootstrap: false,
        buttons: {
            da: function () {
                //Follow the clicked link
                location.href = this.$target.attr('href');
            },
            ne: function () {
                //function holder
            }
        }
    });
});
</script>