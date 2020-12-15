<?php
require('tcpdf.php');

class PDF extends TCPDF
{
    var $xheadertext  = 'PDF created by mikroERP'; 
    var $xheadercolor = array(0,0,200); 
    var $xfootertext  = 'Copyright © %d MikroElektronika. All rights reserved.'; 
    var $xfooterfont  = PDF_FONT_NAME_MAIN; 
    var $xfooterfontsize = 8; 
    var $domestic = false;
    var $terms = false;


    /** 
    * Overwrites the default header 
    * set the text in the view using 
    *    $fpdf->xheadertext = 'YOUR ORGANIZATION'; 
    * set the fill color in the view using 
    *    $fpdf->xheadercolor = array(0,0,100); (r, g, b) 
    * set the font in the view using 
    *    $fpdf->setHeaderFont(array('YourFont','',fontsize)); 
    */ 
    function Header() 
    { 
        if(!$this->domestic){
            $this->ImageSVG(WWW_ROOT.'img'.DS.'company'.DS.'mikroe-logo-new-embedded.svg', 10, 8, 57, 16);
            $this->SetXY(106, 7);
            $this->SetFont('freesans','R',7);
            $this->Cell(30,5,'MIKROELEKTRONIKA Batajnički drum 23, 11000 Belgrade, Serbia');
            $this->SetX(106);
            $this->Cell(30,13,'VAT: SR105917343 | Registration No. 20490918');
            $this->SetXY(106, 7);
            $this->Cell(30,21,'Office phone: + 381 11 78 57 600 | Support phone: + 381 11 78 57 628');
            $this->SetXY(106, 7);
            $this->Cell(30,29,'E-mail: office@mikroe.com | Web: http://www.mikroe.com/');
            $this->SetXY(106, 7);
        }else{
            $this->ImageSVG(WWW_ROOT.'img'.DS.'company'.DS.'mikroe-logo-new-embedded.svg', 10, 8, 57, 16);
            $this->SetXY(106, 7);
            $this->SetFont('freesans','R',7);
            $this->Cell(30,5,'MIKROELEKTRONIKA DOO, Batajnički drum 23, 11186 Zemun, Beograd, Srbija');
            $this->SetX(106);
            $this->Cell(30,13,'Matični broj: 20490918 | PIB: 105917343 | Šifra Delatnosti: 2620');
            $this->SetXY(106, 7);
            $this->Cell(30,21,'Tekući račun: 265-1630310005061-64 | EPPDV: 460202086');
            $this->SetXY(106, 7);
            $this->Cell(30,29,'Telefon: 011/78 57 600 | Faks: 011/63 09 644 | office@mikroe.com | www.mikroe.com');
            $this->SetXY(106, 7);
        }
    } 

    /** 
    * Overwrites the default footer 
    * set the text in the view using 
    * $fpdf->xfootertext = 'Copyright Â© %d YOUR ORGANIZATION. All rights reserved.'; 
    */ 
    function Footer() 
    { 
        $year = date('Y'); 
        
        if($this->terms) $this->xfootertext  = 'You can read our terms and conditions at this address: www.mikroe.com/legal/terms';
        if($this->domestic) $this->xfootertext  = 'Copyright © %d. MikroElektronika d.o.o. zadržava sva autorska prava.';

        $footertext = sprintf($this->xfootertext, $year); 
        $this->SetY(-20); 
        $this->SetTextColor(0, 0, 0); 
        $this->SetFont($this->xfooterfont,'',$this->xfooterfontsize);        
        $this->Cell(0,8, $footertext,'T',0,'L'); 

        //Check for page groups
        if (empty($this->pagegroups)) {
            $pagenumtxt = $this->getAliasNumPage().' / '.$this->getAliasNbPages();
        } else {
            $pagenumtxt = $this->getPageNumGroupAlias().' / '.$this->getPageGroupAlias();
        }

        //Check if domestic and print page numbers
        if(!$this->domestic){
            // Page number
            $this->Cell(0,8, 'Page '.$pagenumtxt,'T',1,'R');
        }else{
            // Page number
            $this->Cell(0,8, 'Strana '.$pagenumtxt,'T',1,'R');
        }
    }//~!

    function checkPB($h=0,$y='',$addpage=true){
        return $this->checkPageBreak($h, $y, $addpage);
    }//~!    
} ?>