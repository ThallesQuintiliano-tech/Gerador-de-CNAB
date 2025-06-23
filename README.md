# Gerador-de-CNAB

Este projeto é um gerador de arquivos CNAB com backend em Laravel e frontend em Vue.js, implantado usando Docker. Inclui serviços para PHP, MySQL, Redis e Nginx.

# Pré-requisitos

Docker: Instale o Docker Desktop para macOS.
Git: Certifique-se de que o Git está instalado (git --version).
Node.js: Necessário para o frontend (npm --version).

# Como Iniciar

# Clonar o Repositório:

git clone git@github.com:ThallesQuintiliano-tech/Gerador-de-CNAB.git
cd Gerador-de-CNAB

# Configurar Arquivos de Ambiente:

# Copie os arquivos de exemplo:

cp backend/.env.example backend/.env
cp frontend/.env.example frontend/.env

# Edite o backend/.env para definir as credenciais do MySQL:

DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=laravel_db
DB_USERNAME=root
DB_PASSWORD=sua_senha_root

# Atualize o frontend/.env se necessário (e.g., URL base da API).

Construir e Iniciar os Serviços Docker:
Construa e inicie os contêineres Docker:
docker-compose up -d --build

# Isso inicia:

backend: Laravel (PHP 8.4)
mysql: MySQL 8.0
redis: Redis
nginx: Servidor web (porta 80)

Instalar Dependências do Backend:
Instale as dependências do Composer:

docker-compose exec backend composer install --no-dev --optimize-autoloader

# Execute as migrações do banco de dados:

docker-compose exec backend php artisan migrate
docker-compose exec backend php artisan migrate:fresh --seed --force

# Instalar Dependências do Frontend:

Navegue para o frontend:

cd frontend

# Instale as dependências do Node.js:

npm install

# Inicie o servidor de desenvolvimento:

npm run dev

# Acessar a Aplicação:

Backend: Acesse a API via http://localhost/api (configurado pelo Nginx).

Frontend: Acesse o Vue.js em http://localhost:5173 (ou a porta definida em vite.config.ts).

MySQL: Conecte-se via MySQL Workbench em 127.0.0.1:3306 com usuário root e senha definida.

# Solução de Problemas

Erro no Composer: Execute docker-compose exec backend composer update se houver conflitos.
Erro no Docker: Verifique os logs com docker-compose logs backend.
Erro no Frontend: Certifique-se de que o proxy em frontend/vite.config.ts aponta para http://localhost.

# Observações

Configure o frontend/vite.config.ts para corresponder à URL do backend.
Use MySQL Workbench para gerenciar o banco laravel_db.
Para desinstalar o PHP localmente, execute:
