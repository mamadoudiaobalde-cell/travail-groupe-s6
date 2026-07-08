#!/usr/bin/env bash
# ============================================================
#  GestSoutenance — Script de déploiement VPS (Ubuntu 22/24)
#  Usage : sudo bash deploy-vps.sh
# ============================================================
set -e

# ── Configuration ────────────────────────────────────────────
BACKEND_REPO="https://github.com/mamadoudiaobalde-cell/travail-groupe-s6.git"
FRONTEND_REPO="https://github.com/abdoulayekande2-dotcom/gest-soutenance-front-end.git"
BACKEND_DIR="/var/www/gest-soutenance-api"
FRONTEND_DIR="/var/www/gest-soutenance-front"
BACKEND_BRANCH="ibrahimadev"
FRONTEND_BRANCH="ibrahimadev"
DB_NAME="gest_soutenance"
DB_USER="gest_admin"
DB_PASS="$(openssl rand -base64 18 | tr -dc 'a-zA-Z0-9' | head -c 20)"
BACKEND_PORT=8001
CREDS_FILE="/root/.gest_soutenance_creds"

# ── Couleurs ─────────────────────────────────────────────────
GREEN='\033[0;32m'; YELLOW='\033[1;33m'; RED='\033[0;31m'; NC='\033[0m'
info()    { echo -e "${GREEN}[INFO]${NC} $1"; }
warning() { echo -e "${YELLOW}[WARN]${NC} $1"; }
section() { echo -e "\n${GREEN}━━━ $1 ━━━${NC}"; }

VPS_IP=$(curl -s --max-time 5 ifconfig.me || hostname -I | awk '{print $1}')
info "IP détectée : $VPS_IP"

# ════════════════════════════════════════════════════════════
section "1 — Paquets système"
# ════════════════════════════════════════════════════════════
apt-get update -qq
apt-get install -y curl git unzip software-properties-common mysql-server

# ════════════════════════════════════════════════════════════
section "2 — PHP 8.2"
# ════════════════════════════════════════════════════════════
if ! php -v &>/dev/null || [[ $(php -r 'echo PHP_MAJOR_VERSION.".".PHP_MINOR_VERSION;') < "8.2" ]]; then
    add-apt-repository -y ppa:ondrej/php
    apt-get update -qq
fi
apt-get install -y \
    php8.2-fpm php8.2-mysql php8.2-xml php8.2-curl \
    php8.2-mbstring php8.2-zip php8.2-bcmath php8.2-tokenizer \
    php8.2-fileinfo php8.2-pdo
systemctl enable php8.2-fpm
systemctl start php8.2-fpm

# ════════════════════════════════════════════════════════════
section "3 — Composer"
# ════════════════════════════════════════════════════════════
if ! command -v composer &>/dev/null; then
    curl -sS https://getcomposer.org/installer | php
    mv composer.phar /usr/local/bin/composer
fi
composer --version

# ════════════════════════════════════════════════════════════
section "4 — Node.js 20"
# ════════════════════════════════════════════════════════════
if ! node -v &>/dev/null; then
    curl -fsSL https://deb.nodesource.com/setup_20.x | bash -
    apt-get install -y nodejs
fi
node -v && npm -v

# ════════════════════════════════════════════════════════════
section "5 — Base de données MySQL"
# ════════════════════════════════════════════════════════════
systemctl start mysql
systemctl enable mysql

# Crée la DB et l'utilisateur seulement si inexistants
mysql -e "CREATE DATABASE IF NOT EXISTS \`$DB_NAME\` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
mysql -e "CREATE USER IF NOT EXISTS '$DB_USER'@'localhost' IDENTIFIED BY '$DB_PASS';"
mysql -e "GRANT ALL PRIVILEGES ON \`$DB_NAME\`.* TO '$DB_USER'@'localhost';"
mysql -e "FLUSH PRIVILEGES;"

# Sauvegarde des credentials
cat > "$CREDS_FILE" <<CREDS
# GestSoutenance — Credentials ($(date))
DB_NAME=$DB_NAME
DB_USER=$DB_USER
DB_PASS=$DB_PASS
VPS_IP=$VPS_IP
FRONTEND_URL=http://$VPS_IP
BACKEND_URL=http://$VPS_IP:$BACKEND_PORT
CREDS
chmod 600 "$CREDS_FILE"

# ════════════════════════════════════════════════════════════
section "6 — Backend Laravel"
# ════════════════════════════════════════════════════════════
if [ -d "$BACKEND_DIR/.git" ]; then
    info "Mise à jour du backend..."
    git -C "$BACKEND_DIR" fetch origin
    git -C "$BACKEND_DIR" checkout $BACKEND_BRANCH
    git -C "$BACKEND_DIR" pull origin $BACKEND_BRANCH
else
    info "Clonage du backend..."
    git clone -b $BACKEND_BRANCH "$BACKEND_REPO" "$BACKEND_DIR"
fi

cd "$BACKEND_DIR"

