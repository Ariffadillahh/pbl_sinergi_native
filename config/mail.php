<?php
return [
    'host'       => $_ENV['MAIL_HOST'],
    'username'   => $_ENV['MAIL_USERNAME'],
    'password'   => $_ENV['MAIL_PASSWORD'],
    'port'       => $_ENV['MAIL_PORT'],
    'encryption' => $_ENV['MAIL_ENCRYPTION'],
    'from_address' => $_ENV['MAIL_FROM_ADDRESS'],
    'from_name'  => $_ENV['MAIL_FROM_NAME'],
];
