<ul class="breadcrumbs">
    <li><?php echo $this->Html->link(__('Početna'), '/'); ?></li>
    <li class="last"><a href="" onclick="return false"><?php echo __('Feedbacks'); ?></a></li>
</ul>
<div id='alert'><?php echo $this->Session->flash(); ?></div>

<div class="name_add_search">
    <div class="name_of_page">
        <h3><i class="icon-envelope-alt"></i> Feedbacks</h3>
    </div>
    <div class="add_and_search">
        <div class="search">
        </div>
    </div>
</div>

<div class='content_data'>
    <div class="col_12 column left"> 
        <fieldset style="margin-top:0;">
            <legend><?php echo __('Filteri pretrage'); ?></legend>
            <div class="clear" style="padding-top:20px;"></div>
            <div>
                <?php echo $this->Form->create('Feedback', array('url' => '/Feedbacks', 'type' => 'get')); ?>
                <div class="col_3">
                    <?php echo $this->Form->input('term', array('label' => 'Pojam za pretragu:&nbsp;', 'type' => 'text', 'required' => false, 'style' => 'font-weight:bold; width:250px;', 'value' => $term)); ?>
                </div>
                <div class="col_2">
                    <?php echo $this->Form->input('from', array('label' => 'Od:&nbsp;', 'placeholder' => 'izaberite datum', 'required' => false, 'autocomplete' => 'off', 'div' => array('style' => 'display:inline-block;'), 'value' => $from, 'onchange' => 'checkDates();')); ?>
                </div>
                <div class="col_2">
                    <?php echo $this->Form->input('to', array('label' => ' do:&nbsp;', 'placeholder' => 'izaberite datum' , 'required' => false, 'autocomplete' => 'off', 'div' => array('style' => 'display:inline-block;'), 'value' => $to, 'onchange' => 'checkDates();')); ?>
                </div>
                <div class="col_3">
                    <?php echo $this->Form->input('status', array('options' => $statuses, 'label' => 'Status:', 'required' => false, 'multiple' => true, 'value' => $selectedStatuses, 'autocomplete' => 'off', 'placeholder' => __('(Svi statusi)'), 'div' => array('style' => 'display:inline-block;')));?>
                </div>
                <div class="clear"></div>
                <?php if ($aclChangeGroup || $aclChangeUser){ ?>
                <div class="col_5">
                    <?php
                        echo __('Feedback dodao: ');
                        echo $this->Form->input(
                            'user_id',
                            array(
                                'type' => 'hidden',
                                'required' => false,
                                'style' => 'font-weight:bold; width:400px;'
                            )
                        );
                    ?>
                </div>
                <div class="col_3">
                <?php
                    echo __('Erp Modul: ');
                    echo $this->Form->input(
                        'erp_unit_id',
                        array(
                            'type' => 'select',
                            'div' => false,
                            'label' => false,
                            'class' => 'dropdown',
                            'style' => 'width: 200px',
                            'value' => $erpUnitId,
                            'empty' => 'Svi'
                        )
                    );
                ?>
                </div>
                <div class="col_3">
                <?php
                    echo __('Feedback dodeljen: ');
                    echo $this->Form->input(
                        'user_working_id',
                        array(
                            'type' => 'select',
                            'div' => false,
                            'label' => false,
                            'class' => 'dropdown',
                            'style' => 'width: 200px',
                            'empty' => 'Svi'
                        )
                    );
                ?>
                </div>
                <?php } ?>
            </div>
            <div class="right" style="margin:35px 0px 0 0;">
                <?php echo $this->Form->button(__('Filtriraj'), array('type' => 'submit', 'class' => 'small green right', 'style' => 'margin-left:10px;')); ?>
            </div>
            <?php echo $this->Form->end(); ?>
        </fieldset>
    </div>
</div>

