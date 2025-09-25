# Enterprise Resource Planning - Sistema de Gestão de Produtos, Pedidos e Estoque

Este é um **Mini Enterprise Resource Planning** desenvolvido em **PHP puro** utilizando o padrão de arquitetura **MVC**, com banco de dados **MySQL**, estilização com **Twinland CSS** e **Bootstrap 5**.

## 🧱 Estrutura do Projeto

```sh
ERP_MONTINK/
├── assets/css/ # Estilos com Twinland + Bootstrap
├── config/ # Configurações gerais (conexão DB)
├── controllers/ # Lógica de controle (MVC)
├── models/ # Regras de negócio e acesso ao banco
├── public/ # Página pública e webhook
├── sql/ # Script SQL de criação das tabelas
├── views/ # Interfaces divididas por módulo
│ ├── carrinho/
│ ├── cupons/
│ ├── layouts/ # header.php / footer.php
│ ├── pedidos/
│ └── produtos/ # form.php, index.php, lista.php
├── .htaccess # Reescrita de URLs (mod_rewrite)
└── README.md # Este arquivo
````
---


## Tecnologias Utilizadas

- **PHP 8+** (sem frameworks)
- **MySQL 8+**
- **Bootstrap 5**
- **[Twinland CSS](https://twinland.org)** – Utilitário CSS leve
- **Padrão MVC** (Model-View-Controller)
- **JavaScript básico** (onde necessário)

---

## Funcionalidades

- Cadastro, edição e exclusão de produtos
- Controle de estoque
- Gestão de cupons de desconto
- Registro de pedidos e carrinho de compras
- Webhook de integração externa
- Layout modular com reutilização de `header` e `footer`