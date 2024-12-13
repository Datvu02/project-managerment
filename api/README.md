## Requirements

- PHP >= 7.1.3
- OpenSSL PHP Extension.
- PDO PHP Extension.
- Mbstring PHP Extension.
- Tokenizer PHP Extension.
- XML PHP Extension.
- Ctype PHP Extension.
- JSON PHP Extension.
- BCMath PHP Extension.

## Usage

1. Clone project.
2. Create .env file, copy content from .env.example to .env file and config your database in .env:
``` bash
DB_CONNECTION=mysql
DB_HOST=database_server_ip
DB_PORT=3306
DB_DATABASE=database_name
DB_USERNAME=username
DB_PASSWORD=password
```
And config email:
``` bash
MAIL_DRIVER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your_email_address
MAIL_PASSWORD=your_email_password
MAIL_ENCRYPTION=tls
```
3. Run
``` bash
$ composer install
$ php artisan key:generate
$ php artisan migrate
$ php artisan storage:link
$ php artisan jwt:secret
$ php artisan route:clear
$ php artisan config:clear
```
4. Local development server
- Run
``` bash
$ php artisan serve

