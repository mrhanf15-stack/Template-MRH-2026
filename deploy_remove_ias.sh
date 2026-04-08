#!/bin/bash
# ============================================================
#  IAS (Infinite Ajax Scroll) – KOMPLETT ENTFERNEN
#  Datum: 08. April 2026
#  Betrifft: mr-hanf.at / tpl_mrh_2026
# ============================================================

set -e

TPL="/home/www/doc/28856/dcp288560004/mr-hanf.at/www/templates/tpl_mrh_2026"
GH="https://raw.githubusercontent.com/mrhanf15-stack/Template-MRH-2026/main/tpl_mrh_2026"
BACKUP_DIR="$TPL/backup_ias_removal_$(date +%Y%m%d_%H%M%S)"

echo "============================================================"
echo "  IAS (Infinite Ajax Scroll) – Entfernung"
echo "  $(date)"
echo "============================================================"

# 1. Backup
echo "[1/5] Backup erstellen..."
mkdir -p "$BACKUP_DIR"
[ -f "$TPL/javascript/infiniteajaxscroll-com.js" ] && cp "$TPL/javascript/infiniteajaxscroll-com.js" "$BACKUP_DIR/"
[ -f "$TPL/img/iasloader.gif" ] && cp "$TPL/img/iasloader.gif" "$BACKUP_DIR/"
echo "  → Backup in: $BACKUP_DIR"

# 2. IAS JavaScript entfernen
echo "[2/5] infiniteajaxscroll-com.js entfernen..."
if [ -f "$TPL/javascript/infiniteajaxscroll-com.js" ]; then
  rm -f "$TPL/javascript/infiniteajaxscroll-com.js"
  echo "  → infiniteajaxscroll-com.js GELÖSCHT"
else
  echo "  → infiniteajaxscroll-com.js nicht gefunden (bereits entfernt?)"
fi

# 3. IAS Loader GIF entfernen
echo "[3/5] iasloader.gif entfernen..."
if [ -f "$TPL/img/iasloader.gif" ]; then
  rm -f "$TPL/img/iasloader.gif"
  echo "  → iasloader.gif GELÖSCHT"
else
  echo "  → iasloader.gif nicht gefunden (bereits entfernt?)"
fi

# 4. CSS aktualisieren (enthält jetzt .ias_spinner { display: none !important })
echo "[4/5] pagination_layout.css aktualisieren (IAS-Spinner Fallback)..."
curl -sSL "$GH/css/pagination_layout.css" -o "$TPL/css/pagination_layout.css"
echo "  → pagination_layout.css aktualisiert"

# 5. Cache leeren
echo "[5/5] Cache leeren..."
rm -rf /home/www/doc/28856/dcp288560004/mr-hanf.at/www/templates_c/*
curl -s "https://mr-hanf.at/opcache_reset.php?token=MrHanf2024Reset" > /dev/null 2>&1
echo "  → Template-Cache und OPcache geleert"

echo ""
echo "============================================================"
echo "  IAS ENTFERNUNG ABGESCHLOSSEN"
echo "============================================================"
echo "  GELÖSCHT:"
echo "    - javascript/infiniteajaxscroll-com.js"
echo "    - img/iasloader.gif"
echo "  AKTUALISIERT:"
echo "    - css/pagination_layout.css (.ias_spinner ausgeblendet)"
echo "  BACKUP:"
echo "    $BACKUP_DIR"
echo "============================================================"
