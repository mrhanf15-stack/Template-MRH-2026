#!/bin/bash
# ============================================================
# Seedfinder Fixes Deployment Script
# Datum: 04.04.2026
# Template: tpl_mrh_2026
# ============================================================

set -e

TPL_DIR="/home/www/doc/28856/dcp288560004/mr-hanf.at/www/templates/tpl_mrh_2026"
BACKUP_DIR="/home/www/doc/28856/dcp288560004/mr-hanf.at/www/templates/tpl_mrh_2026/backup_$(date +%Y%m%d_%H%M%S)"
GITHUB_RAW="https://raw.githubusercontent.com/mrhanf15-stack/Template-MRH-2026/main/tpl_mrh_2026"

echo "============================================================"
echo "Seedfinder Fixes Deployment – $(date)"
echo "============================================================"
echo ""

# 1. Backup erstellen
echo "[1/7] Backup erstellen..."
mkdir -p "$BACKUP_DIR/module"
mkdir -p "$BACKUP_DIR/css"
cp "$TPL_DIR/module/seedfinder.html" "$BACKUP_DIR/module/"
cp "$TPL_DIR/module/seedfinder_beginner_results.html" "$BACKUP_DIR/module/"
cp "$TPL_DIR/module/seedfinder_product_cards.html" "$BACKUP_DIR/module/"
cp "$TPL_DIR/css/seedfinder_disabled_headers.css" "$BACKUP_DIR/css/"
cp "$TPL_DIR/css/seedfinder-combined.min.css" "$BACKUP_DIR/css/"
echo "   Backup gespeichert in: $BACKUP_DIR"

# 2. Fixe Dateien von GitHub herunterladen
echo ""
echo "[2/7] Fixe Dateien von GitHub herunterladen..."
curl -sSL "$GITHUB_RAW/module/seedfinder.html" -o "$TPL_DIR/module/seedfinder.html"
echo "   ✓ seedfinder.html (b4.css + combined.min.css entfernt, Einzeldateien + CURRENT_TEMPLATE)"
curl -sSL "$GITHUB_RAW/module/seedfinder_beginner_results.html" -o "$TPL_DIR/module/seedfinder_beginner_results.html"
echo "   ✓ seedfinder_beginner_results.html (UPPERCASE Keys, BS5 data-bs-*, CURRENT_TEMPLATE)"
curl -sSL "$GITHUB_RAW/module/seedfinder_product_cards.html" -o "$TPL_DIR/module/seedfinder_product_cards.html"
echo "   ✓ seedfinder_product_cards.html (Smarty 2 foreach Syntax)"
curl -sSL "$GITHUB_RAW/css/seedfinder_disabled_headers.css" -o "$TPL_DIR/css/seedfinder_disabled_headers.css"
echo "   ✓ seedfinder_disabled_headers.css (CSS var() statt hardcoded)"

# 3. Lang-Dateien aktualisieren (DE)
echo ""
echo "[3/7] Deutsche Lang-Datei aktualisieren..."
# Prüfe ob [seedfinder] Section existiert und ersetze sie
if grep -q "\[seedfinder\]" "$TPL_DIR/lang/german/lang_german.conf"; then
    # Entferne alte [seedfinder] Section (bis zur nächsten Section oder EOF)
    # Erstelle temporäre Datei ohne alte seedfinder Section
    python3 -c "
import re
with open('$TPL_DIR/lang/german/lang_german.conf', 'r') as f:
    content = f.read()
# Entferne alte [seedfinder] Section
content = re.sub(r'\[seedfinder\].*?(?=\n\[|\Z)', '', content, flags=re.DOTALL)
with open('$TPL_DIR/lang/german/lang_german.conf', 'w') as f:
    f.write(content)
"
fi
# Füge neue [seedfinder] Section am Ende hinzu
curl -sSL "$GITHUB_RAW/lang/german/seedfinder_section.conf" >> "$TPL_DIR/lang/german/lang_german.conf"
echo "   ✓ [seedfinder] Section in lang_german.conf aktualisiert (125 Variablen)"

# 4. Lang-Dateien aktualisieren (EN)
echo ""
echo "[4/7] Englische Lang-Datei aktualisieren..."
if grep -q "\[seedfinder\]" "$TPL_DIR/lang/english/lang_english.conf"; then
    python3 -c "
import re
with open('$TPL_DIR/lang/english/lang_english.conf', 'r') as f:
    content = f.read()
content = re.sub(r'\[seedfinder\].*?(?=\n\[|\Z)', '', content, flags=re.DOTALL)
with open('$TPL_DIR/lang/english/lang_english.conf', 'w') as f:
    f.write(content)
"
fi
curl -sSL "$GITHUB_RAW/lang/english/seedfinder_section.conf" >> "$TPL_DIR/lang/english/lang_english.conf"
echo "   ✓ [seedfinder] Section in lang_english.conf aktualisiert (125 Variablen)"

# 5. Veraltete seedfinder-combined.min.css löschen
echo ""
echo "[5/7] Veraltete seedfinder-combined.min.css entfernen..."
if [ -f "$TPL_DIR/css/seedfinder-combined.min.css" ]; then
    rm "$TPL_DIR/css/seedfinder-combined.min.css"
    echo "   ✓ seedfinder-combined.min.css gelöscht (65 KB gespart)"
else
    echo "   ℹ seedfinder-combined.min.css existiert nicht mehr"
fi

# Auch seedfinder-combined.css löschen falls vorhanden
if [ -f "$TPL_DIR/css/seedfinder-combined.css" ]; then
    rm "$TPL_DIR/css/seedfinder-combined.css"
    echo "   ✓ seedfinder-combined.css gelöscht"
fi

# 6. Smarty Cache leeren
echo ""
echo "[6/7] Smarty Cache leeren..."
rm -rf /home/www/doc/28856/dcp288560004/mr-hanf.at/www/templates_c/*
echo "   ✓ templates_c/ geleert"

# 7. OPcache Reset
echo ""
echo "[7/7] OPcache zurücksetzen..."
curl -s "https://mr-hanf.at/opcache_reset.php?token=MrHanf2024Reset"
echo ""
echo "   ✓ OPcache geleert"

echo ""
echo "============================================================"
echo "DEPLOYMENT ABGESCHLOSSEN"
echo "============================================================"
echo ""
echo "Bitte testen:"
echo "  1. https://mr-hanf.at/seedfinder.php (Stage 1 – Kategorien)"
echo "  2. Klicke auf eine Kategorie (Stage 2 – Filter + Produkte)"
echo "  3. Teste den Anfänger Wizard (Modal)"
echo "  4. Teste die Beginner Results Seite"
echo ""
echo "Backup liegt in: $BACKUP_DIR"
echo ""
