<?php
/* -----------------------------------------------------------------------------------------
   Modified Shop 2.0.7 - Sprachkonstanten für Reklamationsformular
   Datei: lang/german/reklamation.php
   
   Deutsche Sprachkonstanten für das Reklamationssystem
   ----------------------------------------------------------------------------------------- */

// Seitentitel und Meta
define('HEADING_TITLE_REKLAMATION', 'Reklamation einreichen');
define('NAVBAR_TITLE_REKLAMATION', 'Reklamation');
define('META_DESCRIPTION_REKLAMATION', 'Reichen Sie hier Ihre Reklamation für Keimungsprobleme ein. Wir helfen Ihnen gerne weiter.');

// Allgemeine Texte
define('TEXT_REKLAMATION_INTRO', 'Haben Sie Probleme mit der Keimung Ihrer Samen? Teilen Sie uns die Details mit, damit wir Ihnen bestmöglich helfen können.');
define('TEXT_BITTE_WÄHLEN', 'Bitte wählen...');
define('TEXT_ZEICHEN', 'Zeichen');
define('TEXT_WIRD_VERARBEITET', 'Wird verarbeitet...');

// Kundendaten
define('HEADING_KUNDENDATEN', 'Ihre Kontaktdaten');
define('TEXT_KUNDE_NAME', 'Vollständiger Name');
define('TEXT_KUNDE_EMAIL', 'E-Mail-Adresse');
define('TEXT_KUNDE_TELEFON', 'Telefonnummer (optional)');

// Bestelldaten
define('HEADING_BESTELLDATEN', 'Bestellinformationen');
define('TEXT_BESTELLUNG_AUSWÄHLEN', 'Bestellung auswählen');
define('TEXT_BESTELLUNG', 'Bestellung');
define('TEXT_ODER_MANUELL', 'oder manuell eingeben:');
define('TEXT_BESTELLNUMMER', 'Bestellnummer');
define('TEXT_BESTELLNUMMER_HILFE', 'Sie finden Ihre Bestellnummer in der Bestätigungs-E-Mail oder in Ihrem Kundenkonto.');
define('TEXT_BETROFFENES_PRODUKT', 'Betroffenes Produkt');
define('TEXT_PRODUKT_MANUELL', 'Produktname manuell eingeben');

// Keimungsdaten
define('HEADING_KEIMUNGSDATEN', 'Details zur Keimung');
define('TEXT_KEIMUNG_METHODE', 'Keimungsmethode');
define('TEXT_METHODE_PAPIERTUCH', 'Papiertuch-Methode');
define('TEXT_METHODE_ERDE', 'Direkt in Erde');
define('TEXT_METHODE_WASSER', 'Wasserglas-Methode');
define('TEXT_METHODE_STEINWOLLE', 'Steinwolle');
define('TEXT_METHODE_KOKOS', 'Kokosquelltabs');
define('TEXT_METHODE_ANDERE', 'Andere Methode');

define('TEXT_KEIMUNG_MEDIUM', 'Verwendetes Medium');
define('TEXT_MEDIUM_BEISPIEL', 'z.B. Anzuchterde, Kokoserde, Steinwolle...');

define('TEXT_KEIMUNG_TEMPERATUR', 'Temperatur');
define('TEXT_TEMPERATUR_BEISPIEL', 'z.B. 20-25°C, konstant 22°C...');

define('TEXT_KEIMUNG_LUFTFEUCHTIGKEIT', 'Luftfeuchtigkeit');
define('TEXT_LUFTFEUCHTIGKEIT_BEISPIEL', 'z.B. 60-70%, sehr hoch...');

define('TEXT_KEIMUNG_DAUER', 'Keimungsdauer');
define('TEXT_DAUER_BEISPIEL', 'z.B. 5 Tage, 2 Wochen...');

define('TEXT_KEIMUNG_RATE', 'Keimungsrate');
define('TEXT_RATE_BEISPIEL', 'z.B. 2 von 10, 0%, 50%...');

define('TEXT_LICHTBEDINGUNGEN', 'Lichtbedingungen');
define('TEXT_LICHT_DUNKEL', 'Dunkel');
define('TEXT_LICHT_INDIREKT', 'Indirektes Licht');
define('TEXT_LICHT_DIREKT', 'Direktes Sonnenlicht');
define('TEXT_LICHT_KUNST', 'Kunstlicht');
define('TEXT_LICHT_LED', 'LED Grow Light');

define('TEXT_WASSERGABE', 'Wassergabe');
define('TEXT_WASSERGABE_BEISPIEL', 'z.B. täglich besprüht, alle 2 Tage gegossen...');

