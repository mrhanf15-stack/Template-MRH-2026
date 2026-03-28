<?php
/* -----------------------------------------------------------------------------------------
   Modified Shop 2.0.7 - Admin Interface für Reklamationen
   Datei: admin/reklamationen.php
   
   Verwaltungsinterface für Reklamationen im Admin-Bereich
   ----------------------------------------------------------------------------------------- */

require('includes/application_top.php');

// Berechtigung prüfen
if (!isset($_SESSION['customer_id']) || $_SESSION['customer_id'] < 1) {
    xtc_redirect(xtc_href_link(FILENAME_LOGIN, '', 'SSL'));
}

// Aktionen verarbeiten
$action = $_GET['action'] ?? '';
$reklamation_id = (int)($_GET['id'] ?? 0);

switch ($action) {
    case 'edit':
        if ($reklamation_id > 0) {
            include('includes/reklamation_edit.php');
        } else {
            xtc_redirect(xtc_href_link('reklamationen.php'));
        }
        break;
        
    case 'update':
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && $reklamation_id > 0) {
            updateReklamation($reklamation_id);
        }
        xtc_redirect(xtc_href_link('reklamationen.php', 'action=edit&id=' . $reklamation_id));
        break;
        
    case 'delete':
        if ($reklamation_id > 0) {
            deleteReklamation($reklamation_id);
        }
        xtc_redirect(xtc_href_link('reklamationen.php'));
        break;
        
    case 'export':
        exportReklamationen();
        break;
        
    default:
        showReklamationsList();
        break;
}

// Reklamation aktualisieren
function updateReklamation($reklamation_id) {
    $status = xtc_db_input($_POST['status'] ?? '');
    $admin_notizen = xtc_db_input($_POST['admin_notizen'] ?? '');
    $bearbeitet_von = xtc_db_input($_SESSION['customer_firstname'] . ' ' . $_SESSION['customer_lastname']);
    
    $update_query = "UPDATE reklamationen SET 
                     status = '$status',
                     admin_notizen = '$admin_notizen',
                     bearbeitet_am = NOW(),
                     bearbeitet_von = '$bearbeitet_von'
                     WHERE reklamation_id = '$reklamation_id'";
    
    xtc_db_query($update_query);
    
    // Status-Update E-Mail an Kunden senden (optional)
    if (isset($_POST['send_status_email']) && $_POST['send_status_email'] == '1') {
        sendStatusUpdateEmail($reklamation_id, $status);
    }
}

// Reklamation löschen
function deleteReklamation($reklamation_id) {
    // Bilder löschen
    $images_query = xtc_db_query("SELECT dateipfad FROM reklamation_bilder WHERE reklamation_id = '$reklamation_id'");
    while ($image = xtc_db_fetch_array($images_query)) {
        if (file_exists($image['dateipfad'])) {
            unlink($image['dateipfad']);
        }
    }
    
    // Datenbank-Einträge löschen
    xtc_db_query("DELETE FROM reklamation_bilder WHERE reklamation_id = '$reklamation_id'");
    xtc_db_query("DELETE FROM reklamation_emails WHERE reklamation_id = '$reklamation_id'");
    xtc_db_query("DELETE FROM reklamationen WHERE reklamation_id = '$reklamation_id'");
}

// Status-Update E-Mail senden
function sendStatusUpdateEmail($reklamation_id, $new_status) {
    // Reklamationsdaten laden
    $reklamation_query = xtc_db_query("SELECT * FROM reklamationen WHERE reklamation_id = '$reklamation_id'");
    $reklamation = xtc_db_fetch_array($reklamation_query);
    
    if (!$reklamation) return;
    
    require_once(DIR_WS_CLASSES . 'class.phpmailer.php');
    
    $mail = new PHPMailer();
    $mail->CharSet = 'UTF-8';
    
    $status_text = array(
        'neu' => 'Neu',
        'in_bearbeitung' => 'In Bearbeitung',
        'abgeschlossen' => 'Abgeschlossen',
        'abgelehnt' => 'Abgelehnt'
    );
    
    $betreff = "Status-Update zu Ihrer Reklamation #{$reklamation_id}";
    
    $nachricht = "
    <html>
    <head><title>$betreff</title></head>
    <body>
        <h2>Status-Update zu Ihrer Reklamation</h2>
        <p>Der Status Ihrer Reklamation #{$reklamation_id} wurde aktualisiert.</p>
        
        <table border='1' cellpadding='5' cellspacing='0'>
            <tr><td><strong>Neuer Status:</strong></td><td>{$status_text[$new_status]}</td></tr>
            <tr><td><strong>Bestellnummer:</strong></td><td>{$reklamation['bestellnummer']}</td></tr>
            <tr><td><strong>Eingereicht am:</strong></td><td>{$reklamation['erstellt_am']}</td></tr>
        </table>
        
        " . (!empty($reklamation['admin_notizen']) ? "<h3>Notizen:</h3><p>" . nl2br(htmlspecialchars($reklamation['admin_notizen'])) . "</p>" : "") . "
        
        <p>Bei Fragen können Sie sich jederzeit an uns wenden.</p>
        
        <p>Mit freundlichen Grüßen<br>
        Ihr Team von " . STORE_NAME . "</p>
    </body>
    </html>";
    
    $admin_email = getReklamationConfig('admin_email');
    
    $mail->From = $admin_email;
    $mail->FromName = STORE_NAME;
    $mail->AddAddress($reklamation['kunde_email'], $reklamation['kunde_name']);
    $mail->Subject = $betreff;
    $mail->Body = $nachricht;
    $mail->IsHTML(true);
    
    $mail->Send();
    
    // E-Mail-Protokoll
    $insert_log = "INSERT INTO reklamation_emails (reklamation_id, empfänger, betreff, email_typ, status) 
                   VALUES ('$reklamation_id', '{$reklamation['kunde_email']}', '$betreff', 'status_update', 'gesendet')";
    xtc_db_query($insert_log);
}

