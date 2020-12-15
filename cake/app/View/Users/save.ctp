<ul class="breadcrumbs">
    <li><?php echo $this->Html->link(__('Početna'), '/'); ?></li>
    <li> <?php echo $this->Html->link(__('Korisnici'), array('action' => 'index')); ?></li>
    <li class="last"><a href="" onclick="return false"><?php echo __('Korisnik'); ?></a></li>
</ul>
<div id='alert'><?php echo $this->Session->flash(); ?></div>
<div class="name_add_search">
    <div class="name_of_page"><h3><?php echo __('Korisnik'); ?></h3></div>
    <div class="add_and_search">
    </div>
</div><!-- confirm box -->
<fieldset id="confirm_popup" style="width:300px; height:130px; text-align:center;">
    <p><?php echo __('Da li ste sigurni da želite da kreirate dokument knjižna nota sa prethodno unetim podacima?'); ?></p>
        <ul class="button-bar" style="margin-left:80px;">
            <li class="first"><a href="" onclick="sendForm(); return false;"><?php echo __('Da'); ?></a></li>
            <li class="last"><a href="" onclick="$('#confirm_popup').popup('hide'); return false;"><?php echo __('Ne'); ?></a></li>
        </ul>
    
</fieldset>
<!-- confirm box -->

<!-- order products start -->
<fieldset id="order_products_popup" style="width:600px; height:600px;">
<table>
    <thead>
        <tr>
            <th style="width:350px;"><?php echo __('Proizvod'); ?></th>
            <th style="width:80px; text-align:right;"><?php echo __('Količina'); ?></th>
        </tr>
    </thead>
    <tfoot>
        <tr>
            <td style="text-align:left;"><button class="blue small" onclick="addOrderProduct(); return false;"><i class="icon-plus-sign"></i> <?php echo __('Dodaj proizvode'); ?></button></td>
            <td style="text-align:right;"><button class="small" onclick="$('#order_products_popup').popup('hide'); return false;"><i class="icon-off"></i> <?php echo __('Odustani'); ?></button></td>
        </tr>
    </tfoot>
    <tbody style="font-size:13px;">
        <tr>
            <td colspan="2">
                <div style="width:100%; height:500px; overflow:auto;">
                    <table id="order_products"></table>
                </div>
            </td>
        </tr>
    </tbody>
</table>
</fieldset>
<!-- order products end -->

<div class='content_data'>
<?php echo $this->Form->create('User'); ?>
<?php echo $this->Form->input('id', array('type' => 'hidden')); ?>
<fieldset>
    <legend><?php echo __('Podaci o korisniku'); ?></legend>
    <table>
        <tr>
            <th><?php echo __('Tip'); ?>: </th>
            <td>
                <?php echo $this->Form->input('type', array('label' => false, 'style' => 'width:400px;', 'options' => array('approval' => __('Odobrenje'), 'debit' => __('Zaduženje') ), 'required' => false)); ?>
            </td>
            <td id="type_error" class="error-message" style="width:200px;"></td>
        </tr>
        <tr>
            <th><?php echo __('Poreklo'); ?>: </th>
            <td>
            <?php echo $this->Form->input('origin', array('label' => false, 'style' => 'width:400px;', 'options' => array('domestic' => __('Srbija'), 'foreign' => __('inostranstvo')) , 'empty' => __('Da li je kupac stranac ili domaći'), 'required' => false, 'onchange' => 'originChanged(); return false;')); ?>
        </td>
            <td id="origin_error" class="error-message" style="width:200px;"></td>
        </tr>
        <tr>
            <th><?php echo __('Osnov'); ?>: </th>
            <td>
                <?php echo $this->Form->input('credit_note_reason_id', array('label' => false, 'style' => 'width:400px;', 'options' => array() , 'empty' => __('Izaberite osnov izdavanja knjižne note'), 'required' => false)); ?> 
            </td>
            <td id="credit_note_reason_id_error" class="error-message" style="width:200px;"></td>
        </tr>
        <tr>
            <th><?php echo __('Vezano za order'); ?>: </th>
            <td>
                <?php echo $this->Form->input('order_id', array('type' => 'hidden', 'required' => false, 'style' => ' width:400px;')); ?>
                <button class="blue small" onclick="previewOrder(); return false;"><i class="icon-star"></i> <?php echo __('Pregled'); ?></button>
            </td>
            <td id="order_id_error" class="error-message"></td>
        </tr>
        <tr>
            <th style="width:200px; vertical-align:top; "><?php echo __('Komentar'); ?>: </th>
            <td>
                <?php echo $this->Form->input('comment', array('type' => 'textarea', 'label' => false, 'required' => false)); ?>
            </td>
            <td id="comment_error" class="error-message"></td>
        </tr>
    </table>
