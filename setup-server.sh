#!/bin/bash

##############################################################################
# SIAKAD Server Setup Script
# Setup lengkap untuk server siakad.diproses.online
##############################################################################

set -e

# Colors
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m'

echo -e "${BLUE}"
echo "============================================"
echo "  SIAKAD Server Setup Script"
echo "  Domain: siakad.diproses.online"
echo "  User: cintaban"
echo "============================================"
echo -e "${NC}\n"

# Configuration
SSH_USER="cintaban"
SSH_HOST="siakad.diproses.online"
DEPLOY_PATH="/home/cintaban/siakad.diproses.online"
REPO_URL="https://github.com/YOUR-USERNAME/siakad-app.git"

echo -e "${YELLOW}Please update REPO_URL in this script with your actual GitHub repository URL${NC}\n"
read -p "Have you updated REPO_URL? (y/n) " -n 1 -r
echo
if [[ ! $REPLY =~ ^[Yy]$ ]]; then
    echo -e "${RED}Please edit setup-server.sh and set REPO_URL first${NC}"
    exit 1
fi

# Test SSH connection
echo -e "${BLUE}Testing SSH connection...${NC}"
if ssh -o ConnectTimeout=5 ${SSH_USER}@${SSH_HOST} "echo 'SSH OK'" > /dev/null 2>&1; then
    echo -e "${GREEN}✅ SSH connection successful${NC}\n"
else
    echo -e "${RED}❌ SSH connection failed${NC}"
    echo -e "${YELLOW}Please ensure:${NC}"
    echo "1. SSH key is added to server (~/.ssh/authorized_keys)"
    echo "2. Server is accessible"
    echo -e "\nRun this to add SSH key:"
    echo "cat /mnt/host/d/AI/SIAKAD/id_rsa.pub | ssh ${SSH_USER}@${SSH_HOST} \"mkdir -p ~/.ssh && cat >> ~/.ssh/authorized_keys && chmod 600 ~/.ssh/authorized_keys && chmod 700 ~/.ssh\""
    exit 1
fi

# Clone repository
echo -e "${BLUE}Cloning repository...${NC}"
ssh ${SSH_USER}@${SSH_HOST} << ENDSSH
    set -e
    cd /home/${SSH_USER}/

    if [ -d "siakad.diproses.online" ]; then
        echo "Directory already exists. Skipping clone..."
    else
        git clone ${REPO_URL} siakad.diproses.online
        echo "✅ Repository cloned"
    fi
ENDSSH

# Copy .env.production to server
echo -e "${BLUE}Copying .env configuration...${NC}"
scp .env.production ${SSH_USER}@${SSH_HOST}:${DEPLOY_PATH}/.env
echo -e "${GREEN}✅ .env copied${NC}\n"

# Setup application
echo -e "${BLUE}Setting up application...${NC}"
ssh ${SSH_USER}@${SSH_HOST} << 'ENDSSH'
    set -e
    cd /home/cintaban/siakad.diproses.online

    echo "Installing Composer dependencies..."
    composer install --no-interaction --no-dev --prefer-dist --optimize-autoloader

    echo "Generating application key..."
    php artisan key:generate

    echo "Running migrations..."
    php artisan migrate --force

    echo "Seeding database (if needed)..."
    php artisan db:seed --force || echo "No seeder or already seeded"

    echo "Setting permissions..."
    chmod -R 775 storage
    chmod -R 775 bootstrap/cache

    echo "Caching configuration..."
    php artisan config:cache
    php artisan route:cache
    php artisan view:cache

    echo "✅ Application setup completed"
ENDSSH

echo -e "${GREEN}✅ Setup completed successfully!${NC}\n"

# Build frontend assets
echo -e "${BLUE}Building frontend assets...${NC}"
read -p "Build frontend on server? (requires Node.js) (y/n) " -n 1 -r
echo
if [[ $REPLY =~ ^[Yy]$ ]]; then
    ssh ${SSH_USER}@${SSH_HOST} << 'ENDSSH'
        cd /home/cintaban/siakad.diproses.online

        if command -v npm &> /dev/null; then
            echo "Installing NPM dependencies..."
            npm ci --production=false
            echo "Building assets..."
            npm run build
            echo "✅ Frontend built"
        else
            echo "⚠️  Node.js/NPM not found"
            echo "Frontend assets should be built by CI/CD pipeline"
        fi
ENDSSH
else
    echo -e "${YELLOW}Frontend build skipped${NC}"
    echo -e "${BLUE}Assets will be built automatically by GitHub Actions on deployment${NC}"
fi

# Summary
echo -e "\n${BLUE}============================================${NC}"
echo -e "${GREEN}🎉 Setup Completed Successfully! 🎉${NC}"
echo -e "${BLUE}============================================${NC}\n"

echo -e "${YELLOW}Next Steps:${NC}"
echo "1. Verify domain document root in cPanel:"
echo "   → Domains → Manage → siakad.diproses.online"
echo "   → Document Root: /home/cintaban/siakad.diproses.online/public"
echo ""
echo "2. Add GitHub Secrets (if not done):"
echo "   → SSH_PRIVATE_KEY: $(cat /mnt/host/d/AI/SIAKAD/id_rsa | base64 -w0 | head -c 50)..."
echo "   → SSH_HOST: siakad.diproses.online"
echo "   → SSH_USER: cintaban"
echo "   → DEPLOY_PATH: /home/cintaban/siakad.diproses.online"
echo ""
echo "3. Test your application:"
echo "   → https://siakad.diproses.online"
echo ""
echo "4. Setup SSL (if not enabled):"
echo "   → cPanel → SSL/TLS Status → Run AutoSSL"
echo ""
echo "5. Push to GitHub to trigger auto-deployment:"
echo "   → git push origin main"
echo ""

echo -e "${GREEN}Happy deploying! 🚀${NC}\n"
ENDSSH

chmod +x setup-server.sh