# Répertoires requis par Laravel (souvent absents après un git clone)
mkdir -p bootstrap/cache \
         storage/framework/cache/data \
         storage/framework/sessions \
         storage/framework/views \
         storage/logs
chown -R www-data:www-data bootstrap/cache storage
chmod -R 775 bootstrap/cache storage

composer install --no-dev --optimize-autoloader --no-interaction

# Crée le .env si absent
if [ ! -f .env ]; then
    cat > .env <<ENV
APP_NAME=GestSoutenance
APP_ENV=production
APP_KEY=
APP_DEBUG=false
APP_URL=http://$VPS_IP:$BACKEND_PORT

APP_LOCALE=fr
APP_FALLBACK_LOCALE=fr
APP_FAKER_LOCALE=fr_FR

LOG_CHANNEL=stderr
LOG_LEVEL=error

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=$DB_NAME
DB_USERNAME=$DB_USER
DB_PASSWORD=$DB_PASS

SESSION_DRIVER=array
CACHE_STORE=array
QUEUE_CONNECTION=sync
FILESYSTEM_DISK=local

FRONTEND_URL=http://$VPS_IP

MAIL_MAILER=log
ENV
    php artisan key:generate --no-interaction
else
    warning ".env déjà présent, pas de réécriture."
fi

php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan migrate --force
php artisan db:seed --force

chown -R www-data:www-data storage bootstrap/cache
chmod -R 775 storage bootstrap/cache

# ════════════════════════════════════════════════════════════
section "7 — Frontend React (build)"
# ════════════════════════════════════════════════════════════
if [ -d "$FRONTEND_DIR/.git" ]; then
    info "Mise à jour du frontend..."
    git -C "$FRONTEND_DIR" fetch origin
    git -C "$FRONTEND_DIR" checkout $FRONTEND_BRANCH
    git -C "$FRONTEND_DIR" pull origin $FRONTEND_BRANCH
else
    info "Clonage du frontend..."
    git clone -b $FRONTEND_BRANCH "$FRONTEND_REPO" "$FRONTEND_DIR"
fi

cd "$FRONTEND_DIR"
# Variable d'env de build pointant vers l'API
echo "VITE_API_URL=http://$VPS_IP:$BACKEND_PORT" > .env.production
npm ci --silent
npm run build
chown -R www-data:www-data dist

# ════════════════════════════════════════════════════════════
section "8 — Nginx"
# ════════════════════════════════════════════════════════════

# Site backend (port 8001)
cat > /etc/nginx/sites-available/gest-soutenance-api <<NGINX
server {
    listen $BACKEND_PORT;
    server_name _;
    root $BACKEND_DIR/public;
    index index.php;

    location / {
        try_files \$uri \$uri/ /index.php?\$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_index index.php;
        include fastcgi_params;
        fastcgi_param SCRIPT_FILENAME \$realpath_root\$fastcgi_script_name;
    }

    location ~ /\.(?!well-known).* { deny all; }
    location ~* \.(env|log)$       { deny all; }

    client_max_body_size 20M;
}
NGINX

# Site frontend (port 80)
cat > /etc/nginx/sites-available/gest-soutenance-front <<NGINX
server {
    listen 80 default_server;
    server_name _;
    root $FRONTEND_DIR/dist;
    index index.html;

    # SPA routing
    location / {
        try_files \$uri \$uri/ /index.html;
    }

    # Cache assets
    location ~* \.(js|css|png|jpg|svg|ico|woff2)$ {
        expires 30d;
        add_header Cache-Control "public, immutable";
    }

    gzip on;
    gzip_types text/plain text/css application/json application/javascript text/xml;
}
NGINX

ln -sf /etc/nginx/sites-available/gest-soutenance-api  /etc/nginx/sites-enabled/gest-soutenance-api
ln -sf /etc/nginx/sites-available/gest-soutenance-front /etc/nginx/sites-enabled/gest-soutenance-front
rm -f /etc/nginx/sites-enabled/default

nginx -t
systemctl reload nginx

# ════════════════════════════════════════════════════════════
section "✓ Déploiement terminé"
# ════════════════════════════════════════════════════════════
echo ""
echo -e "  ${GREEN}Frontend${NC} : http://$VPS_IP"
echo -e "  ${GREEN}API      ${NC} : http://$VPS_IP:$BACKEND_PORT/api"
echo ""
echo -e "  Credentials enregistrés dans : ${YELLOW}$CREDS_FILE${NC}"
echo ""
echo -e "  Comptes de test (mot de passe : password)"
echo -e "    admin@gestsoutenance.test         → Administrateur"
echo -e "    secretaire@gestsoutenance.test    → Secrétaire"
echo -e "    enseignant@gestsoutenance.test    → Enseignant (ibrahima-fall)"
echo -e "    responsable@gestsoutenance.test   → Responsable pédagogique"
echo -e "    mamadou-diao@etudiant.gestsoutenance.test → Étudiant"
