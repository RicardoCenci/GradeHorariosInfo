# GradeHorariosInfo
Trabalho do primeiro ano de Informatica para Internet no IFRS.
Construido em cima do padrão MVC utilizando apenas bibliotecas externas para cuidar da autenticação com Json Web Tokens
## Configuração Inicial
Copie ```globals.template``` para ```globals.php``` e mude as propriedades de acordo com o seu ambiente
```php
    date_default_timezone_set("America/Sao_Paulo"); //APP DEFAULT TIME ZONE
    define('APP_ROOT',__DIR__);
    define('APP_NAME', "APP NAME"); //YOUR APP NAME
    define('APP_URL' , 'YOUR APP URL'); //APP ROOT URL
    define('APP_SECRET','YOUR APP KEY'); //APP SECRET KEY (12 CHAR) CONTAINING SPECIAL CHARACTERES
    define('APP_CONFIG',array(
    "Database" => [
        'servername' => "localhost", //YOUR DATABASE SERVERNAME
        'dbname' => 'gradehorarios', //YOUR DATABASE DB NAME
        'tablePrefix'=>'', //TABLE PREFIXES, IF NEEDED
        'username'=>'', //DATABASE USER NAME
        'password' =>'' //DATABASE PASSWORD
    ]
```
