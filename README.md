**In order to use this program you need the following:**
1. Composer
2. Node.js
3. Php

**Extensions used in VSCode**
1. SQLite by alexcvzz
2. PHP Intelephense by PHP code intelligence
3. PHP by All-in-One
4. Laravel goto view by codingyu
5. Laravel Extra Intellisense by amir
6. Laravel Blade Snippets by Winnie Lin
7. Laravel Blade formatter by Shuhei Hayashibara
8. IntelliPHP by DEVSENSE
9. Composer by DEVSENSE

**Get Started**
1. Create your .env file using the .env.example as the example and put your own paymongo, google auth, facebook auth, and mailer tokens/secret
2. Open the terminal and type these commands:
    1. composer install (to install necessary dependencies)
    2. php artisan migrate
    3. php artisan db:seed (to create the sample employee accounts)
3. Now you can run the server using ```composer run dev```


**To enable google auth**
1. run php --ini in the terminal
2. ctrl +click the loaded configuration file or go to the file path. ex."C:\php\php.ini"

Then add this:

    curl.cainfo = "file-path\local_dev_only\cacert.pem"
    openssl.cafile = "file-path\MBPL\local_dev_only\cacert.pem"

    Ex.

    curl.cainfo = "E:\Codesheets v2\New folder\MBPL\local_dev_only\cacert.pem"
    openssl.cafile = "E:\Codesheets v2\New folder\MBPL\local_dev_only\cacert.pem"


**Sample accounts for employee:**

Admin

    email: admin@mbpl.com
    password: Admin@12345

Manager

    email: manager@mbpl.com
    password: Manager@12345

Staff

    email: staff@mbpl.com
    password: Staff@12345

Test Card Payment:

    Card Number : 4343434343434345
    Expiry      : 12/26
    CVV         : 123


**Troubleshooting**
If audits table in database was not created, run this command in the terminal

    php artisan vendor:publish --provider="OwenIt\Auditing\AuditingServiceProvider"

then 

    php artisan migrate

If composer install is not working and throwing an error, please check your php.ini file

Make sure that these are not commented: 

    extension=[pdo_sqlite]  //edit depending on your database
    extension=fileinfo
    extension=curl


