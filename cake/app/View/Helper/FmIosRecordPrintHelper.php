<?php
/* /app/View/Helper/FmIosRecordPrintHelper.php (using other helpers) */
App::uses('AppHelper', 'View/Helper');

/**
 * FmIosRecordPrintHelper Helper
 *
 * @property FmIosRecordPrintHelper $FmIosRecordPrintHelper
 */
class FmIosRecordPrintHelper extends AppHelper {
     //Init vars
     var $simple_record_info_w = array(20, 84, 2, 22, 50);
     var $simple_records_table_w = array(20, 20, 20, 20, 20, 26, 22, 22, 22);
     var $footer_w = array(23, 23, 23, 23, 23, 23, 23, 23);

     /*          

     var $extended_account_info_w = array(30, 50, 2, 30, 24, 24, 24);
     var $extended_records_table_w = array(9, 28, 50, 16, 26, 26, 26, 11, 22, 22, 22, 22);
     */

      /**
        * Returns string height of a row
        * @param object $pdf, $params - array('column' => array('text', 'width'))
        * @return height of a string in integer
      */
      private function checkRowHeight($pdf, $params){
        //Set string height
        $stringHeight = 5;

        //Check for string height
        foreach ($params as $column => $data) {
          if(!empty($column) && !empty($data['text']) && !empty($data['width'])){
            $text_height = ceil($pdf->getStringHeight($data['width'], $data['text']) * $pdf->getCellHeightRatio());
            if($text_height > $stringHeight){
              $stringHeight = $text_height;
            }
          }
        }

        //Return height
        return $stringHeight;
      }//~!

     /**
      * method for printing simple account order info
      * @param $pdf - tcPDF object, $client - Client data, $record - FmIosRecord data, $textfont - selected font, $fontsize - selected font size
      * @return void
      */
     public function printDomesticRecordInfo($pdf, $client, $record, $textfont, $fontsize){
           //Set widths
           $w = $this->simple_record_info_w;      

           $pdf->SetFont($textfont,'B', 15);
           $pdf->MultiCell($w[0]+$w[1]+$w[2]+$w[3]+$w[4]+5, 12, __("IZVOD OTVORENIH STAVKI"), 'B', 'C', 1, 0, '', '', true, 0, false, true, 12, 'M');           

           $pdf->Ln();
           $pdf->SetY($pdf->GetY()+5);

           $pdf->SetFont($textfont,'B', $fontsize);
           $pdf->MultiCell($w[0], 5, __("Datum"), 0, 'L', 1, 0, '', '', true, 0, false, true, 5, 'M');
           $pdf->SetFont($textfont,'R', $fontsize);
           $pdf->MultiCell($w[1], 5, date('d.m.Y', strtotime($record['FmIosRecord']['ios_created'])), 'B', 'L', 1, 0, '', '', true, 0, false, true, 5, 'M');

           $pdf->MultiCell($w[2], 5, "", 0, 'L', 1, 0, '', '', true, 0, false, true, 5, 'M');
           
           $pdf->SetFont($textfont,'B', $fontsize);
           $pdf->MultiCell($w[3], 5, __("Broj"), 0, 'L', 1, 0, '', '', true, 0, false, true, 5, 'M');
           $pdf->SetFont($textfont,'R', $fontsize);
           $pdf->MultiCell($w[4], 5, "IOS-Dom ".$record['FmIosRecord']['code'], 'B', 'L', 1, 0, '', '', true, 0, false, true, 5, 'M');

           $pdf->Ln(6);

           $pdf->SetFont($textfont,'B', $fontsize);
           $pdf->MultiCell($w[0], 5, __("Komitent"), 0, 'L', 1, 0, '', '', true, 0, false, true, 5, 'M');           
           $pdf->SetFont($textfont,'R', $fontsize);
           $pdf->MultiCell($w[1], 5, trim($client['Client']['title']), 'B', 'L', 1, 0, '', '', true, 0, false, true, 5, 'M');

           $pdf->MultiCell($w[2], 5, "", 0, 'L', 1, 0, '', '', true, 0, false, true, 5, 'M');
           
           $pdf->SetFont($textfont,'B', $fontsize);
           $pdf->MultiCell($w[3], 5, __("PIB"), 0, 'L', 1, 0, '', '', true, 0, false, true, 5, 'M');
           $pdf->SetFont($textfont,'R', $fontsize);
           $pdf->MultiCell($w[4], 5, trim($client['DomesticClientInfo']['pib']), 'B', 'L', 1, 0, '', '', true, 0, false, true, 5, 'M');

           $pdf->Ln(6);

           $pdf->SetFont($textfont,'B', $fontsize);
           $pdf->MultiCell($w[0], 5, __("Šifra"), 0, 'L', 1, 0, '', '', true, 0, false, true, 5, 'M');           
           $pdf->SetFont($textfont,'R', $fontsize);
           $pdf->MultiCell($w[1], 5, trim($client['Client']['code']), 'B', 'L', 1, 0, '', '', true, 0, false, true, 5, 'M');

           $pdf->MultiCell($w[2], 5, "", 0, 'L', 1, 0, '', '', true, 0, false, true, 5, 'M');
           
           $pdf->SetFont($textfont,'B', $fontsize);
           $pdf->MultiCell($w[3], 5, __("Telefon"), 0, 'L', 1, 0, '', '', true, 0, false, true, 5, 'M');
           $pdf->SetFont($textfont,'R', $fontsize);
           $pdf->MultiCell($w[4], 5, trim($client['DomesticClientAddressInfo']['telefon']), 'B', 'L', 1, 0, '', '', true, 0, false, true, 5, 'M');

           $pdf->Ln(6);

           $pdf->SetFont($textfont,'B', $fontsize);
           $pdf->MultiCell($w[0], 5, __("Mesto"), 0, 'L', 1, 0, '', '', true, 0, false, true, 5, 'M');           
           $pdf->SetFont($textfont,'R', $fontsize);
           $pdf->MultiCell($w[1], 5, trim($client['DomesticClientAddressInfo']['mesto']), 'B', 'L', 1, 0, '', '', true, 0, false, true, 5, 'M');

           $pdf->MultiCell($w[2], 5, "", 0, 'L', 1, 0, '', '', true, 0, false, true, 5, 'M');
           
           $pdf->SetFont($textfont,'B', $fontsize);
           $pdf->MultiCell($w[3], 5, __("Email"), 0, 'L', 1, 0, '', '', true, 0, false, true, 5, 'M');
           $pdf->SetFont($textfont,'R', $fontsize);
           $pdf->MultiCell($w[4], 5, trim($client['DomesticClientInfo']['email_address']), 'B', 'L', 1, 0, '', '', true, 0, false, true, 5, 'M');

           $pdf->Ln(6);

           $pdf->SetFont($textfont,'B', $fontsize);
           $pdf->MultiCell($w[0], 5, __("Ulica i broj"), 0, 'L', 1, 0, '', '', true, 0, false, true, 5, 'M');           
           $pdf->SetFont($textfont,'R', $fontsize);
           
           $address = trim($client['DomesticClientAddressInfo']['adresa']." ".$client['DomesticClientAddressInfo']['adresa_nastavak']);           
           $pdf->MultiCell($w[1], 5, $address, 'B', 'L', 1, 0, '', '', true, 0, false, true, 5, 'M');

           $pdf->MultiCell($w[2], 5, "", 0, 'L', 1, 0, '', '', true, 0, false, true, 5, 'M');
           
           $pdf->SetFont($textfont,'B', $fontsize);
           $pdf->MultiCell($w[3], 5, __("Tekući račun"), 0, 'L', 1, 0, '', '', true, 0, false, true, 5, 'M');
           $pdf->SetFont($textfont,'R', $fontsize);
           $pdf->MultiCell($w[4], 5, trim($client['DomesticCurrentAccount']['tekuci_racun']), 'B', 'L', 1, 0, '', '', true, 0, false, true, 5, 'M');

           $pdf->Ln();         
     }//~!

