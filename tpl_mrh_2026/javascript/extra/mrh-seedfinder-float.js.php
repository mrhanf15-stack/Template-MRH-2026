<?php
/* -----------------------------------------------------------------------------------------
   Floating Seedfinder Button v1.0.0

   Hookpoint: templates/tpl_mrh_2026/javascript/extra/
   Wird automatisch via general_bottom.js.php auto_include geladen.

   Erzeugt einen festen (fixed) schwebenden Seedfinder-Button analog zum
   Floating Vergleichs-Badge. Alle Styles werden per CSS-Variablen (--tpl-sf-float-*)
   aus dem Konfigurator gesteuert.

   Features:
   - Immer sichtbar (kein Count/Status nötig)
   - Position konfigurierbar (4 Ecken)
   - Desktop + Mobile unabhängig konfigurierbar
   - data-faw-ignore gegen FAW-Überschreibungen
   - Versteckt sich automatisch auf /Seedfinder/ Seite (bereits dort)

   @author    Mr. Hanf / Manus AI
   @version   1.0.0
   @date      2026-04-12
   -----------------------------------------------------------------------------------------*/
?>
<script>
(function() {
    'use strict';

    // Nicht auf der Seedfinder-Seite selbst anzeigen
    var path = window.location.pathname.toLowerCase();
    if (path.indexOf('/seedfinder') === 0) return;

    // Prüfe ob der Button schon existiert (FPC-Schutz)
    if (document.getElementById('mrh-seedfinder-float')) return;

    // === Badge-Element erstellen ===
    var badge = document.createElement('div');
    badge.id = 'mrh-seedfinder-float';
    badge.className = 'mrh-seedfinder-float sf-pos-bottom-left';
    badge.setAttribute('data-faw-ignore', 'true');

    var link = document.createElement('a');
    link.href = '/Seedfinder/';
    link.className = 'sf-float-link';
    link.title = 'Seedfinder – Finde deine perfekte Sorte';
    link.setAttribute('data-faw-ignore', 'true');
    link.setAttribute('aria-label', 'Seedfinder öffnen');

    var icon = document.createElement('i');
    icon.className = 'fa-solid fa-seedling';
    icon.setAttribute('data-faw-ignore', 'true');

    link.appendChild(icon);
    badge.appendChild(link);

    // === In den DOM einfügen ===
    document.body.appendChild(badge);

})();
</script>
