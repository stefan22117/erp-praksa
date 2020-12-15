<?php 
    echo $this->Html->script('Script/Helpers/accounting.js?ver='.filemtime(WWW_ROOT . "/js/Script/Helpers/accounting.js"));
?>
<table style="margin:0;">
    <thead>
        <tr class="column_numbers noTopBorder">
            <th rowspan="2" class="center">
                <a href="javascript:void(0)" class="button small add" title="Dodaj red">
                    <i class="icon-plus-sign" style="color:green;"></i>
                </a>
            </th>
            <th rowspan="2" class="center"><?php echo __('RB'); ?></th>
            <th rowspan="2" class="center"><?php echo __('Iznos'); ?></th>
            <th rowspan="2" class="center"><?php echo __('D / P'); ?></th>
            <th rowspan="2" class="center"><?php echo __('Konto'); ?></th>
            <th rowspan="2" class="center"><?php echo __('Analitika'); ?></th>
            <th colspan="2" class="center"><?php echo __('Primarna veza'); ?></th>
            <th colspan="2" class="center"><?php echo __('Sekundarna veza'); ?></th>
            <th rowspan="2" class="center"><?php echo __('Opis'); ?></th>
        </tr>
        <tr class="column_numbers">
            <th class="center"><?php echo __('Vrsta'); ?></th>
            <th class="center"><?php echo __('Broj'); ?></th>
            <th class="center"><?php echo __('Vrsta'); ?></th>
            <th class="center"><?php echo __('Broj'); ?></th>                                    
        </tr>
    </thead> 
    <tbody id="accounting">
        <!-- This part is loaded with frontend -->
    </tbody>
    <tfoot>
        <tr class="sums">
            <td>&nbsp;</td>
            <td class="bold center">D</td>
            <td id="debit_sum" class="bold right">&nbsp;</td>
            <td class="bold"><?php echo __('Duguje'); ?></td>
            <td colspan="7">&nbsp;</td>
        </tr>
        <tr class="sums">
            <td>&nbsp;</td>
            <td class="bold center">P</td>
            <td id="credit_sum" class="bold right">&nbsp;</td>
            <td class="bold"><?php echo __('Potražuje'); ?></td>
            <td colspan="7">&nbsp;</td>
        </tr>
        <tr class="sums">
            <td>&nbsp;</td>
            <td class="bold center">So</td>
            <td id="diff_sum" class="bold right">&nbsp;</td>
            <td class="bold"><?php echo __('Saldo'); ?></td>
            <td colspan="7">&nbsp;</td>
        </tr>    
    </tfoot>                        
</table>