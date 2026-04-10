To user, follow these steps:
Installing PHP and Laravel installer
1. open windows powershell and enter this commands
```# Run as administrator...
Set-ExecutionPolicy Bypass -Scope Process -Force; [System.Net.ServicePointManager]::SecurityProtocol = [System.Net.ServicePointManager]::SecurityProtocol -bor 3072; iex ((New-Object System.Net.WebClient).DownloadString('https://php.new/install/windows/8.4'))
```

1. git clone https://github.com/Tlpczdqp/MBPL.git
2. open MBPL foleder in vscode
3. install this extension
I used these extensions SQLite by alexcvzz, PHP Intelephense by PHP code intelligence, PHP by       All-in-One, Laravel goto view by codingyu, Laravel Extra Intellisense by amir, Laravel Blade Snippets by Winnie Lin, Laravel Blade formatter by Shuhei Hayashibara, IntelliPHP by DEVSENSE, Composer by DEVSENSE

4. ctrl + ` to open the terminal and type these commands:
    1. composer install (to install)
    2. php artisan migrate
    3. php artisan db:seed
    4. composer run dev (to run server)
    

5. extract .env file in MBPL (provided by the dev)



To enable google auth
1. run php --ini in the terminal
2. ctrl +click the loaded configuration file or go to the file path. ex."C:\php\php.ini"
3. add this 
    curl.cainfo = "file-path\local_dev_only\cacert.pem"
    openssl.cafile = "file-path\MBPL\local_dev_only\cacert.pem"

    Ex.
    curl.cainfo = "E:\Codesheets v2\New folder\MBPL\local_dev_only\cacert.pem"
    openssl.cafile = "E:\Codesheets v2\New folder\MBPL\local_dev_only\cacert.pem"


Sample accounts for employee:
1. Admin
    email: admin@mbpl.com
    password: Admin@12345
2. Manager
    email: manager@mbpl.com
    password: Manager@12345
3. Staff
    email: staff@mbpl.com
    password: Staff@12345


1. Use sk_test_ keys in .env 
2. Click "Pay via PayMongo" button
3. PayMongo shows the checkout page (like in your screenshot)
4. Use test card details:

    Card Number : 4343434343434345
   Expiry      : 12/25
   CVV         : 123
    3DS OTP     : 111111

5. Click Continue → Payment succeeds 
6. Redirected back to your success page