#!/bin/bash
# ================================================================
# MRH 2026: Affiliate-Modul BS5.3 Deploy
# Auf dem Server ausführen (SSH):
#   bash deploy_affiliate.sh
# ================================================================

set -e

# --- Konfiguration ---
TPL_DIR="/home/www/doc/28856/dcp288560004/mr-hanf.at/www/templates/tpl_mrh_2026"
REPO_URL="https://github.com/mrhanf15-stack/Template-MRH-2026.git"
TMP_DIR="/tmp/mrh_deploy_$(date +%Y%m%d_%H%M%S)"
BACKUP_DIR="/tmp/mrh_backup_affiliate_$(date +%Y%m%d_%H%M%S)"

echo "=== MRH 2026: Affiliate BS5.3 Deploy ==="
echo "Template-Pfad: $TPL_DIR"
echo ""

# --- 1. Prüfen ob Template-Verzeichnis existiert ---
if [ ! -d "$TPL_DIR" ]; then
    echo "FEHLER: Template-Verzeichnis nicht gefunden: $TPL_DIR"
    exit 1
fi

# --- 2. Backup der aktuellen Dateien ---
echo ">>> Schritt 1/4: Backup erstellen..."
mkdir -p "$BACKUP_DIR/module"
mkdir -p "$BACKUP_DIR/boxes"
mkdir -p "$BACKUP_DIR/css"

for f in affiliate_account_details.html affiliate_affiliate.html affiliate_banners.html \
         affiliate_clicks.html affiliate_contact.html affiliate_details.html \
         affiliate_details_ok.html affiliate_help.html affiliate_logout.html \
         affiliate_password_forgotten.html affiliate_payment.html affiliate_sales.html \
         affiliate_signup.html affiliate_signup_ok.html affiliate_summary.html; do
    if [ -f "$TPL_DIR/module/$f" ]; then
        cp "$TPL_DIR/module/$f" "$BACKUP_DIR/module/"
    fi
done
[ -f "$TPL_DIR/boxes/box_affiliate.html" ] && cp "$TPL_DIR/boxes/box_affiliate.html" "$BACKUP_DIR/boxes/"
[ -f "$TPL_DIR/css/affiliate.css" ] && cp "$TPL_DIR/css/affiliate.css" "$BACKUP_DIR/css/"
echo "    Backup gespeichert in: $BACKUP_DIR"

# --- 3. Repo klonen ---
echo ">>> Schritt 2/4: Repo klonen..."
git clone --depth 1 "$REPO_URL" "$TMP_DIR"
echo "    Geklont nach: $TMP_DIR"

# --- 4. Dateien kopieren ---
echo ">>> Schritt 3/4: Dateien kopieren..."

# Module (15 Dateien)
for f in affiliate_account_details.html affiliate_affiliate.html affiliate_banners.html \
         affiliate_clicks.html affiliate_contact.html affiliate_details.html \
         affiliate_details_ok.html affiliate_help.html affiliate_logout.html \
         affiliate_password_forgotten.html affiliate_payment.html affiliate_sales.html \
         affiliate_signup.html affiliate_signup_ok.html affiliate_summary.html; do
    cp "$TMP_DIR/tpl_mrh_2026/module/$f" "$TPL_DIR/module/$f"
    echo "    OK: module/$f"
done

# Box
cp "$TMP_DIR/tpl_mrh_2026/boxes/box_affiliate.html" "$TPL_DIR/boxes/box_affiliate.html"
echo "    OK: boxes/box_affiliate.html"

# CSS
cp "$TMP_DIR/tpl_mrh_2026/css/affiliate.css" "$TPL_DIR/css/affiliate.css"
echo "    OK: css/affiliate.css"

# --- 5. Aufräumen ---
echo ">>> Schritt 4/4: Aufräumen..."
rm -rf "$TMP_DIR"
echo "    Temp-Verzeichnis gelöscht."

echo ""
echo "=== FERTIG! 17 Dateien deployed ==="
echo "Backup: $BACKUP_DIR"
echo ""
echo "Zum Rückgängigmachen:"
echo "  cp $BACKUP_DIR/module/* $TPL_DIR/module/"
echo "  cp $BACKUP_DIR/boxes/* $TPL_DIR/boxes/"
echo "  cp $BACKUP_DIR/css/* $TPL_DIR/css/"
