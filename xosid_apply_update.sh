#!/bin/bash
# ============================================================
# xosid_apply_update.sh
# XOSID Safe Upstream Update Applier
# Usage: ./xosid_apply_update.sh <versi_baru>
# Contoh: ./xosid_apply_update.sh 2606
# ============================================================

NEW_VER="${1:-2606}"
BASE_DIR="$HOME/Downloads"
NEW_DIR="$BASE_DIR/rilis-premium-$NEW_VER.0.0"
XOSID_DIR="$BASE_DIR/XOSID"
PROTECTED="$XOSID_DIR/.xosid_protected"

RED='\033[0;31m'; YELLOW='\033[1;33m'; GREEN='\033[0;32m'; NC='\033[0m'; BOLD='\033[1m'

echo -e "${BOLD}╔══════════════════════════════════════════════╗${NC}"
echo -e "${BOLD}║   XOSID Safe Update Applier v$NEW_VER          ║${NC}"
echo -e "${BOLD}╚══════════════════════════════════════════════╝${NC}"
echo ""

# Validasi
if [ ! -d "$NEW_DIR" ]; then echo -e "${RED}ERROR: $NEW_DIR tidak ditemukan.${NC}"; exit 1; fi
if [ ! -f "$PROTECTED" ]; then echo -e "${RED}ERROR: .xosid_protected tidak ditemukan.${NC}"; exit 1; fi

# Wajib ada git
if [ ! -d "$XOSID_DIR/.git" ]; then
    echo -e "${RED}ERROR: $XOSID_DIR belum diinisialisasi git!${NC}"
    echo "Jalankan terlebih dahulu:"
    echo "  cd $XOSID_DIR && git init && git add . && git commit -m 'initial'"
    exit 1
fi

cd "$XOSID_DIR"

echo -e "${YELLOW}⚡ Langkah 1: Backup current state ke git...${NC}"
git add -A
git commit -m "backup: sebelum update ke upstream $NEW_VER.0.0 — $(date '+%Y-%m-%d %H:%M')" --allow-empty
echo -e "${GREEN}  ✅ Backup selesai.${NC}"
echo ""

echo -e "${YELLOW}⚡ Langkah 2: Apply upstream update (rsync + exclude protected)...${NC}"
rsync -av \
    --exclude-from="$PROTECTED" \
    --exclude=".git/" \
    --exclude="desa/" \
    --exclude="uploads/" \
    --exclude="storage/framework/sessions/" \
    --exclude="storage/framework/cache/" \
    --exclude="storage/logs/" \
    --exclude="storage/invoices/" \
    --exclude="storage/uploads/" \
    "$NEW_DIR/" "$XOSID_DIR/" \
    2>&1 | grep -E "^(sending|>f|>d|deleting)" | head -50

echo -e "${GREEN}  ✅ Upstream files updated.${NC}"
echo ""

echo -e "${YELLOW}⚡ Langkah 3: Verifikasi file XOSID masih intact...${NC}"
PROTECTED_INTACT=true
while IFS= read -r line; do
    # Skip komentar dan baris kosong
    [[ "$line" =~ ^#.*$ || -z "$line" ]] && continue
    ITEM="$XOSID_DIR/$line"
    # Hapus trailing wildcard untuk cek
    ITEM_CHECK="${ITEM%\**}"
    if [ ! -e "$ITEM_CHECK" ]; then
        echo -e "  ${YELLOW}⚠️  Tidak ditemukan: $line${NC}"
    else
        echo -e "  ${GREEN}✅ OK: $line${NC}"
    fi
done < "$PROTECTED"
echo ""

echo -e "${YELLOW}⚡ Langkah 4: Commit perubahan update...${NC}"
cd "$XOSID_DIR"
git add -A
git commit -m "feat: update upstream OpenSID ke $NEW_VER.0.0" --allow-empty
echo -e "${GREEN}  ✅ Commit selesai.${NC}"
echo ""

echo -e "${YELLOW}⚡ Langkah 5: Pemulihan Kustomisasi XOSID & Tema Neural...${NC}"
# 1. Restore public portal files
if [ -f "./restore_safe_neural_theme.sh" ]; then
    ./restore_safe_neural_theme.sh
fi

# 2. Restore database menu structure on tenant databases
if [ -f "scratch/restore_menu_xosid.sql" ]; then
    docker exec -i xosid_neural_db mysql -u root -ppassword xosid_tenant_bumidaya < scratch/restore_menu_xosid.sql
    docker exec -i xosid_neural_db mysql -u root -ppassword xosid_tenant_demo < scratch/restore_menu_xosid.sql
    echo -e "  ${GREEN}✅ Database menu structure restored for active tenants.${NC}"
fi

# 3. Grant access permissions to all groups for parent modules
docker exec -i xosid_neural_db mysql -u root -ppassword xosid_tenant_bumidaya -e "
INSERT IGNORE INTO grup_akses (config_id, id_grup, id_modul, akses)
SELECT 1, ug.id, sm.id, 7
FROM user_grup ug
CROSS JOIN (
    SELECT id FROM setting_modul WHERE id IN (550, 551, 552)
) sm;
"
docker exec -i xosid_neural_db mysql -u root -ppassword xosid_tenant_demo -e "
INSERT IGNORE INTO grup_akses (config_id, id_grup, id_modul, akses)
SELECT 1, ug.id, sm.id, 7
FROM user_grup ug
CROSS JOIN (
    SELECT id FROM setting_modul WHERE id IN (550, 551, 552)
) sm;
"
echo -e "  ${GREEN}✅ Parent module permissions granted to all user groups.${NC}"

# 4. Copy custom admin views back from backup
if [ -d "storage/xosid_backups/resources/views/admin" ]; then
    cp -r storage/xosid_backups/resources/views/admin/* resources/views/admin/
    echo -e "  ${GREEN}✅ Custom admin views restored.${NC}"
fi

# 5. Clear caches
docker exec xosid_neural_web bash -c "rm -f /var/www/html/storage/framework/views/*"
./xosid_clear_cache.sh
echo -e "  ${GREEN}✅ System caches cleared.${NC}"
echo ""

echo -e "${BOLD}${GREEN}════════════════════════════════════════════${NC}"
echo -e "${BOLD}${GREEN}  Update $NEW_VER berhasil diterapkan & dipulihkan! ${NC}"
echo -e "${BOLD}${GREEN}════════════════════════════════════════════${NC}"
echo ""
echo -e "  Langkah selanjutnya:"
echo -e "  1. Jalankan migration: ${BOLD}./xosid_run_migrations.sh${NC}"
echo -e "  2. Test bumidaya:      ${BOLD}http://bumidaya.localhost:8080${NC}"
echo -e "  3. Kalau OK, perbarui baseline SQL"
echo -e "     (docker exec xosid_neural_db mysqldump -u root -ppassword xosid_tenant_demo > ./xosid_neural_fully_restored_20260512.sql)"