<?php echo $this->element('paginator'); ?>
<div class='content_data'>
   <?php if(empty($feedbacks)){ ?>
   <div class="notice warning">
            <i class="icon-warning-sign icon-large"></i>
                <?php echo __('Nema feedback-a'); ?>.
            <a class="icon-remove" href="#close"></a>
        </div>
   <?php } else { ?>
    <table style="">
    <thead>
        <tr>
            <th style="width:150px;"><?php echo __('Korisnik'); ?></th>
            <th style="width:100px;"><?php echo __('Datum'); ?></th> 
            <th style="width:100px;"><?php echo __('Modul'); ?></th>   
            <th style="width:100px;"><?php echo __('Status'); ?></th>  
            <th style="width:450px;"><?php echo __('Opis'); ?></th>    
            <th></th>      
        </tr>
    </thead>
    <tbody>
    <?php foreach($feedbacks as $feedback): ?>
        <?php
            switch ($feedback['Feedback']['status']) {
                 case 'open':
                     echo '<tr style="vertical-align:top">';
                     break;
                 case 'working_on':
                     echo '<tr class="yellow" style="vertical-align:top">';
                     break;
                 case 'closed':
                     echo '<tr class="canceled" style="vertical-align:top">';
                     break;
                 case 'postponed':
                     echo '<tr style="background-color:#2ECCFA;" style="vertical-align:top">';
                     break;    
                 default:
                     echo '<tr style="vertical-align:top">';
                     break;
             } 
        ?>
            <td>
                <?php if(empty($feedback['User']['avatar_link'])){ ?>
                    <?php echo $this->Html->image('company/avatar_default.png', array('alt' => 'Default Avatar', 'style' => 'width:40px; height:40px;'));?><br>
                <?php }else{ ?>
                    <?php echo $this->Html->image(str_replace("\\", "/", $feedback['User']['avatar_link']), array('alt' => $feedback['User']['username'], 'style' => 'width:40px; height:40px;'));?><br>
                <?php } ?>
                <?php echo $feedback['User']['first_name'].' '.$feedback['User']['last_name']; ?>
            </td>
            <td>
                <?php echo $this->Time->format('j.n.Y. H:i',$feedback['Feedback']['created']); ?>
            </td>
            <td style="word-break:break-word; width:200px;">
                <?php
                if(!empty($feedback['Feedback']['ErpUnit']['code'])) {
                    echo $this->Html->link(
                        $feedback['Feedback']['ErpUnit']['code'].' - '.$feedback['Feedback']['ErpUnit']['name'],
                        array(
                            'controller' => 'ErpUnits',
                            'action' => 'view',
                            $feedback['Feedback']['ErpUnit']['id']
                        )
                    );
                }
                ?>
            </td>
            <td>
            <?php echo $statuses[$feedback['Feedback']['status']]; ?>
            </td>
            <td>
            <?php 
                $descriptions = explode(' ', $feedback['Feedback']['description']);
                foreach ($descriptions as $key => $value) {
                    if(strlen($value) < 40) echo $value.' ';
                }
            ?>
            </td>
            <td class='right'>
            <ul class="button-bar">
                <li class="first">
                <?php echo $this->Html->link('<i class="icon-eye-open"></i>', array('action' => 'view', $feedback['Feedback']['id']), array('title' => __('Pregled'), 'escape' => false, 'style' =>'color:blue;')); ?>
                </li>
                <li class="last">
                <?php
                    echo $this->Html->link(
                        '<i class="icon-link"></i>',
                        $feedback['Feedback']['link'],
                        array(
                            'escape' => false,
                            'title' => __('Link'),
                            'style' => 'color: green;',
                            'target' => '_blank'
                        )
                    );
                ?>
                </li>
            </ul>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
    </table>
    <?php } ?>
</div>

<?php echo $this->element('paginator'); ?>

<script type="text/javascript">
$('#container').ready(function(){
    <?php
    if(!empty($user_id)){
            ?>
            $('#FeedbackUserId').select2("data", { id: <?php echo (int)$user_id; ?>, text: "<?php echo $user_name; ?>" });
            <?php
        }
    ?>
    $('.dropdown').select2();
    $("#FeedbackFrom").datepicker({ dateFormat: "yy-mm-dd" });
    $("#FeedbackTo").datepicker({ dateFormat: "yy-mm-dd" });
    $("#FeedbackStatus").select2({ width: '210px'});
});

// check dates
function checkDates(){
    var from = $("#FeedbackFrom").val();
    var to = $("#FeedbackTo").val();
    $.ajax({
        dataType: 'json',
        type: 'POST',
        evalScripts: true,
        data: ({from: from, to: to}),
        url: "<?php echo Router::url(array('controller' => 'Feedbacks', 'action' => 'checkDates')); ?>",
        success: function(data){
            if(data['to_error']){
                $("#FeedbackTo").val('');
            }
            if(data['from_error']){
                $("#FeedbackFrom").val('');
            }
            if(data['error']){
                showErrorPopup(data['error']);
            }
        },
        error: function(xhr){
            var error_msg = 'Error: ' + xhr.status + ' ' + xhr.statusText;
            showErrorPopup(error_msg);
        }
    });
}//~!

$('#FeedbackUserId').select2({
    minimumInputLength: 0,
    placeholder: "(<?php echo __('Izaberite korisnika'); ?>)",
    allowClear: true,
    query: function (query){
        var process = {results: []};
        $.ajax({
            dataType: "json",
            type: "POST",
            evalScripts: true,
            data: ({ term: query.term}),
            url: '/Feedbacks/getUsersBySearch',
            success: function (data){
                var index;
                for (var index = 0; index < data.length; index++) {
                    process.results.push({id: data[index].User.id, text: data[index].User.first_name + ' ' + data[index].User.last_name});
                };
                query.callback(process);
            },
            error: function(xhr){
                var error_msg = 'Error: ' + xhr.status + ' ' + xhr.statusText;
            }
        });
    }
});

</script>
