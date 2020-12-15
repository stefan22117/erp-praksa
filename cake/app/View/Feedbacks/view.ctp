<ul class="breadcrumbs">
    <li><?php echo $this->Html->link('Početna', '/'); ?></li>
    <li><?php echo $this->Html->link('Feedbacks', array('controller' => 'Feedbacks','action' => 'index')); ?></li> 
    <li class="last"><a href="" onclick="return false">Pregled feedback-a</a></li>
</ul>
<div id='alert'><?php echo $this->Session->flash(); ?></div>

<div class="name_add_search">
    <div class="name_of_page"><h3>Pregled Feedback-a</h3></div>
</div>

<fieldset id="status_popup" style="width:250px; height:auto;">
    <legend>Rezultat rework-a</legend>
    <p>Da li ste sigurni da želite da promenite status?</p>
    <ul class="button-bar" style="margin-left:55px;">
        <li class="first"><a href="" onclick="pri_status_change(); return false;">Da</a></li>
        <li class="last"><a href="" onclick="$('#pri_status_popup').popup('hide'); return false;">Ne</a></li>
    </ul>
</fieldset>

<div class='content_data'>
    <table>
        <tr>
            <th width="180">Feedback otvorio</th>
            <td><?php echo $us['0']['0']['ime']; ?></td>
            <td></td>
        </tr>
        <tr>
            <th>Datum i vreme otvaranja</th>
            <td><?php echo $this->Time->format('j.n.Y. H:i',$fb['Feedback']['created']); ?></td>
            <td></td>
        </tr>
        <tr>
            <th>Feedback vezan za modul</th>
            <td id="unit">
                <?php
                    if (!empty($feedback['ErpUnit'])) {
                        echo $feedback['ErpUnit']['code'].' - '.$feedback['ErpUnit']['name'];
                    }
                ?>
            </td>
            <td></td>
        </tr>
        <?php if($userIsDeveloper): ?>
        <tr>
            <th>Kontroler / Akcija</th>
            <td>
            <?php
                echo $this->Form->create('ErpUnit', array('action' => '/view/2129'));
                echo $this->Form->input(
                    'controller_id',
                    array(
                        'label' => false,
                        'div' => false,
                        'type' => 'select',
                        'class' => 'dropdown',
                        'style' => 'width: 200px;',
                        'options' => $controllers,
                        'empty' => __('(Kontroler)'),
                        //'default' => $controller
                    )
                ).' - ';
                echo $this->Form->input(
                    'action_id',
                    array(
                        'label' => false,
                        'div' => false,
                        'type' => 'select',
                        'class' => 'dropdown',
                        'style' => 'width: 200px;',
                        'empty' => __('(Akcija)'),
                        // 'default' => $action
                    )
                );
                echo $this->Form->end();
            ?>
            </td>
            <td></td>
        </tr>
        <?php endif; ?>
        <tr>
            <th>Modul razvio</th>
            <td id="developers">
                <?php
                    $developers = '';
                    foreach ($feedback['developers'] as $developer) {
                        $firstName = $developer['ErpDeveloper']['User']['first_name'];
                        $lastName = $developer['ErpDeveloper']['User']['last_name'];
                        $developers .= $firstName.' '.$lastName.', ';
                    }
                    $developers = rtrim($developers, ', ');
                    echo $developers;
                ?>
            </td>
            <td></td>
        </tr>
        <tr>
            <th>Modul održava</th>
            <td id="maintainers">
                <?php
                    $maintainers = '';
                    foreach ($feedback['maintainers'] as $maintainer) {
                        $firstName = $maintainer['ErpDeveloper']['User']['first_name'];
                        $lastName = $maintainer['ErpDeveloper']['User']['last_name'];
                        $maintainers .= $firstName.' '.$lastName.', ';
                    }
                    $maintainers = rtrim($maintainers, ', ');
                    echo $maintainers;
                ?>
            </td>
            <td></td>
        </tr>
        <tr>
            <th>Na feedback-u radi</th>
            <?php if($userIsDeveloper): ?>
            <td id="userworking">
                <?php
                    $userWorkingId = null;
                    if(!empty($feedback['UserWorking']['id'])) {
                        $userWorkingId = $feedback['UserWorking']['id'];
                    }
                    echo $this->Form->create('Feedback', array('action' => '/view/2129'));
                    echo $this->Form->input(
                        'user_working_id',
                        array(
                            'label' => false,
                            'div' => false,
                            'type' => 'select',
                            'class' => 'dropdown',
                            'style' => 'width: 200px;',
                            'options' => $users,
                            'empty' => __('Izaberite...'),
                            'default' => $userWorkingId
                        )
                    );
                    echo $this->Form->end();
                ?>
            </td>
            <?php else: ?>
            <td>
            <?php echo $feedback['UserWorking']['first_name'].' '.$feedback['UserWorking']['last_name']; ?>
            </td>
            <?php endif; ?>
            <td></td>
        </tr>
        <tr>
            <th>Feedback zatvorio</th>
            <td>
                <?php
                    if(!empty($feedback['UserClosed']['id']))
                        echo $feedback['UserClosed']['first_name'].' '.$feedback['UserClosed']['last_name'];
                ?>
            </td>
            <td></td>
        </tr>
        <tr>
            <th>Datum i vreme zatvaranja</th>
            <td>
                <?php
                    if(!empty($feedback['Feedback']['date_time_closed']))
                        echo $this->Time->format('d.m.Y. H:i',$feedback['Feedback']['date_time_closed']);
                ?>
            </td>
            <td></td>
        </tr>
        <tr>
            <th>
                Link:
            </th>
            <td>
                <div style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis; width: 800px;">
                    <?php
                        if ($fb['Feedback']['link'] == '')
                            echo '';
                        else
                            echo $this->Html->link(
                                $fb['Feedback']['link'],
                                $fb['Feedback']['link'],
                                array(
                                    'target'=>'_blank',
                                    'escape'=>false
                                )
                            );
                    ?>
                </div>
            </td>
            <td></td>
        </tr>
        <tr>
            <th>
                Status:
            </th>
            <td id="comment_text">
                <?php
                    switch ($fb['Feedback']['status']) {
                         case 'open':
                             echo 'otvoren';
                             break;
                         case 'working_on':
                             echo 'u toku';
                             break;
                         case 'closed':
                             echo 'zatvoren';
                             break;
                         case 'postponed':
                             echo 'odloženo';
                             break;    
                         default:
                             echo '';
                             break;
                     } 
                ?>
                
            </td>
            <td></td>
        </tr>
        <?php if(($aclChangeGroup || $aclChangeUser) && $fb['Feedback']['status'] != 'closed'){ ?> 
        <tr id="change_status">
        <th>
                Promeni status:
            </th>
            <td>
                <?php echo $this->Form->input('feedback_id', array('type'=>'hidden', 'value' => $id)); ?>
                <?php echo $this->Form->input('status', array('label' => false, 'onchange'=>'changeStatus(); return false;', 'class' => 'inputborder', 'required' => false, 'options' => array('open'=>'otvoren','working_on'=>'u toku','closed'=>'zatvoren', 'postponed' => 'odloženo'))); ?>
            </td>
            <td></td>
        </tr>
        <?php } ?>
        <tr>
            <th>
                Opis:
            </th>
            <td>
                <?php echo $fb['Feedback']['description']; ?>
            </td>
            <td></td>
        </tr>
    </table>
    <div class="comment form">
    <?php echo $this->Form->create('Feedback'); ?>
    <table>
        <tr>
            <th>Komentari:</th>
        </tr>
        <tr>
            <td><?php echo $this->Form->input('comment', array('label' => false, 'type' => 'textarea','class'=>'comment_area', 'cols' => '100'));?></td>
        </tr>
        <tr id='comm_button'>
            <td>
               <ul class="button-bar" style="margin-left: 100px;">
                <li class="first last"><a href="" onclick="comment(); return false;"><i class="icon-envelope" style="color: #4096EE;"></i> Pošalji komentar</a></li>
                </ul>
            </td>
        </tr>
    </table>
    <?php echo $this->Form->end(); ?>
    <?php if(count($comments)>0){ ?> 
    <table>
    <?php if($fb['Feedback']['status'] == 'closed'){ ?>
    <tr>
        <th colspan="3">Komentari:</th>
    </tr>
    <?php } ?>
    <tr>
        <th width="180px">Korisnik</th>
        <th width="180px">Datum i vreme</th>
        <th>Komentar</th>
    </tr>
        <?php foreach($comments as $comment) { ?>
        <tr>
            <td>
            <?php if(empty($comment['u']['avatar_link'])){ ?>
                    <?php echo $this->Html->image('company/avatar_default.png', array('alt' => 'Default Avatar', 'style' => 'width:40px; height:40px;'));?><br>
                <?php }else{ ?>
                    <?php echo $this->Html->image(str_replace("\\", "/", $comment['u']['avatar_link']), array('alt' => $fb['User']['username'], 'style' => 'width:40px; height:40px;'));?><br>
                <?php } ?>
            <?php echo $comment['0']['ime']; ?>
            </td>
            <td><?php echo $this->Time->format('j.n.Y. H:i',$comment['fc']['created']); ?></td>
            <td><?php echo $comment['fc']['comment'] ?></td>
        </tr>
        <?php } ?>
    </table>
    <?php } ?>
