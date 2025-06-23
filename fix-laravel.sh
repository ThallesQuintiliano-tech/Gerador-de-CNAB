#!/bin/bash

echo "🔧 Corrigindo estrutura do Laravel..."

# Parar containers
docker-compose down

# Limpar arquivos
rm -f backend/composer.lock
rm -rf backend/vendor

# Criar diretórios necessários
mkdir -p backend/bootstrap
mkdir -p backend/public
mkdir -p backend/routes
mkdir -p backend/config
mkdir -p backend/app/Providers
mkdir -p backend/storage/{app/public/{uploads,cnab},logs,framework/{cache,sessions,views}}
mkdir -p backend/database/{migrations,seeders}

# Definir permissões
chmod +x backend/artisan
chmod -R 755 backend/storage
chmod -R 755 backend/bootstrap/cache

# Limpar cache do Docker
docker system prune -f

# Reconstruir containers
echo "🔨 Reconstruindo containers..."
docker-compose up -d --build

# Aguardar containers
echo "⏳ Aguardando containers..."
sleep 60

echo "✅ Estrutura do Laravel corrigida!" 