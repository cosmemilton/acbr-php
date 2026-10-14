<?php
declare(strict_types=1);
spl_autoload_register(static function (string $class): void {
    if (str_starts_with($class, 'Acbr\\')) {
        $file = dirname(__DIR__) . '/src/' . str_replace('\\', '/', substr($class, 5)) . '.php';
        if (is_file($file)) { require $file; }
    }
});