     /**
      * method for printing simple account order footer
      * @param $pdf - tcPDF object, $email_address - form email addres, $phone_number - form phone number, $textfont - selected font, $fontsize - selected font size
      * @return void
      */
     public function printDomesticRecordFooter($pdf, $email_address, $phone_number, $textfont, $fontsize){
          //Set widths
          $w = $this->footer_w;      

          $pdf->SetY($pdf->GetY()+5);

          $pdf->SetFont($textfont,'R', $fontsize);
          $pdf->MultiCell($w[0]+$w[1], 5, __("Posiljalac izvoda,"), 0, 'L', 1, 0, '', '', true, 0, false, true, 5, 'M');           

          $pdf->MultiCell($w[2]+$w[3]+$w[4], 5, "", 0, 'L', 1, 0, '', '', true, 0, false, true, 5, 'M');

          $pdf->MultiCell($w[5]+$w[6]+$w[7], 5, __("Potvrdjujemo saglasnost otvorenih stavki,"), 0, 'L', 1, 0, '', '', true, 0, false, true, 5, 'M');

           //Check next page
           if($pdf->checkPB(5, '', 0)){
              //Add new page
              $pdf->addPage();
           }
           $pdf->Ln(5);

           $pdf->MultiCell($w[0]+$w[1], 12, "", 'B', 'L', 1, 0, '', '', true, 0, false, true, 12, 'M');           
           $pdf->MultiCell($w[2]+$w[3]+$w[4], 12, "", 0, 'L', 1, 0, '', '', true, 0, false, true, 12, 'M');
           $pdf->MultiCell($w[5]+$w[6]+$w[7], 12, "", 'B', 'L', 1, 0, '', '', true, 0, false, true, 12, 'M');

            //Check next page
            if($pdf->checkPB(12, '', 0)){
                //Add new page
                $pdf->addPage();
            }

           $pdf->Ln();

           $pdf->MultiCell($w[0]+$w[1], 5, __("potpis"), 0, 'C', 1, 0, '', '', true, 0, false, true, 5, 'M');           
           $pdf->MultiCell($w[2]+$w[3]+$w[4], 5, "", 0, 'C', 1, 0, '', '', true, 0, false, true, 5, 'M');
           $pdf->MultiCell($w[5]+$w[6], 5, __("potpis"), 0, 'C', 1, 0, '', '', true, 0, false, true, 5, 'M');
           $pdf->MultiCell($w[7], 5, __("M.P."), 0, 'C', 1, 0, '', '', true, 0, false, true, 5, 'M');

           //Check next page
           if($pdf->checkPB(5, '', 0)){
              //Add new page
              $pdf->addPage();
           }
           $pdf->Ln(5);

           $pdf->MultiCell($w[0]+$w[1], 12, "", 'B', 'L', 1, 0, '', '', true, 0, false, true, 12, 'M');           
           $pdf->MultiCell($w[2]+$w[3]+$w[4], 12, "", 0, 'L', 1, 0, '', '', true, 0, false, true, 12, 'M');
           $pdf->MultiCell($w[5]+$w[6]+$w[7], 12, "", 'B', 'L', 1, 0, '', '', true, 0, false, true, 12, 'M');

           //Check next page
           if($pdf->checkPB(12, '', 0)){
              //Add new page
              $pdf->addPage();
           }
           $pdf->Ln();

           $pdf->MultiCell($w[0]+$w[1], 5, __("Mesto i datum"), 0, 'C', 1, 0, '', '', true, 0, false, true, 5, 'M');           
           $pdf->MultiCell($w[2]+$w[3]+$w[4], 5, "", 0, 'C', 1, 0, '', '', true, 0, false, true, 5, 'M');
           $pdf->MultiCell($w[5]+$w[6]+$w[7], 5, __("Mesto i datum"), 0, 'C', 1, 0, '', '', true, 0, false, true, 5, 'M');

           //Check next page
           if($pdf->checkPB(12, '', 0)){
              //Add new page
              $pdf->addPage();
           }           
           $pdf->Ln(12);

           $pdf->MultiCell($w[0]+$w[1]+$w[2]+$w[3]+$w[4]+$w[5]+$w[6], 5, __("Napomena: Osporavamo iskazano stanje u celini - delimicno za iznos od dinara ")."____________________________", 0, 'L', 1, 0, '', '', true, 0, false, true, 5, 'M');

           //Check next page
           if($pdf->checkPB(10, '', 0)){
              //Add new page
              $pdf->addPage();
           }
           $pdf->Ln(10);          

           $pdf->MultiCell($w[0]+$w[1]+$w[2]+$w[3]+$w[4]+$w[5]+$w[6]+$w[7], 5, __("Iz sledećih razloga")." ______________________________________________________________________________________________________________________", 0, 'L', 1, 0, '', '', true, 0, false, true, 5, 'M');
           //Check next page
           if($pdf->checkPB(8, '', 0)){
              //Add new page
              $pdf->addPage();
           }
           $pdf->Ln(8);

           $pdf->MultiCell($w[0]+$w[1]+$w[2]+$w[3]+$w[4]+$w[5]+$w[6]+$w[7], 5, "_____________________________________________________________________________________________________________________", 0, 'L', 1, 0, '', '', true, 0, false, true, 5, 'M');

           //Check next page
           if($pdf->checkPB(8, '', 0)){
              //Add new page
              $pdf->addPage();
           }
           $pdf->Ln(8);

           $pdf->MultiCell($w[0]+$w[1]+$w[2]+$w[3]+$w[4]+$w[5]+$w[6]+$w[7], 5, "_____________________________________________________________________________________________________________________", 0, 'L', 1, 0, '', '', true, 0, false, true, 5, 'M');

           //Check next page
           if($pdf->checkPB(5, '', 0)){
              //Add new page
              $pdf->addPage();
           }
           $pdf->Ln(5);

           //Set disclaimer
           $disclaimer = "IOS je potrebno potpisati i drugi primerak vratiti u roku od 2 dana na gore naznačenu adresu. Ukoliko ne overite i ne vratite obrazac smatraćemo da prihvatate nase stanje. Za sve informacije i dalje dogovore oko usaglašavanja mozete nas kontaktirati ";
           if(!empty($email_address)){
              $disclaimer .= "putem e-mail adrese: ".trim($email_address);
           }
           if(!empty($phone_number)){
              if(!empty($email_address)){
                $disclaimer .= " ili ";
              }
               $disclaimer .= "preko telefona: ".$phone_number.".";
           }
           //Check next page
           if($pdf->checkPB(12, '', 0)){
              //Add new page
              $pdf->addPage();
           }           
           $pdf->MultiCell(array_sum($w), 12, $disclaimer, 0, 'L', 1, 0, '', '', true, 0, false, true, 12, 'T');
     }//~!

