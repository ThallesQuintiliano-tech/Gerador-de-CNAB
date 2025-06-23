-- Criar banco de dados se não existir
CREATE DATABASE IF NOT EXISTS cnab_generator CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- Garantir que o usuário tenha todas as permissões
GRANT ALL PRIVILEGES ON cnab_generator.* TO 'cnab_user'@'%';
FLUSH PRIVILEGES; 