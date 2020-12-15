<ul class="breadcrumbs">
    <li><?php echo $this->Html->link('Početna', '/'); ?></li>
    <li> <?php echo $this->Html->link('Zaposleni (zarade)', array('action' => 'salary_list')); ?></li>
    <li class="last"><a href="" onclick="return false">Zarada zaposlenog</a></li>
</ul>
<div id='alert'><?php echo $this->Session->flash(); ?></div>
<div class="name_add_search">
    <div class="name_of_page"><h3>Zarada zaposlenog</h3></div>
    <div class="add_and_search">
    </div>
</div>
<!-- confirm box -->
<fieldset id="confirm_popup" style="width:300px; height:130px; text-align:center;">
    <p>Da li ste sigurni da želite da zaposlenom (<b><?php echo $user['User']['name']; ?></b>) dodelite zaradu u iznosu <b><span id='popup_salary'></span></b> u valuti <b><span id='popup_currency'></span></b>?</p>
            <ul class="button-bar" style="margin-left:80px;">
                <li class="first"><a href="" onclick="sendForm(); return false;">Da</a></li>
                <li class="last"><a href="" onclick="$('#confirm_popup').popup('hide'); return false;">Ne</a></li>
            </ul>
    
</fieldset>
<!-- confirm box -->
<!-- error box -->
<div id="error_popup"></div>
<!-- error box -->
<div class='content_data'>
<?php echo $this->Form->create('User'); ?>
<?php echo $this->Form->input('id', array('type' => 'hidden')); ?>
<fieldset>
    <legend><?php echo $user['User']['name']; ?></legend>
    <table>
        <tr>
            <td style="width:100px;" rowspan="2">
                <?php if(empty($user['User']['avatar_link'])){ ?>
                    <?php echo $this->Html->image('company/avatar_default.png', array('alt' => 'Default Avatar', 'style' => 'width:100px; height:100px;'));?><br>
                <?php }else{ ?>
                    <?php echo $this->Html->image(str_replace("\\", "/", $user['User']['avatar_link']), array('alt' => $user['User']['username'], 'style' => 'width:100px; height:100px;'));?><br>
                <?php } ?>
            </td>
            <th style="width:100px; text-align:center;">
                Zarada:
            </th>
            <th style="width:100px; text-align:center;">
                Valuta:
            </th>
            <th></th>
        </tr>
       <tr>
           <td style="width:100px;">
               <?php echo $this->Form->input('salary', array('label' => false, 'type' => 'text', 'required' => false, 'autocomplete' => 'off', 'placeholder' => 'zarada', 'style' => ' width:100px;')); ?>
           </td>
           <td>
               <?php echo $this->Form->input('currency_id', array('label' => false, 'options' => $currencies, 'style' => 'width:150px;', 'empty' => '(izaberite valutu)', 'required' => false)); ?>
           </td>
           <td>
               <button class="small blue" onclick="validation(); return false;"><i class="icon-save"></i> Sačuvaj</button>
           </td>
       </tr>

    </table>
</fieldset>
<?php echo $this->Form->end(); ?>
</div>




<div class="clear"></div>
<script>

$('#container').ready(function(){
});

$("#UserCurrencyId").select2({ width : 'off' });


function sendForm(){
    $("#UserSalaryForm").submit();
}//~!

function validation(){
    var salary = $("#UserSalary").val();
    var currency_id = $("#UserCurrencyId").val();
    var currency = $('#UserCurrencyId').find('option:selected').text();
    var error = '';
    if(salary.length == 0 || currency_id.length == 0 || $.isNumeric(salary) == false ||  salary <= 0){
        if(salary.length == 0){
            error = 'Niste uneli zaradu. ';
        }
        if($.isNumeric(salary) == false){
                error = 'Iznos nije validan! ';
            }
        if(salary <= 0){
            error = 'Iznos nije validan! ';
        }
        if(currency_id.length == 0){
            error += 'Niste izabrali valutu.';
        }
        $('#my_popup').html('<div class="notice error margin20"><i class="icon-remove-sign icon-large"></i>'+error+'</div>');
        $('#my_popup').popup('show');
        setTimeout(function(){$('#my_popup').popup('hide');}, 2000);
    }else{
        $("#popup_salary").html(salary);
        $("#popup_currency").html(currency);
        $('#confirm_popup').popup('show');
    }
}//~!

$('#confirm_popup').popup({
    opacity: 0.3,
    transition: 'all 0.3s'
});

$('#error_popup').popup({
    opacity: 0.3,
    transition: 'all 0.3s'
});






</script>