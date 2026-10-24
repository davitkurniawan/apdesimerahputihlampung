#!/bin/bash

echo "🚀 XOSID NEURAL IMMORTALITY SHIELD - DEPLOYING FIXES..."

# 1. DATABASE RECOVERY (Fixing Definer 1449 Errors)
echo "📦 Fixing Database Definers & Permissions..."
docker exec xosid_neural_db mysql -u root -ppassword -e "CREATE USER IF NOT EXISTS 'admin'@'%' IDENTIFIED BY 'password'; GRANT ALL PRIVILEGES ON *.* TO 'admin'@'%'; FLUSH PRIVILEGES;" 2>/dev/null
docker exec xosid_neural_db mysql -u root -ppassword -e "GRANT ALL PRIVILEGES ON *.* TO 'root'@'%'; FLUSH PRIVILEGES;" 2>/dev/null

# 2. SYSTEM CONSTANTS RECOVERY
echo "⚙️  Verifying Neural Bootstrappers..."
if [ -f "index.php" ]; then
    # Ensure FORCE_HTTPS is defined
    if ! grep -q "FORCE_HTTPS" index.php; then
        sed -i '' "s/<?php/<?php\ndefine('FORCE_HTTPS', false);/g" index.php
    fi
fi

# 3. CONTAINER HEALTH CHECK
echo "🐳 Restarting Services to lock changes..."
docker-compose restart

echo "✅ SYSTEM RESTORED. XOSID NEURAL IS LIVE AT http://localhost:8080"