     /**
      * method for printing simple foreign record header
      * @param $pdf - tcPDF object, $client - Client data, $record - FmIosRecord data, $textfont - selected font, $fontsize - selected font size
      * @return void
      */
     public function printForeignRecordInfo($pdf, $client, $record, $textfont, $fontsize){
           //Set widths
           $w = $this->simple_record_info_w;      

           $pdf->SetFont($textfont,'B', 15);
           $pdf->MultiCell($w[0]+$w[1]+$w[2]+$w[3]+$w[4]+5, 12, __("STATEMENT OF ACCOUNT"), 'B', 'C', 1, 0, '', '', true, 0, false, true, 12, 'M');           

           $pdf->Ln();
           $pdf->SetY($pdf->GetY()+5);

           $pdf->SetFont($textfont,'B', $fontsize);
           $pdf->MultiCell($w[0], 5, __("Date"), 0, 'L', 1, 0, '', '', true, 0, false, true, 5, 'M');
           $pdf->SetFont($textfont,'R', $fontsize);
           $pdf->MultiCell($w[1], 5, date('d-M-Y', strtotime($record['FmIosRecord']['ios_created'])), 'B', 'L', 1, 0, '', '', true, 0, false, true, 5, 'M');

           $pdf->MultiCell($w[2], 5, "", 0, 'L', 1, 0, '', '', true, 0, false, true, 5, 'M');
           
           $pdf->SetFont($textfont,'B', $fontsize);
           $pdf->MultiCell($w[3], 5, __("Account No"), 0, 'L', 1, 0, '', '', true, 0, false, true, 5, 'M');
           $pdf->SetFont($textfont,'R', $fontsize);
           $pdf->MultiCell($w[4], 5, "IOS-Ino ".$record['FmIosRecord']['code'], 'B', 'L', 1, 0, '', '', true, 0, false, true, 5, 'M');

           $pdf->Ln(6);

           $pdf->SetFont($textfont,'B', $fontsize);
           $pdf->MultiCell($w[0], 5, __("Client"), 0, 'L', 1, 0, '', '', true, 0, false, true, 5, 'M');           
           $pdf->SetFont($textfont,'R', $fontsize);
           $pdf->MultiCell($w[1], 5, trim($client['Client']['title']), 'B', 'L', 1, 0, '', '', true, 0, false, true, 5, 'M');

           $pdf->MultiCell($w[2], 5, "", 0, 'L', 1, 0, '', '', true, 0, false, true, 5, 'M');
           
           $pdf->SetFont($textfont,'B', $fontsize);
           $pdf->MultiCell($w[3], 5, __("Code"), 0, 'L', 1, 0, '', '', true, 0, false, true, 5, 'M');
           $pdf->SetFont($textfont,'R', $fontsize);
           $pdf->MultiCell($w[4], 5, trim($client['Client']['code']), 'B', 'L', 1, 0, '', '', true, 0, false, true, 5, 'M');

           $pdf->Ln(6);

           $pdf->SetFont($textfont,'B', $fontsize);
           $pdf->MultiCell($w[0], 5, __("City"), 0, 'L', 1, 0, '', '', true, 0, false, true, 5, 'M');           
           $pdf->SetFont($textfont,'R', $fontsize);           

           $city = trim($client['ClientAddressInfo']['city']);
           if(!empty($client['UsState']['abbrev'])){
              $city .= ", ".trim($client['UsState']['abbrev']);
           }
           $pdf->MultiCell($w[1], 5, $city, 'B', 'L', 1, 0, '', '', true, 0, false, true, 5, 'M');

           $pdf->MultiCell($w[2], 5, "", 0, 'L', 1, 0, '', '', true, 0, false, true, 5, 'M');
           
           $pdf->SetFont($textfont,'B', $fontsize);
           $pdf->MultiCell($w[3], 5, __("Address"), 0, 'L', 1, 0, '', '', true, 0, false, true, 5, 'M');
           $pdf->SetFont($textfont,'R', $fontsize);
           $pdf->MultiCell($w[4], 5, trim($client['ClientAddressInfo']['address_line_1'])." ".trim($client['ClientAddressInfo']['address_line_2']), 'B', 'L', 1, 0, '', '', true, 0, false, true, 5, 'M');

           $pdf->Ln(6);

           $pdf->SetFont($textfont,'B', $fontsize);
           $pdf->MultiCell($w[0], 5, __("Country"), 0, 'L', 1, 0, '', '', true, 0, false, true, 5, 'M');           
           $pdf->SetFont($textfont,'R', $fontsize);
           $pdf->MultiCell($w[1], 5, trim($client['Country']['name']), 'B', 'L', 1, 0, '', '', true, 0, false, true, 5, 'M');

           $pdf->MultiCell($w[2], 5, "", 0, 'L', 1, 0, '', '', true, 0, false, true, 5, 'M');
           
           $pdf->SetFont($textfont,'B', $fontsize);
           $pdf->MultiCell($w[3], 5, __("Tax Number"), 0, 'L', 1, 0, '', '', true, 0, false, true, 5, 'M');
           $pdf->SetFont($textfont,'R', $fontsize);
           $pdf->MultiCell($w[4], 5, trim($client['ClientInfo']['vat']), 'B', 'L', 1, 0, '', '', true, 0, false, true, 5, 'M');

           $pdf->Ln(6);

           $pdf->SetFont($textfont,'B', $fontsize);
           $pdf->MultiCell($w[0], 5, __("Phone"), 0, 'L', 1, 0, '', '', true, 0, false, true, 5, 'M');           
           $pdf->SetFont($textfont,'R', $fontsize);
           
           $address = trim($client['ClientContact']['phone']);           
           $pdf->MultiCell($w[1], 5, $address, 'B', 'L', 1, 0, '', '', true, 0, false, true, 5, 'M');

           $pdf->MultiCell($w[2], 5, "", 0, 'L', 1, 0, '', '', true, 0, false, true, 5, 'M');
           
           $pdf->SetFont($textfont,'B', $fontsize);
           $pdf->MultiCell($w[3], 5, __("Email"), 0, 'L', 1, 0, '', '', true, 0, false, true, 5, 'M');
           $pdf->SetFont($textfont,'R', $fontsize);
           $pdf->MultiCell($w[4], 5, trim($client['ClientContact']['email_address']), 'B', 'L', 1, 0, '', '', true, 0, false, true, 5, 'M');

           $pdf->Ln();         
     }//~!

