<?php
/* -----------------------------------------------------------------------------------------
   Automated Translated by Reqser.com using Modified Modul Version 3.4 on the 18.06.2025
   ---------------------------------------------------------------------------------------*/


/* -----------------------------------------------------------------------------------------
   Modified Shop 2.0.7 - Sprachkonstanten für Reklamationsformular
   Datei: lang/english/reklamation.php
   
   Deutsche Sprachkonstanten für das Reklamationssystem
   ----------------------------------------------------------------------------------------- */
// Seitentitel und Meta
define('HEADING_TITLE_REKLAMATION', 'Submit a complaint');
define('NAVBAR_TITLE_REKLAMATION', 'Complaint');
define('META_DESCRIPTION_REKLAMATION', 'Submit your complaint about germination problems here. We will be happy to help you.');
// Allgemeine Texte
define('TEXT_REKLAMATION_INTRO', 'Do you have problems with the germination of your seeds? Let us know the details so that we can help you in the best possible way.');
define('TEXT_BITTE_WÄHLEN', 'Please select...');
define('TEXT_ZEICHEN', 'Sign');
define('TEXT_WIRD_VERARBEITET', 'Is being processed...');
// Kundendaten
define('HEADING_KUNDENDATEN', 'Your contact details');
define('TEXT_KUNDE_NAME', 'Full name');
define('TEXT_KUNDE_EMAIL', 'E-mail address');
define('TEXT_KUNDE_TELEFON', 'Telephone number (optional)');
// Bestelldaten
define('HEADING_BESTELLDATEN', 'Order information');
define('TEXT_BESTELLUNG_AUSWÄHLEN', 'Select order');
define('TEXT_BESTELLUNG', 'order');
define('TEXT_ODER_MANUELL', 'or enter manually:');
define('TEXT_BESTELLNUMMER', 'Order number');
define('TEXT_BESTELLNUMMER_HILFE', 'You will find your order number in the confirmation e-mail or in your customer account.');
define('TEXT_BETROFFENES_PRODUKT', 'Product concerned');
define('TEXT_PRODUKT_MANUELL', 'Enter product name manually');
// Keimungsdaten
define('HEADING_KEIMUNGSDATEN', 'Germination details');
define('TEXT_KEIMUNG_METHODE', 'Germination method');
define('TEXT_METHODE_PAPIERTUCH', 'Paper towel method');
define('TEXT_METHODE_ERDE', 'Directly in soil');
define('TEXT_METHODE_WASSER', 'Water glass method');
define('TEXT_METHODE_STEINWOLLE', 'Rock wool');
define('TEXT_METHODE_KOKOS', 'Coconut swelling tabs');
define('TEXT_METHODE_ANDERE', 'Other method');
define('TEXT_KEIMUNG_MEDIUM', 'Medium used');
define('TEXT_MEDIUM_BEISPIEL', 'e.g. potting soil, coco soil, rock wool...');
define('TEXT_KEIMUNG_TEMPERATUR', 'Temperature');
define('TEXT_TEMPERATUR_BEISPIEL', 'e.g. 20-25&deg;C, constant 22&deg;C...');
define('TEXT_KEIMUNG_LUFTFEUCHTIGKEIT', 'Air humidity');
define('TEXT_LUFTFEUCHTIGKEIT_BEISPIEL', 'e.g. 60-70%, very high...');
define('TEXT_KEIMUNG_DAUER', 'Germination period');
define('TEXT_DAUER_BEISPIEL', 'e.g. 5 days, 2 weeks...');
define('TEXT_KEIMUNG_RATE', 'Germination rate');
define('TEXT_RATE_BEISPIEL', 'e.g. 2 out of 10, 0%, 50%...');
define('TEXT_LICHTBEDINGUNGEN', 'Lighting conditions');
define('TEXT_LICHT_DUNKEL', 'Dark');
define('TEXT_LICHT_INDIREKT', 'Indirect light');
define('TEXT_LICHT_DIREKT', 'Direct sunlight');
define('TEXT_LICHT_KUNST', 'Artificial light');
define('TEXT_LICHT_LED', 'LED Grow Light');
define('TEXT_WASSERGABE', 'Watering');
define('TEXT_WASSERGABE_BEISPIEL', 'e.g. sprayed daily, watered every 2 days...');
// Problembeschreibung
define('HEADING_PROBLEMBESCHREIBUNG', 'Problem description');
define('TEXT_PROBLEM_BESCHREIBUNG', 'Describe the problem in detail');
define('TEXT_BESCHREIBUNG_PLATZHALTER', 'Please describe as precisely as possible what went wrong during germination. When did you plant the seeds? How did you treat them? What did or did not happen? The more details you give us, the better we can help you.');
// Bildupload
define('HEADING_BILDER', 'Upload images');
define('TEXT_BILDER_UPLOAD', 'Pictures for documentation (optional)');
define('TEXT_BILDER_DRAG_DROP', 'Drag images here or click to select');
define('TEXT_BILDER_INFO', 'Maximum 5 images, JPG or PNG, max. 5MB each');
// Datenschutz und Absenden
define('TEXT_DATENSCHUTZ_ZUSTIMMUNG', 'I consent to the processing of my data in accordance with the <a href="datenschutz.php" target="_blank">Privacy Policy</a>.');
define('BUTTON_REKLAMATION_SENDEN', 'Submit a complaint');
// Erfolgsmeldungen
define('TEXT_REKLAMATION_ERFOLG', 'Your complaint has been successfully submitted.');
define('TEXT_REKLAMATION_ERFOLG_DETAIL', 'You will shortly receive a confirmation e-mail with your complaint number. We will take care of your request as soon as possible.');
// Fehlermeldungen
define('TEXT_ERROR_ALLGEMEIN', 'An error has occurred. Please try again.');
define('TEXT_ERROR_BESTELLNUMMER', 'The specified order number could not be found.');
define('TEXT_ERROR_EMAIL_FORMAT', 'Please enter a valid e-mail address.');
define('TEXT_ERROR_DATEI_GROESSE', 'One or more files are too large (max. 5MB per file).');
define('TEXT_ERROR_DATEI_TYP', 'Only JPG and PNG files are allowed.');
define('TEXT_ERROR_MAX_DATEIEN', 'Maximum 5 pictures allowed.');
define('TEXT_ERROR_UPLOAD', 'Error uploading the images.');
define('TEXT_ERROR_PFLICHTFELD', 'Please fill in all mandatory fields.');
define('TEXT_ERROR_BESCHREIBUNG_LANG', 'The problem description is too long (max. 2000 characters).');
// Admin-Bereich
define('HEADING_ADMIN_REKLAMATIONEN', 'Complaints management');
define('TEXT_ADMIN_NEUE_REKLAMATIONEN', 'New complaints');
define('TEXT_ADMIN_ALLE_REKLAMATIONEN', 'All complaints');
define('TEXT_ADMIN_STATUS_NEU', 'New');
define('TEXT_ADMIN_STATUS_BEARBEITUNG', 'In progress');
define('TEXT_ADMIN_STATUS_ABGESCHLOSSEN', 'Completed');
define('TEXT_ADMIN_STATUS_ABGELEHNT', 'Rejected');
// E-Mail-Templates
define('EMAIL_BETREFF_KUNDE', 'Confirmation of your complaint #{reklamation_id}');
define('EMAIL_BETREFF_ADMIN', 'New complaint #{reklamation_id} received');
define('EMAIL_TEXT_KUNDE_INTRO', 'Thank you for your complaint. We have received your request and will process it as quickly as possible.');
define('EMAIL_TEXT_KUNDE_DETAILS', 'Details of your complaint:');
define('EMAIL_TEXT_KUNDE_KONTAKT', 'You can contact us at any time if you have any questions.');
define('EMAIL_TEXT_ADMIN_INTRO', 'A new complaint has been received and is awaiting processing.');
define('EMAIL_TEXT_ADMIN_LINK', 'For editing in the admin area:');
// Navigation
define('BOX_HEADING_REKLAMATION', 'Complaint');
define('BOX_REKLAMATION_EINREICHEN', 'Submit a complaint');
define('BOX_REKLAMATION_STATUS', 'Track status');
?>

