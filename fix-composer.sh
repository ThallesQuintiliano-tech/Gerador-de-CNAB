#!/bin/bash

echo "🔧 Corrigindo problemas do Composer..."

# Parar containers
docker-compose down

# Limpar arquivos do Composer
echo "🧹 Limpando arquivos do Composer..."
rm -f backend/composer.lock
rm -rf backend/vendor

# Limpar cache do Docker
echo "️ Limpando cache do Docker..."
docker system prune -f

# Remover imagens antigas
echo "️ Removendo imagens antigas..."
docker rmi $(docker images -q cnab_backend) 2>/dev/null || true

# Criar diretórios
echo " Criando diretórios..."
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
sleep 60

# Verificar se composer funcionou
echo "✅ Verificando instalação..."
docker-compose exec backend composer list

echo "✅ Problema resolvido!" 