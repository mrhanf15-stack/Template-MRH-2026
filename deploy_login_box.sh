#!/bin/bash
# ================================================================
# MRH 2026: Login-Box + Partnerlogin Deploy (v3)
# Inkl. PHP-Dateien + Extra-Sprachdateien
# Auf dem Server ausführen:
#   curl -sL https://raw.githubusercontent.com/mrhanf15-stack/Template-MRH-2026/main/deploy_login_box.sh -o /tmp/deploy_login_box.sh && bash /tmp/deploy_login_box.sh
# ================================================================
set -e
SHOP_DIR="/home/www/doc/28856/dcp288560004/mr-hanf.at/www"
TPL_DIR="$SHOP_DIR/templates/tpl_mrh_2026"
REPO_URL="https://github.com/mrhanf15-stack/Template-MRH-2026.git"
TMP_DIR="/tmp/mrh_deploy_login_$(date +%Y%m%d_%H%M%S)"
BACKUP_DIR="/tmp/mrh_backup_login_$(date +%Y%m%d_%H%M%S)"

echo "=== MRH 2026: Login-Box + Partnerlogin Deploy (v3) ==="
echo "Template-Pfad: $TPL_DIR"
echo "Shop-Pfad:     $SHOP_DIR"
echo ""

# 1. Backup
echo ">>> Schritt 1/4: Backup erstellen..."
mkdir -p "$BACKUP_DIR/boxes" "$BACKUP_DIR/source/boxes"
[ -f "$TPL_DIR/boxes/box_login.html" ] && cp "$TPL_DIR/boxes/box_login.html" "$BACKUP_DIR/boxes/"
[ -f "$TPL_DIR/source/boxes/login.php" ] && cp "$TPL_DIR/source/boxes/login.php" "$BACKUP_DIR/source/boxes/"
[ -f "$TPL_DIR/source/boxes/loginbox.php" ] && cp "$TPL_DIR/source/boxes/loginbox.php" "$BACKUP_DIR/source/boxes/"
for lang in german english french dutch; do
    if [ -f "$SHOP_DIR/lang/$lang/extra/mrh_affiliate_login.php" ]; then
        mkdir -p "$BACKUP_DIR/lang_extra/$lang"
        cp "$SHOP_DIR/lang/$lang/extra/mrh_affiliate_login.php" "$BACKUP_DIR/lang_extra/$lang/"
    fi
done
echo "    Backup: $BACKUP_DIR"

# 2. Clone
echo ">>> Schritt 2/4: Repo klonen..."
git clone --depth 1 "$REPO_URL" "$TMP_DIR"
echo "    Geklont: $TMP_DIR"

# 3. Copy
echo ">>> Schritt 3/4: Dateien kopieren..."

# Template
cp "$TMP_DIR/tpl_mrh_2026/boxes/box_login.html" "$TPL_DIR/boxes/box_login.html"
echo "    OK: boxes/box_login.html"

# PHP-Dateien
cp "$TMP_DIR/tpl_mrh_2026/source/boxes/login.php" "$TPL_DIR/source/boxes/login.php"
echo "    OK: source/boxes/login.php"
cp "$TMP_DIR/tpl_mrh_2026/source/boxes/loginbox.php" "$TPL_DIR/source/boxes/loginbox.php"
echo "    OK: source/boxes/loginbox.php"

# Extra-Sprachdateien -> /lang/{sprache}/extra/
for lang in german english french dutch; do
    if [ -f "$TMP_DIR/lang_extra/$lang/mrh_affiliate_login.php" ]; then
        mkdir -p "$SHOP_DIR/lang/$lang/extra"
        cp "$TMP_DIR/lang_extra/$lang/mrh_affiliate_login.php" "$SHOP_DIR/lang/$lang/extra/mrh_affiliate_login.php"
        echo "    OK: lang/$lang/extra/mrh_affiliate_login.php"
    fi
done

# 4. Cleanup
echo ">>> Schritt 4/4: Aufraumen..."
rm -rf "$TMP_DIR"
echo ""
echo "=== FERTIG! Dateien deployed ==="
echo "  Template:"
echo "    - boxes/box_login.html"
echo "  PHP:"
echo "    - source/boxes/login.php (LINK_AFFILIATE via xtc_href_link)"
echo "    - source/boxes/loginbox.php (LINK_AFFILIATE als Fallback)"
echo "  Sprachdateien (in /lang/{sprache}/extra/):"
echo "    - german/extra/mrh_affiliate_login.php"
echo "    - english/extra/mrh_affiliate_login.php"
echo "    - french/extra/mrh_affiliate_login.php"
echo "    - dutch/extra/mrh_affiliate_login.php"
echo ""
echo "Backup: $BACKUP_DIR"
echo ""
echo "Zum Rueckgaengigmachen:"
echo "  cp $BACKUP_DIR/boxes/* $TPL_DIR/boxes/"
echo "  cp $BACKUP_DIR/source/boxes/* $TPL_DIR/source/boxes/"
