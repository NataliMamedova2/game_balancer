<?php

if (isset($_ENV['BOOTSTRAP_CLEAR_CACHE_ENV'])) {
    // executes the "php bin/console cache:clear" command
    passthru(sprintf(
        'php "%s/../bin/console" cache:clear --env=%s --no-warmup',
        __DIR__,
        $_ENV['BOOTSTRAP_CLEAR_CACHE_ENV']
    ));
}

if ($_ENV['BOOTSTRAP_CLEAR_CACHE_ENV'] === true) {
    echo $a;
}

require __DIR__ . '/../vendor/autoload.php';
