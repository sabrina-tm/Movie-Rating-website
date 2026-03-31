<?php
/**
 * Test Database Connection - FIXED VERSION
 * MyMovieDB Project - Step 2 Test
 */

// Enable error reporting for debugging (ADD THIS AT THE VERY TOP)
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>🔧 PHP Debug Test Started...</h1>";

// Test 1: Check if PHP is working
echo "<h2>Test 1: PHP Status</h2>";
echo "<p>✅ PHP is working! Version: " . phpversion() . "</p>";

// Test 2: Check if config file exists
echo "<h2>Test 2: Config File</h2>";
if (file_exists('config.php')) {
    echo "<p>✅ config.php file found!</p>";
    require_once 'config.php';
    echo "<p>✅ config.php loaded successfully!</p>";
} else {
    echo "<p>❌ config.php file NOT found!</p>";
    die("Cannot continue without config.php");
}

// Test 3: Database connection
echo "<h2>Test 3: Database Connection</h2>";
try {
    // Simple direct connection test
    $pdo = new PDO('mysql:host=localhost;dbname=mymoviedb', 'root', '');
    echo "<p>✅ Direct database connection successful!</p>";
    
    // Test using our function
    $pdo2 = getDBConnection();
    echo "<p>✅ Function-based connection successful!</p>";
    
} catch (Exception $e) {
    echo "<p>❌ Database connection failed: " . $e->getMessage() . "</p>";
}

// Test 4: Check if database and table exist
echo "<h2>Test 4: Database Structure</h2>";
try {
    $pdo = new PDO('mysql:host=localhost;dbname=mymoviedb', 'root', '');
    
    // Check if movies table exists
    $stmt = $pdo->query("SHOW TABLES LIKE 'movies'");
    $table_exists = $stmt->rowCount() > 0;
    
    if ($table_exists) {
        echo "<p>✅ Movies table exists!</p>";
        
        // Count movies
        $stmt = $pdo->query("SELECT COUNT(*) as count FROM movies");
        $count = $stmt->fetch();
        echo "<p>✅ Found " . $count['count'] . " movies in database!</p>";
        
    } else {
        echo "<p>❌ Movies table does NOT exist!</p>";
    }
    
} catch (Exception $e) {
    echo "<p>❌ Error checking database structure: " . $e->getMessage() . "</p>";
}

// Test 5: Fetch and display movies
echo "<h2>Test 5: Fetch Sample Movies</h2>";
try {
    $pdo = new PDO('mysql:host=localhost;dbname=mymoviedb', 'root', '');
    $stmt = $pdo->query("SELECT * FROM movies ORDER BY date_added DESC LIMIT 5");
    $movies = $stmt->fetchAll();
    
    if (count($movies) > 0) {
        echo "<p>✅ Successfully fetched " . count($movies) . " movies!</p>";
        
        echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
        echo "<tr style='background: #f0f0f0;'>";
        echo "<th>ID</th><th>Title</th><th>Director</th><th>Genre</th><th>Year</th><th>Rating</th>";
        echo "</tr>";
        
        foreach ($movies as $movie) {
            echo "<tr>";
            echo "<td>" . $movie['id'] . "</td>";
            echo "<td>" . htmlspecialchars($movie['title']) . "</td>";
            echo "<td>" . htmlspecialchars($movie['director']) . "</td>";
            echo "<td>" . htmlspecialchars($movie['genre']) . "</td>";
            echo "<td>" . $movie['release_year'] . "</td>";
            echo "<td>" . ($movie['rating'] ? $movie['rating'] . "/5" : "Not rated") . "</td>";
            echo "</tr>";
        }
        echo "</table>";
        
    } else {
        echo "<p>❌ No movies found in database!</p>";
    }
    
} catch (Exception $e) {
    echo "<p>❌ Error fetching movies: " . $e->getMessage() . "</p>";
}

echo "<hr>";
echo "<h2>🎯 Summary</h2>";
echo "<p>If you see ✅ marks above, everything is working!</p>";
echo "<p>If you see ❌ marks, we need to fix those issues.</p>";
echo "<p><strong>Next step:</strong> Build the main application interface!</p>";

?>