     /**
      * method for printing simple foreign record footer
      * @param $pdf - tcPDF object, $email_address - form email addres, $phone_number - form phone number, $textfont - selected font, $fontsize - selected font size
      * @return void
      */
     public function printForeignRecordFooter($pdf, $email_address, $phone_number, $textfont, $fontsize){
           //Set widths
           $w = $this->footer_w;      

           $pdf->SetY($pdf->GetY()+5);

           $pdf->SetFont($textfont,'R', $fontsize);
           $pdf->MultiCell($w[0]+$w[1], 5, __("Mikroelektronika d.o.o."), 0, 'L', 1, 0, '', '', true, 0, false, true, 5, 'M');           

           $pdf->MultiCell($w[2]+$w[3], 5, "", 0, 'L', 1, 0, '', '', true, 0, false, true, 5, 'M');

           $pdf->MultiCell($w[4]+$w[5]+$w[6]+$w[7], 5, __("Client"), 0, 'L', 1, 0, '', '', true, 0, false, true, 5, 'M');

           $pdf->Ln(5);

           $pdf->MultiCell($w[0]+$w[1]+$w[2], 12, "", 'B', 'L', 1, 0, '', '', true, 0, false, true, 12, 'M');           
           $pdf->MultiCell($w[3], 12, "", 0, 'L', 1, 0, '', '', true, 0, false, true, 12, 'M');
           $pdf->MultiCell($w[4]+$w[5]+$w[6], 12, "", 'B', 'L', 1, 0, '', '', true, 0, false, true, 12, 'M');
           $pdf->MultiCell($w[7], 12, "", 0, 'L', 1, 0, '', '', true, 0, false, true, 12, 'M');

           $pdf->Ln();

           $pdf->MultiCell($w[0]+$w[1]+$w[2], 5, __("Accountant (first and last name, signature)"), 0, 'C', 1, 0, '', '', true, 0, false, true, 5, 'M');           
           $pdf->MultiCell($w[3], 5, __("SEAL"), 0, 'C', 1, 0, '', '', true, 0, false, true, 5, 'M');
           $pdf->MultiCell($w[4]+$w[5]+$w[6], 5, __("Accountant (name, surname, signature)"), 0, 'C', 1, 0, '', '', true, 0, false, true, 5, 'M');
           $pdf->MultiCell($w[7], 5, __("SEAL"), 0, 'C', 1, 0, '', '', true, 0, false, true, 5, 'M');

           $pdf->Ln(15);

           //Set disclaimer
           $disclaimer = "Please return one filled and signed copy in 10 days. If the answer is not received, we will consider the balance approved according to our data.\nFor all informations and further agreements over balance you can contact us ";
           if(!empty($email_address)){
              $disclaimer .= "via e-mail address: ".trim($email_address);
           }
           if(!empty($phone_number)){
              if(!empty($email_address)){
                $disclaimer .= " or\n";
              }
               $disclaimer .= "via phone number: ".$phone_number.".";
           }
           $pdf->MultiCell(array_sum($w), 12, $disclaimer, 0, 'L', 1, 0, '', '', true, 0, false, true, 12, 'T');
     }//~!     

     /**
      * method for printing domestic changes hearder version one
      * @param $pdf - tcPDF object, $textfont - selected font, $fontsize - selected font size
      * @return void
      */
     public function printDomesticChangesHeaderVersionOne($pdf, $textfont, $fontsize){
          //Set widths
          $w = $this->simple_records_table_w;

          //Print header          
          $pdf->SetX(8);
          $pdf->MultiCell(array_sum($w), 5, __("Pregled po stavkama"), 1, 'C', 1, 0, '', '', true, 0, false, true, 5, 'M');

          $pdf->Ln();

          $pdf->SetX(8);
          $pdf->SetFillColor(200,200,200);

          $pdf->SetFont($textfont,'B', $fontsize);      
          $pdf->MultiCell($w[0], 10, __("Datum knjiženja"), 1, 'C', 1, 0, '', '', true, 0, false, true, 10, 'M');
          $pdf->MultiCell($w[1], 10, __("Broj naloga"), 1, 'C', 1, 0, '', '', true, 0, false, true, 10, 'M');
          $pdf->MultiCell($w[2], 10, __("Vrsta naloga"), 1, 'C', 1, 0, '', '', true, 0, false, true, 10, 'M');
          $pdf->MultiCell($w[3], 10, __("Datum"), 1, 'C', 1, 0, '', '', true, 0, false, true, 10, 'M');
          $pdf->MultiCell($w[4], 10, __("Dospeva"), 1, 'C', 1, 0, '', '', true, 0, false, true, 10, 'M');
          $pdf->MultiCell($w[5], 10, __("Dokument"), 1, 'C', 1, 0, '', '', true, 0, false, true, 10, 'M');
          $pdf->MultiCell($w[6], 10, __("Duguje"), 1, 'C', 1, 0, '', '', true, 0, false, true, 10, 'M');
          $pdf->MultiCell($w[7], 10, __("Potražuje"), 1, 'C', 1, 0, '', '', true, 0, false, true, 10, 'M');
          $pdf->MultiCell($w[8], 10, __("Saldo"), 1, 'C', 1, 0, '', '', true, 0, false, true, 10, 'M');

          $pdf->SetFont($textfont,'R', $fontsize);

          $pdf->SetFillColor(255,255,255);

          $pdf->Ln();
     }//~!

     /**
      * method for printing domestic changes hearder version two
      * @param $pdf - tcPDF object, $textfont - selected font, $fontsize - selected font size
      * @return void
      */
     public function printDomesticChangesHeaderVersionTwo($pdf, $textfont, $fontsize){
          //Set widths
          $w = $this->simple_records_table_w;

          //Print header     
          $pdf->SetX(8);
          $pdf->MultiCell($w[1]+$w[2]+$w[3]+$w[5]*5, 5, __("Pregled po dokumentima"), 1, 'C', 1, 0, '', '', true, 0, false, true, 5, 'M');

          $pdf->Ln();

          $pdf->SetX(8);
          $pdf->SetFillColor(200,200,200);

          $pdf->SetFont($textfont,'B', $fontsize);      
          $pdf->MultiCell($w[1]+$w[2]+$w[3], 10, __("Dokument"), 1, 'C', 1, 0, '', '', true, 0, false, true, 10, 'M');
          $pdf->MultiCell($w[5], 10, __("Duguje"), 1, 'C', 1, 0, '', '', true, 0, false, true, 10, 'M');
          $pdf->MultiCell($w[5], 10, __("Potražuje"), 1, 'C', 1, 0, '', '', true, 0, false, true, 10, 'M');
          $pdf->MultiCell($w[5], 10, __("Otvoreno\nduguje"), 1, 'C', 1, 0, '', '', true, 0, false, true, 10, 'M');
          $pdf->MultiCell($w[5], 10, __("Otvoreno potražuje"), 1, 'C', 1, 0, '', '', true, 0, false, true, 10, 'M');          
          $pdf->MultiCell($w[5], 10, __("Saldo"), 1, 'C', 1, 0, '', '', true, 0, false, true, 10, 'M');

          $pdf->SetFont($textfont,'R', $fontsize);

          $pdf->SetFillColor(255,255,255);

          $pdf->Ln();
     }//~!     

     /**
      * method for printing domestic changes hearder version three
      * @param $pdf - tcPDF object, $textfont - selected font, $fontsize - selected font size
      * @return void
      */
     public function printDomesticChangesHeaderVersionThree($pdf, $textfont, $fontsize){
          //Set widths
          $w = $this->simple_records_table_w;

          //Print header
          $pdf->SetX(8);
          $pdf->MultiCell(array_sum($w), 5, __("Rekapitular prometa na konto-kartici"), 1, 'C', 1, 0, '', '', true, 0, false, true, 5, 'M');

          $pdf->Ln();
                              
          $pdf->SetX(8);
          $pdf->SetFillColor(200,200,200);

          $pdf->SetFont($textfont,'B', $fontsize);      
          $pdf->MultiCell($w[0]+$w[1]+$w[2], 10, __("Stavke"), 1, 'C', 1, 0, '', '', true, 0, false, true, 10, 'M');
          $pdf->MultiCell($w[3]+$w[4], 10, __("Duguje"), 1, 'C', 1, 0, '', '', true, 0, false, true, 10, 'M');
          $pdf->MultiCell($w[5]+$w[6], 10, __("Potražuje"), 1, 'C', 1, 0, '', '', true, 0, false, true, 10, 'M');
          $pdf->MultiCell($w[7]+$w[8], 10, __("Saldo"), 1, 'C', 1, 0, '', '', true, 0, false, true, 10, 'M');

          $pdf->SetFont($textfont,'R', $fontsize);

          $pdf->SetFillColor(255,255,255);

          $pdf->Ln();
     }//~!     

