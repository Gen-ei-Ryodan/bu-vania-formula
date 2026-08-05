#!/bin/bash

set -e

REPO_DIR="/home/alurelab/repositories/bu-vania-formula"
TARGET_DIR="/home/alurelab/formula.3putraperkasa.com"
BRANCH="main"
COMPOSER="/home/alurelab/.local/bin/composer"

echo "========================================"
echo "  Deploying BU VANIA — Formula"
echo "  Branch : $BRANCH"
echo "  Target : $TARGET_DIR"
echo "========================================"

echo ""
echo "[1/5] Pulling latest code..."
cd "$REPO_DIR"
git checkout "$BRANCH"
git pull origin "$BRANCH"

echo ""
echo "[2/5] Syncing files to target directory..."
cd "$REPO_DIR"
for f in "$REPO_DIR"/* "$REPO_DIR"/.[!.]*; do
    base=$(basename "$f")
    case "$base" in
        .git|.gitignore|.env|node_modules|vendor)
            continue
            ;;
    esac
    [ -e "$f" ] && cp -a "$f" "$TARGET_DIR/"
done

echo ""
echo "[3/5] Setting up storage..."
cd "$TARGET_DIR"
mkdir -p "$TARGET_DIR/storage/framework/cache/data"
mkdir -p "$TARGET_DIR/storage/framework/sessions"
mkdir -p "$TARGET_DIR/storage/framework/views"
mkdir -p "$TARGET_DIR/storage/logs"
rm -rf "$TARGET_DIR/storage/framework/cache/data/"* 2>/dev/null || true
rm -rf "$TARGET_DIR/storage/framework/sessions/"* 2>/dev/null || true
rm -rf "$TARGET_DIR/storage/framework/views/"* 2>/dev/null || true
rm -rf "$TARGET_DIR/storage/logs/"* 2>/dev/null || true
php artisan storage:link --force 2>/dev/null || true

echo ""
echo "[4/5] Installing Composer dependencies..."
if [ -f "$TARGET_DIR/composer.lock" ]; then
    cd "$TARGET_DIR" && "$COMPOSER" install --no-dev --optimize-autoloader --no-interaction
else
    cd "$TARGET_DIR" && "$COMPOSER" install --no-interaction
fi

echo ""
echo "[5/5] Optimizing Laravel..."
cd "$TARGET_DIR"
/usr/local/bin/php artisan optimize:clear 2>/dev/null || true
/usr/local/bin/php artisan view:cache 2>/dev/null || true
/usr/local/bin/php artisan config:cache 2>/dev/null || true
/usr/local/bin/php artisan route:cache 2>/dev/null || true

echo ""
echo "[6/6] Setting permissions..."
chmod -R 775 "$TARGET_DIR/storage"
chmod -R 775 "$TARGET_DIR/bootstrap/cache"
if [ -d "$TARGET_DIR/public/storage" ]; then
    chmod -R 775 "$TARGET_DIR/public/storage"
fi

echo ""
echo "========================================"
echo "  ✅ Deploy complete!"
echo "  Target: $TARGET_DIR"
echo "========================================"
