#!/bin/bash

echo "🔧 Corrigindo todos os problemas..."

# Parar containers
echo "🛑 Parando containers..."
docker-compose down

# Limpar arquivos do Composer
echo "🧹 Limpando arquivos do Composer..."
rm -f backend/composer.lock
rm -rf backend/vendor

# Limpar cache do Docker
echo "🧹 Limpando cache do Docker..."
docker system prune -f

# Criar diretórios
echo "🔨 Criando diretórios..."
mkdir -p mysql/init nginx/logs
mkdir -p backend/storage/app/public/{uploads,cnab}
mkdir -p backend/storage/logs

# Definir permissões
echo "🔐 Definindo permissões..."
chmod -R 755 backend/storage
chmod -R 755 backend/bootstrap/cache

# Reconstruir containers
echo "🔨 Reconstruindo containers..."
docker-compose up -d --build

# Aguardar containers
echo "⏳ Aguardando containers..."
sleep 45

# Configurar Laravel
echo "⚙️ Configurando Laravel..."
docker-compose exec backend cp .env.example .env
docker-compose exec backend php artisan key:generate
docker-compose exec backend php artisan jwt:secret
docker-compose exec backend php artisan migrate --force
docker-compose exec backend php artisan db:seed --force
docker-compose exec backend php artisan storage:link

echo "✅ Todos os problemas corrigidos!"
echo "🌐 Frontend: http://localhost:3000"
echo "🔧 Backend: http://localhost/api" 