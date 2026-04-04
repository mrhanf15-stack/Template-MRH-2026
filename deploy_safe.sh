#!/bin/bash
# ============================================================
# SICHERES DEPLOYMENT – MRH 2026 Template
# ============================================================
# Regel: HTML überschreiben, CSS + Sprachdateien nur ERGÄNZEN
# Datum: 04.04.2026
# ============================================================

set -e  # Bei Fehler abbrechen

TPL="/home/www/doc/28856/dcp288560004/mr-hanf.at/www/templates/tpl_mrh_2026"
GH="https://raw.githubusercontent.com/mrhanf15-stack/Template-MRH-2026/main/tpl_mrh_2026"
BACKUP_DIR="$TPL/backup_$(date +%Y%m%d_%H%M%S)"

echo "============================================================"
echo "  MRH 2026 – Sicheres Deployment"
echo "  $(date)"
echo "============================================================"

# ============================================================
# SCHRITT 1: Backup erstellen
# ============================================================
echo ""
echo "[1/6] Backup erstellen..."
mkdir -p "$BACKUP_DIR/module"
mkdir -p "$BACKUP_DIR/css"
mkdir -p "$BACKUP_DIR/lang/german"
mkdir -p "$BACKUP_DIR/lang/english"
mkdir -p "$BACKUP_DIR/lang/french"
mkdir -p "$BACKUP_DIR/lang/spanish"

# Seedfinder HTML sichern
cp "$TPL/module/seedfinder.html" "$BACKUP_DIR/module/" 2>/dev/null || true
cp "$TPL/module/seedfinder_beginner_results.html" "$BACKUP_DIR/module/" 2>/dev/null || true
cp "$TPL/module/seedfinder_product_cards.html" "$BACKUP_DIR/module/" 2>/dev/null || true

# CSS sichern
cp "$TPL/css/seedfinder.css" "$BACKUP_DIR/css/" 2>/dev/null || true
cp "$TPL/css/seedfinder-combined.css" "$BACKUP_DIR/css/" 2>/dev/null || true
cp "$TPL/css/seedfinder-combined.min.css" "$BACKUP_DIR/css/" 2>/dev/null || true

# Conf-Dateien sichern
for lang in german english french spanish; do
    cp "$TPL/lang/$lang/lang_$lang.conf" "$BACKUP_DIR/lang/$lang/" 2>/dev/null || true
    cp "$TPL/lang/$lang/seedfinder_section.conf" "$BACKUP_DIR/lang/$lang/" 2>/dev/null || true
done

echo "  → Backup in: $BACKUP_DIR"

# ============================================================
# SCHRITT 2: Seedfinder HTML ÜBERSCHREIBEN (sicher)
# ============================================================
echo ""
echo "[2/6] Seedfinder HTML überschreiben..."
curl -sSL "$GH/module/seedfinder.html" -o "$TPL/module/seedfinder.html"
curl -sSL "$GH/module/seedfinder_beginner_results.html" -o "$TPL/module/seedfinder_beginner_results.html"
curl -sSL "$GH/module/seedfinder_product_cards.html" -o "$TPL/module/seedfinder_product_cards.html"
echo "  → 3 HTML-Dateien überschrieben"

# ============================================================
# SCHRITT 3: CSS nur ERGÄNZEN (neue Dateien hinzufügen)
# ============================================================
echo ""
echo "[3/6] CSS ergänzen..."

# seedfinder.css: Nur überschreiben, da seedfinder-spezifisch
# und die neuen HTML-Templates darauf verweisen
curl -sSL "$GH/css/seedfinder.css" -o "$TPL/css/seedfinder.css"
echo "  → seedfinder.css aktualisiert (seedfinder-spezifisch)"

# seedfinder_disabled_headers.css: NEUE Datei hinzufügen
curl -sSL "$GH/css/seedfinder_disabled_headers.css" -o "$TPL/css/seedfinder_disabled_headers.css"
echo "  → seedfinder_disabled_headers.css hinzugefügt (NEU)"

# Alte combined-Dateien entfernen (werden nicht mehr referenziert)
rm -f "$TPL/css/seedfinder-combined.min.css"
rm -f "$TPL/css/seedfinder-combined.css"
echo "  → Alte seedfinder-combined CSS entfernt"