// Reklamationsliste anzeigen
function showReklamationsList() {
    // Filter und Pagination
    $filter_status = $_GET['filter_status'] ?? '';
    $search = $_GET['search'] ?? '';
    $page = max(1, (int)($_GET['page'] ?? 1));
    $per_page = 20;
    $offset = ($page - 1) * $per_page;
    
    // WHERE-Bedingungen aufbauen
    $where_conditions = array();
    
    if (!empty($filter_status)) {
        $where_conditions[] = "r.status = '" . xtc_db_input($filter_status) . "'";
    }
    
    if (!empty($search)) {
        $search_term = xtc_db_input($search);
        $where_conditions[] = "(r.bestellnummer LIKE '%$search_term%' OR 
                              r.kunde_name LIKE '%$search_term%' OR 
                              r.kunde_email LIKE '%$search_term%' OR 
                              r.problem_beschreibung LIKE '%$search_term%')";
    }
    
    $where_clause = !empty($where_conditions) ? 'WHERE ' . implode(' AND ', $where_conditions) : '';
    
    // Gesamtanzahl ermitteln
    $count_query = xtc_db_query("SELECT COUNT(*) as total FROM reklamationen r $where_clause");
    $count_result = xtc_db_fetch_array($count_query);
    $total_records = $count_result['total'];
    $total_pages = ceil($total_records / $per_page);
    
    // Reklamationen laden
    $reklamationen_query = xtc_db_query("
        SELECT 
            r.*,
            (SELECT COUNT(*) FROM reklamation_bilder rb WHERE rb.reklamation_id = r.reklamation_id) as anzahl_bilder
        FROM reklamationen r 
        $where_clause
        ORDER BY r.erstellt_am DESC 
        LIMIT $offset, $per_page
    ");
    
    $reklamationen = array();
    while ($reklamation = xtc_db_fetch_array($reklamationen_query)) {
        $reklamationen[] = $reklamation;
    }
    
    // Statistiken
    $stats_query = xtc_db_query("
        SELECT 
            status,
            COUNT(*) as anzahl
        FROM reklamationen 
        GROUP BY status
    ");
    
    $statistics = array();
    while ($stat = xtc_db_fetch_array($stats_query)) {
        $statistics[$stat['status']] = $stat['anzahl'];
    }
    
    // Template-Variablen
    include('includes/header.php');
    ?>
    
    <div class="admin-content">
        <div class="page-header">
            <h1>Reklamationsverwaltung</h1>
            <div class="header-actions">
                <a href="?action=export" class="btn btn-secondary">
                    <i class="icon-download"></i> Export
                </a>
            </div>
        </div>
        
        <!-- Statistiken -->
        <div class="stats-row">
            <div class="stat-card">
                <div class="stat-number"><?= $statistics['neu'] ?? 0 ?></div>
                <div class="stat-label">Neue Reklamationen</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?= $statistics['in_bearbeitung'] ?? 0 ?></div>
                <div class="stat-label">In Bearbeitung</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?= $statistics['abgeschlossen'] ?? 0 ?></div>
                <div class="stat-label">Abgeschlossen</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?= $total_records ?></div>
                <div class="stat-label">Gesamt</div>
            </div>
        </div>
        
        <!-- Filter und Suche -->
        <div class="filter-section">
            <form method="GET" class="filter-form">
                <div class="filter-group">
                    <label>Status:</label>
                    <select name="filter_status">
                        <option value="">Alle Status</option>
                        <option value="neu" <?= $filter_status == 'neu' ? 'selected' : '' ?>>Neu</option>
                        <option value="in_bearbeitung" <?= $filter_status == 'in_bearbeitung' ? 'selected' : '' ?>>In Bearbeitung</option>
                        <option value="abgeschlossen" <?= $filter_status == 'abgeschlossen' ? 'selected' : '' ?>>Abgeschlossen</option>
                        <option value="abgelehnt" <?= $filter_status == 'abgelehnt' ? 'selected' : '' ?>>Abgelehnt</option>
                    </select>
                </div>
                <div class="filter-group">
                    <label>Suche:</label>
                    <input type="text" name="search" value="<?= htmlspecialchars($search) ?>" placeholder="Bestellnummer, Name, E-Mail...">
                </div>
                <div class="filter-group">
                    <button type="submit" class="btn btn-primary">Filtern</button>
                    <a href="reklamationen.php" class="btn btn-secondary">Zurücksetzen</a>
                </div>
            </form>
        </div>
        
        <!-- Reklamationsliste -->
        <div class="table-container">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Datum</th>
                        <th>Kunde</th>
                        <th>Bestellung</th>
                        <th>Produkt</th>
                        <th>Status</th>
                        <th>Bilder</th>
                        <th>Aktionen</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($reklamationen as $reklamation): ?>
                    <tr>
                        <td><?= $reklamation['reklamation_id'] ?></td>
                        <td><?= date('d.m.Y H:i', strtotime($reklamation['erstellt_am'])) ?></td>
                        <td>
                            <strong><?= htmlspecialchars($reklamation['kunde_name']) ?></strong><br>
                            <small><?= htmlspecialchars($reklamation['kunde_email']) ?></small>
                        </td>
                        <td><?= htmlspecialchars($reklamation['bestellnummer']) ?></td>
                        <td><?= htmlspecialchars($reklamation['produkt_name']) ?></td>
                        <td>
                            <span class="status-badge status-<?= $reklamation['status'] ?>">
                                <?= ucfirst(str_replace('_', ' ', $reklamation['status'])) ?>
                            </span>
                        </td>
                        <td>
                            <?php if ($reklamation['anzahl_bilder'] > 0): ?>
                                <i class="icon-image"></i> <?= $reklamation['anzahl_bilder'] ?>
                            <?php else: ?>
                                -
                            <?php endif; ?>
                        </td>
                        <td class="actions">
                            <a href="?action=edit&id=<?= $reklamation['reklamation_id'] ?>" class="btn btn-sm btn-primary" title="Bearbeiten">
                                <i class="icon-edit"></i>
                            </a>
                            <a href="?action=delete&id=<?= $reklamation['reklamation_id'] ?>" 
                               class="btn btn-sm btn-danger" title="Löschen"
                               onclick="return confirm('Reklamation wirklich löschen?')">
                                <i class="icon-delete"></i>
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <?php if ($total_pages > 1): ?>
        <div class="pagination">
            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                <a href="?page=<?= $i ?>&filter_status=<?= urlencode($filter_status) ?>&search=<?= urlencode($search) ?>" 
                   class="page-link <?= $i == $page ? 'active' : '' ?>">
                    <?= $i ?>
                </a>
            <?php endfor; ?>
        </div>
        <?php endif; ?>
    </div>
    
    <link rel="stylesheet" href="includes/css/reklamation_admin.css">
    
    <?php
    include('includes/footer.php');
}

// Export-Funktion
function exportReklamationen() {
    $format = $_GET['format'] ?? 'csv';
    
    if ($format === 'csv') {
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="reklamationen_' . date('Y-m-d') . '.csv"');
        
        $output = fopen('php://output', 'w');
        
        // CSV-Header
        fputcsv($output, array(
            'ID', 'Datum', 'Kunde', 'E-Mail', 'Telefon', 'Bestellnummer', 'Produkt',
            'Keimungsmethode', 'Medium', 'Temperatur', 'Luftfeuchtigkeit', 'Dauer', 'Rate',
            'Lichtbedingungen', 'Wassergabe', 'Problembeschreibung', 'Status', 'Admin-Notizen'
        ));
        
        // Daten exportieren
        $export_query = xtc_db_query("SELECT * FROM reklamationen ORDER BY erstellt_am DESC");
        while ($row = xtc_db_fetch_array($export_query)) {
            fputcsv($output, array(
                $row['reklamation_id'],
                $row['erstellt_am'],
                $row['kunde_name'],
                $row['kunde_email'],
                $row['kunde_telefon'],
                $row['bestellnummer'],
                $row['produkt_name'],
                $row['keimung_methode'],
                $row['keimung_medium'],
                $row['keimung_temperatur'],
                $row['keimung_luftfeuchtigkeit'],
                $row['keimung_dauer'],
                $row['keimung_rate'],
                $row['lichtbedingungen'],
                $row['wassergabe'],
                $row['problem_beschreibung'],
                $row['status'],
                $row['admin_notizen']
            ));
        }
        
        fclose($output);
        exit;
    }
}

// Hilfsfunktion für Konfiguration
function getReklamationConfig($key) {
    $query = xtc_db_query("SELECT config_value FROM reklamation_config WHERE config_key = '" . xtc_db_input($key) . "'");
    $result = xtc_db_fetch_array($query);
    return $result ? $result['config_value'] : null;
}

require('includes/application_bottom.php');
?>

