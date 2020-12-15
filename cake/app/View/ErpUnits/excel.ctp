<?php 
	$writer->writeSheetHeader('ERP moduli', $header);
	$writer->writeSheet($data, 'ERP moduli');
	$writer->writeToStdOut();