# ============================================================
# SCHRITT 4: Seedfinder Section-Dateien ÜBERSCHREIBEN (separate Dateien)
# ============================================================
echo ""
echo "[4/6] Seedfinder Section-Dateien überschreiben..."
for lang in german english french spanish; do
    curl -sSL "$GH/lang/$lang/seedfinder_section.conf" -o "$TPL/lang/$lang/seedfinder_section.conf"
    echo "  → $lang/seedfinder_section.conf überschrieben"
done

# ============================================================
# SCHRITT 5: Conf-Dateien NUR ERGÄNZEN (APPEND, nicht überschreiben!)
# ============================================================
echo ""
echo "[5/6] Sprachvariablen an Conf-Dateien ANHÄNGEN..."

for lang in german english french spanish; do
    CONF="$TPL/lang/$lang/lang_$lang.conf"
    
    # Prüfen ob die neuen Sections bereits vorhanden sind
    if grep -q "\[bottom_bar\]" "$CONF" 2>/dev/null; then
        echo "  → $lang: Neue Sections bereits vorhanden – ÜBERSPRUNGEN"
    else
        # Neue Sections von GitHub herunterladen und ANHÄNGEN
        echo "" >> "$CONF"
        curl -sSL "$GH/lang/$lang/lang_additions.conf" >> "$CONF"
        echo "  → $lang: 134 neue Variablen angehängt (10 Sections)"
    fi
    
    # [seedfinder] Section in der Conf durch neue Version ersetzen
    # Verwende Python für sicheres Ersetzen
    python3 -c "
import re
conf_path = '$CONF'
sect_path = '$TPL/lang/$lang/seedfinder_section.conf'
with open(conf_path, 'r') as f:
    content = f.read()
# Alte [seedfinder] Section entfernen (bis zur nächsten Section)
content = re.sub(r'\[seedfinder\].*?(?=\n\[|\Z)', '', content, flags=re.DOTALL)
# Neue seedfinder Section aus separater Datei laden
with open(sect_path, 'r') as f:
    new_section = f.read()
# Vor [bottom_bar] einfügen (falls vorhanden) oder am Ende anhängen
if '[bottom_bar]' in content:
    content = content.replace('[bottom_bar]', new_section.rstrip() + '\n\n[bottom_bar]')
else:
    content = content.rstrip() + '\n\n' + new_section + '\n'
with open(conf_path, 'w') as f:
    f.write(content)
print(f'  → $lang: [seedfinder] Section ersetzt')
"
done

# ============================================================
# SCHRITT 6: Cache leeren
# ============================================================
echo ""
echo "[6/6] Cache leeren..."
rm -rf /home/www/doc/28856/dcp288560004/mr-hanf.at/www/templates_c/*
curl -s "https://mr-hanf.at/opcache_reset.php?token=MrHanf2024Reset" > /dev/null 2>&1
echo "  → Template-Cache und OPcache geleert"

# ============================================================
# ZUSAMMENFASSUNG
# ============================================================
echo ""
echo "============================================================"
echo "  DEPLOYMENT ABGESCHLOSSEN"
echo "============================================================"
echo ""
echo "  ÜBERSCHRIEBEN:"
echo "    - module/seedfinder.html"
echo "    - module/seedfinder_beginner_results.html"
echo "    - module/seedfinder_product_cards.html"
echo "    - css/seedfinder.css"
echo "    - lang/*/seedfinder_section.conf"
echo ""
echo "  NEU HINZUGEFÜGT:"
echo "    - css/seedfinder_disabled_headers.css"
echo ""
echo "  ANGEHÄNGT (nicht überschrieben):"
echo "    - lang/*/lang_*.conf (134 neue Vars, 10 Sections)"
echo ""
echo "  ENTFERNT:"
echo "    - css/seedfinder-combined.css"
echo "    - css/seedfinder-combined.min.css"
echo ""
echo "  NICHT BERÜHRT:"
echo "    - Megamenü-Variablen (bleiben intakt)"
echo "    - Alle anderen CSS-Dateien"
echo "    - Alle anderen Template-Dateien"
echo ""
echo "  Backup: $BACKUP_DIR"
echo "============================================================"
