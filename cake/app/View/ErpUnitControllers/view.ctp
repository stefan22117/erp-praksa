<style>
    table th {
        background: #f8f8f8;
    }
</style>
<!-- Breadcrumbs -->
<div class="breadcrumbs_holder">
    <ul class="breadcrumbs">
        <li><?php echo $this->Html->link(__('Početna'), '/'); ?></li>
        <li><?php echo $this->Html->link(__('Organizacija ERP-a'), array('controller' => 'ErpUnits', 'action' => 'index')); ?></li>
        <li class="last"><a href="" onclick="return false"><?php echo __('Konroleri i akcije'); ?></a></li>
    </ul>
</div>
<!-- Messages -->
<div id='alert'><?php echo $this->Session->flash(); ?></div>
<!-- Top bar -->
<div class="name_add_search">
    <!-- Page title -->
    <div class="name_of_page">
        <h3><i class="icon-stop"></i> <?php echo $erpUnitController['Aco']['alias']; ?></h3>
    </div>
    <div class="clear"></div>
</div>

<div class="content_data">
    <table>
        <tr>
            <th width="15%">Modul</th>
            <th width="15%">Kontroller</th>
            <th width="15%">Akcija</th>
            <th width="50%">Opis</th>
            <th width="5%"></th>
        </tr>
        <tr>
            <td><?php echo $erpUnitController['ErpUnit']['name']; ?></td>
            <td><?php echo ($erpUnitController['ParentAco']['id'] == '1') ? $erpUnitController['Aco']['alias'] : $erpUnitController['ParentAco']['alias']; ?></td>
            <td><?php echo ($erpUnitController['ParentAco']['id'] == '1') ? '' : $erpUnitController['Aco']['alias']; ?></td>
            <td class="description"><?php echo $erpUnitController['ErpUnitController']['description']; ?></td>
            <td>
            <?php
                echo $this->Html->link(
                    'Edituj opis',
                    array(),
                    array(
                        'class' => 'btn small change',
                        'onclick' => 'return false',
                        'data-id' => $erpUnitController['ErpUnitController']['id']
                    )
                );
            ?>
            </td>
        </tr>
        <?php foreach ($erpUnitControllers as $unitController): ?>
        <tr>
            <td><?php echo $unitController['ErpUnit']['name']; ?></td>
            <td><?php echo ($unitController['ParentAco']['id'] == '1') ? $unitController['Aco']['alias'] : $unitController['ParentAco']['alias']; ?></td>
            <td><?php echo ($unitController['ParentAco']['id'] == '1') ? '' : $unitController['Aco']['alias']; ?></td>
            <td class="description"><?php echo $unitController['ErpUnitController']['description']; ?></td>
            <td>
            <?php
                echo $this->Html->link(
                    'Edituj opis',
                    array(),
                    array(
                        'class' => 'btn small change',
                        'onclick' => 'return false',
                        'data-id' => $unitController['ErpUnitController']['id']
                    )
                );
            ?>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
</div>
<script>
    $(document).ready(function(){
        $('.change').click(function(){
            var value = $(this).parent().parent().find($('.description')).html();
            $(this).parent().parent().find($('.description')).html('<input type="text" style="width:80%;" value="'+value+'" id="newValue"/><input type="button" value="Snimi" style="padding: 6px 10px 6px 6px; font-size: 11px; margin-left: 10px;" id="save" />');
            $('#newValue').focus();
            $('.change').css('visibility', 'hidden');
            var id = $(this).attr('data-id');
            $('#save').click(function(){
                $('.change').css('visibility', 'visible');
                var newValue = $('#newValue').val();
                $(this).parent().parent().find($('.description')).html(newValue);
                $.ajax({
                    url: '<?php echo $this->Html->url(array('controller' => 'ErpUnitControllers', 'action' => 'changeDescription'));  ?>/' + id,
                    type: 'post',
                    data: {
                        newValue: newValue
                    },
                    success: function(response) {}
                });
            });
            $('#newValue').keydown(function(e){
                if(e.which == 13) {
                    $('#save').trigger('click');
                }
                if(e.which == 27) {
                    $('.change').css('visibility', 'visible');
                    $(this).parent().parent().find($('.description')).html(value);
                }
            });
        });
    });
</script>