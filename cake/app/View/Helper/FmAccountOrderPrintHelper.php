<?php
/* /app/View/Helper/FmAccountOrderPrintHelper.php (using other helpers) */
App::uses('AppHelper', 'View/Helper');

/**
 * FmAccountOrderPrint Helper
 *
 * @property FmAccountOrderPrint $FmAccountOrderPrint
 */
class FmAccountOrderPrintHelper extends AppHelper {
     //Init vars
     var $simple_account_info_w = array(30, 50, 2, 30, 84);
     var $simple_records_table_w = array(9, 28, 36, 16, 26, 26, 11, 22, 22);

     var $extended_account_info_w = array(30, 50, 2, 30, 24, 24, 24);
     var $extended_records_table_w = array(9, 28, 50, 16, 26, 26, 26, 11, 22, 22, 22, 22);

     /**
      * method for new page checking
      * @param $pdf - tcPDF object, $print_type - 'simple' or 'extended', $textfont - selected font, $fontsize - selected font size, $height - row height, $print_header - print table header flag
      * @return void
      */ 
      public function checkNewPage($pdf, $print_type, $textfont, $fontsize, $h=10, $print_header=true){
            //Set new page
            if($pdf->checkPB($h, '', 0)){
                //Add new page
                $pdf->addPage();

               //Set header
               if($print_header){
                    if($print_type == 'simple'){
                        $this->printSimpleTableHeader($pdf, $textfont, $fontsize);
                    }
                    if($print_type == 'extended'){
                        $this->printExtendedTableHeader($pdf, $textfont, $fontsize);
                    }
               }
            }  
      }//~!

     /**
      * method for string shortening
      * @param $pdf - tcPDF object, $print_type - 'simple' or 'extended', $textfont - selected font, $fontsize - selected font size, $height - row height
      * @return void
      */       
     public function shortString($pdf, $width, $string){
          //Init width and count
          $width = $width - 2; //Add some padding
          $string_width = ceil($pdf->GetStringWidth($string));
          $string_count = strlen($string);

          //Check for string width and width with reserve of 1
          if($string_width > $width){
              //Calculate how much characters to cut (including ... and one letter for reserve)
              $diff = $string_width - $width;

              $width_per_letter = ceil($string_width / $string_count);
              $remove_chars = ceil($diff / $width_per_letter) + 3;

              //Remove chars until width reached
              while ($string_width > $width) {
                //Shorten string
                $check_string = mb_substr($string, 0, $string_count - $remove_chars, "utf-8")."...";
                $string_width = ceil($pdf->GetStringWidth($check_string));
                $remove_chars++;
              }            

              //Set new string
              $string = $check_string;
          }
          return $string;
     }//~!

