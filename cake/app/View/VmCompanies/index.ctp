COMPANIES index
<ul class="breadcrumbs">
    <li><?= $this->Html->link('Home', array('controller' => 'pages', 'action' => 'display')); ?></li>
    <li class="last"><a href="" onclick="return false">Companies</a></li>
</ul>

<h2>Companies</h2>
<?php
if (isset($vm_companies) && count($vm_companies) > 0) {
?>
    <table>
        <tr>
            <th>
                name
            </th>
            <th>
                address
            </th>
            <th>
                city
            </th>
            <th>
                email
            </th>
            <th>
                zip_code
            </th>
            <th></th>
        </tr>

        <?php
        foreach ($vm_companies as $vm_company) {
        ?>
            <tr>
                <td>
                    <?= $vm_company['VmCompany']['name'] ?>
                </td>
                <td>
                    <?= $vm_company['VmCompany']['address'] ?>
                </td>
                <td>
                    <?= $vm_company['VmCompany']['city'] ?>
                </td>
                <td>
                    <?= $vm_company['VmCompany']['email'] ?>
                </td>
                <td>
                    <?= $vm_company['VmCompany']['zip_code'] ?>
                </td>

                <td>

                    <ul class="button-bar">

                        <li class="first"><?php echo $this->Js->link('<i class="icon-eye-open"></i>View', array('action' => 'view', $vm_company['VmCompany']['id']), array('update' => '#container', 'buffer' => false, 'escape' => false, 'before' => '$(".submit_loader").show();', 'success' => 'history.pushState(null, "MikroERP - Edit menu", this.url); $(document).attr("title", "MikroERP - Edit menu");')); ?></li>

                        <li><?php echo $this->Js->link('<i class="icon-edit"></i>Edit', array('action' => 'edit', $vm_company['VmCompany']['id']), array('update' => '#container', 'buffer' => false, 'escape' => false, 'before' => '$(".submit_loader").show();', 'success' => 'history.pushState(null, "MikroERP - Add menu element", this.url); $(document).attr("title", "MikroERP - Add menu element");')); ?></li>

                        <li class="last"><?php echo $this->Js->link('<i class="icon-trash"></i>Delete', array('action' => 'delete', $vm_company['VmCompany']['id']), array('update' => '#container', 'escape' => false, 'buffer' => false, 'confirm' => "Are you sure you want to delete this vechile?",)); ?></li>
                    </ul>
                </td>

            </tr>


        <?php
        }
        ?>
    </table>
    <?php
} else {
    ?>
<h3>There's no companies</h3>

    <?php
}
    ?>


<div class="clear"></div>
<div class="submit_loader">
    <?php echo $this->Html->image('submit_loader.gif', array('alt' => 'Loader')); ?>
    <h2>Molimo sačekajte...</h2>
</div>

<script>
    $('#container').ready(function() {
        $(".submit_loader").hide();
    });
</script>