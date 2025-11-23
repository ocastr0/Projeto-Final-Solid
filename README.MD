# Sistema de Controle de Estacionamento Inteligente

RA: 1993141 - ISAQUE CASTRO MOREIRA CAVALCANTE
RA: - GABRIEL FORCONI

## Sobre o Projeto

Sistema web desenvolvido em PHP 8+ com banco de dados SQLite para gerenciar operações de um estacionamento. O projeto foi desenvolvido seguindo os princípios SOLID, DRY, KISS, Clean Code e Object Calisthenics, garantindo código limpo, manutenível e extensível.

### Objetivo

Permitir o controle eficiente de veículos estacionados, calculando tarifas baseadas em tempo de permanência e tipo de veículo, além de fornecer relatórios consolidados de faturamento.

---

## Funcionalidades

- Cadastro automático de veículos (Carro, Moto, Caminhão)
- Registro de entrada com horário real
- Registro de saída com cálculo automático de tarifa
- Cálculo de tempo arredondado para cima (horas)
- Geração de relatórios de faturamento por tipo de veículo
- Validação de entradas duplicadas
- Persistência de dados em SQLite
- Interface web responsiva e intuitiva

---

## Arquitetura e Princípios

### SOLID

**SRP (Single Responsibility Principle)**  
Cada classe possui uma única responsabilidade (Entity, Repository, Service)

**OCP (Open/Closed Principle)**  
Novos tipos de veículos podem ser adicionados sem modificar código existente

**LSP (Liskov Substitution Principle)**  
Todas as subclasses de Veiculo são substituíveis

**ISP (Interface Segregation Principle)**  
Interfaces segregadas (VehicleRepository, ParkingRepository, PricingStrategy)

**DIP (Dependency Inversion Principle)**  
Serviços dependem de interfaces, não de implementações concretas

### Outros Princípios

**DRY (Don't Repeat Yourself)**  
Lógica de cálculo centralizada

**KISS (Keep It Simple, Stupid)**  
Soluções simples e diretas

**Clean Code**  
Nomes significativos, métodos pequenos, código autodocumentado

**Object Calisthenics**  
Encapsulamento adequado, sem primitivas soltas

---

## Tecnologias Utilizadas

### Backend
- PHP 8.3+ - Linguagem principal
- SQLite 3 - Banco de dados
- Composer - Gerenciador de dependências
- PSR-4 - Autoload de classes

### Frontend
- HTML5 - Estrutura
- CSS3 - Estilização
- JavaScript - Interatividade

---

## Pré-requisitos

Antes de começar, você precisa ter instalado:

- PHP 8.0 ou superior
- Composer
- Extensões PHP habilitadas: `pdo_sqlite` e `sqlite3`

### Verificar instalação do PHP

php --version

### Verificar extensões SQLite

php -m | findstr sqlite
Deve aparecer: pdo_sqlite       sqlite3

---

## Instalação

### 1. Clone o repositório

### 2. Instale as dependências

composer install

Ou, se preferir apenas gerar o autoload:

composer dump-autoload

### 3. Configure as extensões PHP (se necessário)

Edite o arquivo `php.ini` e descomente as linhas:

extension=pdo_sqlite
extension=sqlite3

Para encontrar o `php.ini`:

php --ini

---

## Como Usar

### Iniciar o servidor

Na raiz do projeto, execute:

php -S localhost:8000 -t public


### Acessar o sistema

Abra o navegador e acesse:

http://localhost:8000


### Funcionalidades disponíveis

**Registrar Entrada**
- Informe a placa (ex: ABC-1234)
- Selecione o tipo (Carro, Moto ou Caminhão)
- Clique em "Registrar Entrada"

**Registrar Saída**
- Informe a placa do veículo
- Clique em "Registrar Saída"
- O sistema calcula e exibe o valor a pagar

**Ver Relatório**
- Clique no botão "Ver Relatório"
- Visualize total de veículos e faturamento por tipo

---

**DEMONSTRAÇÃO EM VIDEO **          

- CLIQUE AQUI:   https://youtu.be/MmcsQUd8omY
