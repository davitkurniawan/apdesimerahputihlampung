#!/bin/bash
# ============================================================
# xosid_update_diff.sh
# XOSID Monthly Update Analyzer
# Usage: ./xosid_update_diff.sh <versi_lama> <versi_baru>
# Contoh: ./xosid_update_diff.sh 2605 2606
# ============================================================

OLD_VER="${1:-2605}"
NEW_VER="${2:-2606}"
BASE_DIR="$HOME/Downloads"
OLD_DIR="$BASE_DIR/rilis-premium-$OLD_VER.0.0"
NEW_DIR="$BASE_DIR/rilis-premium-$NEW_VER.0.0"
REPORT="$HOME/Downloads/XOSID/xosid_update_report_$NEW_VER.md"

# Warna
RED='\033[0;31m'; YELLOW='\033[1;33m'; GREEN='\033[0;32m'; NC='\033[0m'; BOLD='\033[1m'

echo -e "${BOLD}╔══════════════════════════════════════════════╗${NC}"
echo -e "${BOLD}║   XOSID Update Analyzer: $OLD_VER → $NEW_VER       ║${NC}"
echo -e "${BOLD}╚══════════════════════════════════════════════╝${NC}"
echo ""

# Validasi direktori
if [ ! -d "$OLD_DIR" ]; then echo -e "${RED}ERROR: $OLD_DIR tidak ditemukan.${NC}"; exit 1; fi
if [ ! -d "$NEW_DIR" ]; then echo -e "${RED}ERROR: $NEW_DIR tidak ditemukan.${NC}"; exit 1; fi

echo -e "${BOLD}📋 Catatan Rilis $NEW_VER:${NC}"
echo "────────────────────────────────────────────"
head -50 "$NEW_DIR/catatan_rilis.md" 2>/dev/null || echo "(tidak ada catatan rilis)"
echo ""

echo -e "${BOLD}🔍 Menganalisis perubahan...${NC}"

# File yang berubah di LUAR vendor/ dan donjo-app/
CHANGED_NON_ENCRYPTED=$(diff -rq \
    --exclude="*.log" --exclude=".DS_Store" --exclude="*.cache" \
    "$OLD_DIR" "$NEW_DIR" 2>/dev/null \
    | grep -v "/vendor/" \
    | grep -v "/donjo-app/" \
    | grep -v "/node_modules/")

# File yang berubah DI donjo-app/
CHANGED_ENCRYPTED=$(diff -rq "$OLD_DIR/donjo-app" "$NEW_DIR/donjo-app" 2>/dev/null | wc -l)

# File baru/dihapus
FILES_ONLY_NEW=$(diff -rq "$OLD_DIR" "$NEW_DIR" 2>/dev/null | grep "Only in $NEW_DIR" | grep -v vendor | grep -v donjo-app)
FILES_ONLY_OLD=$(diff -rq "$OLD_DIR" "$NEW_DIR" 2>/dev/null | grep "Only in $OLD_DIR" | grep -v vendor | grep -v donjo-app)

# Cek apakah index.php upstream berubah
INDEX_CHANGED=""
diff -q "$OLD_DIR/index.php" "$NEW_DIR/index.php" &>/dev/null || INDEX_CHANGED="⚠️  YA — PERLU CEK MANUAL"

# Cek migration baru
MIGRATIONS_NEW=$(diff -rq "$OLD_DIR/Modules" "$NEW_DIR/Modules" 2>/dev/null | grep "Only in $NEW_DIR.*Migration" | head -20)

# Buat laporan
cat > "$REPORT" << REPORT_EOF
# XOSID Update Report: $OLD_VER → $NEW_VER
*Generated: $(date '+%Y-%m-%d %H:%M:%S')*

## Ringkasan

| Item | Jumlah |
|------|--------|
| File berubah (non-encrypted) | $(echo "$CHANGED_NON_ENCRYPTED" | grep -c "Files") |
| File berubah di donjo-app (encrypted) | $CHANGED_ENCRYPTED |
| File BARU di $NEW_VER | $(echo "$FILES_ONLY_NEW" | grep -c .) |
| File DIHAPUS di $NEW_VER | $(echo "$FILES_ONLY_OLD" | grep -c .) |
| index.php upstream berubah? | ${INDEX_CHANGED:-"✅ Tidak"} |

## Catatan Rilis
$(cat "$NEW_DIR/catatan_rilis.md" 2>/dev/null)

## ⚠️ File Kritis yang Berubah (Perlu Review)

### index.php upstream
${INDEX_CHANGED:-"✅ Tidak berubah — tidak ada aksi diperlukan."}

### Migration Baru (Perlu dijalankan di semua tenant DB)
\`\`\`
$MIGRATIONS_NEW
\`\`\`

### File Baru (Only in $NEW_VER)
\`\`\`
$FILES_ONLY_NEW
\`\`\`

### File Dihapus (Only in $OLD_VER)
\`\`\`
$FILES_ONLY_OLD
\`\`\`

### File Non-Encrypted yang Berubah
\`\`\`
$CHANGED_NON_ENCRYPTED
\`\`\`

## Checklist Update

- [ ] Baca catatan rilis
- [ ] Cek laporan ini
- [ ] Backup XOSID sebelum update: \`git add . && git commit -m "backup sebelum update $NEW_VER"\`
- [ ] Jalankan rsync update: \`./xosid_apply_update.sh $NEW_VER\`
- [ ] Jalankan migration di tenant: \`./xosid_run_migrations.sh\`
- [ ] Test di staging (bumidaya)
- [ ] Perbarui baseline SQL: \`./xosid_refresh_baseline.sh\`
- [ ] Commit: \`git commit -m "feat: update upstream ke $NEW_VER.0.0"\`
REPORT_EOF

echo -e "${GREEN}✅ Laporan berhasil dibuat: $REPORT${NC}"
echo ""
echo -e "${BOLD}📊 RINGKASAN CEPAT:${NC}"
echo "────────────────────────────────────────────"
echo -e "  File berubah (non-encrypted): ${YELLOW}$(echo "$CHANGED_NON_ENCRYPTED" | grep -c "Files")${NC}"
echo -e "  File berubah donjo-app:       ${YELLOW}$CHANGED_ENCRYPTED${NC}"
echo -e "  index.php upstream berubah?   ${INDEX_CHANGED:-"${GREEN}Tidak${NC}"}"
echo ""
if [ -n "$MIGRATIONS_NEW" ]; then
    echo -e "${RED}⚠️  ADA MIGRATION BARU! Perlu dijalankan di semua tenant:${NC}"
    echo "$MIGRATIONS_NEW"
    echo ""
fi
echo -e "Buka laporan lengkap: ${BOLD}$REPORT${NC}"