     /**
      * method for printing simple account order info
      * @param $pdf - tcPDF object, $account_order - FmAccountOrder data, $account_statuses - FmAccountOrder account_statuses, $textfont - selected font, $fontsize - selected font size
      * @return void
      */  
     public function printSimpleAccountOrderInfo($pdf, $account_order, $account_statuses, $textfont, $fontsize) {
           //Set widths
           $w = $this->simple_account_info_w;      

           $pdf->SetX(8);

           $pdf->MultiCell($w[0], 5, __("Vrsta NL"), 0, 'L', 1, 0, '', '', true, 0, false, true, 5, 'M');
           $pdf->MultiCell($w[1], 5, $account_order['FmOrderType']['code'], 1, 'L', 1, 0, '', '', true, 0, false, true, 5, 'M');

           $pdf->MultiCell($w[2], 5, "", 'L', 'L', 1, 0, '', '', true, 0, false, true, 5, 'M');
           
           $pdf->MultiCell($w[3], 5, __("Naziv"), 0, 'L', 1, 0, '', '', true, 0, false, true, 5, 'M');
           $pdf->SetFont($textfont,'R', $fontsize);
           $pdf->MultiCell($w[4], 5, $account_order['FmOrderType']['name'], 1, 'L', 1, 0, '', '', true, 0, false, true, 5, 'M');

           $pdf->Ln();

           $pdf->SetX(8);

           $pdf->SetFont($textfont,'B', $fontsize);
           $pdf->MultiCell($w[0], 5, __("Datum naloga"), 0, 'L', 1, 0, '', '', true, 0, false, true, 5, 'M');
           $pdf->MultiCell($w[1], 5, date('d.m.Y', strtotime($account_order['FmAccountOrder']['account_date'])), 1, 'L', 1, 0, '', '', true, 0, false, true, 5, 'M');

           $pdf->MultiCell($w[2], 5, "", 'L', 'L', 1, 0, '', '', true, 0, false, true, 5, 'M');

           $pdf->MultiCell($w[3], 5, __("Datum štampe"), 0, 'L', 1, 0, '', '', true, 0, false, true, 5, 'M');
           $pdf->SetFont($textfont,'R', $fontsize);
           $pdf->MultiCell($w[4], 5, date('d.m.Y H:i:s'), 1, 'L', 1, 0, '', '', true, 0, false, true, 5, 'M');

           $pdf->Ln();

           $pdf->SetX(8);

           $pdf->SetFont($textfont,'B', $fontsize);
           $pdf->MultiCell($w[0], 5, __("Broj NL"), 0, 'L', 1, 0, '', '', true, 0, false, true, 5, 'M');

           $pdf->SetFont($textfont,'R', $fontsize);
           $pdf->MultiCell($w[1], 5, $account_order['FmAccountOrder']['code'], 1, 'L', 1, 0, '', '', true, 0, false, true, 5, 'M');

           $pdf->MultiCell($w[2], 5, "", 'L', 'L', 1, 0, '', '', true, 0, false, true, 5, 'M');
           
           $pdf->SetFont($textfont,'B', $fontsize);
           $pdf->MultiCell($w[3], 5, __("Duguje (RSD)"), 0, 'L', 1, 0, '', '', true, 0, false, true, 5, 'M');

           $pdf->SetFont($textfont,'R', $fontsize);
           $pdf->MultiCell($w[4], 5, number_format($account_order['FmAccountOrder']['total_rsd_debit'], 2, '.', ','), 1, 'R', 1, 0, '', '', true, 0, false, true, 5, 'M');

           $pdf->Ln();

           $pdf->SetX(8);

           $pdf->SetFont($textfont,'B', $fontsize);
           $pdf->MultiCell($w[0], 5, __("Broj stavki"), 0, 'L', 1, 0, '', '', true, 0, false, true, 5, 'M');

           $pdf->SetFont($textfont,'R', $fontsize);
           $pdf->MultiCell($w[1], 5, $account_order['FmAccountOrder']['total_items'], 1, 'L', 1, 0, '', '', true, 0, false, true, 5, 'M');

           $pdf->MultiCell($w[2], 5, "", 'L', 'L', 1, 0, '', '', true, 0, false, true, 5, 'M');
           
           $pdf->SetFont($textfont,'B', $fontsize);
           $pdf->MultiCell($w[3], 5, __("Potražuje (RSD)"), 0, 'L', 1, 0, '', '', true, 0, false, true, 5, 'M');
           $pdf->SetFont($textfont,'R', $fontsize);
           $pdf->MultiCell($w[4], 5, number_format($account_order['FmAccountOrder']['total_rsd_credit'], 2, '.', ','), 1, 'R', 1, 0, '', '', true, 0, false, true, 5, 'M');

           $pdf->Ln();

           $pdf->SetX(8);

           $pdf->SetFont($textfont,'B', $fontsize);
           $pdf->MultiCell($w[0], 5, __("Status naloga"), 0, 'L', 1, 0, '', '', true, 0, false, true, 5, 'M');

           $pdf->SetFont($textfont,'R', $fontsize);
           $pdf->MultiCell($w[1], 5, $account_statuses[$account_order['FmAccountOrder']['account_status']], 1, 'L', 1, 0, '', '', true, 0, false, true, 5, 'M');

           $pdf->MultiCell($w[2], 5, "", 'L', 'L', 1, 0, '', '', true, 0, false, true, 5, 'M');
           
           $pdf->SetFont($textfont,'B', $fontsize);
           $pdf->MultiCell($w[3], 5, __("Saldo (RSD)"), 0, 'L', 1, 0, '', '', true, 0, false, true, 5, 'M');
           $pdf->SetFont($textfont,'R', $fontsize);

           $balance_rsd = '';
           if(!empty($account_order['FmAccountOrder']['balance_rsd'])){
               $balance_rsd = number_format($account_order['FmAccountOrder']['balance_rsd'], 2, '.', ',');
           }
           $pdf->MultiCell($w[4], 5, $balance_rsd, 1, 'R', 1, 0, '', '', true, 0, false, true, 5, 'M');

           $pdf->Ln();
           $pdf->Ln();      
     }//~!

