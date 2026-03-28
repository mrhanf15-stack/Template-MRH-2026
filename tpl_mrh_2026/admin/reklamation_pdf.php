<?php
/* -----------------------------------------------------------------------------------------
   Modified Shop 2.0.7 - PDF-Export für Reklamationen
   Datei: admin/reklamation_pdf.php
   
   PDF-Export-Funktionalität für einzelne Reklamationen
   ----------------------------------------------------------------------------------------- */

require('includes/application_top.php');

// Berechtigung prüfen
if (!isset($_SESSION['customer_id']) || $_SESSION['customer_id'] < 1) {
    xtc_redirect(xtc_href_link(FILENAME_LOGIN, '', 'SSL'));
}

$reklamation_id = (int)($_GET['id'] ?? 0);

if ($reklamation_id <= 0) {
    xtc_redirect(xtc_href_link('reklamationen.php'));
}

// Reklamationsdaten laden
$reklamation_query = xtc_db_query("SELECT * FROM reklamationen WHERE reklamation_id = '$reklamation_id'");
if (xtc_db_num_rows($reklamation_query) == 0) {
    xtc_redirect(xtc_href_link('reklamationen.php'));
}

$reklamation = xtc_db_fetch_array($reklamation_query);

// Bilder laden
$bilder_query = xtc_db_query("SELECT * FROM reklamation_bilder WHERE reklamation_id = '$reklamation_id' ORDER BY hochgeladen_am");
$bilder = array();
while ($bild = xtc_db_fetch_array($bilder_query)) {
    $bilder[] = $bild;
}

// E-Mail-Protokoll laden
$emails_query = xtc_db_query("SELECT * FROM reklamation_emails WHERE reklamation_id = '$reklamation_id' ORDER BY gesendet_am DESC");
$emails = array();
while ($email = xtc_db_fetch_array($emails_query)) {
    $emails[] = $email;
}

// PDF-Bibliothek laden
require_once(DIR_WS_CLASSES . 'fpdf/fpdf.php');

class ReklamationPDF extends FPDF {
    private $reklamation;
    
    public function __construct($reklamation) {
        parent::__construct();
        $this->reklamation = $reklamation;
    }
    
    // Header
    function Header() {
        $this->SetFont('Arial', 'B', 16);
        $this->Cell(0, 10, STORE_NAME, 0, 1, 'C');
        $this->SetFont('Arial', '', 12);
        $this->Cell(0, 10, 'Reklamationsbericht #' . $this->reklamation['reklamation_id'], 0, 1, 'C');
        $this->Ln(10);
    }
    
    // Footer
    function Footer() {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
        $this->Cell(0, 10, 'Seite ' . $this->PageNo() . ' - Erstellt am ' . date('d.m.Y H:i'), 0, 0, 'C');
    }
    
    // Sektion hinzufügen
    function AddSection($title, $content) {
        $this->SetFont('Arial', 'B', 14);
        $this->Cell(0, 10, $title, 0, 1);
        $this->SetFont('Arial', '', 10);
        
        if (is_array($content)) {
            foreach ($content as $key => $value) {
                $this->Cell(50, 6, $key . ':', 0, 0);
                $this->Cell(0, 6, $value, 0, 1);
            }
        } else {
            $this->MultiCell(0, 6, $content);
        }
        
        $this->Ln(5);
    }
    
    // Tabelle hinzufügen
    function AddTable($headers, $data) {
        $this->SetFont('Arial', 'B', 10);
        
        // Header
        $w = array(40, 40, 40, 70);
        for ($i = 0; $i < count($headers); $i++) {
            $this->Cell($w[$i], 7, $headers[$i], 1, 0, 'C');
        }
        $this->Ln();
        
        // Daten
        $this->SetFont('Arial', '', 9);
        foreach ($data as $row) {
            for ($i = 0; $i < count($row); $i++) {
                $this->Cell($w[$i], 6, $row[$i], 1, 0);
            }
            $this->Ln();
        }
        $this->Ln(5);
    }
}

// PDF erstellen
$pdf = new ReklamationPDF($reklamation);
$pdf->AddPage();