</fieldset>
<?php echo $this->Form->end(); ?>
<fieldset id="order_list">
<legend><?php echo __('Proizvodi'); ?></legend>
    <table>
        <thead>
            <tr>
                <th style="width:400px;">Proizvod</th>
                <th style="width:60px; text-align:center;">Količina</th>
                <th style="width:100px; text-align:center;">Kit</th>
                <th style="width:80px; text-align:center;">Faktura</th>
                <th style="width:300px; text-align:left;">Kupac</th>
                <th></th>
            </tr>
        </thead>
        <tbody id="order_list_tbody"></tbody>
    </table>
</fieldset>
<button class="small green" onclick="validate(); return false;"><i class="icon-ok"></i> <?php echo __('Sačuvaj'); ?></button>
</div>

<div class="clear"></div>
<script>
var customer_id = null;
var curr_id = null;
$('#container').ready(function(){
});



$("#CreditNoteType").select2({ width : 'off' });
$("#CreditNoteOrigin").select2({ width : 'off' });
$("#CreditNoteReasonId").select2({ width : 'off' });
$('#CreditNoteComment').ckeditor({ height : '200px', width: '450px' } );

/**
    * origin changed
    * reset related fields
    * fill reasons for credit
*/


//pretraga tj biranje ordera
$("#CreditNoteOrderId").select2({
        minimumInputLength: 2,
        placeholder: "(Odaberite order - fakturu)",
        allowClear: true,
        query: function (query) {
            var process = {results: []};
            $.ajax({
                dataType: "json",
                type: "POST",
                evalScripts: true,
                data: ({ term: query.term, client_id:customer_id, currency_id:curr_id}),
                url: '/CreditNoteProducts/getOrdersBySearch/',
                success: function (data){
                    var index;
                    for (index = 0; index < data.length; ++index) {
                        var txt = data[index].Order.invoice_number + ' ( ' + data[index].Order.customer_title + ' - '+ data[index].Order.order_number + ' )';
                        process.results.push({id: data[index].Order.id, text: txt });
                    }                   
                    query.callback(process);
                },
                error:function(xhr){
                    var error_msg = "Došlo je do greške: " + xhr.status + " " + xhr.statusText;
                    showErrorPopup(error_msg);
                    
                }
            });
        }
    });

