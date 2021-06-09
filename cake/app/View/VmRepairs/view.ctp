<h2><i class="icon-wrench"></i> Repairs for <?= $vm_vehicle['VmVehicle']['brand_and_model'] ?></h2>

<?php
//spojiti da se vide i kola i hr i kompanija

if (isset($vm_repairs) && count($vm_repairs)) {
?>
    <table>
        <tr>
            <td>Amount</td>
            <td>Spent time</td>
            <td>Description</td>
            <td>Date</td>
            <td>Company</td>
        </tr>
        <tr>
        <?php
        foreach($vm_repairs as $vm_repair){
        ?>
        <td>
            <?= $vm_repair['VmRepair']['amount'] ?>
        </td>
        <td>
            <?= $vm_repair['VmRepair']['spent_time'] ?>
        </td>
        <td>
            <?= $vm_repair['VmRepair']['description'] ?>
        </td>
        <td>
            <?= 'datum...'//$vm_repair['VmRepair']['date'] ?>
        </td>
        <td>
        <ul class="button-bar">
                                <li class="first"><?php echo $this->Js->link('<i class="icon-eye-open"></i>'. $vm_repair['VmCompany']['name'], array('controller' => 'vmcompanies', 'action' => 'view',$vm_repair['VmCompany']['id']), array('update' => '#container', 'buffer' => false, 'escape' => false, 'before' => '$(".submit_loader").show();', 'success' => 'history.pushState(null, "MikroERP - Edit menu", this.url); $(document).attr("title", "MikroERP - Edit menu");')); ?></li>
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



<?php
}
?>