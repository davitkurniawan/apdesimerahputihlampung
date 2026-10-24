#!/bin/bash

# ==============================================================================
# XOSID NEURAL CUSTOMIZATION RESTORATION SCRIPT
# ==============================================================================
# Skrip ini digunakan untuk memulihkan seluruh kustomisasi tampilan XOSID Neural
# yang telah diperbaiki agar tidak hilang/rusak setelah melakukan update upstream.
# ==============================================================================

# Warna output terminal
GREEN='\033[0;32m'
CYAN='\033[0;36m'
YELLOW='\033[1;33m'
RED='\033[0;31m'
NC='\033[0m' # No Color

echo -e "${CYAN}======================================================================${NC}"
echo -e "${CYAN}             XOSID NEURAL - RESTORASI KUSTOMISASI TAMPILAN            ${NC}"
echo -e "${CYAN}======================================================================${NC}"
echo -e "Memulai proses pemulihan berkas tampilan..."

# Verifikasi folder backup
if [ ! -d "xosid_backups" ]; then
    echo -e "${RED}[ERROR] Folder 'xosid_backups' tidak ditemukan di direktori saat ini!${NC}"
    echo -e "Pastikan Anda menjalankan skrip ini dari root project XOSID."
    exit 1
fi

# 1. Pemulihan Form Identitas Desa
if [ -f "xosid_backups/identitas_desa/form.blade.php" ]; then
    cp xosid_backups/identitas_desa/form.blade.php resources/views/admin/identitas_desa/
    echo -e "${GREEN}[OK]${NC} Tampilan Form Identitas Desa berhasil dipulihkan."
else
    echo -e "${YELLOW}[WARN]${NC} Backup form.blade.php tidak ditemukan."
fi

# 2. Pemulihan Tab Umum Identitas Desa
if [ -f "xosid_backups/identitas_desa/tab-umum.blade.php" ]; then
    cp xosid_backups/identitas_desa/tab-umum.blade.php resources/views/admin/identitas_desa/
    echo -e "${GREEN}[OK]${NC} Tab Data Administrasi berhasil dipulihkan."
else
    echo -e "${YELLOW}[WARN]${NC} Backup tab-umum.blade.php tidak ditemukan."
fi

# 3. Pemulihan Select2 Pencarian Desa Lokal
if [ -f "xosid_backups/components/select2_desa.blade.php" ]; then
    cp xosid_backups/components/select2_desa.blade.php resources/views/admin/layouts/components/
    echo -e "${GREEN}[OK]${NC} Script Pencarian Desa Lokal (Select2) berhasil dipulihkan."
else
    echo -e "${YELLOW}[WARN]${NC} Backup select2_desa.blade.php tidak ditemukan."
fi

# 4. Pemulihan Peta Kantor (XOSID Spatial Studio)
if [ -f "xosid_backups/wilayah/maps_kantor.blade.php" ]; then
    cp xosid_backups/wilayah/maps_kantor.blade.php resources/views/admin/wilayah/
    echo -e "${GREEN}[OK]${NC} Peta Lokasi Kantor Desa (Spatial Studio) berhasil dipulihkan."
else
    echo -e "${YELLOW}[WARN]${NC} Backup maps_kantor.blade.php tidak ditemukan."
fi

# 5. Pemulihan Peta Wilayah (XOSID Spatial Studio)
if [ -f "xosid_backups/wilayah/maps_wilayah.blade.php" ]; then
    cp xosid_backups/wilayah/maps_wilayah.blade.php resources/views/admin/wilayah/
    echo -e "${GREEN}[OK]${NC} Peta Batas Wilayah Desa (Spatial Studio) berhasil dipulihkan."
else
    echo -e "${YELLOW}[WARN]${NC} Backup maps_wilayah.blade.php tidak ditemukan."
fi

echo -e "\n${CYAN}======================================================================${NC}"
echo -e "Membersihkan cache tampilan Laravel..."

# Membersihkan cache view Laravel di dalam Docker
if docker ps | grep -q "xosid_neural_web"; then
    docker exec xosid_neural_web php artisan view:clear
    echo -e "${GREEN}[OK]${NC} Cache tampilan Laravel berhasil dibersihkan."
else
    # Fallback jika dijalankan di lokal tanpa docker
    if command -v php &> /dev/null; then
        php artisan view:clear &> /dev/null
        echo -e "${GREEN}[OK]${NC} Cache tampilan Laravel berhasil dibersihkan (Lokal)."
    else
        echo -e "${YELLOW}[WARN]${NC} Container docker 'xosid_neural_web' tidak aktif dan PHP lokal tidak ditemukan."
        echo -e "Silakan jalankan 'php artisan view:clear' secara manual setelah web server aktif."
    fi
fi

echo -e "${CYAN}======================================================================${NC}"
echo -e "${GREEN}[SUCCESS] Seluruh Tampilan Premium XOSID Neural Berhasil Dipulihkan!${NC}"
echo -e "${CYAN}======================================================================${NC}"