     /**
      * method for printing foreign changes hearder version one
      * @param $pdf - tcPDF object, $textfont - selected font, $fontsize - selected font size
      * @return void
      */
     public function printForeignChangesHeaderVersionOne($pdf, $textfont, $fontsize){
          //Set widths
          $w = $this->simple_records_table_w;

          //Print header          
          $pdf->SetX(16);
          $pdf->SetFillColor(200,200,200);

          $pdf->SetFont($textfont,'B', $fontsize);      
          $pdf->MultiCell($w[0]+$w[1]+$w[2], 10, __("Document No"), 1, 'C', 1, 0, '', '', true, 0, false, true, 10, 'M');
          $pdf->MultiCell($w[3], 10, __("Date"), 1, 'C', 1, 0, '', '', true, 0, false, true, 10, 'M');
          $pdf->MultiCell($w[4], 10, __("Document Type"), 1, 'C', 1, 0, '', '', true, 0, false, true, 10, 'M');
          $pdf->MultiCell($w[5], 10, __("Text"), 1, 'C', 1, 0, '', '', true, 0, false, true, 10, 'M');
          $pdf->MultiCell($w[6], 10, __("Debit"), 1, 'C', 1, 0, '', '', true, 0, false, true, 10, 'M');
          $pdf->MultiCell($w[7], 10, __("Credit"), 1, 'C', 1, 0, '', '', true, 0, false, true, 10, 'M');
          $pdf->SetFont($textfont,'R', $fontsize);

          $pdf->SetFillColor(255,255,255);

          $pdf->Ln();
     }//~!

     /**
      * method for printing domestic changes hearder version one
      * @param $pdf - tcPDF object, $changes - Client data, $record - FmIosRecord data, $changes - FmIosRecord changes data, $change_sums - array of total sums, $textfont - selected font, $fontsize - selected font size
      * @return void
      */
      public function printDomesticChangesRecordsVersionOne($pdf, $record, $changes, $change_sums, $textfont, $fontsize){
          //Set widths
          $w = $this->simple_records_table_w;

          //Init records
          $pdf->SetX(8);

          $pdf->SetY($pdf->GetY()+5);           
          $pdf->SetFont($textfont,'R', $fontsize);
          $account_text = "Prema našim poslovnim knjigama, vas konto ".$record['FmChartAccount']['code']." na dan ".date('d.m.Y',  strtotime($record['FmIosRecord']['ios_date_to']))." iskazuje sledece otvorene stavke";
          $pdf->MultiCell(array_sum($w), 5, $account_text, 0, 'L', 1, 0, '', '', true, 0, false, true, 5, 'M');

          $pdf->Ln();     

          //Print record table header
          $this->printDomesticChangesHeaderVersionOne($pdf, $textfont, $fontsize);

          //Set regular font
          $pdf->SetFont($textfont, 'R', $fontsize);
          $pdf->SetFillColor(255,255,255);

          //Print table content
          $count = count($changes);      
          $index = 0;

          foreach ($changes as $change) {
               //Reset x coordinate
               $pdf->SetX(8);

               $params = array('primary_document_code' => array('width' => $w[5], 'text' => $change['FmAccountOrderRecord']['primary_document_code']));
               $row_height = $this->checkRowHeight($pdf, $params);

               //Write first part
               $pdf->MultiCell($w[0], $row_height, date('d.m.Y', strtotime($change['FmAccountOrder']['account_date'])), 1, 'C', 1, 0, '', '', true, 0, false, true, $row_height, 'M');
               $pdf->MultiCell($w[1], $row_height, $change['FmAccountOrder']['code'], 1, 'C', 1, 0, '', '', true, 0, false, true, $row_height, 'M');
               $pdf->MultiCell($w[2], $row_height, $change['FmOrderType']['code'], 1, 'C', 1, 0, '', '', true, 0, false, true, $row_height, 'M');
               $pdf->MultiCell($w[3], $row_height, date('d.m.Y', strtotime($change['FmAccountOrderRecord']['dpo'])), 1, 'C', 1, 0, '', '', true, 0, false, true, $row_height, 'M');

               $valuta_dospeca = "";
               if(!empty($change['FmAccountOrderRecord']['valuta_dospeca_datum'])){
                  $valuta_dospeca = date('d.m.Y', strtotime($change['FmAccountOrderRecord']['valuta_dospeca_datum']));
               }               
               $pdf->MultiCell($w[4], $row_height, $valuta_dospeca, 1, 'C', 1, 0, '', '', true, 0, false, true, $row_height, 'M');
               $pdf->MultiCell($w[5], $row_height, $change['FmAccountOrderRecord']['primary_document_code'], 1, 'L', 1, 0, '', '', true, 0, false, true, $row_height, 'M');

               $domestic_debit = 0;
               if(!empty($change['FmAccountOrderRecord']['domestic_debit'])){                                 
                  $pdf->MultiCell($w[6], $row_height, number_format($change['FmAccountOrderRecord']['domestic_debit'], 3, '.', ','), 1, 'R', 1, 0, '', '', true, 0, false, true, $row_height, 'M');
                  $domestic_debit = $change['FmAccountOrderRecord']['domestic_debit'];
               }else{
                  $pdf->MultiCell($w[6], $row_height, "", 1, 'R', 1, 0, '', '', true, 0, false, true, $row_height, 'M');
               }

               $domestic_credit = 0;
               if(!empty($change['FmAccountOrderRecord']['domestic_credit'])){                                 
                  $pdf->MultiCell($w[7], $row_height, number_format($change['FmAccountOrderRecord']['domestic_credit'], 3, '.', ','), 1, 'R', 1, 0, '', '', true, 0, false, true, $row_height, 'M');
                  $domestic_credit = $change['FmAccountOrderRecord']['domestic_credit'];
               }else{
                  $pdf->MultiCell($w[7], $row_height, "", 1, 'R', 1, 0, '', '', true, 0, false, true, $row_height, 'M');
               }

               $domestic_total = $domestic_debit - $domestic_credit;
               if(abs($domestic_total) < 0.01) {
                  $pdf->MultiCell($w[7], $row_height, "0.00", 1, 'R', 1, 0, '', '', true, 0, false, true, $row_height, 'M');                  
               }else{
                  $pdf->MultiCell($w[7], $row_height, number_format(round($domestic_total, 2), 2, '.', ','), 1, 'R', 1, 0, '', '', true, 0, false, true, $row_height, 'M');
               }

               //Set new line            
               $pdf->Ln($row_height);

               //Increment record index
               $index++;

               //Check if last record
               if($count > $index){        
                    //Set new page
                    if($pdf->checkPB($row_height+1, '', 0)){
                        //Add new page
                        $pdf->addPage();

                        //Print record table header
                        $this->printDomesticChangesHeaderVersionOne($pdf, $textfont, $fontsize);
                    }  
               }
          }  

        //Check for new page
        if($pdf->checkPB($row_height+1, '', 0)){
            //Add new page
            $pdf->addPage();

            //Print record table header
            $this->printDomesticChangesHeaderVersionOne($pdf, $textfont, $fontsize);
        }  

        //Reset x coordinate
        $pdf->SetX(8);

        //Set total color and bold
        $pdf->SetFillColor(255,255,204);
        $pdf->SetFont($textfont,'B', $fontsize);

        //Set total title
        $pdf->MultiCell($w[0]+$w[1]+$w[2]+$w[3]+$w[4]+$w[5], 5, __("Ukupno"), 1, 'R', 1, 0, '', '', true, 0, false, true, 5, 'M');

        $traffic_rsd_debit = 0;
        $traffic_rsd_credit = 0;               
        if(!empty($change_sums['sum_domestic_debit'])){
            $traffic_rsd_debit = $change_sums['sum_domestic_debit'];
        }
        if(!empty($change_sums['sum_domestic_credit'])){
           $traffic_rsd_credit = $change_sums['sum_domestic_credit'];               
        }               
        $traffic_rsd_total = round($traffic_rsd_debit - $traffic_rsd_credit, 2);

        $pdf->MultiCell($w[6], 5, number_format($traffic_rsd_debit, 2, '.', ','), 1, 'R', 1, 0, '', '', true, 0, false, true, 5, 'M');
        $pdf->MultiCell($w[7], 5, number_format($traffic_rsd_credit, 2, '.', ','), 1, 'R', 1, 0, '', '', true, 0, false, true, 5, 'M');
        $pdf->MultiCell($w[8], 5, number_format($traffic_rsd_total, 2, '.', ','), 1, 'R', 1, 0, '', '', true, 0, false, true, 5, 'M');

        $pdf->Ln(6);
        $pdf->SetFillColor(255,255,255);

        //Check for new page
        if($pdf->checkPB(15, '', 0)){
            //Add new page
            $pdf->addPage();
        }          
      }//~!

