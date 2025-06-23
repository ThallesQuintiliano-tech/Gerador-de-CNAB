#!/bin/bash

echo "🧪 Testando conexão com MySQL..."

# Verificar se o container MySQL está rodando
if ! docker-compose ps mysql | grep -q "Up"; then
    echo "❌ Container MySQL não está rodando"
    echo "Execute: docker-compose up -d mysql"
    exit 1
fi

# Testar conexão com MySQL
echo "📊 Testando conexão..."
docker-compose exec mysql mysql -u cnab_user -pcnab_password -e "SELECT 1;" cnab_generator

if [ $? -eq 0 ]; then
    echo "✅ Conexão com MySQL estabelecida com sucesso!"
    
    # Verificar tabelas
    echo "�� Tabelas existentes:"
    docker-compose exec mysql mysql -u cnab_user -pcnab_password -e "SHOW TABLES;" cnab_generator
    
    # Verificar usuários
    echo "👥 Usuários cadastrados:"
    docker-compose exec mysql mysql -u cnab_user -pcnab_password -e "SELECT id, name, email, profile FROM users;" cnab_generator
    
    # Verificar fundos
    echo "�� Fundos cadastrados:"
    docker-compose exec mysql mysql -u cnab_user -pcnab_password -e "SELECT id, name, cnpj FROM funds;" cnab_generator
else
    echo "❌ Falha na conexão com MySQL"
    exit 1
fi 