	/**
	 * method for printing simple table header
	 * @param $pdf - tcPDF object, $w - array of column widths, $textfont - selected font, $fontsize - selected font size
	 * @return void
	 */	
    public function printSimpleTableHeader($pdf, $textfont, $fontsize) {
          //Init widths
          $w = $this->simple_records_table_w;

          //Print header
          $pdf->SetX(8);

          $pdf->SetFillColor(200,200,200);

          $pdf->SetFont($textfont,'B', $fontsize);      
          $pdf->MultiCell($w[0], 15, __("RB"), 1, 'C', 1, 0, '', '', true, 0, false, true, 15, 'M');

          $pdf->MultiCell($w[1], 5, __("Konto"), 1, 'C', 1, 0, '', '', true, 0, false, true, 5, 'M');
          $pdf->MultiCell($w[2], 5, __("Analitika"), 1, 'C', 1, 0, '', '', true, 0, false, true, 5, 'M');

          $pdf->MultiCell($w[3], 15, __("DPO"), 1, 'C', 1, 0, '', '', true, 0, false, true, 15, 'M');

          $pdf->MultiCell($w[4], 5, __("Dokument"), 1, 'C', 1, 0, '', '', true, 0, false, true, 5, 'M');
          $pdf->MultiCell($w[5], 5, __("Primarna veza"), 1, 'C', 1, 0, '', '', true, 0, false, true, 5, 'M');

          $pdf->MultiCell($w[6], 15, __("Šifra valute"), 1, 'C', 1, 0, '', '', true, 0, false, true, 15, 'M');

          $pdf->MultiCell($w[7], 5, __("DUGUJE"), 1, 'C', 1, 0, '', '', true, 0, false, true, 5, 'M');
          $pdf->MultiCell($w[8], 5, __("POTRAŽUJE"), 1, 'C', 1, 1, '', '', true, 0, false, true, 5, 'M');

          $pdf->SetX(8);
          $pdf->MultiCell($w[0], 5, "", 'L', 'C', 1, 0, '', '', true, 0, false, true, 5, 'M');

          $pdf->SetFillColor(255,255,255);
          $pdf->MultiCell($w[1], 5, __("Broj"), 1, 'L', 1, 0, '', '', true, 0, false, true, 5, 'M');
          $pdf->MultiCell($w[2], 5, __("Broj"), 1, 'L', 1, 0, '', '', true, 0, false, true, 5, 'M');

          $pdf->SetFillColor(200,200,200);
          $pdf->MultiCell($w[3], 5, "", 'L', 'C', 1, 0, '', '', true, 0, false, true, 5, 'M');

          $pdf->SetFillColor(255,255,255);
          $pdf->MultiCell($w[4], 5, __("Vrsta"), 1, 'L', 1, 0, '', '', true, 0, false, true, 5, 'M');
          $pdf->MultiCell($w[5], 5, __("Vrsta"), 1, 'L', 1, 0, '', '', true, 0, false, true, 5, 'M');

          $pdf->SetFillColor(200,200,200);
          $pdf->MultiCell($w[6], 5, "", 'L', 'C', 1, 0, '', '', true, 0, false, true, 5, 'M');      

          $pdf->SetFillColor(255,255,255);
          $pdf->MultiCell($w[7], 5, __("u RSD"), 1, 'C', 1, 0, '', '', true, 0, false, true, 5, 'M');
          $pdf->MultiCell($w[8], 5, __("u RSD"), 1, 'C', 1, 1, '', '', true, 0, false, true, 5, 'M');

          $pdf->SetX(8);
          $pdf->SetFillColor(200,200,200);
          $pdf->MultiCell($w[0], 5, "", 'L', 'C', 1, 0, '', '', true, 0, false, true, 5, 'M');

          $pdf->SetFillColor(255,255,255);
          $pdf->MultiCell($w[1], 5, __("Naziv"), 1, 'L', 1, 0, '', '', true, 0, false, true, 5, 'M');
          $pdf->MultiCell($w[2], 5, __("Naziv"), 1, 'L', 1, 0, '', '', true, 0, false, true, 5, 'M');

          $pdf->SetFillColor(200,200,200);
          $pdf->MultiCell($w[3], 5, "", 'L', 'C', 1, 0, '', '', true, 0, false, true, 5, 'M');

          $pdf->SetFillColor(255,255,255);
          $pdf->MultiCell($w[4], 5, __("Broj"), 1, 'L', 1, 0, '', '', true, 0, false, true, 5, 'M');
          $pdf->MultiCell($w[5], 5, __("Broj"), 1, 'L', 1, 0, '', '', true, 0, false, true, 5, 'M');

          $pdf->SetFillColor(200,200,200);
          $pdf->MultiCell($w[6], 5, "", 'L', 'C', 1, 0, '', '', true, 0, false, true, 5, 'M');      

          $pdf->SetFillColor(255,255,255);
          $pdf->MultiCell($w[7], 5, __("u deviz.valuti"), 1, 'C', 1, 0, '', '', true, 0, false, true, 5, 'M');
          $pdf->MultiCell($w[8], 5, __("u deviz.valuti"), 1, 'C', 1, 0, '', '', true, 0, false, true, 5, 'M');      

          $pdf->SetFont($textfont,'R', $fontsize);

          $pdf->Ln();
    }//~!