/**
    * pregled proizvoda izabranog ordera
    * biranje proizvoda iz ordera za knjiznu notu
*/
function previewOrder(){
    var str_order_id = $('#CreditNoteOrderId').val();
    if(str_order_id.length > 0){
        var order_id = parseInt(str_order_id);

        //dohvatanje već izabranih proizvoda iz ovog ordera ako ih ima
        var chosen_products = new Array();
        $('.trop').each(function(){
            var str_id = this.id;

            var i_id_index = str_id.indexOf('i_id');
            var fak_id_index = str_id.indexOf('fak_id');

            var c_order_product_id = parseInt(str_id.substring(7, i_id_index));
            var c_item_id = parseInt(str_id.substring((i_id_index+4), fak_id_index));
            var c_order_id = parseInt(str_id.substr(fak_id_index+6));

            if(order_id == c_order_id) chosen_products.push([c_order_product_id, c_item_id]);
        });



        $.ajax({
            dataType: 'json',
            type: 'POST',
            evalScripts: true,
            data: ({order_id:order_id, chosen_products:chosen_products}),
            url: "<?php echo Router::url(array('controller' => 'CreditNoteProducts', 'action' => 'previewOrder')); ?>",
            success: function(data){
                $('#order_products').html('');
                if(data['error']){
                    showErrorPopup(data['error']);
                }
                if(data['success']){
                    var order_products = $.map(data['success'], function(k,v){
                        return[k];
                    });
                    var row = null;
                    if(order_products.length > 0){
                        for (var i = 0; i < order_products.length; i++) {
                            row = order_products[i];
                            var order_product_id = parseInt(row['OrderProduct']['id']);
                            var product = 'MIKROE-' + row['Item']['pid'] + ' ' + row['Item']['name'];
                            var item_id = parseInt(row['Item']['id']);
                            var qty = parseInt(row['OrderProduct']['qty']);
                            var product_kit_id = row['OrderProduct']['product_kit_id'];
                            var available = parseInt(row['OrderProduct']['available']);
                            var kit = new Array();
                            kit = row['OrderProduct']['kit'];
                            if(kit.length > 0){
                                $('#order_products').append('<tr id="order_product'+order_product_id+'"></tr><tr><td colspan="2"><table id="kit_content'+order_product_id+'"></table></td></tr>');
                            }else{
                                $('#order_products').append('<tr id="order_product'+order_product_id+'"></tr>');
                            }
                            if(available > 0){
                                $('#order_product'+order_product_id).append('<td><input type="checkbox" id="checkbox_popup_product'+order_product_id+'" class="cbk_op"/> <input type="hidden" id="order_product_kit_id'+order_product_id+'" value="'+product_kit_id+'" /> <a href="/Items/view/'+item_id+'" target = "_blank">'+product+'</a></td>');
                            }else{
                                $('#order_product'+order_product_id).append('<td><i class="icon-ok" style="color:green;"></i> <a href="/Items/view/'+item_id+'" target = "_blank">'+product+'</a></td>');
                            }

                            $('#order_product'+order_product_id).append('<td style="text-align:right;">'+qty+'</td>');
                            if(kit.length > 0){
                                var kit_row = null;
                                for (var j = 0; j < kit.length; j++) {
                                    kit_row = kit[j];
                                    var k_product = kit_row['product'];
                                    var k_item_id = kit_row['item_id'];
                                    var k_qty = parseInt(kit_row['qty']);
                                    var k_product_kit_id = kit_row['product_kit_id'];
                                    var k_order_product_id = kit_row['order_product_id'];
                                    var k_available = parseInt(kit_row['available']);

                                    $('#kit_content'+order_product_id).append('<tr id="kit_content_item'+k_item_id+'" style="font-size:12px; background-color:#CCCCCC;"></tr>');
                                    if(k_available > 0){
                                        $('#kit_content_item'+k_item_id).append('<td style="witdh:300px;"><input type="checkbox" id="checkbox_kit_content'+k_item_id+'" class="cbk_kc"/> <input type="hidden" id="order_product_value'+k_item_id+'" value="'+k_order_product_id+'" /> <a href="/Items/view/'+k_item_id+'" target = "_blank">'+k_product+'</a></td>');
                                    }else{
                                        $('#kit_content_item'+k_item_id).append('<td style="witdh:300px;"><i class="icon-ok" style="color:green;"></i> <a href="/Items/view/'+k_item_id+'" target = "_blank">'+k_product+'</a></td>');
                                    }

                                    $('#kit_content_item'+k_item_id).append('<td style="width:50px; text-align:right;">'+k_qty+'</td>');
                                };
                            }
                        };
                        $('#order_products_popup').popup('show');
                    }
                }
            },
            error: function(xhr){
                var error_msg = 'Error: ' + xhr.status + ' ' + xhr.statusText;
                showErrorPopup(error_msg)
            }
        });
    }else{
        showWarningPopup('<?php echo __("Niste izabrali order!"); ?>');
    }
}//~!


