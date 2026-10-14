<?php

/**
 * Laravel Local Development Server Router
 * Serves static assets from /public and routes all other requests through public/index.php
 */

$uri = urldecode(
    parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?? ''
);

$publicPath = __DIR__ . '/public';

// Serve static assets directly if they exist in public/
if ($uri !== '/' && file_exists($publicPath . $uri)) {
    // Set MIME type based on extension
    $ext = strtolower(pathinfo($uri, PATHINFO_EXTENSION));
    $mimeTypes = [
        'css'  => 'text/css',
        'js'   => 'application/javascript',
        'png'  => 'image/png',
        'jpg'  => 'image/jpeg',
        'jpeg' => 'image/jpeg',
        'gif'  => 'image/gif',
        'svg'  => 'image/svg+xml',
        'ico'  => 'image/x-icon',
        'woff' => 'font/woff',
        'woff2'=> 'font/woff2',
        'ttf'  => 'font/ttf',
        'eot'  => 'application/vnd.ms-fontobject',
        'map'  => 'application/json',
        'json' => 'application/json',
        'txt'  => 'text/plain',
    ];
    if (isset($mimeTypes[$ext])) {
        header('Content-Type: ' . $mimeTypes[$ext]);
    }
    readfile($publicPath . $uri);
    return;
}

require_once __DIR__ . '/public/index.php';
