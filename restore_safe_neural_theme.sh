#!/bin/bash

# ==============================================================================
# XOSID NEURAL - RESTORE TO SAFE POSITION SCRIPT
# ==============================================================================
# Skrip ini memulihkan seluruh kustomisasi tema neural, tombol toggle tema,
# serta perluasan data profil desa yang telah diselesaikan pada tanggal 16 Juni 2026.
# ==============================================================================

GREEN='\033[0;32m'
CYAN='\033[0;36m'
YELLOW='\033[1;33m'
RED='\033[0;31m'
NC='\033[0m' # No Color

echo -e "${CYAN}======================================================================${NC}"
echo -e "${CYAN}        XOSID NEURAL - PEMULIHAN KE POSISI AMAN TEMA NEURAL          ${NC}"
echo -e "${CYAN}======================================================================${NC}"
echo -e "Memulai proses restorasi berkas kustomisasi..."

# Verifikasi folder backup
BACKUP_DIR="xosid_backups/safe-neural-theme"
if [ ! -d "$BACKUP_DIR" ]; then
    echo -e "${RED}[ERROR] Folder backup '$BACKUP_DIR' tidak ditemukan!${NC}"
    echo -e "Pastikan Anda menjalankan skrip ini dari root project XOSID."
    exit 1
fi

# 1. Pemulihan layout dan view blade xosid portal
echo -e "\n${CYAN}[1/4] Memulihkan berkas tampilan (.blade.php)...${NC}"
for file in portal_layout.blade.php profil.blade.php potensi.blade.php opendata.blade.php lapak.blade.php kabardesa.blade.php detail.blade.php surat_mandiri.blade.php portal2.blade.php; do
    if [ -f "$BACKUP_DIR/xosid/$file" ]; then
        cp "$BACKUP_DIR/xosid/$file" resources/views/xosid/
        echo -e "  ${GREEN}[OK]${NC} Tampilan resources/views/xosid/$file berhasil dipulihkan."
    else
        echo -e "  ${YELLOW}[WARN]${NC} Berkas backup untuk $file tidak ditemukan."
    fi
done

# 2. Pemulihan interceptor portal
echo -e "\n${CYAN}[2/4] Memulihkan berkas interceptor portal...${NC}"
if [ -f "$BACKUP_DIR/xosid_portal2_interceptor.php" ]; then
    cp "$BACKUP_DIR/xosid_portal2_interceptor.php" ./
    echo -e "  ${GREEN}[OK]${NC} xosid_portal2_interceptor.php berhasil dipulihkan."
else
    echo -e "  ${YELLOW}[WARN]${NC} Berkas backup xosid_portal2_interceptor.php tidak ditemukan."
fi

# 3. Pemulihan emergency autoloader
echo -e "\n${CYAN}[3/4] Memulihkan berkas emergency autoloader...${NC}"
if [ -f "$BACKUP_DIR/xosid_emergency_autoload.php" ]; then
    cp "$BACKUP_DIR/xosid_emergency_autoload.php" ./
    echo -e "  ${GREEN}[OK]${NC} xosid_emergency_autoload.php berhasil dipulihkan."
else
    echo -e "  ${YELLOW}[WARN]${NC} Berkas backup xosid_emergency_autoload.php tidak ditemukan."
fi

# 4. Pembersihan Cache View Laravel
echo -e "\n${CYAN}[4/4] Membersihkan cache tampilan Laravel...${NC}"
if docker ps | grep -q "xosid_neural_web"; then
    docker exec xosid_neural_web bash -c "rm -f /var/www/html/storage/framework/views/*"
    echo -e "  ${GREEN}[OK]${NC} Cache tampilan Laravel di dalam Docker berhasil dibersihkan."
else
    if command -v php &> /dev/null; then
        php artisan view:clear &> /dev/null
        echo -e "  ${GREEN}[OK]${NC} Cache tampilan Laravel (Lokal) berhasil dibersihkan."
    else
        echo -e "  ${YELLOW}[WARN]${NC} Container 'xosid_neural_web' tidak aktif dan PHP lokal tidak ditemukan."
    fi
fi

echo -e "\n${CYAN}======================================================================${NC}"
echo -e "${GREEN}[SUCCESS] Sistem berhasil dikembalikan ke Posisi Aman Tema Neural!${NC}"
echo -e "${CYAN}======================================================================${NC}"
