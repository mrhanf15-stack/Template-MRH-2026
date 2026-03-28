<?php
/**
 * OPCache Reset – Token-geschützt
 * Aufruf: https://reinsalz.de/opcache_reset.php?token=MrHanf2024Reset
 */

$valid_token = 'MrHanf2024Reset';

if (!isset($_GET['token']) || $_GET['token'] !== $valid_token) {
    http_response_code(403);
    die('Forbidden');
}

if (function_exists('opcache_reset')) {
    opcache_reset();
    echo 'OPCache erfolgreich geleert (' . date('Y-m-d H:i:s') . ')';
} else {
    echo 'OPCache ist nicht verfügbar auf diesem Server';
}
