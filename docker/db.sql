
DROP TABLE IF EXISTS public.pessoas;

CREATE TABLE IF NOT EXISTS public.pessoas
(
    id         uuid         NOT NULL,
    apelido    varchar(32)  NOT NULL,
    nome       varchar(100) NOT NULL,
    nascimento date         NOT NULL,
    stack      json NULL,
    created_at timestamp(0) NULL,
    updated_at timestamp(0) NULL,
    CONSTRAINT pessoas_apelido_unique UNIQUE (apelido),
    CONSTRAINT pessoas_pkey PRIMARY KEY (id)
);