     /**
      * method for printing simple table header
      * @param $pdf - tcPDF object, $records - FmAccountOrderRecords data, $textfont - selected font, $fontsize - selected font size
      * @return void
      */  
    public function printSimpleRecordsTable($pdf, $records, $textfont, $fontsize) {
          //Init widths
          $w = $this->simple_records_table_w;

          //Set regular font
          $pdf->SetFont($textfont, 'R', $fontsize);
          $pdf->SetFillColor(255,255,255);

          //Print table content
          $record_count = count($records);      
          $record_index = 0;

          foreach ($records as $record) {
               //Reset x coordinate
               $pdf->SetX(8);

               //Write first part
               $pdf->MultiCell($w[0], 10, $record['FmAccountOrderRecord']['ordinal'], 1, 'C', 1, 0, '', '', true, 0, false, true, 10, 'M');
               $pdf->MultiCell($w[1], 5, $record['FmChartAccount']['analiticki_konto'], 1, 'L', 1, 0, '', '', true, 0, false, true, 5, 'M');

               //Set data code
               $data_code = (!empty($record['CodebookConnectionData']['data_code'])) ? $this->shortString($pdf, $w[2], $record['CodebookConnectionData']['data_code']) : "";
               $pdf->MultiCell($w[2], 5, $data_code, 1, 'L', 1, 0, '', '', true, 0, false, true, 5, 'M');

               //Set dpo
               $dpo = (!empty($record['FmAccountOrderRecord']['dpo'])) ? date('d.m.Y', strtotime($record['FmAccountOrderRecord']['dpo'])) : "";
               $pdf->MultiCell($w[3], 10, $dpo, 1, 'C', 1, 0, '', '', true, 0, false, true, 10, 'M');

               //Set codebook type code
               $codebook_type_code = (!empty($record['CodebookDocumentType']['code'])) ? $record['CodebookDocumentType']['code'] : "";
               $pdf->MultiCell($w[4], 5, $codebook_type_code, 1, 'L', 1, 0, '', '', true, 0, false, true, 5, 'M');

               //Set primary type code
               $primary_type_code = (!empty($record['PrimaryDocumentType']['code'])) ? $record['PrimaryDocumentType']['code'] : "";
               $pdf->MultiCell($w[5], 5, $primary_type_code, 1, 'L', 1, 0, '', '', true, 0, false, true, 5, 'M');

               //Set currency
               $currency = (!empty($record['Currency']['iso'])) ? $record['Currency']['iso'] : "";
               $pdf->MultiCell($w[6], 10, $currency, 1, 'C', 1, 0, '', '', true, 0, false, true, 10, 'M');          

               //Set domestic debit
               $domestic_debit = (!empty($record['FmAccountOrderRecord']['domestic_debit'])) ? number_format($record['FmAccountOrderRecord']['domestic_debit'], 3, '.', ',') : "";
               $pdf->MultiCell($w[7], 5, $domestic_debit, 1, 'R', 1, 0, '', '', true, 0, false, true, 5, 'M');

               //Set domestic credit
               $domestic_credit = (!empty($record['FmAccountOrderRecord']['domestic_credit'])) ? number_format($record['FmAccountOrderRecord']['domestic_credit'], 3, '.', ',') : "";
               $pdf->MultiCell($w[8], 5, $domestic_credit, 1, 'R', 1, 1, '', '', true, 0, false, true, 5, 'M');

               $pdf->SetX(8);

               //Write second part
               $pdf->MultiCell($w[0], 5, "", 'LRB', 'C', 1, 0, '', '', true, 0, false, true, 5, 'M');
               $pdf->MultiCell($w[1], 5, $this->shortString($pdf, $w[1], $record['FmChartAccount']['naziv']), 1, 'L', 1, 0, '', '', true, 0, false, true, 5, 'M');

               //Set data title
               $data_title = (!empty($record['CodebookConnectionData']['data_title'])) ? $this->shortString($pdf, $w[2], $record['CodebookConnectionData']['data_title']) : "";
               $pdf->MultiCell($w[2], 5, $data_title, 1, 'L', 1, 0, '', '', true, 0, false, true, 5, 'M');

               $pdf->MultiCell($w[3], 5, "", 'LRB', 'C', 1, 0, '', '', true, 0, false, true, 5, 'M');

               //Set codebook document code
               $codebook_document_code = (!empty($record['FmAccountOrderRecord']['codebook_document_code'])) ? $this->shortString($pdf, $w[4], $record['FmAccountOrderRecord']['codebook_document_code']) : "";
               $pdf->MultiCell($w[4], 5, $codebook_document_code, 1, 'L', 1, 0, '', '', true, 0, false, true, 5, 'M');

               //Set primary document code
               $primary_document_code = (!empty($record['FmAccountOrderRecord']['primary_document_code'])) ? $this->shortString($pdf, $w[5], $record['FmAccountOrderRecord']['primary_document_code']) : "";
               $pdf->MultiCell($w[5], 5, $primary_document_code, 1, 'L', 1, 0, '', '', true, 0, false, true, 5, 'M');

               $pdf->MultiCell($w[6], 5, "", 'LRB', 'C', 1, 0, '', '', true, 0, false, true, 5, 'M');

               //Set foreign debit
               $foreign_debit = (!empty($record['FmAccountOrderRecord']['foreign_debit'])) ? number_format($record['FmAccountOrderRecord']['foreign_debit'], 2, '.', ',') : "";
               $pdf->MultiCell($w[7], 5, $foreign_debit, 1, 'R', 1, 0, '', '', true, 0, false, true, 5, 'M');

               //Set foreign credit
               $foreign_credit = (!empty($record['FmAccountOrderRecord']['foreign_credit'])) ? number_format($record['FmAccountOrderRecord']['foreign_credit'], 2, '.', ',') : "";
               $pdf->MultiCell($w[8], 5, $foreign_credit, 1, 'R', 1, 0, '', '', true, 0, false, true, 5, 'M');

               //Set new line            
               $pdf->Ln(7);

               //Increment record index
               $record_index++;

               //Check if last record
               if($record_count > $record_index){
                    //Check new page
                    $this->checkNewPage($pdf, 'simple', $textfont, $fontsize, 10);                    
               }
          }          
     }//~!