/*
    * dodavanje proizvoda iz ordera(popup) u listu za dodavanje u knjiznu notu
*/
function addOrderProduct(){
    //dohvatanje cekiranih regularnih proizvoda
    var order_products = new Array();
    $('.cbk_op').each(function(){
        if(this.checked){
            var cbk_id = this.id;
            var order_product_id = parseInt(cbk_id.substr(22));
            order_products.push(order_product_id);
        }
    });

    //dohvatanje cekrianeih proizvoda koji se nalaze u kitu
    var kit_products = new Array();
    $('.cbk_kc').each(function(){
        if(this.checked){
            var cbk_id = this.id;
            var item_id = parseInt(cbk_id.substr(20));
            var op_id = parseInt($('#order_product_value'+item_id).val());

            //provera da li je cekiran kit i uklanjanje iz nize proizvoda ako jeste
            kit_products.push([item_id, op_id]);
            var index = order_products.indexOf(op_id);
            if(index > -1){
                order_products.splice(index, 1);
            }
        }
    });
    
    
    if(order_products.length > 0 || kit_products.length > 0){
        $.ajax({
            dataType: 'json',
            type: 'POST',
            evalScripts: true,
            data: ({order_products:order_products, kit_products:kit_products}),
            url: "<?php echo Router::url(array('controller' => 'CreditNoteProducts', 'action' => 'getOrderProducts')); ?>",
            success: function(data){
                if(data['error']){
                    showErrorPopup(data['error']);
                }

                if(data['success']){
                    var result_op = $.map(data['order_products'], function(k,v){
                        return[k];
                    });


                    var result_kc = $.map(data['kit_products'], function(p,m){
                        return[p];
                    });


                    if(result_op.length > 0){
                        var row = null;

                        var product = null;
                        var item_id = null;
                        var order_product_id = null;
                        var product_kit_id = null;
                        var qty = null;
                        var order_id = null;
                        var invoice = null;
                        var customer = null;
                        var client_id = null;
                        var currency_id = null;

                        for (var i = 0; i < result_op.length; i++) {
                            row = result_op[i];
                            product = 'MIKROE-'+row['Item']['pid']+' '+row['Item']['name'];
                            item_id = parseInt(row['Item']['id']);
                            var kit = '';
                            if(row['OrderProduct']['product_kit_id'] != null) kit = 'KIT';

                            qty = parseInt(row['OrderProduct']['qty']);
                            order_id = parseInt(row['OrderProduct']['order_id']);
                            order_product_id = parseInt(row['OrderProduct']['id']);
                            invoice = row['Order']['invoice_number'];
                            customer = row['Order']['customer_title'];
                            client_id = row['ClientOrder']['client_id'];
                            currency_id = parseInt(row['Order']['currency_id']);

                            customer_id = client_id;
                            curr_id = currency_id;

                            $('#order_list_tbody').append('<tr id="tr_op_d'+order_product_id+'i_id'+item_id+'fak_id'+order_id+'" class="trop"></tr>');
                            $('#tr_op_d'+order_product_id+'i_id'+item_id+'fak_id'+order_id).append('<td><a href="/Items/view/'+item_id+'" target="_blank">'+product+'</a></td');
                            $('#tr_op_d'+order_product_id+'i_id'+item_id+'fak_id'+order_id).append('<td style="text-align:right;">'+qty+'</td');
                            $('#tr_op_d'+order_product_id+'i_id'+item_id+'fak_id'+order_id).append('<td style="text-align:center;">'+kit+'</td');
                            $('#tr_op_d'+order_product_id+'i_id'+item_id+'fak_id'+order_id).append('<td style="text-align:center;"><a href="/Orders/view/'+order_id+'" target="_blank">'+invoice+'</a></td');
                            $('#tr_op_d'+order_product_id+'i_id'+item_id+'fak_id'+order_id).append('<td style="text-align:left;"><a href="/Clients/view/'+client_id+'" target="_blank">'+customer+'</a></td');
                            $('#tr_op_d'+order_product_id+'i_id'+item_id+'fak_id'+order_id).append('<td style="text-align:right;"><button class="red small" onclick="removeProduct('+order_product_id+','+item_id+','+order_id+'); return false;"><i class="icon-minus-sign"></i></button></td');



                        };

                    }


                    if(result_kc.length > 0){
                        var row = null;

                        var product = null;
                        var item_id = null;
                        var qty = null;
                        var order_product_id = null;
                        var order_product = null;
                        var order_product_item_id = null;
                        var customer = null;
                        var client_id = null;
                        var invoice_number = null;
                        var order_id = null;
                        var currency_id = null;

                        for (var i = 0; i < result_kc.length; i++) {
                            row = result_kc[i];

                            product = row['product'];
                            item_id = parseInt(row['item_id']);
                            qty = parseInt(row['qty']);
                            order_product_id = parseInt(row['order_product_id']);
                            order_product = row['order_product'];
                            order_product_item_id = parseInt(row['order_product_item_id']);
                            customer = row['customer'];
                            client_id = parseInt(row['client_id']);
                            invoice_number = row['invoice_number'];
                            order_id = parseInt(row['order_id']);
                            currency_id = parseInt(row['currency_id']);

                            customer_id = client_id;
                            curr_id = currency_id;

                            $('#order_list_tbody').append('<tr id="tr_op_d'+order_product_id+'i_id'+item_id+'fak_id'+order_id+'" class="trop"></tr>');
                            $('#tr_op_d'+order_product_id+'i_id'+item_id+'fak_id'+order_id).append('<td><a href="/Items/view/'+item_id+'" target="_blank">'+product+'</a></td');
                            $('#tr_op_d'+order_product_id+'i_id'+item_id+'fak_id'+order_id).append('<td style="text-align:right;">'+qty+'</td');
                            $('#tr_op_d'+order_product_id+'i_id'+item_id+'fak_id'+order_id).append('<td style="text-align:center; font-size:11px;"><a href="/Items/view/'+order_product_item_id+'" target="_blank" title="'+order_product+'">kit content</a></td');
                            $('#tr_op_d'+order_product_id+'i_id'+item_id+'fak_id'+order_id).append('<td style="text-align:center;"><a href="/Orders/view/'+order_id+'" target="_blank">'+invoice_number+'</a></td');
                            $('#tr_op_d'+order_product_id+'i_id'+item_id+'fak_id'+order_id).append('<td style="text-align:left;"><a href="/Clients/view/'+client_id+'" target="_blank">'+customer+'</a></td');
                            $('#tr_op_d'+order_product_id+'i_id'+item_id+'fak_id'+order_id).append('<td style="text-align:right;"><button class="red small" onclick="removeProduct('+order_product_id+','+item_id+','+order_id+'); return false;"><i class="icon-minus-sign"></i></button></td');
                        };
                    }

                    $('#order_products_popup').popup('hide');
                }
            },
            error: function(xhr){
                var error_msg = 'Error ' + xhr.status + ' ' + xhr.statusText;
                showErrorPopup(error_msg);
            }
        });
    }
}//~!