     /**
      * method for printing domestic changes hearder version two
      * @param $pdf - tcPDF object, $changes - Client data, $record - FmIosRecord data, $changes - FmIosRecord changes data, $textfont - selected font, $fontsize - selected font size
      * @return void
      */
      public function printDomesticChangesRecordsVersionTwo($pdf, $record, $changes, $textfont, $fontsize){
          //Set widths
          $w = $this->simple_records_table_w;

          //Init records
          $pdf->SetX(8);

          $pdf->SetY($pdf->GetY()+5);           
          $pdf->SetFont($textfont,'R', $fontsize);
          $account_text = "Prema našim poslovnim knjigama, vas konto ".$record['FmChartAccount']['code']." na dan ".date('d.m.Y',  strtotime($record['FmIosRecord']['ios_date_to']))." iskazuje sledece otvorene stavke";
          $pdf->MultiCell(array_sum($w), 5, $account_text, 0, 'L', 1, 0, '', '', true, 0, false, true, 5, 'M');

          $pdf->Ln();     

          //Print record table header
          $this->printDomesticChangesHeaderVersionTwo($pdf, $textfont, $fontsize);

          //Set regular font
          $pdf->SetFont($textfont, 'R', $fontsize);
          $pdf->SetFillColor(255,255,255);

          //Print table content
          $count = count($changes);      
          $index = 0;

          //Init sums
          $sum_open_debit = 0;
          $sum_open_credit = 0;

          foreach ($changes as $change) {
               //Reset x coordinate
               $pdf->SetX(8);

               $params = array('primary_document_code' => array('width' => $w[5], 'text' => $change['FmAccountOrderRecord']['primary_document_code']));
               $row_height = $this->checkRowHeight($pdf, $params);

               //Write document code
               $pdf->MultiCell($w[0]+$w[1]+$w[2], $row_height, $change['FmAccountOrderRecord']['primary_document_code'], 1, 'L', 1, 0, '', '', true, 0, false, true, $row_height, 'M');

               $domestic_debit = 0;
               if(!empty($change[0]['total_domestic_debit'])){                                 
                  $pdf->MultiCell($w[5], $row_height, number_format($change[0]['total_domestic_debit'], 3, '.', ','), 1, 'R', 1, 0, '', '', true, 0, false, true, $row_height, 'M');
                  $domestic_debit = $change[0]['total_domestic_debit'];
               }else{
                  $pdf->MultiCell($w[5], $row_height, "", 1, 'R', 1, 0, '', '', true, 0, false, true, $row_height, 'M');
               }

               $domestic_credit = 0;
               if(!empty($change[0]['total_domestic_credit'])){                                 
                  $pdf->MultiCell($w[5], $row_height, number_format($change[0]['total_domestic_credit'], 3, '.', ','), 1, 'R', 1, 0, '', '', true, 0, false, true, $row_height, 'M');
                  $domestic_credit = $change[0]['total_domestic_credit'];
               }else{
                  $pdf->MultiCell($w[5], $row_height, "", 1, 'R', 1, 0, '', '', true, 0, false, true, $row_height, 'M');
               }

               $open_debit = 0;
               if($domestic_debit > $domestic_credit){
                  $open_debit = $domestic_debit - $domestic_credit;
                  $pdf->MultiCell($w[5], $row_height, number_format($open_debit, 3, '.', ','), 1, 'R', 1, 0, '', '', true, 0, false, true, $row_height, 'M');
                  $sum_open_debit += $open_debit;
               }else{
                  $pdf->MultiCell($w[5], $row_height, "", 1, 'R', 1, 0, '', '', true, 0, false, true, $row_height, 'M');
               }

               $open_credit = 0;
               if($domestic_debit < $domestic_credit){
                  $open_credit = $domestic_credit - $domestic_debit;
                  $pdf->MultiCell($w[5], $row_height, number_format($open_credit, 3, '.', ','), 1, 'R', 1, 0, '', '', true, 0, false, true, $row_height, 'M');
                  $sum_open_credit += $open_credit;
               }else{
                  $pdf->MultiCell($w[5], $row_height, "", 1, 'R', 1, 0, '', '', true, 0, false, true, $row_height, 'M');
               }

               $open_total = $open_debit - $open_credit;
               if(abs($open_total) < 0.01) {
                  $pdf->MultiCell($w[5], $row_height, "0.00", 1, 'R', 1, 0, '', '', true, 0, false, true, $row_height, 'M');
               }else{
                  $pdf->MultiCell($w[5], $row_height, number_format(round($open_total, 2), 2, '.', ','), 1, 'R', 1, 0, '', '', true, 0, false, true, $row_height, 'M');
               }

               //Set new line            
               $pdf->Ln($row_height);

               //Increment record index
               $index++;

               //Check if last record
               if($count > $index){        
                    //Set new page
                    if($pdf->checkPB($row_height+1, '', 0)){
                        //Add new page
                        $pdf->addPage();

                        //Print record table header
                        $this->printDomesticChangesHeaderVersionTwo($pdf, $textfont, $fontsize);
                    }  
               }
          }  

        //Check for new page
        if($pdf->checkPB($row_height+1, '', 0)){
            //Add new page
            $pdf->addPage();

            //Print record table header
            $this->printDomesticChangesHeaderVersionTwo($pdf, $textfont, $fontsize);
        }  
        
        //Reset x coordinate
        $pdf->SetX(8);

        //Set total color and bold
        $pdf->SetFillColor(255,255,204);
        $pdf->SetFont($textfont,'B', $fontsize);      

        //Set total title
        $pdf->MultiCell($w[0]+$w[1]+$w[2]+$w[5]+$w[5], 5, __("Ukupno"), 1, 'R', 1, 0, '', '', true, 0, false, true, 5, 'M');

        $pdf->MultiCell($w[5], 5, number_format($sum_open_debit, 2, '.', ','), 1, 'R', 1, 0, '', '', true, 0, false, true, 5, 'M');
        $pdf->MultiCell($w[5], 5, number_format($sum_open_credit, 2, '.', ','), 1, 'R', 1, 0, '', '', true, 0, false, true, 5, 'M');

        $sum_open_total = round($sum_open_debit - $sum_open_credit, 2);      
        $pdf->MultiCell($w[5], 5, number_format($sum_open_total, 2, '.', ','), 1, 'R', 1, 0, '', '', true, 0, false, true, 5, 'M');

        $pdf->Ln(6);
        $pdf->SetFillColor(255,255,255);

        //Check for new page
        if($pdf->checkPB(15, '', 0)){
            //Add new page
            $pdf->addPage();
        }           
      }//~! 