     /**
      * method for printing extended account order info
      * @param $pdf - tcPDF object, $account_order - FmAccountOrder data, $account_statuses - FmAccountOrder account_statuses, $textfont - selected font, $fontsize - selected font size
      * @return void
     */  
     public function printExtendedAccountOrderInfo($pdf, $account_order, $account_statuses, $textfont, $fontsize) {
          //Set widths
          $w = $this->extended_account_info_w;

          $pdf->SetX(8);

          //Print account order info
          $pdf->MultiCell($w[0], 5, __("Vrsta NL"), 0, 'L', 1, 0, '', '', true, 0, false, true, 5, 'M');
          $pdf->MultiCell($w[1], 5, $account_order['FmOrderType']['code'], 1, 'L', 1, 0, '', '', true, 0, false, true, 5, 'M');

          $pdf->MultiCell($w[2], 5, "", 'L', 'L', 1, 0, '', '', true, 0, false, true, 5, 'M');
           
          $pdf->MultiCell($w[3], 5, __("Naziv"), 0, 'L', 1, 0, '', '', true, 0, false, true, 5, 'M');
          $pdf->SetFont($textfont,'R', $fontsize);
          $pdf->MultiCell($w[4]+$w[5]+$w[6], 5, $account_order['FmOrderType']['name'], 1, 'L', 1, 0, '', '', true, 0, false, true, 5, 'M');

          $pdf->Ln();

          $pdf->SetX(8);

          $pdf->SetFont($textfont,'B', $fontsize);
          $pdf->MultiCell($w[0], 5, __("Datum naloga"), 0, 'L', 1, 0, '', '', true, 0, false, true, 5, 'M');
          $pdf->MultiCell($w[1], 5, date('d.m.Y', strtotime($account_order['FmAccountOrder']['account_date'])), 1, 'L', 1, 0, '', '', true, 0, false, true, 5, 'M');

          $pdf->MultiCell($w[2], 5, "", 'L', 'L', 1, 0, '', '', true, 0, false, true, 5, 'M');

          $pdf->MultiCell($w[3], 5, __("Datum štampe"), 0, 'L', 1, 0, '', '', true, 0, false, true, 5, 'M');
          $pdf->SetFont($textfont,'R', $fontsize);
          $pdf->MultiCell($w[4]+$w[5]+$w[6], 5, date('d.m.Y H:i:s'), 1, 'L', 1, 0, '', '', true, 0, false, true, 5, 'M');

          $pdf->Ln();

          $pdf->SetX(8);

          $pdf->SetFont($textfont,'B', $fontsize);
          $pdf->MultiCell($w[0], 5, __("Broj NL"), 0, 'L', 1, 0, '', '', true, 0, false, true, 5, 'M');

          $pdf->SetFont($textfont,'R', $fontsize);
          $pdf->MultiCell($w[1], 5, $account_order['FmAccountOrder']['code'], 1, 'L', 1, 0, '', '', true, 0, false, true, 5, 'M');

          $pdf->MultiCell($w[2], 5, "", 'L', 'L', 1, 0, '', '', true, 0, false, true, 5, 'M');
           
          $pdf->SetFont($textfont,'B', $fontsize);
          $pdf->MultiCell($w[3], 5, __("Duguje (RSD)"), 0, 'L', 1, 0, '', '', true, 0, false, true, 5, 'M');

          $pdf->SetFont($textfont,'R', $fontsize);
          $pdf->MultiCell($w[4], 5, number_format($account_order['FmAccountOrder']['total_rsd_debit'], 2, '.', ','), 1, 'R', 1, 0, '', '', true, 0, false, true, 5, 'M');

          $pdf->SetFont($textfont,'B', $fontsize);
          $pdf->MultiCell($w[5], 5, __("Duguje (USD)"), 'LT', 'L', 1, 0, '', '', true, 0, false, true, 5, 'M');

          $pdf->SetFont($textfont,'R', $fontsize);
          $pdf->MultiCell($w[6], 5, number_format($account_order['FmAccountOrder']['total_usd_debit'], 2, '.', ','), 1, 'R', 1, 0, '', '', true, 0, false, true, 5, 'M');      

          $pdf->Ln();

          $pdf->SetX(8);

          $pdf->SetFont($textfont,'B', $fontsize);
          $pdf->MultiCell($w[0], 5, __("Broj stavki"), 0, 'L', 1, 0, '', '', true, 0, false, true, 5, 'M');

          $pdf->SetFont($textfont,'R', $fontsize);
          $pdf->MultiCell($w[1], 5, $account_order['FmAccountOrder']['total_items'], 1, 'L', 1, 0, '', '', true, 0, false, true, 5, 'M');

          $pdf->MultiCell($w[2], 5, "", 'L', 'L', 1, 0, '', '', true, 0, false, true, 5, 'M');
           
          $pdf->SetFont($textfont,'B', $fontsize);
          $pdf->MultiCell($w[3], 5, __("Potražuje (RSD)"), 0, 'L', 1, 0, '', '', true, 0, false, true, 5, 'M');
          $pdf->SetFont($textfont,'R', $fontsize);
          $pdf->MultiCell($w[4], 5, number_format($account_order['FmAccountOrder']['total_rsd_credit'], 2, '.', ','), 1, 'R', 1, 0, '', '', true, 0, false, true, 5, 'M');

          $pdf->SetFont($textfont,'B', $fontsize);
          $pdf->MultiCell($w[5], 5, __("Potražuje (USD)"), 'L', 'L', 1, 0, '', '', true, 0, false, true, 5, 'M');
          $pdf->SetFont($textfont,'R', $fontsize);
          $pdf->MultiCell($w[6], 5, number_format($account_order['FmAccountOrder']['total_usd_credit'], 2, '.', ','), 1, 'R', 1, 0, '', '', true, 0, false, true, 5, 'M');

          $pdf->Ln();

          $pdf->SetX(8);

          $pdf->SetFont($textfont,'B', $fontsize);
          $pdf->MultiCell($w[0], 5, __("Status naloga"), 0, 'L', 1, 0, '', '', true, 0, false, true, 5, 'M');

          $pdf->SetFont($textfont,'R', $fontsize);
          $pdf->MultiCell($w[1], 5, $account_statuses[$account_order['FmAccountOrder']['account_status']], 1, 'L', 1, 0, '', '', true, 0, false, true, 5, 'M');

          $pdf->MultiCell($w[2], 5, "", 'L', 'L', 1, 0, '', '', true, 0, false, true, 5, 'M');
           
          $pdf->SetFont($textfont,'B', $fontsize);
          $pdf->MultiCell($w[3], 5, __("Saldo (RSD)"), 0, 'L', 1, 0, '', '', true, 0, false, true, 5, 'M');
          $pdf->SetFont($textfont,'R', $fontsize);

          $balance_rsd = (!empty($account_order['FmAccountOrder']['balance_rsd'])) ? number_format($account_order['FmAccountOrder']['balance_rsd'], 2, '.', ',') : "";
          $pdf->MultiCell($w[4], 5, $balance_rsd, 1, 'R', 1, 0, '', '', true, 0, false, true, 5, 'M');

          $pdf->SetFont($textfont,'B', $fontsize);
          $pdf->MultiCell($w[5], 5, __("Saldo (USD)"), 'L', 'L', 1, 0, '', '', true, 0, false, true, 5, 'M');
          $pdf->SetFont($textfont,'R', $fontsize);

          $balance_usd = (!empty($account_order['FmAccountOrder']['balance_usd'])) ? number_format($account_order['FmAccountOrder']['balance_usd'], 2, '.', ',') : "";
          $pdf->MultiCell($w[6], 5, $balance_usd, 1, 'R', 1, 0, '', '', true, 0, false, true, 5, 'M');

          $pdf->Ln();
          $pdf->Ln();
     }//~!

