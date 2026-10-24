#!/bin/bash

# Configuration
INTERVAL=30 # Jeda waktu pengecekan (dalam detik)
PROJECT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
BRANCH="main"

echo "================================================="
echo "   XOSID Git Auto-Sync Service Berjalan..."
echo "   Folder Target: $PROJECT_DIR"
echo "   Branch Aktif : $BRANCH"
echo "   Interval Cek : $INTERVAL detik"
echo "   Tekan [CTRL+C] untuk menghentikan layanan"
echo "================================================="

cd "$PROJECT_DIR" || exit 1

# Pastikan git dikonfigurasi dengan benar di folder ini
if ! git rev-parse --is-inside-work-tree >/dev/null 2>&1; then
    echo "Error: Folder ini bukan repositori Git!"
    exit 1
fi

while true; do
    # Cek apakah ada perubahan (tambah, edit, hapus file)
    CHANGES=$(git status --porcelain)
    if [[ -n "$CHANGES" ]]; then
        echo -e "\n[$(date '+%Y-%m-%d %H:%M:%S')] Perubahan terdeteksi. Memulai sinkronisasi..."
        
        # Tampilkan file apa saja yang berubah secara singkat
        echo "$CHANGES" | sed 's/^/  /'
        
        # Add semua file baru/terubah/terhapus
        git add -A
        
        # Commit perubahan
        COMMIT_MSG="auto: sync $(date '+%Y-%m-%d %H:%M:%S')"
        git commit -m "$COMMIT_MSG"
        
        # Push ke GitHub
        echo "Mengirim ke GitHub (git push origin $BRANCH)..."
        git push origin "$BRANCH"
        
        if [ $? -eq 0 ]; then
            echo "[$(date '+%Y-%m-%d %H:%M:%S')] Berhasil disinkronkan ke GitHub! ✅"
        else
            echo "[$(date '+%Y-%m-%d %H:%M:%S')] Peringatan: Push gagal. Akan dicoba lagi pada interval berikutnya. ⚠️"
        fi
    fi
    
    # Tunggu untuk interval berikutnya
    sleep "$INTERVAL"
done
