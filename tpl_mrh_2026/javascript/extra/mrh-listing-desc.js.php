<?php
/* ============================================================
   MRH 2026 – Listing Description Badges + Mini-Table
   ============================================================
   Extrahiert Icons (Geschlecht, Autoflowering) und Tabellendaten
   (THC, CBD, Kreuzung) aus der Kurzbeschreibung in Listings/Boxen
   und zeigt sie als Badge-Row + Mini-Tabelle an.
   
   Wird automatisch ueber auto_include() in general_bottom.js.php
   geladen und bei COMPRESS_JAVASCRIPT komprimiert.
   
   Badge v4.9 – 2026-04-12
   ============================================================ */
?>
<script>
(function() {
  'use strict';

  var MRH_ListingDesc = {
    fields: ['Geschlecht', 'THC', 'CBD', 'Kreuzung'],
    seedMarkers: ['Geschlecht', 'THC', 'Sorte', 'Blütezeit Indoor'],

    createBadgeRow: function(data, hasAutoIcon) {
      var row = document.createElement('div');
      row.className = 'mrh-listing-badges';

      var geschlecht = (data['Geschlecht'] || '').toLowerCase();
      if (!geschlecht) return row;

      var isFem = geschlecht.indexOf('feminis') !== -1;
      var isReg = geschlecht.indexOf('regul') !== -1;

      if (isFem || isReg) {
        var icon = document.createElement('span');
        icon.className = 'mrh-type-badge ' + (isFem ? 'mrh-badge-fem' : 'mrh-badge-reg');
        icon.innerHTML = '<span class="mrh-badge-icon">' +
          (isFem ? '\u2640' : '\u2642') + '</span> ' +
          (isFem ? 'Feminisiert' : 'Regul\u00e4r');
        row.appendChild(icon);
      }

      if (hasAutoIcon) {
        var auto = document.createElement('span');
        auto.className = 'fa fa-fw fa-tachometer shortfongc';
        auto.title = 'Autoflowering';
        row.appendChild(auto);
      } else if (isFem || isReg) {
        var photo = document.createElement('span');
        photo.className = 'mrh-type-badge mrh-badge-photo';
        photo.textContent = 'Photoperiodisch';
        row.appendChild(photo);
      }

      return row;
    },

    processDesc: function(desc) {
      /* Schon verarbeitet? */
      if (desc.getAttribute('data-mrh-seed')) return;

      var table = desc.querySelector('table.tebals');
      if (!table) return;

      /* Daten aus der Tabelle extrahieren */
      var data = {};
      var rows = table.querySelectorAll('tr');
      for (var i = 0; i < rows.length; i++) {
        var cells = rows[i].querySelectorAll('td');
        if (cells.length >= 2) {
          var label = cells[0].textContent.trim();
          var value = cells[cells.length - 1].textContent.trim();
          if (label && value) data[label] = value;
        }
      }

      /* Pruefen ob Samen-Produkt */
      var isSeed = false;
      for (var j = 0; j < this.seedMarkers.length; j++) {
        if (data[this.seedMarkers[j]]) { isSeed = true; break; }
      }
      desc.setAttribute('data-mrh-seed', isSeed ? '1' : '0');

      if (!isSeed) return;

      /* Auto-Icon erkennen */
      var picto = desc.querySelector('.picto.templatestyle');
      var hasAutoIcon = picto ? !!picto.querySelector('.shortfongc') : false;

      /* Altes Picto-Div ausblenden */
      if (picto) picto.style.display = 'none';

      /* Badge-Row erstellen und einfuegen */
      var badgeRow = this.createBadgeRow(data, hasAutoIcon);
      desc.insertBefore(badgeRow, desc.firstChild);

      /* Mini-Tabelle erstellen */
      var miniTable = document.createElement('table');
      miniTable.className = 'mrh-mini-table';
      var hasRows = false;

      for (var k = 0; k < this.fields.length; k++) {
        var field = this.fields[k];
        if (!data[field]) continue;
        hasRows = true;
        var tr = document.createElement('tr');
        var tdLabel = document.createElement('td');
        tdLabel.textContent = field;
        var tdValue = document.createElement('td');
        tdValue.textContent = data[field];
        tr.appendChild(tdLabel);
        tr.appendChild(tdValue);
        miniTable.appendChild(tr);
      }

      if (hasRows) {
        desc.appendChild(miniTable);
      }
    },

    init: function() {
      var self = this;
      /* Alle Listing/Box Beschreibungen verarbeiten */
      var descs = document.querySelectorAll('.lb_desc, .lr_desc');
      for (var i = 0; i < descs.length; i++) {
        self.processDesc(descs[i]);
      }
    }
  };

  /* Auf DOMContentLoaded ausfuehren */
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', function() {
      MRH_ListingDesc.init();
    });
  } else {
    /* DOM ist bereits geladen (z.B. bei defer/async) */
    MRH_ListingDesc.init();
  }

  /* Global verfuegbar machen fuer Re-Init (z.B. nach AJAX) */
  window.MRH_ListingDesc = MRH_ListingDesc;

})();
</script>