</div>
</div>
<div class="clear"></div>

<script type="text/javascript">
$('#FeedbackComment').ckeditor( function() { /* callback code */ }, { height : '200px' } );
$('#container').ready(function(){
    $("#status").val("<?php echo $fb['Feedback']['status']; ?>");
    $(".dropdown").select2();

    $('#FeedbackUserWorkingId').change(function(){
        var feedbackId = <?php echo $fb['Feedback']['id']; ?>;
        var userId = $("#FeedbackUserWorkingId").val();
        if(userId != '') {
            $.ajax({
                url: "<?php echo $this->Html->url(array('controller' => 'Feedbacks', 'action' => 'addUserWorking')) ?>/" + feedbackId + "/" + userId,
                success: function(response) {
                    $('#user-working').html(response)
                    data = [];
                    data.success = true;
                    data.message = 'Zaposleni dodat da radi na feedbacku';
                    successNotification(data);
                },
                error: function() {
                    data = [];
                    data.success = false;
                    data.message = 'Zaposleni nije dodat da radi na feedbacku';
                    successNotification(data);
                }
            });
        } else {
            data = [];
            data.success = false;
            data.message = 'Nije izabran zaposleni da radi na feedbacku';
            successNotification(data);
        }
    });

    $('#ErpUnitControllerId').change(function(){
        $('#ErpUnitActionId option[value!=""]').remove();
        $("#ErpUnitActionId").off("select2-selecting");
        $("#ErpUnitActionId").select2();
        var id = $(this).val();
        $.ajax({
            url: '<?php echo $this->Html->url(array('controller' => 'MenuItems', 'action'=>'selectActions')); ?>',
            type: "post",
            dataType: "json",
            evalScripts: true,
            data: {id: id},
            success: function(response) {
                Object.keys(response).sort().forEach(function(key, i) {
                    $('#ErpUnitActionId').append($("<option></option>").attr("value",key).text(response[key]));
                });
                $('#ErpUnitActionId').val(<?php echo $action; ?>);
                $('#ErpUnitActionId').trigger('change.select2');
            }
        });
    });

    $("#ErpUnitActionId").change(function(){
        var feedbackId = <?php echo $fb['Feedback']['id']; ?>;
        var acoId = $("#ErpUnitActionId").val();
        if(acoId) {
            $.ajax({
                url: "<?php echo $this->Html->url(array('controller' => 'Feedbacks', 'action' => 'addAco')) ?>/" + feedbackId + "/" + acoId,
                success: function(response) {
                    result = $.parseJSON(response);
                    $('#unit').html(result['module']);
                    $('#developers').html(result['developers']);
                    $('#maintainers').html(result['maintainers']);
                    data = [];
                    data.success = true;
                    data.message = 'Feedback povezan sa akcijom kontrolera';
                    successNotification(data);
                },
                error: function() {
                    data = [];
                    data.success = false;
                    data.message = 'Feedback nije povezan sa akcijom kontrolera';
                    successNotification(data);
                }
            });
        } else {
            data = [];
            data.success = false;
            data.message = 'Nije izabrana akcija kontrolera';
            successNotification(data);
        }
    });

    $('#ErpUnitControllerId').val(<?php echo $controller; ?>);
    $('#ErpUnitControllerId').change();
 
});

