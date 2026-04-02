#!/bin/bash
# ================================================================
# MRH 2026: Login-Box + Partnerlogin Deploy
# Auf dem Server ausführen: bash deploy_login_box.sh
# ================================================================
set -e
TPL_DIR="/home/www/doc/28856/dcp288560004/mr-hanf.at/www/templates/tpl_mrh_2026"
REPO_URL="https://github.com/mrhanf15-stack/Template-MRH-2026.git"
TMP_DIR="/tmp/mrh_deploy_login_$(date +%Y%m%d_%H%M%S)"
BACKUP_DIR="/tmp/mrh_backup_login_$(date +%Y%m%d_%H%M%S)"

echo "=== MRH 2026: Login-Box + Partnerlogin Deploy ==="
echo "Template-Pfad: $TPL_DIR"
echo ""

# Backup
echo ">>> Schritt 1/4: Backup erstellen..."
mkdir -p "$BACKUP_DIR/boxes" "$BACKUP_DIR/lang"
[ -f "$TPL_DIR/boxes/box_login.html" ] && cp "$TPL_DIR/boxes/box_login.html" "$BACKUP_DIR/boxes/"
for lang in german english french dutch; do
    [ -f "$TPL_DIR/lang/$lang/lang_$lang.section" ] && mkdir -p "$BACKUP_DIR/lang/$lang" && cp "$TPL_DIR/lang/$lang/lang_$lang.section" "$BACKUP_DIR/lang/$lang/"
done
echo "    Backup: $BACKUP_DIR"

# Clone
echo ">>> Schritt 2/4: Repo klonen..."
git clone --depth 1 "$REPO_URL" "$TMP_DIR"
echo "    Geklont: $TMP_DIR"

# Copy
echo ">>> Schritt 3/4: Dateien kopieren..."
cp "$TMP_DIR/tpl_mrh_2026/boxes/box_login.html" "$TPL_DIR/boxes/box_login.html"
echo "    OK: boxes/box_login.html"
for lang in german english french dutch; do
    if [ -f "$TMP_DIR/tpl_mrh_2026/lang/$lang/lang_$lang.section" ]; then
        cp "$TMP_DIR/tpl_mrh_2026/lang/$lang/lang_$lang.section" "$TPL_DIR/lang/$lang/lang_$lang.section"
        echo "    OK: lang/$lang/lang_$lang.section"
    fi
done

# Cleanup
echo ">>> Schritt 4/4: Aufräumen..."
rm -rf "$TMP_DIR"
echo ""
echo "=== FERTIG! 5 Dateien deployed ==="
echo "Backup: $BACKUP_DIR"
echo ""
echo "Zum Rückgängigmachen:"
echo "  cp $BACKUP_DIR/boxes/* $TPL_DIR/boxes/"
echo "  for lang in german english french dutch; do cp $BACKUP_DIR/lang/\$lang/* $TPL_DIR/lang/\$lang/; done"
