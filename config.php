<?php

// Database connection settings for XAMPP
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'mymoviedb');

// Create database connection using PDO
function getDBConnection() {
    try {
        $pdo = new PDO(
            "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4",
            DB_USER,
            DB_PASS,
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ]
        );
        return $pdo;
    } catch (PDOException $e) {
        die("❌ Database connection failed: " . $e->getMessage());
    }
}

// Test database connection
function testConnection() {
    try {
        $pdo = getDBConnection();
        return "✅ Database connection successful!";
    } catch (Exception $e) {
        return "❌ Database connection failed: " . $e->getMessage();
    }
}

// Helper function to display star ratings
function displayStars($rating) {
    if (!$rating) return '<span style="color: #666;">Not rated</span>';
    
    $stars = '';
    for ($i = 1; $i <= 5; $i++) {
        if ($i <= $rating) {
            $stars .= '⭐';
        } else {
            $stars .= '☆';
        }
    }
    return $stars . ' (' . $rating . '/5)';
}

// Helper function to format watch status
function formatWatchStatus($status) {
    switch ($status) {
        case 'watched':
            return '✅ Watched';
        case 'want_to_watch':
            return '📋 Want to Watch';
        case 'currently_watching':
            return '👀 Currently Watching';
        default:
            return '❓ Unknown';
    }
}
?>