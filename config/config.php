<?php
// config/config.php
define('SITE_TITLE', 'Indonesian Operational System Monitoring');
define('SUPPORT_PHONE', '192');

function detectSiteUrl() {
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || 
                (isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == 443) ? 'https' : 'http';
    
    $host = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : 'localhost';
    
    if (php_sapi_name() === 'cli-server') {
        return $protocol . '://' . $host;
    }
    
    $scriptPath = isset($_SERVER['SCRIPT_NAME']) ? $_SERVER['SCRIPT_NAME'] : '';
    $baseDir = dirname($scriptPath);
    
    $baseDir = rtrim(preg_replace('#/+#', '/', $baseDir), '/');
    
    if ($baseDir === '' || $baseDir === '.') {
        $baseDir = '';
    }
    
    return $protocol . '://' . $host . $baseDir;
}

define('SITE_URL', detectSiteUrl());
define('BASE_PATH', realpath(dirname(__DIR__)));

$isDev = true; 

if ($isDev) {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
} else {
    error_reporting(0);
    ini_set('display_errors', 0);
}

date_default_timezone_set('Asia/Jakarta');


if (session_status() === PHP_SESSION_NONE) {
    ini_set('session.cookie_httponly', 1);
    ini_set('session.use_only_cookies', 1);
    session_start();
}

function asset($path = '') {
    if (php_sapi_name() === 'cli-server') {
        return '/assets/' . ltrim($path, '/');
    }
 
    $baseUrl = rtrim(SITE_URL, '/');
    
   
    $urlParts = parse_url($baseUrl);
    $rootUrl = $urlParts['scheme'] . '://' . $urlParts['host'];
    if (isset($urlParts['port'])) {
        $rootUrl .= ':' . $urlParts['port'];
    }
    

    $assetPath = ltrim($path, '/');
    
    return $rootUrl . '/assets/' . $assetPath;
}

function url($path = '') {
  
    $baseUrl = rtrim(SITE_URL, '/');
   
    if (empty($path)) {
        return $baseUrl . '/index.php';
    }
    
    $startsWithSlash = substr($path, 0, 1) === '/';
    $cleanPath = ltrim($path, '/');
    
    if (strpos($cleanPath, 'http://') === 0 || strpos($cleanPath, 'https://') === 0) {
        return $cleanPath;
    }
    
    if ($startsWithSlash) {
        $urlParts = parse_url($baseUrl);
        $rootUrl = $urlParts['scheme'] . '://' . $urlParts['host'];
        if (isset($urlParts['port'])) {
            $rootUrl .= ':' . $urlParts['port'];
        }
        
        if (strpos($cleanPath, '.') === false && substr($cleanPath, -1) !== '/') {
            if (is_dir(BASE_PATH . '/' . $cleanPath)) {
                $cleanPath .= '/index.php';
            } else {
                $cleanPath .= '.php';
            }
        }
        
        return $rootUrl . '/' . $cleanPath;
    }
    
    if (strpos($cleanPath, '.') === false && substr($cleanPath, -1) !== '/') {
        if (is_dir(BASE_PATH . '/' . $cleanPath)) {
            $cleanPath .= '/index.php';
        } else {
            $cleanPath .= '.php';
        }
    }
    
    return $baseUrl . '/' . $cleanPath;
}

function isActivePage($path = '') {
    $currentPath = $_SERVER['REQUEST_URI'] ?? '';
    $targetPath = ltrim($path ?? '', '/');
    
    if ($targetPath === 'index.php' || $targetPath === '') {
        return $currentPath === '/' || $currentPath === '/index.php';
    }
    
    $currentPathNormalized = rtrim(strtok($currentPath, '?'), '/');
    $targetPathNormalized = rtrim($targetPath, '/');
    
    if (substr($currentPathNormalized, -4) === '.php') {
        $currentPathNormalized = substr($currentPathNormalized, 0, -4);
    }
    if (substr($targetPathNormalized, -4) === '.php') {
        $targetPathNormalized = substr($targetPathNormalized, 0, -4);
    }
    
    if (substr($targetPathNormalized, -5) === 'index') {
        $targetPathNormalized = substr($targetPathNormalized, 0, -5);
        $targetPathNormalized = rtrim($targetPathNormalized, '/');
    }
    if (substr($currentPathNormalized, -5) === 'index') {
        $currentPathNormalized = substr($currentPathNormalized, 0, -5);
        $currentPathNormalized = rtrim($currentPathNormalized, '/');
    }
    
    return $currentPathNormalized === $targetPathNormalized || 
           strpos($currentPathNormalized, $targetPathNormalized) !== false;
}


function sanitize($input) {
    return is_array($input) ? array_map('sanitize', $input) : htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
}

function redirect($url) {
    header("Location: $url");
    exit();
}

foreach (['logs', 'data'] as $dir) {
    $dirPath = BASE_PATH . '/' . $dir;
    if (!is_dir($dirPath)) {
        mkdir($dirPath, 0755, true);
    }
}
?>