/**
    * remove product from list
    * @param int order_product_id
    * @param int item_id
    * @param int order_id
*/
function removeProduct(order_product_id, item_id, order_id){
    $('#tr_op_d'+order_product_id+'i_id'+item_id+'fak_id'+order_id).remove();
    //ako nema vise proizvoda na listi resetovanje klijenta
    var chosen_products = 0;
    $('.trop').each(function(){
        chosen_products++;
    });
    if(chosen_products == 0){
        customer_id = null;
        curr_id = null;
    }
    showSuccessPopup('<?php echo __('Proizvod ukljonjen sa liste'); ?>');
}//~!




$('#confirm_popup').popup({
    opacity: 0.3,
    transition: 'all 0.3s',
    scrolllock: true
});

/**
    * initialize order products popup
*/
$("#order_products_popup").popup({
    opacity: 0.3,
    transition: 'all 0.3s',
    scrolllock: true
});




// validation and submiting
function validate(){
   $(".error-message").html('');
    var number_of_errors = 0;

    var credit_note_type = $("#CreditNoteType").val();
    if(credit_note_type.length == 0){
        number_of_errors++;
        $("#type_error").html('Niste izabrali tip knjižne note!');
    }

    var credit_note_reason_id = $("#CreditNoteCreditNoteReasonId").val();
    if(credit_note_reason_id.length == 0){
        number_of_errors++;
        $("#credit_note_reason_id_error").html('Niste izabrali osnov izdavanja knjižne note!');
    }
   

    var chosen_products = 0;
    $('.trop').each(function(){
        chosen_products++;
    });
    if(chosen_products == 0){
      number_of_errors++;
      $("#order_id_error").html('Niste izabrali proizvode!');  
    } 
 
    if(number_of_errors > 0){
        showErrorPopup('Niste uneli sve podatke!')
    }else{
        var chosen_products = new Array();
        $('.trop').each(function(){
            var str_id = this.id;

            var i_id_index = str_id.indexOf('i_id');
            var fak_id_index = str_id.indexOf('fak_id');

            var c_order_product_id = parseInt(str_id.substring(7, i_id_index));
            var c_item_id = parseInt(str_id.substring((i_id_index+4), fak_id_index));
            var c_order_id = parseInt(str_id.substr(fak_id_index+6));

            chosen_products.push([c_order_product_id, c_item_id, c_order_id]);
        });
        $('#CreditNoteProducts').val(JSON.stringify(chosen_products));
        $('#CreditNoteCurrencyId').val(JSON.stringify(curr_id));
        $('#CreditNoteCustomerId').val(JSON.stringify(customer_id));
        $("#confirm_popup").popup('show');
    }
}//~!

function sendForm(){
    $('#CreditNoteAddForm').submit();
}

</script>