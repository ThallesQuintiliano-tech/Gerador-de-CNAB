#!/bin/bash

# Aguardar MySQL estar pronto
echo "Aguardando MySQL..."
while ! mysqladmin ping -h"mysql" -P"3306" --silent; do
    sleep 1
done
echo "MySQL está pronto!"

# Aguardar Redis estar pronto
echo "Aguardando Redis..."
while ! redis-cli -h redis ping; do
    sleep 1
done
echo "Redis está pronto!"

# Copiar arquivo de ambiente se não existir
if [ ! -f .env ]; then
    cp .env.example .env
    echo "Arquivo .env criado"
fi

# Gerar chave da aplicação se não existir
if ! grep -q "APP_KEY=base64:" .env; then
    php artisan key:generate
    echo "Chave da aplicação gerada"
fi

# Gerar chave JWT se não existir
if ! grep -q "JWT_SECRET=" .env; then
    php artisan jwt:secret
    echo "Chave JWT gerada"
fi

# Executar migrações
echo "Executando migrações..."
php artisan migrate --force

# Executar seeders
echo "Executando seeders..."
php artisan db:seed --force

# Criar link simbólico para storage
if [ ! -L public/storage ]; then
    php artisan storage:link
    echo "Link simbólico para storage criado"
fi

# Limpar cache
php artisan config:clear
php artisan cache:clear
php artisan route:clear

echo "Backend inicializado com sucesso!"

# Executar comando passado como argumento
exec "$@" 