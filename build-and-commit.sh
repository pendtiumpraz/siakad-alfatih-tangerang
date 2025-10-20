#!/bin/bash

##############################################################################
# Build Assets and Commit
# Otomatis build, copy ke public, dan commit
##############################################################################

set -e

echo "🔨 Building assets..."
npm run build

echo "📦 Copying compiled assets..."
cp public/build/assets/app-*.css public/css/app.css
cp public/build/assets/app-*.js public/js/app.js

echo "📊 Asset sizes:"
ls -lh public/css/app.css public/js/app.js

echo ""
read -p "Commit and push? (y/n) " -n 1 -r
echo

if [[ $REPLY =~ ^[Yy]$ ]]; then
    git add public/css/app.css public/js/app.js
    git commit -m "build: update compiled assets $(date +%Y-%m-%d)"
    git push origin main
    echo "✅ Assets built and pushed!"
else
    echo "⚠️  Skipped commit/push. Run manually if needed."
fi
