<?php
/* -----------------------------------------------------------------------------------------
   Modified Shop 2.0.7 - Reklamation Bearbeitung
   Datei: admin/includes/reklamation_edit.php
   
   Detailansicht und Bearbeitung einer einzelnen Reklamation
   ----------------------------------------------------------------------------------------- */

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

include('includes/header.php');
?>

<div class="admin-content">
    <div class="page-header">
        <h1>Reklamation #<?= $reklamation['reklamation_id'] ?> bearbeiten</h1>
        <div class="header-actions">
            <a href="reklamationen.php" class="btn btn-secondary">
                <i class="icon-back"></i> Zurück zur Liste
            </a>
            <a href="?action=delete&id=<?= $reklamation_id ?>" class="btn btn-danger" 
               onclick="return confirm('Reklamation wirklich löschen?')">
                <i class="icon-delete"></i> Löschen
            </a>
        </div>
    </div>

    <div class="edit-container">
        <!-- Kundendaten -->
        <div class="info-section">
            <h2>Kundendaten</h2>
            <div class="info-grid">
                <div class="info-item">
                    <label>Name:</label>
                    <span><?= htmlspecialchars($reklamation['kunde_name']) ?></span>
                </div>
                <div class="info-item">
                    <label>E-Mail:</label>
                    <span><a href="mailto:<?= htmlspecialchars($reklamation['kunde_email']) ?>"><?= htmlspecialchars($reklamation['kunde_email']) ?></a></span>
                </div>
                <div class="info-item">
                    <label>Telefon:</label>
                    <span><?= htmlspecialchars($reklamation['kunde_telefon']) ?: '-' ?></span>
                </div>
                <div class="info-item">
                    <label>Kundentyp:</label>
                    <span><?= $reklamation['ist_gast'] ? 'Gast' : 'Registrierter Kunde' ?></span>
                </div>
                <div class="info-item">
                    <label>Eingereicht am:</label>
                    <span><?= date('d.m.Y H:i:s', strtotime($reklamation['erstellt_am'])) ?></span>
                </div>
                <div class="info-item">
                    <label>Bestellnummer:</label>
                    <span><?= htmlspecialchars($reklamation['bestellnummer']) ?></span>
                </div>
            </div>
        </div>

        <!-- Produktdaten -->
        <div class="info-section">
            <h2>Produktinformationen</h2>
            <div class="info-grid">
                <div class="info-item">
                    <label>Produkt:</label>
                    <span><?= htmlspecialchars($reklamation['produkt_name']) ?: '-' ?></span>
                </div>
                <div class="info-item">
                    <label>Artikelnummer:</label>
                    <span><?= htmlspecialchars($reklamation['produkt_model']) ?: '-' ?></span>
                </div>
            </div>
        </div>

        <!-- Keimungsdaten -->
        <div class="info-section">
            <h2>Keimungsdaten</h2>
            <div class="info-grid">
                <div class="info-item">
                    <label>Keimungsmethode:</label>
                    <span><?= htmlspecialchars($reklamation['keimung_methode']) ?: '-' ?></span>
                </div>
                <div class="info-item">
                    <label>Medium:</label>
                    <span><?= htmlspecialchars($reklamation['keimung_medium']) ?: '-' ?></span>
                </div>
                <div class="info-item">
                    <label>Temperatur:</label>
                    <span><?= htmlspecialchars($reklamation['keimung_temperatur']) ?: '-' ?></span>
                </div>
                <div class="info-item">
                    <label>Luftfeuchtigkeit:</label>
                    <span><?= htmlspecialchars($reklamation['keimung_luftfeuchtigkeit']) ?: '-' ?></span>
                </div>
                <div class="info-item">
                    <label>Keimungsdauer:</label>
                    <span><?= htmlspecialchars($reklamation['keimung_dauer']) ?: '-' ?></span>
                </div>
                <div class="info-item">
                    <label>Keimungsrate:</label>
                    <span><?= htmlspecialchars($reklamation['keimung_rate']) ?: '-' ?></span>
                </div>
                <div class="info-item">
                    <label>Lichtbedingungen:</label>
                    <span><?= htmlspecialchars($reklamation['lichtbedingungen']) ?: '-' ?></span>
                </div>
                <div class="info-item">
                    <label>Wassergabe:</label>
                    <span><?= htmlspecialchars($reklamation['wassergabe']) ?: '-' ?></span>
                </div>
            </div>
        </div>

        <!-- Problembeschreibung -->
        <div class="info-section">
            <h2>Problembeschreibung</h2>
            <div class="problem-description">
                <?= nl2br(htmlspecialchars($reklamation['problem_beschreibung'])) ?>
            </div>
        </div>

        <!-- Bilder -->
        <?php if (!empty($bilder)): ?>
        <div class="info-section">
            <h2>Hochgeladene Bilder (<?= count($bilder) ?>)</h2>
            <div class="image-gallery">
                <?php foreach ($bilder as $bild): ?>
                <div class="image-item">
                    <div class="image-preview">
                        <?php 
                        $image_url = str_replace(DIR_FS_CATALOG, HTTP_SERVER . DIR_WS_CATALOG, $bild['dateipfad']);
                        ?>
                        <img src="<?= $image_url ?>" alt="<?= htmlspecialchars($bild['original_name']) ?>" 
                             onclick="openImageModal('<?= $image_url ?>', '<?= htmlspecialchars($bild['original_name']) ?>')">
                    </div>
                    <div class="image-info">
                        <div class="image-name"><?= htmlspecialchars($bild['original_name']) ?></div>
                        <div class="image-details">
                            <?= number_format($bild['dateigröße'] / 1024, 1) ?> KB | 
                            <?= date('d.m.Y H:i', strtotime($bild['hochgeladen_am'])) ?>
                        </div>
                        <div class="image-actions">
                            <a href="<?= $image_url ?>" target="_blank" class="btn btn-sm btn-primary">Öffnen</a>
                            <a href="<?= $image_url ?>" download="<?= $bild['original_name'] ?>" class="btn btn-sm btn-secondary">Download</a>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>

        <!-- Status und Bearbeitung -->
        <div class="info-section">
            <h2>Status und Bearbeitung</h2>
            <form method="POST" action="?action=update&id=<?= $reklamation_id ?>" class="edit-form">
                <div class="form-row">
                    <label for="status">Status:</label>
                    <select name="status" id="status" class="form-control">
                        <option value="neu" <?= $reklamation['status'] == 'neu' ? 'selected' : '' ?>>Neu</option>
                        <option value="in_bearbeitung" <?= $reklamation['status'] == 'in_bearbeitung' ? 'selected' : '' ?>>In Bearbeitung</option>
                        <option value="abgeschlossen" <?= $reklamation['status'] == 'abgeschlossen' ? 'selected' : '' ?>>Abgeschlossen</option>
                        <option value="abgelehnt" <?= $reklamation['status'] == 'abgelehnt' ? 'selected' : '' ?>>Abgelehnt</option>
                    </select>
                </div>

                <div class="form-row">
                    <label for="admin_notizen">Admin-Notizen:</label>
                    <textarea name="admin_notizen" id="admin_notizen" rows="6" class="form-control" 
                              placeholder="Interne Notizen zur Bearbeitung..."><?= htmlspecialchars($reklamation['admin_notizen']) ?></textarea>
                </div>

                <div class="form-row">
                    <label class="checkbox-label">
                        <input type="checkbox" name="send_status_email" value="1">
                        <span class="checkmark"></span>
                        Status-Update per E-Mail an Kunden senden
                    </label>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">
                        <i class="icon-save"></i> Speichern
                    </button>
                </div>
            </form>

            <?php if ($reklamation['bearbeitet_am']): ?>
            <div class="edit-history">
                <strong>Zuletzt bearbeitet:</strong> 
                <?= date('d.m.Y H:i:s', strtotime($reklamation['bearbeitet_am'])) ?>
                <?php if ($reklamation['bearbeitet_von']): ?>
                    von <?= htmlspecialchars($reklamation['bearbeitet_von']) ?>
                <?php endif; ?>
            </div>
            <?php endif; ?>
        </div>

        <!-- E-Mail-Protokoll -->
        <?php if (!empty($emails)): ?>
        <div class="info-section">
            <h2>E-Mail-Protokoll (<?= count($emails) ?>)</h2>
            <div class="email-log">
                <?php foreach ($emails as $email): ?>
                <div class="email-entry">
                    <div class="email-header">
                        <span class="email-type"><?= ucfirst(str_replace('_', ' ', $email['email_typ'])) ?></span>
                        <span class="email-date"><?= date('d.m.Y H:i:s', strtotime($email['gesendet_am'])) ?></span>
                        <span class="email-status status-<?= $email['status'] ?>"><?= ucfirst($email['status']) ?></span>
                    </div>
                    <div class="email-details">
                        <strong>An:</strong> <?= htmlspecialchars($email['empfänger']) ?><br>
                        <strong>Betreff:</strong> <?= htmlspecialchars($email['betreff']) ?>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>

<!-- Bild-Modal -->
<div id="imageModal" class="modal" onclick="closeImageModal()">
    <div class="modal-content">
        <span class="modal-close" onclick="closeImageModal()">&times;</span>
        <img id="modalImage" src="" alt="">
        <div id="modalCaption"></div>
    </div>
</div>

<script>
function openImageModal(src, caption) {
    document.getElementById('imageModal').style.display = 'block';
    document.getElementById('modalImage').src = src;
    document.getElementById('modalCaption').textContent = caption;
}

function closeImageModal() {
    document.getElementById('imageModal').style.display = 'none';
}

// ESC-Taste zum Schließen
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeImageModal();
    }
});
</script>

<link rel="stylesheet" href="includes/css/reklamation_admin.css">

<?php
include('includes/footer.php');
?>

