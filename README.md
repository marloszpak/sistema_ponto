1️⃣ Clonar o repositório
git clone https://github.com/marloszpak/sistema_ponto.git
cd .\sistema_ponto\

2️⃣ Instalar dependências PHP
composer install

3️⃣ Copiar o arquivo de ambiente
cp .env.example .env

4️⃣ Configurar o arquivo .env
Abra o arquivo .env e configure o banco de dados conforme suas credenciais:

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=sistema_ponto
DB_USERNAME=root
DB_PASSWORD=

5️⃣ Gerar a chave da aplicação
php artisan key:generate

6️⃣ Criar as tabelas no banco de dados
php artisan migrate

7️⃣ Iniciar o servidor local
php artisan serve


Exportação de relatórios
Relatórios podem ser exportados em PDF ou CSV.
Para gerar PDF, certifique-se de ter instalado o pacote:

composer require barryvdh/laravel-dompdf

