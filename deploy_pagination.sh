#!/bin/bash
# ============================================================
# DEPLOYMENT – Pagination v2.0
# ============================================================
# Neue Pagination-Komponente deployen:
#   - module/pagination.html (Smarty Template, BEM, aria, rel=prev/next)
#   - css/pagination_layout.css (Mobile First, CSS Custom Properties)
#   - js/pagination.js (Vanilla JS: Scroll, KeyNav, Prefetch)
#
# Datum: 08. April 2026
# ============================================================
set -e  # Bei Fehler abbrechen

TPL="/home/www/doc/28856/dcp288560004/mr-hanf.at/www/templates/tpl_mrh_2026"
GH="https://raw.githubusercontent.com/mrhanf15-stack/Template-MRH-2026/main/tpl_mrh_2026"
BACKUP_DIR="$TPL/backup_pagination_$(date +%Y%m%d_%H%M%S)"

echo "============================================================"
echo "  Pagination v2.0 – Deployment"
echo "  $(date)"
echo "============================================================"

# ============================================================
# SCHRITT 1: Backup erstellen
# ============================================================
echo ""
echo "[1/5] Backup erstellen..."
mkdir -p "$BACKUP_DIR/module"
mkdir -p "$BACKUP_DIR/css"

# Alte Pagination-Dateien sichern
cp "$TPL/module/pagination.html" "$BACKUP_DIR/module/" 2>/dev/null || true
cp "$TPL/css/pagination_layout.css" "$BACKUP_DIR/css/" 2>/dev/null || true
echo "  → Backup in: $BACKUP_DIR"

# ============================================================
# SCHRITT 2: pagination.html ÜBERSCHREIBEN
# ============================================================
echo ""
echo "[2/5] pagination.html überschreiben..."
curl -sSL "$GH/module/pagination.html" -o "$TPL/module/pagination.html"
echo "  → module/pagination.html aktualisiert"

# ============================================================
# SCHRITT 3: pagination_layout.css ÜBERSCHREIBEN
# ============================================================
echo ""
echo "[3/5] pagination_layout.css überschreiben..."
curl -sSL "$GH/css/pagination_layout.css" -o "$TPL/css/pagination_layout.css"
echo "  → css/pagination_layout.css aktualisiert"

# ============================================================
# SCHRITT 4: pagination.js NEU HINZUFÜGEN
# ============================================================
echo ""
echo "[4/5] pagination.js hinzufügen..."
mkdir -p "$TPL/js"
curl -sSL "$GH/js/pagination.js" -o "$TPL/js/pagination.js"
echo "  → js/pagination.js hinzugefügt (NEU)"

# ============================================================
# SCHRITT 4a: pagination_layout.css in general.css.php einbinden
# (nur wenn noch nicht vorhanden)
# ============================================================
echo ""
echo "[4a] CSS-Einbindung prüfen..."
GENERAL_CSS="$TPL/css/general.css.php"
if grep -q "pagination_layout" "$GENERAL_CSS" 2>/dev/null; then
    echo "  → pagination_layout.css bereits in general.css.php eingebunden – ÜBERSPRUNGEN"
else
    # Vor der schließenden Klammer des $css_array einfügen
    # Suche nach 'mrh-custom.css' und füge danach pagination_layout.css ein
    python3 -c "
import re
with open('$GENERAL_CSS', 'r') as f:
    content = f.read()

# Nach mrh-custom.css einfügen
target = \"DIR_TMPL_CSS . 'mrh-custom.css',\"
replacement = target + \"\\n    DIR_TMPL_CSS . 'pagination_layout.css',\"

if target in content:
    content = content.replace(target, replacement)
    with open('$GENERAL_CSS', 'w') as f:
        f.write(content)
    print('  → pagination_layout.css in general.css.php eingebunden (nach mrh-custom.css)')
else:
    print('  → WARNUNG: mrh-custom.css nicht gefunden – manuelle Einbindung nötig!')
"
fi

# ============================================================
# SCHRITT 4b: pagination.js in index.html einbinden
# (nur wenn noch nicht vorhanden)
# ============================================================
echo ""
echo "[4b] JS-Einbindung prüfen..."
INDEX_HTML="$TPL/index.html"
if grep -q "pagination\.js" "$INDEX_HTML" 2>/dev/null; then
    echo "  → pagination.js bereits in index.html eingebunden – ÜBERSPRUNGEN"
else
    # Vor </body> oder vor dem letzten {/if} einfügen
    python3 -c "
with open('$INDEX_HTML', 'r') as f:
    content = f.read()

# Script-Tag vor dem letzten </body> oder vor dem Footer-Bereich einfügen
script_tag = '<script src=\"{\$tpl_path}js/pagination.js\" defer></script>'

# Suche nach dem Accessibility-Widget Script (letztes Script im Template)
if 'fietz-accessibility-widget' in content:
    content = content.replace(
        '<script async src=\"{\$tpl_path}assets/fietz-accessibility-widget/fietz-accessibility-widget.min.js\"></script>',
        '<script async src=\"{\$tpl_path}assets/fietz-accessibility-widget/fietz-accessibility-widget.min.js\"></script>\n' + script_tag
    )
    with open('$INDEX_HTML', 'w') as f:
        f.write(content)
    print('  → pagination.js in index.html eingebunden (nach accessibility-widget)')
else:
    # Fallback: Vor dem letzten </body> einfügen
    if '</body>' in content:
        content = content.replace('</body>', script_tag + '\n</body>')
        with open('$INDEX_HTML', 'w') as f:
            f.write(content)
        print('  → pagination.js in index.html eingebunden (vor </body>)')
    else:
        print('  → WARNUNG: Konnte pagination.js nicht automatisch einbinden!')
"
fi

# ============================================================
# SCHRITT 5: Cache leeren
# ============================================================
echo ""
echo "[5/5] Cache leeren..."
rm -rf /home/www/doc/28856/dcp288560004/mr-hanf.at/www/templates_c/*
curl -s "https://mr-hanf.at/opcache_reset.php?token=MrHanf2024Reset" > /dev/null 2>&1
echo "  → Template-Cache und OPcache geleert"

# ============================================================
# ZUSAMMENFASSUNG
# ============================================================
echo ""
echo "============================================================"
echo "  PAGINATION v2.0 DEPLOYMENT ABGESCHLOSSEN"
echo "============================================================"
echo ""
echo "  ÜBERSCHRIEBEN:"
echo "    - module/pagination.html  (Smarty, BEM, aria, rel=prev/next)"
echo "    - css/pagination_layout.css  (Mobile First, CSS Custom Props)"
echo ""
echo "  NEU HINZUGEFÜGT:"
echo "    - js/pagination.js  (Vanilla JS: Scroll, KeyNav, Prefetch)"
echo ""
echo "  EINGEBUNDEN:"
echo "    - pagination_layout.css → css/general.css.php"
echo "    - pagination.js → index.html (defer)"
echo ""
echo "  NICHT BERÜHRT:"
echo "    - Alle anderen Template-Dateien"
echo "    - Megamenü, Seedfinder, etc."
echo ""
echo "  Backup: $BACKUP_DIR"
echo ""
echo "  HINWEIS: TEMPLATE_PAGINATION muss in der Shop-Konfig"
echo "  auf 'true' stehen (Admin → Konfiguration → Mein Shop)"
echo "============================================================"
