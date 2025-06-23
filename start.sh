#!/bin/bash

echo "🚀 Iniciando CNAB Generator..."

# Verificar se Docker está instalado
if ! command -v docker &> /dev/null; then
    echo "❌ Docker não está instalado. Por favor, instale o Docker primeiro."
    exit 1
fi

# Verificar se Docker Compose está instalado
if ! command -v docker-compose &> /dev/null; then
    echo "❌ Docker Compose não está instalado. Por favor, instale o Docker Compose primeiro."
    exit 1
fi

# Criar diretórios necessários
echo "🔨 Criando diretórios..."
mkdir -p mysql/init nginx/logs

# Construir e iniciar containers
echo "🔨 Construindo containers..."
docker-compose up -d --build

# Aguardar containers estarem prontos
echo "⏳ Aguardando containers..."
sleep 30

# Verificar status
echo "📊 Status dos containers:"
docker-compose ps

echo "✅ CNAB Generator iniciado com sucesso!"
echo "🌐 Frontend: http://localhost:3000"
echo "🔧 Backend API: http://localhost/api"
echo "🔋 MySQL: localhost:3306"
echo "📝 Logs: docker-compose logs -f" 