<?php

if (!defined('BASEPATH'))
	exit('No direct script access allowed');

require_once dirname(__FILE__) . '/tcpdf/tcpdf.php';
//require_once dirname(__FILE__) . '/tcpdf/config/lang/eng.php';

class Pdf extends TCPDF {

	function __construct() {
		parent::__construct();
	}

	public function CreateTextBox($textval, $x = 0, $y, $width = 0, $height = 10, $fontsize = 10, $fontstyle = '', $align = 'L') {
		$this->SetXY($x + 20, $y); // 20 = margin left
		$this->SetFont(PDF_FONT_NAME_MAIN, $fontstyle, $fontsize);
		$this->Cell($width, $height, $textval, 0, false, $align);
	}

}