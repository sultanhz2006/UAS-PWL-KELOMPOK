<?php
// config/app.php

defined('APP_NAME')    || define('APP_NAME',    'VyanTravel');
defined('APP_VERSION') || define('APP_VERSION', '1.0.0');
defined('BASE_URL')    || define('BASE_URL',    'http://localhost/PWL/E%20Travel/public');

// Path absolut — ROOT_PATH sudah didefinisikan di index.php, cukup turunkan dari sana
defined('ROOT_PATH')   || define('ROOT_PATH',   dirname(__DIR__));
defined('APP_PATH')    || define('APP_PATH',    ROOT_PATH . '/app');
defined('PUBLIC_PATH') || define('PUBLIC_PATH', ROOT_PATH . '/public');
defined('UPLOAD_PATH') || define('UPLOAD_PATH', PUBLIC_PATH . '/uploads/paket');
defined('TICKET_PATH') || define('TICKET_PATH', ROOT_PATH . '/storage/pdf_tickets');

defined('MAX_FILE_SIZE')   || define('MAX_FILE_SIZE',   2 * 1024 * 1024);
defined('ALLOWED_IMG_EXT') || define('ALLOWED_IMG_EXT', ['jpg', 'jpeg', 'png', 'webp']);

defined('OPENWEATHER_API_KEY')  || define('OPENWEATHER_API_KEY',  'YOUR_OPENWEATHER_API_KEY_HERE');
defined('OPENWEATHER_BASE_URL') || define('OPENWEATHER_BASE_URL', 'https://api.openweathermap.org/data/2.5/weather');

defined('SESSION_NAME') || define('SESSION_NAME', 'vyantravel_sess');

date_default_timezone_set('Asia/Jakarta');