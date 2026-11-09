
<?php

    return [

    'local_path'     => env(
        'BACKUP_LOCAL_PATH',
        'C:\\WholeSaleMarket-Backup'
    ),

    'database_path'  => env(
        'BACKUP_DATABASE_PATH',
        'C:\\WholeSaleMarket-Backup\\database\\2026m'
    ),

    'usb_path'       => env(
        'BACKUP_USB_PATH',
        'D:\\WholesaleMarket-Backup'
    ),

    'mysqldump_path' => env(
        'MYSQLDUMP_PATH',
        'C:\\xampp\\mysql\\bin\\mysqldump.exe'
    ),

    'mysql_path'     => env(
        'MYSQL_PATH',
        'C:\\xampp\\mysql\\bin\\mysql.exe'
    ),

    'timeout'        => 3600,

];
