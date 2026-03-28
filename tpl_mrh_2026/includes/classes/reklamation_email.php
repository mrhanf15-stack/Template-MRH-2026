<?php
/* -----------------------------------------------------------------------------------------
   Modified Shop 2.0.7 - E-Mail-Template-System für Reklamationen
   Datei: includes/classes/reklamation_email.php
   
   Erweiterte E-Mail-Funktionalität mit Templates und Anhängen
   ----------------------------------------------------------------------------------------- */

class ReklamationEmail {
    private $config;
    private $mailer;
    
    public function __construct() {
        $this->loadConfig();
        $this->initMailer();
    }
    
    // Konfiguration laden
    private function loadConfig() {
        $this->config = array();
        $config_query = xtc_db_query("SELECT config_key, config_value FROM reklamation_config");
        while ($row = xtc_db_fetch_array($config_query)) {
            $this->config[$row['config_key']] = $row['config_value'];
        }
    }
    
    // PHPMailer initialisieren
    private function initMailer() {
        require_once(DIR_WS_CLASSES . 'class.phpmailer.php');
        $this->mailer = new PHPMailer();
        $this->mailer->CharSet = 'UTF-8';
        $this->mailer->From = $this->config['admin_email'];
        $this->mailer->FromName = STORE_NAME;
        $this->mailer->IsHTML(true);
    }
    
    // Bestätigungs-E-Mail an Kunden senden
    public function sendCustomerConfirmation($reklamation_id, $reklamation_data) {
        $template_data = array_merge($reklamation_data, array(
            'reklamation_id' => $reklamation_id,
            'store_name' => STORE_NAME,
            'admin_email' => $this->config['admin_email'],
            'datum' => date('d.m.Y H:i:s')
        ));
        
        $betreff = $this->processTemplate($this->config['email_betreff_kunde'], $template_data);
        $nachricht = $this->getCustomerConfirmationTemplate($template_data);
        
        $this->mailer->ClearAddresses();
        $this->mailer->AddAddress($reklamation_data['kunde_email'], $reklamation_data['kunde_name']);
        $this->mailer->Subject = $betreff;
        $this->mailer->Body = $nachricht;
        
        $success = $this->mailer->Send();
        $this->logEmail($reklamation_id, $reklamation_data['kunde_email'], $betreff, 'bestätigung', $success);
        
        return $success;
    }
    
    // Admin-Benachrichtigung senden
    public function sendAdminNotification($reklamation_id, $reklamation_data) {
        $template_data = array_merge($reklamation_data, array(
            'reklamation_id' => $reklamation_id,
            'store_name' => STORE_NAME,
            'admin_url' => HTTP_SERVER . DIR_WS_ADMIN . 'reklamationen.php?action=edit&id=' . $reklamation_id,
            'datum' => date('d.m.Y H:i:s')
        ));
        
        $betreff = $this->processTemplate($this->config['email_betreff_admin'], $template_data);
        $nachricht = $this->getAdminNotificationTemplate($template_data);
        
        $this->mailer->ClearAddresses();
        $this->mailer->AddAddress($this->config['admin_email']);
        $this->mailer->Subject = $betreff;
        $this->mailer->Body = $nachricht;
        
        $success = $this->mailer->Send();
        $this->logEmail($reklamation_id, $this->config['admin_email'], $betreff, 'admin_benachrichtigung', $success);
        
        return $success;
    }
    
    // Status-Update E-Mail senden
    public function sendStatusUpdate($reklamation_id, $reklamation_data, $new_status, $admin_notizen = '') {
        $status_text = array(
            'neu' => 'Neu',
            'in_bearbeitung' => 'In Bearbeitung',
            'abgeschlossen' => 'Abgeschlossen',
            'abgelehnt' => 'Abgelehnt'
        );
        
        $template_data = array_merge($reklamation_data, array(
            'reklamation_id' => $reklamation_id,
            'store_name' => STORE_NAME,
            'new_status' => $status_text[$new_status],
            'admin_notizen' => $admin_notizen,
            'datum' => date('d.m.Y H:i:s')
        ));
        
        $betreff = "Status-Update zu Ihrer Reklamation #$reklamation_id";
        $nachricht = $this->getStatusUpdateTemplate($template_data);
        
        $this->mailer->ClearAddresses();
        $this->mailer->AddAddress($reklamation_data['kunde_email'], $reklamation_data['kunde_name']);
        $this->mailer->Subject = $betreff;
        $this->mailer->Body = $nachricht;
        
        $success = $this->mailer->Send();
        $this->logEmail($reklamation_id, $reklamation_data['kunde_email'], $betreff, 'status_update', $success);
        
        return $success;
    }
    