     /**
      * method for printing extended table header
      * @param $pdf - tcPDF object, $w - array of column widths, $textfont - selected font, $fontsize - selected font size
      * @return void
     */  
     public function printExtendedTableHeader($pdf, $textfont, $fontsize) {
          //Set width
          $w = $this->extended_records_table_w;

          //Print table header
          $pdf->SetX(8);

          $pdf->SetFillColor(200,200,200);

          $pdf->SetFont($textfont,'B', $fontsize);      
          $pdf->MultiCell($w[0], 15, __("RB"), 1, 'C', 1, 0, '', '', true, 0, false, true, 15, 'M');

          $pdf->MultiCell($w[1], 5, __("Konto"), 1, 'C', 1, 0, '', '', true, 0, false, true, 5, 'M');
          $pdf->MultiCell($w[2], 5, __("Analitika"), 1, 'C', 1, 0, '', '', true, 0, false, true, 5, 'M');

          $pdf->MultiCell($w[3], 15, __("DPO"), 1, 'C', 1, 0, '', '', true, 0, false, true, 15, 'M');

          $pdf->MultiCell($w[4], 5, __("Dokument"), 1, 'C', 1, 0, '', '', true, 0, false, true, 5, 'M');
          $pdf->MultiCell($w[5], 5, __("Primarna veza"), 1, 'C', 1, 0, '', '', true, 0, false, true, 5, 'M');
          $pdf->MultiCell($w[6], 5, __("Sekundarna veza"), 1, 'C', 1, 0, '', '', true, 0, false, true, 5, 'M');

          $pdf->MultiCell($w[7], 15, __("Šifra valute"), 1, 'C', 1, 0, '', '', true, 0, false, true, 15, 'M');

          $pdf->MultiCell($w[8], 5, __("DUGUJE"), 1, 'C', 1, 0, '', '', true, 0, false, true, 5, 'M');
          $pdf->MultiCell($w[9], 5, __("POTRAŽUJE"), 1, 'C', 1, 0, '', '', true, 0, false, true, 5, 'M');
          $pdf->MultiCell($w[10]+$w[11], 5, __("Preračun u izvestajnu valutu"), 1, 'C', 1, 1, '', '', true, 0, false, true, 5, 'M');

          $pdf->SetX(8);
          $pdf->MultiCell($w[0], 5, "", 'L', 'C', 1, 0, '', '', true, 0, false, true, 5, 'M');

          $pdf->SetFillColor(255,255,255);
          $pdf->MultiCell($w[1], 5, __("Broj"), 1, 'L', 1, 0, '', '', true, 0, false, true, 5, 'M');
          $pdf->MultiCell($w[2], 5, __("Broj"), 1, 'L', 1, 0, '', '', true, 0, false, true, 5, 'M');

          $pdf->SetFillColor(200,200,200);
          $pdf->MultiCell($w[3], 5, "", 'L', 'C', 1, 0, '', '', true, 0, false, true, 5, 'M');

          $pdf->SetFillColor(255,255,255);
          $pdf->MultiCell($w[4], 5, __("Vrsta"), 1, 'L', 1, 0, '', '', true, 0, false, true, 5, 'M');
          $pdf->MultiCell($w[5], 5, __("Vrsta"), 1, 'L', 1, 0, '', '', true, 0, false, true, 5, 'M');
          $pdf->MultiCell($w[6], 5, __("Vrsta"), 1, 'L', 1, 0, '', '', true, 0, false, true, 5, 'M');

          $pdf->SetFillColor(200,200,200);
          $pdf->MultiCell($w[7], 5, "", 'L', 'C', 1, 0, '', '', true, 0, false, true, 5, 'M');      

          $pdf->SetFillColor(255,255,255);
          $pdf->MultiCell($w[8], 5, __("u RSD"), 1, 'C', 1, 0, '', '', true, 0, false, true, 5, 'M');
          $pdf->MultiCell($w[9], 5, __("u RSD"), 1, 'C', 1, 0, '', '', true, 0, false, true, 5, 'M');

          $pdf->SetFillColor(200,200,200);
          $pdf->MultiCell($w[10], 5, __("DUGUJE"), 1, 'C', 1, 0, '', '', true, 0, false, true, 5, 'M');
          $pdf->MultiCell($w[11], 5, __("POTRAŽUJE"), 1, 'C', 1, 1, '', '', true, 0, false, true, 5, 'M');          

          $pdf->SetX(8);
          $pdf->SetFillColor(200,200,200);
          $pdf->MultiCell($w[0], 5, "", 'L', 'C', 1, 0, '', '', true, 0, false, true, 5, 'M');

          $pdf->SetFillColor(255,255,255);
          $pdf->MultiCell($w[1], 5, __("Naziv"), 1, 'L', 1, 0, '', '', true, 0, false, true, 5, 'M');
          $pdf->MultiCell($w[2], 5, __("Naziv"), 1, 'L', 1, 0, '', '', true, 0, false, true, 5, 'M');

          $pdf->SetFillColor(200,200,200);
          $pdf->MultiCell($w[3], 5, "", 'L', 'C', 1, 0, '', '', true, 0, false, true, 5, 'M');

          $pdf->SetFillColor(255,255,255);
          $pdf->MultiCell($w[4], 5, __("Broj"), 1, 'L', 1, 0, '', '', true, 0, false, true, 5, 'M');
          $pdf->MultiCell($w[5], 5, __("Broj"), 1, 'L', 1, 0, '', '', true, 0, false, true, 5, 'M');
          $pdf->MultiCell($w[6], 5, __("Broj"), 1, 'L', 1, 0, '', '', true, 0, false, true, 5, 'M');

          $pdf->SetFillColor(200,200,200);
          $pdf->MultiCell($w[7], 5, "", 'L', 'C', 1, 0, '', '', true, 0, false, true, 5, 'M');      

          $pdf->SetFillColor(255,255,255);
          $pdf->MultiCell($w[8], 5, __("u deviz.valuti"), 1, 'C', 1, 0, '', '', true, 0, false, true, 5, 'M');
          $pdf->MultiCell($w[9], 5, __("u deviz.valuti"), 1, 'C', 1, 0, '', '', true, 0, false, true, 5, 'M');

          $pdf->MultiCell($w[10], 5, __("u USD"), 1, 'C', 1, 0, '', '', true, 0, false, true, 5, 'M');
          $pdf->MultiCell($w[11], 5, __("u USD"), 1, 'C', 1, 0, '', '', true, 0, false, true, 5, 'M');

          $pdf->SetFont($textfont,'R', $fontsize);

          $pdf->Ln();
     }//~!

