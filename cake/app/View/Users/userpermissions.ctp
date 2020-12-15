<?php if(!empty($show_users)){ ?>
    <?php echo $this->element('../Users/users'); ?>
<?php } ?>
  <ul class="breadcrumbs margin20">
      <li><?php echo $this->Html->link('Home', '/'); ?></li>
      <li><?php echo $this->Js->link('Users', array('controller' => 'Users', 'action' => 'index'), array('update' => '#container', 'buffer' => false, 'before' => '$(".submit_loader").show();', 'success' => 'history.pushState(null, "MikroERP - List users", this.url); $(document).attr("title", "MikroERP - List users");')); ?></li>
      <li class="last"><a href="" onclick="return false">Set Permissions</a></li>
  </ul>
<div id='alert'><?php echo $this->Session->flash(); ?></div>

<div class="name_add_search">
    <div class="name_of_page"><h3>Add user permissions</h3></div>
    <div class="add_and_search">
        <div class="search"><?php echo $this->Form->create('User', array('action' => 'search')); ?>
            <?php echo $this->Form->input('name', array('label' => false, 'class' => 'input_search pictureInput', 'placeholder' =>'Podaci za pretragu'));?>
            <?php echo $this->Js->submit('Search', array(
                        'url' => array('action' => 'search'),
                        'update' => '#container',
                        'div' => false,
                        'class' => "button_search",
                        'buffer' => false,
                        'before' => '$(".submit_loader").show();',
                        'success' => 'history.pushState(null, "MikroERP - Search", "'.Router::url(array('controller' => 'users', 'action' => 'search')).'"); $(document).attr("title", "MikroERP - Search");'
                    ));?>
            <?php echo $this->Form->end(); ?></div>
    </div>
</div>

<?php if(!empty($controllers)){ ?>

<?php echo $this->Form->create('User', array('class' => 'form-signin')); ?>

<div class="content_data">
<div class="formular">

  <table>
    <?php  echo $this->Form->input($user['User']['id'], array('type' => 'hidden'));?>
    <?php foreach($controllers as $controller => $methods):
      $arrController[$controller] = $controller; 
          endforeach; ?>
    <tr><td>

    <div class="content_text_input">
      <label for="UserEmail"> Select controller</label>
      <?php echo $this->Form->input('controller', array('label' => false, 'options' => $arrController, 'empty' => '(Choose Controller)', 'class' => 'col_9')); ?>
    </div>
    </td></tr>
    <tr id="checkboxcontroller"><td>
      <div id="target"><a href="#" class="button small" onclick="return false;">Check / Uncheck all</a></div>
    </td></tr>
  </table>
  <table>
    <thead id="headpermissions">
      <tr><th>Method</th><th>Group</th><th>User</th></tr>
    </thead>
    <tbody id="permissions">

    </tbody>
  </table>
  <table class='table submitbutton'>
    <tr><td>
    <?php echo $this->Js->submit('Set permissions', array(
                    'update' => '#container',
                    'div' => false,
                    'class' => "button blue",
                    'style' => "margin:20px 0 20px 0;",
                    'buffer' => false,
                    'before' => '$(".submit_loader").show();',
                    'success' => 'history.pushState(null, "MikroERP - List users", this.url); $(document).attr("title", "MikroERP - List users");'
                )); ?>
    <?php echo $this->Js->link('Cancel', array('controller' => 'Users', 'action' => 'index'), array('update' => '#container', 'buffer' => false,'htmlAttributes' => array('class' => 'button', 'style' => 'margin:20px 0 20px 0;'), 'before' => '$(".submit_loader").show();', 'success' => 'history.pushState(null, "MikroERP - List users", this.url); $(document).attr("title", "MikroERP - List users");')); ?>
  <?php echo $this->Form->end(); ?>
    
    </td></tr>
  </table>
</div>
</div>
<div class="clear"></div>
<div class="submit_loader">
  <?php echo $this->Html->image('submit_loader.gif', array('alt' => 'Loader')); ?>
  <h2>Molimo sačekajte...</h2>
</div>


<?php $id=$user['User']['id']; ?>
<script type="text/javascript">
  function getActions(){  

    $.ajax({
      dataType: "json",
      type: "POST",
      evalScripts: true,
      url: "<?php echo Router::url(array('controller'=>'users', 'action'=>'getByControllerAndUser'));?>",
      data: ({controller:$("#UserController" ).val(), user_id:<?php echo $user['User']['id']; ?>}),
      success:function(data){
        var result = $.map(data, function(k, v) {
            return [k];
        });
        var length = result.length,
            element = null;
        if(length > 0){
          $('#permissions').empty();
          $('.submitbutton').show();
          $('#headpermissions').show();
          $('#checkboxcontroller').show();
            for (var i = 0; i < length; i++) {
            element = result[i];
              $('#permissions').append('<tr> ');
              $('#permissions').append('<td>' + element["method"] + '</td> ');

            if(element["group"]["allowed"])
              $('#permissions').append('<td><input name="' + element["method"] + '" type="checkbox" checked="checked" disabled /></td> ');
            else
              $('#permissions').append('<td><input name="' + element["method"] + '" type="checkbox" disabled /></td> ');

            if(element["user"]["allowed"])
              $('#permissions').append('<td><input name="' + element["method"] + '" type="checkbox" class="checkboxes" checked="checked" /></td>');
            else
              $('#permissions').append('<td><input name="' + element["method"] + '"  type="checkbox" class="checkboxes" /></td>');

              $('#permissions').append('</tr> ');

            }  
        }else{
            $('#permissions').empty();
            $('#checkboxcontroller').hide();
            $('.submitbutton').hide();
            $('#headpermissions').hide();
            //alert('greska');
        }          
        $("#errors").html("");
        $("#errors").addClass("hide");         

        //$("#json_response").html('<pre>' + JSON.stringify(data, null, 4) + '</pre>');
      },
      error:function(xhr){
        var error_msg = "An error occured: " + xhr.status + " " + xhr.statusText;
        $("#errors").html("<p>"+error_msg+"</p>");
        $("#errors").removeClass("hide");
      }
    });      
  }

  $('#container').ready(function() {
      $(".submit_loader").hide();
      $('.submitbutton').hide();
      $('#headpermissions').hide();
      $('#checkboxcontroller').hide();
      $("#UserController").change(function() {
        getActions();
      });     

    if($('#UserController').val().length > 0){
          getActions();
    }

    $('#target').click(function() {
    var n = $( ".checkboxes:checked" ).length;
    var r = $( ".checkboxes" ).length;
    if(n==r){
    $(".checkboxes").prop('checked', false);
    }
    else{
    $(".checkboxes").prop('checked', true);
    }
    });
  });


</script>



<?php } else{ ?>
<h1>No results found for this user</h1>
<?php }
$this->Session->flash();
 ?>