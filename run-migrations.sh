#!/bin/bash

# Script untuk menjalankan migration di production
# Jalankan: bash run-migrations.sh

echo "=========================================="
echo "  Running Migrations for SIAKAD"
echo "=========================================="
echo ""

# Check if we're in the right directory
if [ ! -f "artisan" ]; then
    echo "❌ Error: artisan file not found"
    echo "   Please run this script from project root directory"
    exit 1
fi

echo "📋 Checking pending migrations..."
php artisan migrate:status

echo ""
echo "🚀 Running migrations..."
php artisan migrate --force

echo ""
echo "✅ Migrations completed!"
echo ""
echo "📊 Current migration status:"
php artisan migrate:status

echo ""
echo "=========================================="
echo "  Migration Complete!"
echo "=========================================="