     /**
      * method for printing domestic changes hearder version three
      * @param $pdf - tcPDF object, $record - FmIosRecord data, $gross_traffic_overview - Gross traffic overview, $textfont - selected font, $fontsize - selected font size
      * @return void
      */
      public function printDomesticChangesRecordsVersionThree($pdf, $record, $gross_traffic_overview, $textfont, $fontsize){
          //Set widths
          $w = $this->simple_records_table_w;

          //Init records
          $pdf->SetX(8);

          $pdf->SetY($pdf->GetY()+5);           
          $pdf->SetFont($textfont,'R', $fontsize);
          $account_text = "Prema našim poslovnim knjigama, vas konto ".$record['FmChartAccount']['code']." na dan ".date('d.m.Y',  strtotime($record['FmIosRecord']['ios_date_to']))." iskazuje sledece otvorene stavke";
          $pdf->MultiCell(array_sum($w), 5, $account_text, 0, 'L', 1, 0, '', '', true, 0, false, true, 5, 'M');

          $pdf->Ln();     

          //Print record table header
          $this->printDomesticChangesHeaderVersionThree($pdf, $textfont, $fontsize);

          //Set regular font
          $pdf->SetFont($textfont, 'R', $fontsize);
          $pdf->SetFillColor(255,255,255);

          //Reset x coordinate
          $pdf->SetX(8);
          $row_height = 5;

          $closed_domestic_debit = 0;
          $closed_domestic_credit = 0;

          $opened_domestic_debit = 0;
          $opened_domestic_credit = 0;

          //Write gross traffic
          $pdf->MultiCell($w[0]+$w[1]+$w[2], $row_height, __("Zatvorene stavke"), 1, 'L', 1, 0, '', '', true, 0, false, true, $row_height, 'M');

          $closed_domestic_debit = floatval($gross_traffic_overview['closed']['sum_domestic_debit']);
          if(!empty($closed_domestic_debit)){
            $pdf->MultiCell($w[3]+$w[4], $row_height, number_format($closed_domestic_debit, 3, '.', ','), 1, 'R', 1, 0, '', '', true, 0, false, true, $row_height, 'M');
          } else {
            $pdf->MultiCell($w[3]+$w[4], $row_height, "", 1, 'R', 1, 0, '', '', true, 0, false, true, $row_height, 'M');
          }

          $closed_domestic_credit = floatval($gross_traffic_overview['closed']['sum_domestic_credit']);
          if(!empty($closed_domestic_credit)){
            $pdf->MultiCell($w[5]+$w[6], $row_height, number_format($closed_domestic_credit, 3, '.', ','), 1, 'R', 1, 0, '', '', true, 0, false, true, $row_height, 'M');
          } else {
            $pdf->MultiCell($w[5]+$w[6], $row_height, "", 1, 'R', 1, 0, '', '', true, 0, false, true, $row_height, 'M');
          }

          $closed_domestic_diff = round($closed_domestic_debit - $closed_domestic_credit, 2);
          if(!empty($closed_domestic_diff)){
            $pdf->MultiCell($w[7]+$w[8], $row_height, number_format($closed_domestic_diff, 3, '.', ','), 1, 'R', 1, 0, '', '', true, 0, false, true, $row_height, 'M');
          } else {
            $pdf->MultiCell($w[7]+$w[8], $row_height, "", 1, 'R', 1, 0, '', '', true, 0, false, true, $row_height, 'M');
          }

          //Set new line            
          $pdf->Ln($row_height);

          //Reset x coordinate
          $pdf->SetX(8);          

          $pdf->MultiCell($w[0]+$w[1]+$w[2], $row_height, __("Otvorene stavke"), 1, 'L', 1, 0, '', '', true, 0, false, true, $row_height, 'M');

          $opened_domestic_debit = floatval($gross_traffic_overview['opened']['sum_domestic_debit']);
          if(!empty($opened_domestic_debit)){
            $pdf->MultiCell($w[3]+$w[4], $row_height, number_format($opened_domestic_debit, 3, '.', ','), 1, 'R', 1, 0, '', '', true, 0, false, true, $row_height, 'M');
          } else {
            $pdf->MultiCell($w[3]+$w[4], $row_height, "", 1, 'R', 1, 0, '', '', true, 0, false, true, $row_height, 'M');
          }

          $opened_domestic_credit = floatval($gross_traffic_overview['opened']['sum_domestic_credit']);
          if(!empty($opened_domestic_credit)){
            $pdf->MultiCell($w[5]+$w[6], $row_height, number_format($opened_domestic_credit, 3, '.', ','), 1, 'R', 1, 0, '', '', true, 0, false, true, $row_height, 'M');
          } else {
            $pdf->MultiCell($w[5]+$w[6], $row_height, "", 1, 'R', 1, 0, '', '', true, 0, false, true, $row_height, 'M');
          }

          $opened_domestic_diff = round($opened_domestic_debit - $opened_domestic_credit, 2);
          if(!empty($opened_domestic_diff)){
            $pdf->MultiCell($w[7]+$w[8], $row_height, number_format($opened_domestic_diff, 2, '.', ','), 1, 'R', 1, 0, '', '', true, 0, false, true, $row_height, 'M');
          } else {
            $pdf->MultiCell($w[7]+$w[8], $row_height, "", 1, 'R', 1, 0, '', '', true, 0, false, true, $row_height, 'M');
          }          

          //Set new line            
          $pdf->Ln($row_height);

          //Reset x coordinate
          $pdf->SetX(8);

          //Set total color and bold
          $pdf->SetFillColor(255,255,204);
          $pdf->SetFont($textfont,'B', $fontsize);      

          //Set total title
          $pdf->MultiCell($w[0]+$w[1]+$w[2], 5, __("Ukupno"), 1, 'R', 1, 0, '', '', true, 0, false, true, 5, 'M');
        
          $total_domestic_debit = $closed_domestic_debit + $opened_domestic_debit;      
          $total_domestic_credit = $closed_domestic_credit + $opened_domestic_credit;
          $total_domestic_diff = $total_domestic_debit - $total_domestic_credit;

          $pdf->MultiCell($w[3]+$w[4], 5, number_format($total_domestic_debit, 2, '.', ','), 1, 'R', 1, 0, '', '', true, 0, false, true, 5, 'M');
          $pdf->MultiCell($w[5]+$w[6], 5, number_format($total_domestic_credit, 2, '.', ','), 1, 'R', 1, 0, '', '', true, 0, false, true, 5, 'M');
          $pdf->MultiCell($w[7]+$w[8], 5, number_format($total_domestic_diff, 2, '.', ','), 1, 'R', 1, 0, '', '', true, 0, false, true, 5, 'M');

          $pdf->Ln(6);
          $pdf->SetFillColor(255,255,255);

          //Check for new page
          if($pdf->checkPB(15, '', 0)){
              //Add new page
              $pdf->addPage();
          }             
      }//~!

