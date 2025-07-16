<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// Define the TCPDF directory path
define('TCPDF_PATH', APPPATH . 'third_party/tcpdf/');

// Include the main TCPDF library
require_once TCPDF_PATH . 'tcpdf.php';

class Pdf extends TCPDF {
    protected $headerTitle = 'Report';

    public function __construct() {
        parent::__construct();
    }

    public function setHeaderTitle($title) {
        $this->headerTitle = $title;
    }

    // Header
    public function Header() {
        // Set font
        $this->SetFont('helvetica', 'B', 20);
        // Title
        $this->Cell(0, 15, $this->headerTitle, 0, false, 'C', 0, '', 0, false, 'M', 'M');
    }

    // Footer
    public function Footer() {
        // Position at 15 mm from bottom
        $this->SetY(-15);
        // Set font
        $this->SetFont('helvetica', 'I', 8);
        // Page number
        $this->Cell(0, 10, 'Page '.$this->getAliasNumPage().'/'.$this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');
    }
}?> 