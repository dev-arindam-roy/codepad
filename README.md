# CodePad++

## Code Bank For Developers.
### As a developer, You can manage your codes easily.


|using LARAVEL 8 & VUE Js


|HOW TO SET UP IN YOUR LOCAL
|------------------------------------------------------------

STEP1: Create a virtual host in your local server (WAMP or XAMPP etc..)
STEP2: Clone this repo or download all files
STEP3: composer install
STEP4: Create a database in mysql
STEP5: Set database, username & password in .env
STEP6: php artisan migrate
STEP7: run this by using the virtual host name (ex: codepad.local)

|HOW TO CREATE A VIRTUAL HOST
|---------------------------------------------------------------

`
<VirtualHost *:80> 
    ServerName codepad.local
    ServerAlias www.codepad.local
    DocumentRoot "C:\wamp64\www\codepad" 
    <Directory "C:\wamp64\www\codepad"> 
        Options Indexes FollowSymLinks MultiViews 
        AllowOverride All 
        Require all granted
    </Directory>
</VirtualHost>
`