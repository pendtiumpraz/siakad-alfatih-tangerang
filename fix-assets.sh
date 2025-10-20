#!/bin/bash

##############################################################################
# Fix CSS/Assets Not Loading in Production
##############################################################################

set -e

RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m'

echo -e "${BLUE}"
echo "============================================"
echo "  Fix CSS/Assets Not Loading"
echo "  siakad.diproses.online"
echo "============================================"
echo -e "${NC}\n"

SSH_USER="cintaban"
SSH_HOST="siakad.diproses.online"
DEPLOY_PATH="/home/cintaban/siakad.diproses.online"

# Check if we can build locally
if command -v npm &> /dev/null; then
    echo -e "${GREEN}✅ Node.js found locally${NC}"
    BUILD_LOCALLY=true
else
    echo -e "${YELLOW}⚠️  Node.js not found locally${NC}"
    BUILD_LOCALLY=false
fi

# Build assets locally
if [ "$BUILD_LOCALLY" = true ]; then
    echo -e "${BLUE}Building assets locally...${NC}"
    npm install
    npm run build

    if [ -d "public/build" ]; then
        echo -e "${GREEN}✅ Assets built successfully${NC}\n"

        echo -e "${BLUE}Copying assets to server...${NC}"
        rsync -avz -e "ssh -i /mnt/host/d/AI/SIAKAD/id_rsa" \
            public/build/ \
            ${SSH_USER}@${SSH_HOST}:${DEPLOY_PATH}/public/build/
        echo -e "${GREEN}✅ Assets copied to server${NC}\n"
    else
        echo -e "${RED}❌ Build failed${NC}"
        exit 1
    fi
fi

# Fix on server
echo -e "${BLUE}Fixing configuration on server...${NC}"
ssh -i /mnt/host/d/AI/SIAKAD/id_rsa ${SSH_USER}@${SSH_HOST} << 'ENDSSH'
    cd /home/cintaban/siakad.diproses.online

    echo "📝 Checking .env configuration..."

    # Check APP_URL
    if grep -q "APP_URL=https://siakad.diproses.online" .env; then
        echo "✅ APP_URL is correct"
    else
        echo "⚠️  Fixing APP_URL..."
        sed -i 's|^APP_URL=.*|APP_URL=https://siakad.diproses.online|g' .env
    fi

    # Check APP_ENV
    if grep -q "APP_ENV=production" .env; then
        echo "✅ APP_ENV is correct"
    else
        echo "⚠️  Fixing APP_ENV..."
        sed -i 's|^APP_ENV=.*|APP_ENV=production|g' .env
    fi

    # Check APP_DEBUG
    if grep -q "APP_DEBUG=false" .env; then
        echo "✅ APP_DEBUG is correct"
    else
        echo "⚠️  Fixing APP_DEBUG..."
        sed -i 's|^APP_DEBUG=.*|APP_DEBUG=false|g' .env
    fi

    echo ""
    echo "🔗 Creating storage link..."
    php artisan storage:link || echo "Storage link already exists"

    echo ""
    echo "🧹 Clearing caches..."
    php artisan config:clear
    php artisan cache:clear
    php artisan route:clear
    php artisan view:clear

    echo ""
    echo "📦 Caching configuration..."
    php artisan config:cache
    php artisan route:cache
    php artisan view:cache

    echo ""
    echo "🔧 Setting permissions..."
    chmod -R 775 storage bootstrap/cache
    chmod -R 775 public/build 2>/dev/null || true

    echo ""
    echo "✅ Server configuration fixed!"
ENDSSH

echo -e "\n${GREEN}============================================${NC}"
echo -e "${GREEN}✅ Fix completed!${NC}"
echo -e "${GREEN}============================================${NC}\n"

echo -e "${YELLOW}Next steps:${NC}"
echo "1. Open browser and hard refresh: Ctrl+Shift+R"
echo "2. Check: https://siakad.diproses.online"
echo "3. Open DevTools (F12) → Console tab to check for errors"
echo ""

if [ "$BUILD_LOCALLY" = false ]; then
    echo -e "${YELLOW}⚠️  Node.js not found locally!${NC}"
    echo "Assets need to be built on server. SSH and run:"
    echo "  ssh ${SSH_USER}@${SSH_HOST}"
    echo "  cd ${DEPLOY_PATH}"
    echo "  npm install && npm run build"
    echo ""
fi
ENDSSH

chmod +x fix-assets.sh
