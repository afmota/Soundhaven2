-- Migração: adicionar coluna 'role' em tb_usuarios
-- Possíveis valores: 'curador' (admin), 'user' (padrão)

ALTER TABLE tb_usuarios
    ADD COLUMN IF NOT EXISTS role VARCHAR(50) NOT NULL DEFAULT 'user';

-- Garantir que o usuário curador padrão seja curador
UPDATE tb_usuarios SET role = 'curador' WHERE email = 'curador@soundhaven.com';