// Problembeschreibung
define('HEADING_PROBLEMBESCHREIBUNG', 'Problembeschreibung');
define('TEXT_PROBLEM_BESCHREIBUNG', 'Beschreiben Sie das Problem detailliert');
define('TEXT_BESCHREIBUNG_PLATZHALTER', 'Beschreiben Sie bitte so genau wie möglich, was bei der Keimung schief gelaufen ist. Wann haben Sie die Samen eingesetzt? Wie haben Sie sie behandelt? Was ist passiert oder nicht passiert? Je mehr Details Sie uns geben, desto besser können wir Ihnen helfen.');

// Bildupload
define('HEADING_BILDER', 'Bilder hochladen');
define('TEXT_BILDER_UPLOAD', 'Bilder zur Dokumentation (optional)');
define('TEXT_BILDER_DRAG_DROP', 'Bilder hier hinziehen oder klicken zum Auswählen');
define('TEXT_BILDER_INFO', 'Maximal 5 Bilder, JPG oder PNG, je max. 5MB');

// Datenschutz und Absenden
define('TEXT_DATENSCHUTZ_ZUSTIMMUNG', 'Ich stimme der Verarbeitung meiner Daten gemäß der <a href="datenschutz.php" target="_blank">Datenschutzerklärung</a> zu.');
define('BUTTON_REKLAMATION_SENDEN', 'Reklamation einreichen');

// Erfolgsmeldungen
define('TEXT_REKLAMATION_ERFOLG', 'Ihre Reklamation wurde erfolgreich eingereicht.');
define('TEXT_REKLAMATION_ERFOLG_DETAIL', 'Sie erhalten in Kürze eine Bestätigungs-E-Mail mit Ihrer Reklamationsnummer. Wir werden uns schnellstmöglich um Ihr Anliegen kümmern.');

// Fehlermeldungen
define('TEXT_ERROR_ALLGEMEIN', 'Es ist ein Fehler aufgetreten. Bitte versuchen Sie es erneut.');
define('TEXT_ERROR_BESTELLNUMMER', 'Die angegebene Bestellnummer konnte nicht gefunden werden.');
define('TEXT_ERROR_EMAIL_FORMAT', 'Bitte geben Sie eine gültige E-Mail-Adresse ein.');
define('TEXT_ERROR_DATEI_GROESSE', 'Eine oder mehrere Dateien sind zu groß (max. 5MB pro Datei).');
define('TEXT_ERROR_DATEI_TYP', 'Nur JPG und PNG Dateien sind erlaubt.');
define('TEXT_ERROR_MAX_DATEIEN', 'Maximal 5 Bilder erlaubt.');
define('TEXT_ERROR_UPLOAD', 'Fehler beim Hochladen der Bilder.');
define('TEXT_ERROR_PFLICHTFELD', 'Bitte füllen Sie alle Pflichtfelder aus.');
define('TEXT_ERROR_BESCHREIBUNG_LANG', 'Die Problembeschreibung ist zu lang (max. 2000 Zeichen).');

// Admin-Bereich
define('HEADING_ADMIN_REKLAMATIONEN', 'Reklamationsverwaltung');
define('TEXT_ADMIN_NEUE_REKLAMATIONEN', 'Neue Reklamationen');
define('TEXT_ADMIN_ALLE_REKLAMATIONEN', 'Alle Reklamationen');
define('TEXT_ADMIN_STATUS_NEU', 'Neu');
define('TEXT_ADMIN_STATUS_BEARBEITUNG', 'In Bearbeitung');
define('TEXT_ADMIN_STATUS_ABGESCHLOSSEN', 'Abgeschlossen');
define('TEXT_ADMIN_STATUS_ABGELEHNT', 'Abgelehnt');

// E-Mail-Templates
define('EMAIL_BETREFF_KUNDE', 'Bestätigung Ihrer Reklamation #{reklamation_id}');
define('EMAIL_BETREFF_ADMIN', 'Neue Reklamation #{reklamation_id} eingegangen');

define('EMAIL_TEXT_KUNDE_INTRO', 'Vielen Dank für Ihre Reklamation. Wir haben Ihre Anfrage erhalten und werden sie schnellstmöglich bearbeiten.');
define('EMAIL_TEXT_KUNDE_DETAILS', 'Details Ihrer Reklamation:');
define('EMAIL_TEXT_KUNDE_KONTAKT', 'Bei Rückfragen können Sie sich jederzeit an uns wenden.');

define('EMAIL_TEXT_ADMIN_INTRO', 'Eine neue Reklamation ist eingegangen und wartet auf Bearbeitung.');
define('EMAIL_TEXT_ADMIN_LINK', 'Zur Bearbeitung im Admin-Bereich:');

// Navigation
define('BOX_HEADING_REKLAMATION', 'Reklamation');
define('BOX_REKLAMATION_EINREICHEN', 'Reklamation einreichen');
define('BOX_REKLAMATION_STATUS', 'Status verfolgen');

?>

