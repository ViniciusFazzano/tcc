CREATE TABLE cliente (
    id INT PRIMARY KEY,
    nome VARCHAR(100),
    cpf_cnpj VARCHAR(20),
    endereco VARCHAR(255),
    telefone VARCHAR(20)
);

CREATE TABLE fazenda (
    id INT PRIMARY KEY,
    clienteId INT,
    nome VARCHAR(100),
    localizacao VARCHAR(255),
    tamanho_hectares FLOAT,
    observacoes TEXT,
    FOREIGN KEY (clienteId) REFERENCES Cliente(id)
);

CREATE TABLE produto (
    id INT PRIMARY KEY,
    nome VARCHAR(100),
    preco FLOAT,
    estoque INT,
    observacao TEXT,
    ncm VARCHAR(20)
);

CREATE TABLE usuario (
    id INT PRIMARY KEY,
    nome VARCHAR(100),
    email VARCHAR(100),
    senha VARCHAR(255),
    nivel_acesso VARCHAR(20),
    ativo BOOLEAN,
    data_criacao timestamp,
    observacoes TEXT
);

CREATE TABLE chat_bot (
    id INT PRIMARY KEY,
    clienteId INT,
    data_interacao timestamp,
    pergunta_cliente TEXT,
    resposta_bot TEXT,
    status_resposta VARCHAR(50),
    observacoes TEXT,
    FOREIGN KEY (clienteId) REFERENCES Cliente(id)
);


CREATE TABLE pedido (
    id INT PRIMARY KEY,
    data DATE,
    clienteId INT,
    total FLOAT,
    recomendacaoBatidaId INT,
    FOREIGN KEY (clienteId) REFERENCES Cliente(id)
);


CREATE TABLE item_pedido (
    id INT PRIMARY KEY,
    pedidoId INT,
    produtoId INT,
    quantidade INT,
    preco_unitario FLOAT,
    FOREIGN KEY (pedidoId) REFERENCES Pedido(id),
    FOREIGN KEY (produtoId) REFERENCES Produto(id)
);


CREATE TABLE recomendacao_batida (
    id INT PRIMARY KEY,
    pedidoId INT,
    qnt_saco FLOAT,
    kilo_batida FLOAT,
    kilo_saco FLOAT,
    qnt_cabeca FLOAT,
    consumo_cabeca_g FLOAT,
    grama_homeopatia_cabeca FLOAT,
    gramas_homeopatia_caixa FLOAT,
    peso_total_kg FLOAT,
    quantidade_batidas INT,
    consumo_cabeca_kg FLOAT,
    cabecas_por_saco FLOAT,
    gramas_homeopatia_saco FLOAT,
    kilos_homeopatia_batida FLOAT,
    quantidade_caixas FLOAT,
    observacoes TEXT,
    data_calculo DATE,
    FOREIGN KEY (pedidoId) REFERENCES Pedido(id)
);


CREATE TABLE veterinario (
    id INT PRIMARY KEY,
    nome VARCHAR(100),
    crmv VARCHAR(20),
    email VARCHAR(100)
);


CREATE TABLE protocolo (
    id INT PRIMARY KEY,
    data_criacao DATE,
    status VARCHAR(50),
    veterinarioId INT,
    recomendacaoId INT,
    FOREIGN KEY (veterinarioId) REFERENCES Veterinario(id),
    FOREIGN KEY (recomendacaoId) REFERENCES RecomendacaoBatida(id)
);


CREATE TABLE nota_fiscal (
    id INT PRIMARY KEY,
    pedidoId INT,
    numero_nf VARCHAR(50),
    data_emissao timestamp,
    valor_total FLOAT,
    chave_acesso VARCHAR(100),
    xml_nf TEXT,
    observacoes TEXT,
    FOREIGN KEY (pedidoId) REFERENCES Pedido(id)
);

CREATE TABLE empresa_batedora (
    id INT PRIMARY KEY,
    nome VARCHAR(100),
    cnpj VARCHAR(20),
    endereco VARCHAR(255),
    telefone VARCHAR(20),
    responsavel_tecnico VARCHAR(100),
    crq_responsavel VARCHAR(50),
    observacoes TEXT
);