     /**
      * method for printing foreign changes version one
      * @param $pdf - tcPDF object, $changes - Client data, $record - FmIosRecord data, $changes - FmIosRecord changes data, $change_sums - array of total sums, $textfont - selected font, $fontsize - selected font size
      * @return void
      */
      public function printForeignChangesRecordsVersionOne($pdf, $record, $changes, $change_sums, $textfont, $fontsize){
          //Set widths
          $w = $this->simple_records_table_w;

          //Init records
          $pdf->SetY($pdf->GetY()+5);           
          $pdf->SetFont($textfont,'R', $fontsize);
          $account_text = "Please confirm, that the following Our/Yours Overdue account for ".date('d-M-Y', strtotime($record['FmIosRecord']['ios_date_to']))." is ";

          $pdf->SetX(16);
          $pdf->MultiCell(array_sum($w), 5, $account_text, 0, 'L', 1, 0, '', '', true, 0, false, true, 5, 'M');

          $pdf->Ln();     

          //Print record table header
          $this->printForeignChangesHeaderVersionOne($pdf, $textfont, $fontsize);

          //Set regular font
          $pdf->SetFont($textfont, 'R', $fontsize);
          $pdf->SetFillColor(255,255,255);

          //Print table content
          $count = count($changes);
          $index = 0;

          //Reset sums
          $sum_foreign_debit = 0;
          $sum_foreign_credit = 0;

          foreach ($changes as $change) {
               //Reset x coordinate
               $pdf->SetX(16);

               $params = array('primary_document_code' => array('width' => $w[5], 'text' => $change['FmAccountOrderRecord']['primary_document_code']));
               $row_height = $this->checkRowHeight($pdf, $params);

               $pdf->MultiCell($w[0]+$w[1]+$w[2], $row_height, $change['FmAccountOrderRecord']['primary_document_code'], 1, 'L', 1, 0, '', '', true, 0, false, true, $row_height, 'M');
               $pdf->MultiCell($w[3], $row_height, date('d-M-Y', strtotime($change['FmAccountOrderRecord']['dpo'])), 1, 'C', 1, 0, '', '', true, 0, false, true, $row_height, 'M');
               $pdf->MultiCell($w[4], $row_height, $change['PrimaryDocumentType']['code'], 1, 'L', 1, 0, '', '', true, 0, false, true, $row_height, 'M');
               $pdf->MultiCell($w[5], $row_height, "", 1, 'L', 1, 0, '', '', true, 0, false, true, $row_height, 'M');

               $foreign_debit = 0;
               if(!empty($change['FmAccountOrderRecord']['foreign_debit'])){                                 
                  $pdf->MultiCell($w[6], $row_height, number_format($change['FmAccountOrderRecord']['foreign_debit'], 2, '.', ','), 1, 'R', 1, 0, '', '', true, 0, false, true, $row_height, 'M');
                  $sum_foreign_debit += $change['FmAccountOrderRecord']['foreign_debit'];
               }else{
                  $pdf->MultiCell($w[6], $row_height, "", 1, 'R', 1, 0, '', '', true, 0, false, true, $row_height, 'M');
               }

               $foreign_credit = 0;
               if(!empty($change['FmAccountOrderRecord']['foreign_credit'])){                                 
                  $pdf->MultiCell($w[7], $row_height, number_format($change['FmAccountOrderRecord']['foreign_credit'], 2, '.', ','), 1, 'R', 1, 0, '', '', true, 0, false, true, $row_height, 'M');
                  $sum_foreign_credit += $change['FmAccountOrderRecord']['foreign_credit'];
               }else{
                  $pdf->MultiCell($w[7], $row_height, "", 1, 'R', 1, 0, '', '', true, 0, false, true, $row_height, 'M');
               }

               //Set new line            
               $pdf->Ln($row_height);

               //Increment record index
               $index++;

               //Check if last record
               if($count > $index){        
                    //Set new page
                    if($pdf->checkPB($row_height+1, '', 0)){
                        //Add new page
                        $pdf->addPage();

                        //Print record table header
                        $this->printForeignChangesHeaderVersionOne($pdf, $textfont, $fontsize);
                    }  
               }
          }  

        //Check for new page
        if($pdf->checkPB($row_height+1, '', 0)){
            //Add new page
            $pdf->addPage();

            //Print record table header
            $this->printForeignChangesHeaderVersionOne($pdf, $textfont, $fontsize);
        }  

        //Reset x coordinate
        $pdf->SetX(16);

        //Set total color and bold
        $pdf->SetFillColor(255,255,204);
        $pdf->SetFont($textfont,'B', $fontsize);      

        //Print totals
        $pdf->MultiCell($w[0]+$w[1]+$w[2], 5, __("Total"), 1, 'R', 1, 0, '', '', true, 0, false, true, 5, 'M');
        $pdf->MultiCell($w[3], 5, date('d-M-Y', strtotime($record['FmIosRecord']['ios_date_to']." +1 day")), 1, 'C', 1, 0, '', '', true, 0, false, true, 5, 'M');
        $pdf->MultiCell($w[4], 5, "", 1, 'L', 1, 0, '', '', true, 0, false, true, 5, 'M');
        $pdf->MultiCell($w[5], 5, $record['Currency']['iso'], 1, 'C', 1, 0, '', '', true, 0, false, true, 5, 'M');

        if(!empty($sum_foreign_debit)){
          $pdf->MultiCell($w[6], 5, number_format($sum_foreign_debit, 2, '.', ','), 1, 'R', 1, 0, '', '', true, 0, false, true, 5, 'M');
        } else {
          $pdf->MultiCell($w[6], 5, "", 1, 'R', 1, 0, '', '', true, 0, false, true, 5, 'M');
        }        

        if(!empty($sum_foreign_credit)){
          $pdf->MultiCell($w[7], 5, number_format($sum_foreign_credit, 2, '.', ','), 1, 'R', 1, 0, '', '', true, 0, false, true, 5, 'M');
        } else {
          $pdf->MultiCell($w[7], 5, "", 1, 'R', 1, 0, '', '', true, 0, false, true, 5, 'M');
        }
        $pdf->Ln();

        //Check if new page is needed
        if($pdf->checkPB(6, '', 0)){
            //Add new page
            $pdf->addPage();
        }        

        //Reset x coordinate
        $pdf->SetX(16);

        $pdf->MultiCell($w[0]+$w[1]+$w[2], 5, __("Balance"), 1, 'R', 1, 0, '', '', true, 0, false, true, 5, 'M');
        $pdf->MultiCell($w[3], 5, date('d-M-Y', strtotime($record['FmIosRecord']['ios_date_to']." +1 day")), 1, 'C', 1, 0, '', '', true, 0, false, true, 5, 'M');
        $pdf->MultiCell($w[4], 5, "", 1, 'L', 1, 0, '', '', true, 0, false, true, 5, 'M');
        $pdf->MultiCell($w[5], 5, $record['Currency']['iso'], 1, 'C', 1, 0, '', '', true, 0, false, true, 5, 'M');

        if($sum_foreign_debit > $sum_foreign_credit){
          $pdf->MultiCell($w[6], 5, number_format($sum_foreign_debit - $sum_foreign_credit, 2, '.', ','), 1, 'R', 1, 0, '', '', true, 0, false, true, 5, 'M');
        } else {
          $pdf->MultiCell($w[6], 5, "", 1, 'R', 1, 0, '', '', true, 0, false, true, 5, 'M');
        }

        if($sum_foreign_debit < $sum_foreign_credit){
          $pdf->MultiCell($w[7], 5, number_format($sum_foreign_credit - $sum_foreign_debit, 2, '.', ','), 1, 'R', 1, 0, '', '', true, 0, false, true, 5, 'M');
        } else {
          $pdf->MultiCell($w[7], 5, "", 1, 'R', 1, 0, '', '', true, 0, false, true, 5, 'M');
        }

        $pdf->Ln(6);
        $pdf->SetFillColor(255,255,255);

        //Check for new page
        if($pdf->checkPB(15, '', 0)){
            //Add new page
            $pdf->addPage();
        }           
      }//~!      
}