$('#status_popup').popup({
    opacity: 0.3,
    transition: 'all 0.3s',
    scrolllock: true
});

function comment(){
    $("#FeedbackViewForm").submit();
}

function changeStatus(){
    var status = $("#status").val();
    var r = confirm("Da li ste sigurni da želite da promenite status?");
    if (r == true) {
    var id = $("#feedback_id").val();
        $.ajax({
            dataType: "json",
            type: "POST",
            evalScripts: true,
            data: ({status:status, id:id}),
            url: "<?php echo Router::url(array('controller' => 'Feedbacks', 'action' => 'change')); ?>",
            success: function (data){
           if(data['success']){
            $("#comment_text").html('');
            $("#comment_text").html(data['status']);
                if (data['status'] == 'zatvoren'){
                    $("#change_status").remove();
                }
            }else{
                alert(data['error']);
            }
            },
            error:function(xhr){
            var error_msg = "An error occured: " + xhr.status + " " + xhr.statusText;
            $("#results").html("<p>"+error_msg+"</p>");
            }
        });
    }else{
        $("#status").val("<?php echo $fb['Feedback']['status']; ?>");    
    } 
}

function successNotification(data){
	if ( data.success ){
		$.ambiance({
			message: data.message,
			type: "success",
			timeout: 3,
		});
	}else{
		$.ambiance({
			message: data.message,
			type: "error",
			timeout: 10,
			fade: false
		});
	}
}//~!

</script>