     /**
      * method for printing extended table header
      * @param $pdf - tcPDF object, $records - FmAccountOrderRecords data, $textfont - selected font, $fontsize - selected font size
      * @return void
     */  
     public function printExtendedRecordsTable($pdf, $records, $textfont, $fontsize) {
          //Set table widths
          $w = $this->extended_records_table_w;

          //Set regular font
          $pdf->SetFont($textfont, 'R', $fontsize);
          $pdf->SetFillColor(255,255,255);

          //Print table content
          $record_count = count($records);      
          $record_index = 0;

          foreach ($records as $record) {  
               //Reset x coordinate
               $pdf->SetX(8);

               //Write first part
               $pdf->MultiCell($w[0], 10, $record['FmAccountOrderRecord']['ordinal'], 1, 'C', 1, 0, '', '', true, 0, false, true, 10, 'M');
               $pdf->MultiCell($w[1], 5, $record['FmChartAccount']['analiticki_konto'], 1, 'L', 1, 0, '', '', true, 0, false, true, 5, 'M');

               //Set data code
               $data_code = (!empty($record['CodebookConnectionData']['data_code'])) ? $this->shortString($pdf, $w[2], $record['CodebookConnectionData']['data_code']) : "";
               $pdf->MultiCell($w[2], 5, $data_code, 1, 'L', 1, 0, '', '', true, 0, false, true, 5, 'M');

               //Set dpo
               $dpo = (!empty($record['FmAccountOrderRecord']['dpo'])) ? date('d.m.Y', strtotime($record['FmAccountOrderRecord']['dpo'])) : "";
               $pdf->MultiCell($w[3], 10, $dpo, 1, 'C', 1, 0, '', '', true, 0, false, true, 10, 'M');

               //Set codebook type code
               $codebook_type_code = (!empty($record['CodebookDocumentType']['code'])) ? $record['CodebookDocumentType']['code'] : "";
               $pdf->MultiCell($w[4], 5, $codebook_type_code, 1, 'L', 1, 0, '', '', true, 0, false, true, 5, 'M');

               //Set primary type code
               $primary_type_code = (!empty($record['PrimaryDocumentType']['code'])) ? $record['PrimaryDocumentType']['code'] : "";
               $pdf->MultiCell($w[5], 5, $primary_type_code, 1, 'L', 1, 0, '', '', true, 0, false, true, 5, 'M');

               //Set secondary type code
               $secondary_type_code = (!empty($record['SecondaryDocumentType']['code'])) ? $record['SecondaryDocumentType']['code'] : "";
               $pdf->MultiCell($w[6], 5, $secondary_type_code, 1, 'L', 1, 0, '', '', true, 0, false, true, 5, 'M');

               //Set currency
               $currency = (!empty($record['Currency']['iso'])) ? $record['Currency']['iso'] : "";
               $pdf->MultiCell($w[7], 10, $currency, 1, 'C', 1, 0, '', '', true, 0, false, true, 10, 'M');

               //Set domestic debit
               $domestic_debit = (!empty($record['FmAccountOrderRecord']['domestic_debit'])) ? number_format($record['FmAccountOrderRecord']['domestic_debit'], 3, '.', ',') : "";
               $pdf->MultiCell($w[8], 5, $domestic_debit, 1, 'R', 1, 0, '', '', true, 0, false, true, 5, 'M');

               //Set domestic credit
               $domestic_credit = (!empty($record['FmAccountOrderRecord']['domestic_credit'])) ? number_format($record['FmAccountOrderRecord']['domestic_credit'], 3, '.', ',') : "";
               $pdf->MultiCell($w[9], 5, $domestic_credit, 1, 'R', 1, 0, '', '', true, 0, false, true, 5, 'M');

               //Set usd debit
               $usd_debit = (!empty($record['FmAccountOrderRecord']['usd_debit'])) ? number_format($record['FmAccountOrderRecord']['usd_debit'], 3, '.', ',') : "";
               $pdf->MultiCell($w[10], 5, $usd_debit, 1, 'R', 1, 0, '', '', true, 0, false, true, 5, 'M');

               //Set usd credit
               $usd_credit = (!empty($record['FmAccountOrderRecord']['usd_credit'])) ? number_format($record['FmAccountOrderRecord']['usd_credit'], 3, '.', ',') : "";
               $pdf->MultiCell($w[11], 5, $usd_credit, 1, 'R', 1, 1, '', '', true, 1, false, true, 5, 'M');

               $pdf->SetX(8);

               //Write second part
               $pdf->MultiCell($w[0], 5, "", 'LRB', 'C', 1, 0, '', '', true, 0, false, true, 5, 'M');
               $pdf->MultiCell($w[1], 5, $this->shortString($pdf, $w[1], $record['FmChartAccount']['naziv']), 1, 'L', 1, 0, '', '', true, 0, false, true, 5, 'M');

               //Set data title
               $data_title = (!empty($record['CodebookConnectionData']['data_title'])) ? $this->shortString($pdf, $w[2], $record['CodebookConnectionData']['data_title']) : "";
               $pdf->MultiCell($w[2], 5, $data_title, 1, 'L', 1, 0, '', '', true, 0, false, true, 5, 'M');

               $pdf->MultiCell($w[3], 5, "", 'LRB', 'C', 1, 0, '', '', true, 0, false, true, 5, 'M');

               //Set codebook document code
               $codebook_document_code = (!empty($record['FmAccountOrderRecord']['codebook_document_code'])) ? $this->shortString($pdf, $w[4], $record['FmAccountOrderRecord']['codebook_document_code']) : "";
               $pdf->MultiCell($w[4], 5, $codebook_document_code, 1, 'L', 1, 0, '', '', true, 0, false, true, 5, 'M');

               //Set primary document code
               $primary_document_code = (!empty($record['FmAccountOrderRecord']['primary_document_code'])) ? $this->shortString($pdf, $w[5], $record['FmAccountOrderRecord']['primary_document_code']) : "";
               $pdf->MultiCell($w[5], 5, $primary_document_code, 1, 'L', 1, 0, '', '', true, 0, false, true, 5, 'M');

               //Set secondary document code
               $secondary_document_code = (!empty($record['FmAccountOrderRecord']['secondary_document_code'])) ? $this->shortString($pdf, $w[6], $record['FmAccountOrderRecord']['secondary_document_code']) : "";
               $pdf->MultiCell($w[6], 5, $secondary_document_code, 1, 'L', 1, 0, '', '', true, 0, false, true, 5, 'M');

               $pdf->MultiCell($w[7], 5, "", 'LRB', 'C', 1, 0, '', '', true, 0, false, true, 5, 'M');

               //Set foreign debit
               $foreign_debit = (!empty($record['FmAccountOrderRecord']['foreign_debit'])) ? number_format($record['FmAccountOrderRecord']['foreign_debit'], 2, '.', ',') : "";
               $pdf->MultiCell($w[8], 5, $foreign_debit, 1, 'R', 1, 0, '', '', true, 0, false, true, 5, 'M');

               //Set foreign credit
               $foreign_credit = (!empty($record['FmAccountOrderRecord']['foreign_credit'])) ? number_format($record['FmAccountOrderRecord']['foreign_credit'], 2, '.', ',') : "";
               $pdf->MultiCell($w[9], 5, $foreign_credit, 1, 'R', 1, 0, '', '', true, 0, false, true, 5, 'M');

               //Set blank fields for usd
               $pdf->MultiCell($w[10], 5, "", 1, 'R', 1, 0, '', '', true, 0, false, true, 5, 'M');
               $pdf->MultiCell($w[11], 5, "", 1, 'R', 1, 0, '', '', true, 0, false, true, 5, 'M');

               //Set new line            
               $pdf->Ln(7);

               //Increment record index
               $record_index++;

               //Check if last record
               if($record_count > $record_index){
                    //Check new page
                    $this->checkNewPage($pdf, 'extended', $textfont, $fontsize, 10);
               }
           }
     }//~!         
}