    // E-Mail mit PDF-Anhang senden
    public function sendWithPDFAttachment($reklamation_id, $recipient_email, $recipient_name, $pdf_path) {
        $betreff = "Reklamationsbericht #$reklamation_id";
        $nachricht = $this->getPDFAttachmentTemplate(array(
            'reklamation_id' => $reklamation_id,
            'store_name' => STORE_NAME,
            'recipient_name' => $recipient_name
        ));
        
        $this->mailer->ClearAddresses();
        $this->mailer->ClearAttachments();
        $this->mailer->AddAddress($recipient_email, $recipient_name);
        $this->mailer->AddAttachment($pdf_path, "Reklamation_$reklamation_id.pdf");
        $this->mailer->Subject = $betreff;
        $this->mailer->Body = $nachricht;
        
        $success = $this->mailer->Send();
        $this->logEmail($reklamation_id, $recipient_email, $betreff, 'pdf_anhang', $success);
        
        return $success;
    }
    
    // Template-Variablen ersetzen
    private function processTemplate($template, $data) {
        foreach ($data as $key => $value) {
            $template = str_replace('{' . $key . '}', $value, $template);
        }
        return $template;
    }
    
    // Kunden-Bestätigungs-Template
    private function getCustomerConfirmationTemplate($data) {
        return "
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset='UTF-8'>
            <title>Reklamationsbestätigung</title>
            <style>
                body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                .header { background: #f8f9fa; padding: 20px; text-align: center; border-radius: 8px; margin-bottom: 20px; }
                .content { background: white; padding: 20px; border: 1px solid #e0e0e0; border-radius: 8px; }
                .table { width: 100%; border-collapse: collapse; margin: 15px 0; }
                .table th, .table td { padding: 10px; border: 1px solid #ddd; text-align: left; }
                .table th { background: #f8f9fa; font-weight: bold; }
                .highlight { background: #e8f5e8; padding: 15px; border-radius: 6px; margin: 15px 0; }
                .footer { margin-top: 30px; padding-top: 20px; border-top: 1px solid #e0e0e0; font-size: 14px; color: #666; }
            </style>
        </head>
        <body>
            <div class='container'>
                <div class='header'>
                    <h1>{$data['store_name']}</h1>
                    <h2>Bestätigung Ihrer Reklamation</h2>
                </div>
                
                <div class='content'>
                    <div class='highlight'>
                        <h3>Ihre Reklamationsnummer: #{$data['reklamation_id']}</h3>
                        <p>Bitte notieren Sie sich diese Nummer für Ihre Unterlagen.</p>
                    </div>
                    
                    <p>Liebe/r {$data['kunde_name']},</p>
                    
                    <p>vielen Dank für Ihre Reklamation. Wir haben Ihre Anfrage erhalten und werden sie schnellstmöglich bearbeiten.</p>
                    
                    <h3>Zusammenfassung Ihrer Reklamation:</h3>
                    <table class='table'>
                        <tr><th>Bestellnummer</th><td>{$data['bestellnummer']}</td></tr>
                        <tr><th>Produkt</th><td>{$data['produkt_name']}</td></tr>
                        <tr><th>Eingereicht am</th><td>{$data['datum']}</td></tr>
                        <tr><th>Status</th><td>Neu</td></tr>
                    </table>
                    
                    <h3>Keimungsdaten:</h3>
                    <table class='table'>
                        <tr><th>Methode</th><td>{$data['keimung_methode']}</td></tr>
                        <tr><th>Medium</th><td>{$data['keimung_medium']}</td></tr>
                        <tr><th>Temperatur</th><td>{$data['keimung_temperatur']}</td></tr>
                        <tr><th>Luftfeuchtigkeit</th><td>{$data['keimung_luftfeuchtigkeit']}</td></tr>
                    </table>
                    
                    <h3>Wie geht es weiter?</h3>
                    <ul>
                        <li>Wir prüfen Ihre Reklamation innerhalb von 2-3 Werktagen</li>
                        <li>Bei Rückfragen melden wir uns per E-Mail oder Telefon</li>
                        <li>Sie erhalten eine Benachrichtigung über den Bearbeitungsstand</li>
                    </ul>
                    
                    <p>Bei dringenden Fragen können Sie uns unter <a href='mailto:{$data['admin_email']}'>{$data['admin_email']}</a> kontaktieren.</p>
                </div>
                
                <div class='footer'>
                    <p>Mit freundlichen Grüßen<br>
                    Ihr Team von {$data['store_name']}</p>
                    
                    <p><small>Diese E-Mail wurde automatisch generiert. Bitte antworten Sie nicht direkt auf diese E-Mail.</small></p>
                </div>
            </div>
        </body>
        </html>";
    }
    
    // Admin-Benachrichtigungs-Template
    private function getAdminNotificationTemplate($data) {
        return "
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset='UTF-8'>
            <title>Neue Reklamation</title>
            <style>
                body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                .container { max-width: 700px; margin: 0 auto; padding: 20px; }
                .header { background: #fff3cd; padding: 20px; text-align: center; border-radius: 8px; margin-bottom: 20px; border: 1px solid #ffeaa7; }
                .content { background: white; padding: 20px; border: 1px solid #e0e0e0; border-radius: 8px; }
                .table { width: 100%; border-collapse: collapse; margin: 15px 0; }
                .table th, .table td { padding: 8px 12px; border: 1px solid #ddd; text-align: left; }
                .table th { background: #f8f9fa; font-weight: bold; }
                .urgent { background: #f8d7da; padding: 15px; border-radius: 6px; margin: 15px 0; border: 1px solid #f5c6cb; }
                .action-button { display: inline-block; background: #007bff; color: white; padding: 12px 24px; text-decoration: none; border-radius: 6px; margin: 10px 0; }
                .problem-text { background: #f8f9fa; padding: 15px; border-radius: 6px; border-left: 4px solid #007bff; margin: 15px 0; }
            </style>
        </head>
        <body>
            <div class='container'>
                <div class='header'>
                    <h1>⚠️ Neue Reklamation eingegangen</h1>
                    <h2>Reklamations-ID: #{$data['reklamation_id']}</h2>
                </div>
                
                <div class='content'>
                    <div class='urgent'>
                        <strong>Aktion erforderlich:</strong> Eine neue Reklamation wartet auf Bearbeitung.
                    </div>
                    
                    <h3>Kundendaten:</h3>
                    <table class='table'>
                        <tr><th>Name</th><td>{$data['kunde_name']}</td></tr>
                        <tr><th>E-Mail</th><td><a href='mailto:{$data['kunde_email']}'>{$data['kunde_email']}</a></td></tr>
                        <tr><th>Telefon</th><td>{$data['kunde_telefon']}</td></tr>
                        <tr><th>Kundentyp</th><td>" . ($data['ist_gast'] ? 'Gast' : 'Registrierter Kunde') . "</td></tr>
                        <tr><th>Bestellnummer</th><td>{$data['bestellnummer']}</td></tr>
                        <tr><th>Eingereicht am</th><td>{$data['datum']}</td></tr>
                    </table>
                    
                    <h3>Produktinformationen:</h3>
                    <table class='table'>
                        <tr><th>Produkt</th><td>{$data['produkt_name']}</td></tr>
                        <tr><th>Artikelnummer</th><td>{$data['produkt_model']}</td></tr>
                    </table>
                    
                    <h3>Keimungsdaten:</h3>
                    <table class='table'>
                        <tr><th>Methode</th><td>{$data['keimung_methode']}</td></tr>
                        <tr><th>Medium</th><td>{$data['keimung_medium']}</td></tr>
                        <tr><th>Temperatur</th><td>{$data['keimung_temperatur']}</td></tr>
                        <tr><th>Luftfeuchtigkeit</th><td>{$data['keimung_luftfeuchtigkeit']}</td></tr>
                        <tr><th>Dauer</th><td>{$data['keimung_dauer']}</td></tr>
                        <tr><th>Rate</th><td>{$data['keimung_rate']}</td></tr>
                        <tr><th>Lichtbedingungen</th><td>{$data['lichtbedingungen']}</td></tr>
                        <tr><th>Wassergabe</th><td>{$data['wassergabe']}</td></tr>
                    </table>
                    
                    <h3>Problembeschreibung:</h3>
                    <div class='problem-text'>
                        " . nl2br(htmlspecialchars($data['problem_beschreibung'])) . "
                    </div>
                    
                    <div style='text-align: center; margin: 30px 0;'>
                        <a href='{$data['admin_url']}' class='action-button'>
                            🔧 Reklamation im Admin-Bereich bearbeiten
                        </a>
                    </div>
                </div>
            </div>
        </body>
        </html>";
    }
    
    // Status-Update-Template
    private function getStatusUpdateTemplate($data) {
        return "
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset='UTF-8'>
            <title>Status-Update</title>
            <style>
                body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                .header { background: #e3f2fd; padding: 20px; text-align: center; border-radius: 8px; margin-bottom: 20px; }
                .content { background: white; padding: 20px; border: 1px solid #e0e0e0; border-radius: 8px; }
                .status-update { background: #e8f5e8; padding: 15px; border-radius: 6px; margin: 15px 0; text-align: center; }
                .table { width: 100%; border-collapse: collapse; margin: 15px 0; }
                .table th, .table td { padding: 10px; border: 1px solid #ddd; text-align: left; }
                .table th { background: #f8f9fa; font-weight: bold; }
                .notes { background: #f8f9fa; padding: 15px; border-radius: 6px; border-left: 4px solid #007bff; margin: 15px 0; }
            </style>
        </head>
        <body>
            <div class='container'>
                <div class='header'>
                    <h1>{$data['store_name']}</h1>
                    <h2>Status-Update zu Ihrer Reklamation</h2>
                </div>
                
                <div class='content'>
                    <p>Liebe/r {$data['kunde_name']},</p>
                    
                    <p>der Status Ihrer Reklamation #{$data['reklamation_id']} wurde aktualisiert.</p>
                    
                    <div class='status-update'>
                        <h3>Neuer Status: {$data['new_status']}</h3>
                    </div>
                    
                    <table class='table'>
                        <tr><th>Reklamations-ID</th><td>#{$data['reklamation_id']}</td></tr>
                        <tr><th>Bestellnummer</th><td>{$data['bestellnummer']}</td></tr>
                        <tr><th>Produkt</th><td>{$data['produkt_name']}</td></tr>
                        <tr><th>Status aktualisiert am</th><td>{$data['datum']}</td></tr>
                    </table>
                    
                    " . (!empty($data['admin_notizen']) ? "
                    <h3>Notizen zur Bearbeitung:</h3>
                    <div class='notes'>
                        " . nl2br(htmlspecialchars($data['admin_notizen'])) . "
                    </div>
                    " : "") . "
                    
                    <p>Bei weiteren Fragen können Sie sich jederzeit an uns wenden.</p>
                    
                    <p>Mit freundlichen Grüßen<br>
                    Ihr Team von {$data['store_name']}</p>
                </div>
            </div>
        </body>
        </html>";
    }
    
    // PDF-Anhang-Template
    private function getPDFAttachmentTemplate($data) {
        return "
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset='UTF-8'>
            <title>Reklamationsbericht</title>
            <style>
                body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                .header { background: #f8f9fa; padding: 20px; text-align: center; border-radius: 8px; margin-bottom: 20px; }
                .content { background: white; padding: 20px; border: 1px solid #e0e0e0; border-radius: 8px; }
                .attachment-info { background: #e3f2fd; padding: 15px; border-radius: 6px; margin: 15px 0; text-align: center; }
            </style>
        </head>
        <body>
            <div class='container'>
                <div class='header'>
                    <h1>{$data['store_name']}</h1>
                    <h2>Reklamationsbericht</h2>
                </div>
                
                <div class='content'>
                    <p>Liebe/r {$data['recipient_name']},</p>
                    
                    <p>anbei erhalten Sie den detaillierten Bericht zu Reklamation #{$data['reklamation_id']}.</p>
                    
                    <div class='attachment-info'>
                        <h3>📎 PDF-Anhang</h3>
                        <p>Der vollständige Reklamationsbericht ist als PDF-Datei angehängt.</p>
                    </div>
                    
                    <p>Der Bericht enthält alle relevanten Informationen einschließlich:</p>
                    <ul>
                        <li>Kundendaten und Kontaktinformationen</li>
                        <li>Produktinformationen</li>
                        <li>Detaillierte Keimungsdaten</li>
                        <li>Problembeschreibung</li>
                        <li>Bearbeitungsstatus und Notizen</li>
                        <li>E-Mail-Protokoll</li>
                        <li>Informationen zu hochgeladenen Bildern</li>
                    </ul>
                    
                    <p>Bei Fragen stehen wir Ihnen gerne zur Verfügung.</p>
                    
                    <p>Mit freundlichen Grüßen<br>
                    Ihr Team von {$data['store_name']}</p>
                </div>
            </div>
        </body>
        </html>";
    }
    
    // E-Mail-Protokoll
    private function logEmail($reklamation_id, $empfänger, $betreff, $typ, $success) {
        $status = $success ? 'gesendet' : 'fehler';
        $insert_log = "INSERT INTO reklamation_emails (reklamation_id, empfänger, betreff, email_typ, status) 
                       VALUES ('" . (int)$reklamation_id . "', '" . xtc_db_input($empfänger) . "', 
                              '" . xtc_db_input($betreff) . "', '" . xtc_db_input($typ) . "', '$status')";
        xtc_db_query($insert_log);
    }
}
?>

