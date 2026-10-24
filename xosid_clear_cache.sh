#!/bin/bash
# xosid_clear_cache.sh — Clear semua cache setelah update
XOSID_DIR="$HOME/Downloads/XOSID"
GREEN='\033[0;32m'; NC='\033[0m'; BOLD='\033[1m'

echo -e "${BOLD}🧹 Clearing XOSID cache...${NC}"

find "$XOSID_DIR/storage/framework/cache/data" -type f -delete 2>/dev/null
find "$XOSID_DIR/storage/framework/views" -type f -delete 2>/dev/null
find "$XOSID_DIR/storage/framework/sessions" -type f -delete 2>/dev/null
find "$XOSID_DIR/desa/cache" -name "*.php" -delete 2>/dev/null

echo -e "${GREEN}✅ Cache cleared!${NC}"