// Kundendaten
$kundendaten = array(
    'Name' => $reklamation['kunde_name'],
    'E-Mail' => $reklamation['kunde_email'],
    'Telefon' => $reklamation['kunde_telefon'] ?: '-',
    'Kundentyp' => $reklamation['ist_gast'] ? 'Gast' : 'Registrierter Kunde',
    'Eingereicht am' => date('d.m.Y H:i:s', strtotime($reklamation['erstellt_am'])),
    'Bestellnummer' => $reklamation['bestellnummer']
);

$pdf->AddSection('Kundendaten', $kundendaten);

// Produktdaten
if ($reklamation['produkt_name']) {
    $produktdaten = array(
        'Produkt' => $reklamation['produkt_name'],
        'Artikelnummer' => $reklamation['produkt_model'] ?: '-'
    );
    $pdf->AddSection('Produktinformationen', $produktdaten);
}

// Keimungsdaten
$keimungsdaten = array(
    'Keimungsmethode' => $reklamation['keimung_methode'] ?: '-',
    'Medium' => $reklamation['keimung_medium'] ?: '-',
    'Temperatur' => $reklamation['keimung_temperatur'] ?: '-',
    'Luftfeuchtigkeit' => $reklamation['keimung_luftfeuchtigkeit'] ?: '-',
    'Keimungsdauer' => $reklamation['keimung_dauer'] ?: '-',
    'Keimungsrate' => $reklamation['keimung_rate'] ?: '-',
    'Lichtbedingungen' => $reklamation['lichtbedingungen'] ?: '-',
    'Wassergabe' => $reklamation['wassergabe'] ?: '-'
);

$pdf->AddSection('Keimungsdaten', $keimungsdaten);

// Problembeschreibung
$pdf->AddSection('Problembeschreibung', $reklamation['problem_beschreibung']);

// Status und Bearbeitung
$status_text = array(
    'neu' => 'Neu',
    'in_bearbeitung' => 'In Bearbeitung',
    'abgeschlossen' => 'Abgeschlossen',
    'abgelehnt' => 'Abgelehnt'
);

$bearbeitungsdaten = array(
    'Status' => $status_text[$reklamation['status']],
    'Bearbeitet am' => $reklamation['bearbeitet_am'] ? date('d.m.Y H:i:s', strtotime($reklamation['bearbeitet_am'])) : '-',
    'Bearbeitet von' => $reklamation['bearbeitet_von'] ?: '-'
);

$pdf->AddSection('Status und Bearbeitung', $bearbeitungsdaten);

// Admin-Notizen
if ($reklamation['admin_notizen']) {
    $pdf->AddSection('Admin-Notizen', $reklamation['admin_notizen']);
}

// Bilder-Information
if (!empty($bilder)) {
    $pdf->SetFont('Arial', 'B', 14);
    $pdf->Cell(0, 10, 'Hochgeladene Bilder (' . count($bilder) . ')', 0, 1);
    
    $bild_data = array();
    foreach ($bilder as $bild) {
        $bild_data[] = array(
            $bild['original_name'],
            number_format($bild['dateigröße'] / 1024, 1) . ' KB',
            $bild['mime_type'],
            date('d.m.Y H:i', strtotime($bild['hochgeladen_am']))
        );
    }
    
    $pdf->AddTable(
        array('Dateiname', 'Größe', 'Typ', 'Hochgeladen'),
        $bild_data
    );
}

// E-Mail-Protokoll
if (!empty($emails)) {
    $pdf->SetFont('Arial', 'B', 14);
    $pdf->Cell(0, 10, 'E-Mail-Protokoll (' . count($emails) . ')', 0, 1);
    
    $email_data = array();
    foreach ($emails as $email) {
        $email_data[] = array(
            ucfirst(str_replace('_', ' ', $email['email_typ'])),
            $email['empfänger'],
            ucfirst($email['status']),
            date('d.m.Y H:i', strtotime($email['gesendet_am']))
        );
    }
    
    $pdf->AddTable(
        array('Typ', 'Empfänger', 'Status', 'Gesendet'),
        $email_data
    );
}

// PDF ausgeben
$filename = 'Reklamation_' . $reklamation_id . '_' . date('Y-m-d') . '.pdf';
$pdf->Output('D', $filename);

require('includes/application_bottom.php');
?>

