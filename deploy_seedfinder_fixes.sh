#!/bin/bash
# ============================================================
# Seedfinder Deployment Script v23.0
# Datum: 22.04.2026
# Template: tpl_mrh_2026
#
# v23.0: Stage 3 entfernt - Wizard leitet auf Stage 2
#        seedfinder_beginner_results.html nicht mehr nötig
#        seedfinder-beginner-results.js nicht mehr nötig
# ============================================================

set -e

TPL_DIR="/home/www/doc/28856/dcp288560004/mr-hanf.at/www/templates/tpl_mrh_2026"
BACKUP_DIR="/home/www/doc/28856/dcp288560004/mr-hanf.at/www/templates/tpl_mrh_2026/backup_$(date +%Y%m%d_%H%M%S)"
GITHUB_RAW="https://raw.githubusercontent.com/mrhanf15-stack/Template-MRH-2026/main/tpl_mrh_2026"

echo "============================================================"
echo "Seedfinder Deployment v23.0 – $(date)"
echo "============================================================"
echo ""

# 1. Backup erstellen
echo "[1/6] Backup erstellen..."
mkdir -p "$BACKUP_DIR/module"
mkdir -p "$BACKUP_DIR/css"
mkdir -p "$BACKUP_DIR/javascript"
cp "$TPL_DIR/module/seedfinder.html" "$BACKUP_DIR/module/"
cp "$TPL_DIR/module/seedfinder_product_cards.html" "$BACKUP_DIR/module/"
cp "$TPL_DIR/module/seedfinder_beginner-wizard-modal-with-results.html" "$BACKUP_DIR/module/" 2>/dev/null || true
cp "$TPL_DIR/javascript/seedfinder-beginner-with-results.js" "$BACKUP_DIR/javascript/" 2>/dev/null || true
cp "$TPL_DIR/css/seedfinder_disabled_headers.css" "$BACKUP_DIR/css/" 2>/dev/null || true
echo "   Backup gespeichert in: $BACKUP_DIR"

# 2. Template-Dateien von GitHub herunterladen
echo ""
echo "[2/6] Template-Dateien von GitHub herunterladen..."
curl -sSL "$GITHUB_RAW/module/seedfinder.html" -o "$TPL_DIR/module/seedfinder.html"
echo "   ✓ seedfinder.html"
curl -sSL "$GITHUB_RAW/module/seedfinder_product_cards.html" -o "$TPL_DIR/module/seedfinder_product_cards.html"
echo "   ✓ seedfinder_product_cards.html"
curl -sSL "$GITHUB_RAW/module/seedfinder_beginner-wizard-modal-with-results.html" -o "$TPL_DIR/module/seedfinder_beginner-wizard-modal-with-results.html"
echo "   ✓ seedfinder_beginner-wizard-modal-with-results.html (mit Wizard CSS)"

# 3. JavaScript-Dateien von GitHub herunterladen
echo ""
echo "[3/6] JavaScript-Dateien herunterladen..."
curl -sSL "$GITHUB_RAW/javascript/seedfinder-beginner-with-results.js" -o "$TPL_DIR/javascript/seedfinder-beginner-with-results.js"
echo "   ✓ seedfinder-beginner-with-results.js v1.3 (Stage 2 Redirect)"

# 4. Lang-Dateien aktualisieren (DE + EN)
echo ""
echo "[4/6] Lang-Dateien aktualisieren..."
for LANG in german english; do
    LANG_FILE="$TPL_DIR/lang/$LANG/lang_$LANG.conf"
    if grep -q "\[seedfinder\]" "$LANG_FILE"; then
        python3 -c "
import re
with open('$LANG_FILE', 'r') as f:
    content = f.read()
content = re.sub(r'\[seedfinder\].*?(?=\n\[|\Z)', '', content, flags=re.DOTALL)
with open('$LANG_FILE', 'w') as f:
    f.write(content)
"
    fi
    curl -sSL "$GITHUB_RAW/lang/$LANG/seedfinder_section.conf" >> "$LANG_FILE"
    echo "   ✓ [seedfinder] Section in lang_$LANG.conf aktualisiert"
done

# 5. Smarty + Seedfinder Cache leeren
echo ""
echo "[5/6] Cache leeren..."
rm -rf /home/www/doc/28856/dcp288560004/mr-hanf.at/www/templates_c/*
rm -rf /home/www/doc/28856/dcp288560004/mr-hanf.at/www/cache/fpc/*
rm -rf /home/www/doc/28856/dcp288560004/mr-hanf.at/www/cache/seedfinder/*
echo "   ✓ templates_c/, cache/fpc/, cache/seedfinder/ geleert"

# 6. OPcache Reset
echo ""
echo "[6/6] OPcache zurücksetzen..."
curl -s -u "Alex:19649541BNZUUHJHBBHZi" "https://mr-hanf.at/opcache_reset.php?token=MrHanf2024Reset"
echo ""
echo "   ✓ OPcache geleert"

# Hinweis: Obsolete Dateien (können manuell gelöscht werden)
echo ""
echo "============================================================"
echo "DEPLOYMENT ABGESCHLOSSEN"
echo "============================================================"
echo ""
echo "Obsolete Dateien (können gelöscht werden):"
echo "  - $TPL_DIR/module/seedfinder_beginner_results.html"
echo "  - $TPL_DIR/javascript/seedfinder-beginner-results.js"
echo ""
echo "Bitte testen:"
echo "  1. https://mr-hanf.at/seedfinder.php (Stage 1 – Kategorien)"
echo "  2. Klicke auf eine Kategorie (Stage 2 – Filter + Produkte)"
echo "  3. Teste den Anfänger Wizard → Ergebnis = Stage 2 mit Filterbar"
echo ""
echo "Backup liegt in: $BACKUP_DIR"
echo ""
