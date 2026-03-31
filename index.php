<?php
/**
 * MyMovieDB - Complete CRUD Application
 * Web Programming Project - Spring 2025
 * Student: [Your Name Here]
 * Istanbul Medipol University
 */

// Enable error reporting for development
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include database configuration
require_once 'config.php';

// Get the action from URL (default is 'read' - view movies)
$action = $_GET['action'] ?? 'read';
$message = '';
$error = '';

try {
    $pdo = getDBConnection();
    
    // Handle CRUD operations
    
    // CREATE - Add new movie
    if ($action === 'create' && $_POST) {
        $stmt = $pdo->prepare("INSERT INTO movies (title, director, genre, release_year, duration, rating, watch_status, review_text, personal_notes) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            $_POST['title'],
            $_POST['director'],
            $_POST['genre'],
            $_POST['release_year'],
            $_POST['duration'] ?: null,
            $_POST['rating'] ?: null,
            $_POST['watch_status'],
            $_POST['review_text'] ?: null,
            $_POST['personal_notes'] ?: null
        ]);
        $message = "🎬 Movie added successfully!";
        $action = 'read'; // Redirect to list view
    }
    
    // UPDATE - Edit existing movie
    if ($action === 'update' && $_POST && isset($_POST['id'])) {
        $stmt = $pdo->prepare("UPDATE movies SET title=?, director=?, genre=?, release_year=?, duration=?, rating=?, watch_status=?, review_text=?, personal_notes=? WHERE id=?");
        $stmt->execute([
            $_POST['title'],
            $_POST['director'],
            $_POST['genre'],
            $_POST['release_year'],
            $_POST['duration'] ?: null,
            $_POST['rating'] ?: null,
            $_POST['watch_status'],
            $_POST['review_text'] ?: null,
            $_POST['personal_notes'] ?: null,
            $_POST['id']
        ]);
        $message = "✏️ Movie updated successfully!";
        $action = 'read';
    }
    
    // DELETE - Remove movie
    if ($action === 'delete' && isset($_GET['id'])) {
        $stmt = $pdo->prepare("DELETE FROM movies WHERE id = ?");
        $stmt->execute([$_GET['id']]);
        $message = "🗑️ Movie deleted successfully!";
        $action = 'read';
    }
    
    // READ - Get all movies
    $movies = [];
    if ($action === 'read' || $action === 'create') {
        $stmt = $pdo->query("SELECT * FROM movies ORDER BY date_added DESC");
        $movies = $stmt->fetchAll();
    }
    
    // Get single movie for editing
    $editMovie = null;
    if ($action === 'edit' && isset($_GET['id'])) {
        $stmt = $pdo->prepare("SELECT * FROM movies WHERE id = ?");
        $stmt->execute([$_GET['id']]);
        $editMovie = $stmt->fetch();
        if (!$editMovie) {
            $error = "Movie not found!";
            $action = 'read';
        }
    }
    
} catch (Exception $e) {
    $error = "❌ Error: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MyMovieDB - Personal Movie Collection</title>
    <style>
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: 'Segoe UI', -apple-system, BlinkMacSystemFont, sans-serif;
    line-height: 1.6;
    color: #e0e0e0;
    background: linear-gradient(to right, #0f0c29, #302b63, #24243e);
    min-height: 100vh;
    padding: 20px;
}

.container {
    max-width: 1200px;
    margin: 0 auto;
    background: rgba(255, 255, 255, 0.05);
    backdrop-filter: blur(10px);
    padding: 30px;
    border-radius: 12px;
    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
    border: 1px solid rgba(255, 255, 255, 0.1);
}

.header {
    text-align: center;
    margin-bottom: 40px;
    padding-bottom: 20px;
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
}

.header h1 {
    color: #fff;
    margin-bottom: 10px;
    font-size: 2.8em;
    font-weight: 700;
    letter-spacing: -0.5px;
    text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
}

.header p {
    color: #a0a0a0;
    font-size: 1.2em;
    font-weight: 400;
}

.nav {
    text-align: center;
    margin-bottom: 30px;
}

.nav a {
    display: inline-block;
    padding: 12px 24px;
    margin: 0 10px;
    background: rgba(52, 152, 219, 0.8);
    color: white;
    text-decoration: none;
    border-radius: 8px;
    transition: all 0.3s ease;
    font-weight: 500;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.nav a:hover {
    background: rgba(41, 128, 185, 0.9);
    transform: translateY(-2px);
    box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2);
}

.nav a.active {
    background: rgba(46, 204, 113, 0.9);
}

/* Messages */
.message {
    padding: 15px;
    margin: 20px 0;
    border-radius: 8px;
    text-align: center;
    font-weight: 500;
}

.message.success {
    background: rgba(46, 204, 113, 0.2);
    color: #d4edda;
    border: 1px solid rgba(46, 204, 113, 0.3);
}

.message.error {
    background: rgba(231, 76, 60, 0.2);
    color: #f8d7da;
    border: 1px solid rgba(231, 76, 60, 0.3);
}

/* Forms */
.form-section {
    margin: 30px 0;
    background: rgba(255, 255, 255, 0.08);
    padding: 30px;
    border-radius: 10px;
    border: 1px solid rgba(255, 255, 255, 0.1);
}

.form-section h2 {
    margin-bottom: 25px;
    color: #fff;
    font-weight: 600;
}

.form-group {
    margin-bottom: 20px;
}

.form-group label {
    display: block;
    margin-bottom: 8px;
    font-weight: 600;
    color: #ddd;
    font-size: 0.95em;
}

.form-group input,
.form-group select,
.form-group textarea {
    width: 100%;
    padding: 12px 15px;
    background: rgba(255, 255, 255, 0.1);
    border: 1px solid rgba(255, 255, 255, 0.2);
    border-radius: 6px;
    font-size: 16px;
    color: #fff;
    transition: all 0.3s ease;
}

.form-group input:focus,
.form-group select:focus,
.form-group textarea:focus {
    outline: none;
    border-color: #3498db;
    background: rgba(255, 255, 255, 0.15);
    box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.2);
}

/* Buttons */
.btn {
    padding: 12px 24px;
    margin: 0 8px;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    font-size: 16px;
    font-weight: 500;
    transition: all 0.3s ease;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2);
}

.btn-primary {
    background: #27ae60;
    color: white;
}

.btn-primary:hover {
    background: #2ecc71;
}

.btn-secondary {
    background: #95a5a6;
    color: white;
}

.btn-secondary:hover {
    background: #7f8c8d;
}

.btn-warning {
    background: #f39c12;
    color: white;
}

.btn-warning:hover {
    background: #e67e22;
}

.btn-danger {
    background: #e74c3c;
    color: white;
}

.btn-danger:hover {
    background: #c0392b;
}

/* Movie Cards */
.movie-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 25px;
    margin: 30px 0;
}

.movie-card {
    background: rgba(255, 255, 255, 0.08);
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: 12px;
    padding: 25px;
    box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
    transition: all 0.3s ease;
    overflow: hidden;
    position: relative;
}

.movie-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 12px 24px rgba(0, 0, 0, 0.3);
    border-color: rgba(255, 255, 255, 0.2);
}

.movie-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 4px;
    background: linear-gradient(to right, #3498db, #9b59b6);
}

.movie-header {
    margin-bottom: 20px;
    padding-bottom: 15px;
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
}

.movie-header h3 {
    color: #fff;
    margin-bottom: 8px;
    font-size: 1.4em;
    font-weight: 600;
}

.movie-header small {
    color: #a0a0a0;
    font-size: 0.9em;
}

.movie-info p {
    margin-bottom: 10px;
    color: #ccc;
    font-size: 0.95em;
}

.movie-info strong {
    color: #fff;
}

.movie-actions {
    margin-top: 20px;
    padding-top: 15px;
    border-top: 1px solid rgba(255, 255, 255, 0.1);
    text-align: center;
}

/* Empty State */
.empty-state {
    text-align: center;
    padding: 80px 20px;
    color: #a0a0a0;
}

.empty-state h3 {
    margin-bottom: 15px;
    color: #ccc;
    font-weight: 500;
}

/* Stats Bar */
.stats-bar {
    background: rgba(255, 255, 255, 0.08);
    padding: 20px;
    border-radius: 8px;
    margin-bottom: 30px;
    text-align: center;
    border: 1px solid rgba(255, 255, 255, 0.1);
}

.stats-bar p {
    margin: 0;
    font-size: 1.1em;
    color: #fff;
    font-weight: 500;
}
    </style>
</head>
<body>
    <div class="container">
        <header class="header">
            <h1>🎬 MyMovieDB</h1>
            <p>Your Personal Movie Review System</p>
            <small>Web Programming Project - Spring 2025</small>
        </header>

        <nav class="nav">
            <a href="?action=read" class="<?= $action === 'read' ? 'active' : '' ?>">📚 My Movies</a>
            <a href="?action=create" class="<?= $action === 'create' ? 'active' : '' ?>">➕ Add Movie</a>
        </nav>

        <?php if ($message): ?>
            <div class="message success"><?= htmlspecialchars($message) ?></div>
        <?php endif; ?>

        <?php if ($error): ?>
            <div class="message error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <main>
            <?php if ($action === 'read'): ?>
                <!-- MOVIE LIST VIEW -->
                <section>
                    <h2>📖 My Movie Collection</h2>
                    
                    <?php if (empty($movies)): ?>
                        <div class="empty-state">
                            <h3>🎬 No movies in your collection yet!</h3>
                            <p>Start building your movie library!</p>
                            <a href="?action=create" class="btn btn-primary">Add Your First Movie</a>
                        </div>
                    <?php else: ?>
                        <div class="stats-bar">
                            <p>📊 Total movies in your collection: <strong><?= count($movies) ?></strong></p>
                        </div>
                        
                        <div class="movie-grid">
                            <?php foreach ($movies as $movie): ?>
                                <div class="movie-card">
                                    <div class="movie-header">
                                        <h3><?= htmlspecialchars($movie['title']) ?></h3>
                                        <small>Released: <?= $movie['release_year'] ?></small>
                                    </div>
                                    
                                    <div class="movie-info">
                                        <p><strong>🎯 Director:</strong> <?= htmlspecialchars($movie['director']) ?></p>
                                        <p><strong>🎭 Genre:</strong> <?= htmlspecialchars($movie['genre']) ?></p>
                                        
                                        <?php if ($movie['duration']): ?>
                                            <p><strong>⏱️ Duration:</strong> <?= $movie['duration'] ?> minutes</p>
                                        <?php endif; ?>
                                        
                                        <p><strong>📺 Status:</strong> <?= formatWatchStatus($movie['watch_status']) ?></p>
                                        
                                        <?php if ($movie['rating']): ?>
                                            <p><strong>⭐ My Rating:</strong> <?= displayStars($movie['rating']) ?></p>
                                        <?php endif; ?>
                                        
                                        <?php if ($movie['review_text']): ?>
                                            <p><strong>📝 Review:</strong> <?= htmlspecialchars(substr($movie['review_text'], 0, 100)) ?><?= strlen($movie['review_text']) > 100 ? '...' : '' ?></p>
                                        <?php endif; ?>
                                        
                                        <?php if ($movie['personal_notes']): ?>
                                            <p><strong>📋 Notes:</strong> <?= htmlspecialchars(substr($movie['personal_notes'], 0, 80)) ?><?= strlen($movie['personal_notes']) > 80 ? '...' : '' ?></p>
                                        <?php endif; ?>
                                    </div>
                                    
                                    <div class="movie-actions">
                                        <a href="?action=edit&id=<?= $movie['id'] ?>" class="btn btn-warning">✏️ Edit</a>
                                        <a href="?action=delete&id=<?= $movie['id'] ?>" 
                                           onclick="return confirm('Are you sure you want to delete \"<?= htmlspecialchars($movie['title']) ?>\"?')" 
                                           class="btn btn-danger">🗑️ Delete</a>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </section>

            <?php elseif ($action === 'create' || $action === 'edit'): ?>
                <!-- ADD/EDIT MOVIE FORM -->
                <section class="form-section">
                    <h2><?= $action === 'edit' ? '✏️ Edit Movie' : '🎬 Add New Movie' ?></h2>
                    
                    <form method="POST" action="?action=<?= $action === 'edit' ? 'update' : 'create' ?>">
                        <?php if ($action === 'edit' && $editMovie): ?>
                            <input type="hidden" name="id" value="<?= $editMovie['id'] ?>">
                        <?php endif; ?>
                        
                        <div class="form-group">
                            <label for="title">🎬 Movie Title *</label>
                            <input type="text" id="title" name="title" required 
                                   value="<?= htmlspecialchars($editMovie['title'] ?? '') ?>"
                                   placeholder="Enter movie title">
                        </div>
                        
                        <div class="form-group">
                            <label for="director">🎯 Director *</label>
                            <input type="text" id="director" name="director" required 
                                   value="<?= htmlspecialchars($editMovie['director'] ?? '') ?>"
                                   placeholder="Enter director name">
                        </div>
                        
                        <div class="form-group">
                            <label for="genre">🎭 Genre *</label>
                            <select id="genre" name="genre" required>
                                <option value="">Select Genre</option>
                                <?php 
                                $genres = ['Action', 'Adventure', 'Animation', 'Comedy', 'Crime', 'Drama', 'Fantasy', 'Horror', 'Romance', 'Sci-Fi', 'Thriller'];
                                foreach ($genres as $genre): 
                                ?>
                                    <option value="<?= $genre ?>" <?= ($editMovie['genre'] ?? '') === $genre ? 'selected' : '' ?>>
                                        <?= $genre ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="release_year">📅 Release Year *</label>
                            <input type="number" id="release_year" name="release_year" required 
                                   min="1900" max="2030"
                                   value="<?= $editMovie['release_year'] ?? '' ?>"
                                   placeholder="2024">
                        </div>
                        
                        <div class="form-group">
                            <label for="duration">⏱️ Duration (minutes)</label>
                            <input type="number" id="duration" name="duration" 
                                   min="1" max="500"
                                   value="<?= $editMovie['duration'] ?? '' ?>"
                                   placeholder="120">
                        </div>
                        
                        <div class="form-group">
                            <label for="watch_status">📺 Watch Status *</label>
                            <select id="watch_status" name="watch_status" required>
                                <option value="want_to_watch" <?= ($editMovie['watch_status'] ?? 'want_to_watch') === 'want_to_watch' ? 'selected' : '' ?>>📋 Want to Watch</option>
                                <option value="currently_watching" <?= ($editMovie['watch_status'] ?? '') === 'currently_watching' ? 'selected' : '' ?>>👀 Currently Watching</option>
                                <option value="watched" <?= ($editMovie['watch_status'] ?? '') === 'watched' ? 'selected' : '' ?>>✅ Watched</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="rating">⭐ Your Rating (1-5 stars)</label>
                            <select id="rating" name="rating">
                                <option value="">Not Rated</option>
                                <option value="1" <?= ($editMovie['rating'] ?? '') == '1' ? 'selected' : '' ?>>⭐ 1 - Terrible</option>
                                <option value="2" <?= ($editMovie['rating'] ?? '') == '2' ? 'selected' : '' ?>>⭐⭐ 2 - Poor</option>
                                <option value="3" <?= ($editMovie['rating'] ?? '') == '3' ? 'selected' : '' ?>>⭐⭐⭐ 3 - Okay</option>
                                <option value="4" <?= ($editMovie['rating'] ?? '') == '4' ? 'selected' : '' ?>>⭐⭐⭐⭐ 4 - Good</option>
                                <option value="5" <?= ($editMovie['rating'] ?? '') == '5' ? 'selected' : '' ?>>⭐⭐⭐⭐⭐ 5 - Excellent</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="review_text">📝 Your Review</label>
                            <textarea id="review_text" name="review_text" rows="4" 
                                      placeholder="Write your thoughts about this movie..."><?= htmlspecialchars($editMovie['review_text'] ?? '') ?></textarea>
                        </div>
                        
                        <div class="form-group">
                            <label for="personal_notes">📋 Personal Notes</label>
                            <textarea id="personal_notes" name="personal_notes" rows="3" 
                                      placeholder="Any personal notes, reminders, or comments..."><?= htmlspecialchars($editMovie['personal_notes'] ?? '') ?></textarea>
                        </div>
                        
                        <div class="form-actions">
                            <button type="submit" class="btn btn-primary">
                                <?= $action === 'edit' ? '✏️ Update Movie' : '🎬 Add Movie' ?>
                            </button>
                            <a href="?action=read" class="btn btn-secondary">❌ Cancel</a>
                        </div>
                    </form>
                </section>
            <?php endif; ?>
        </main>

        <footer style="text-align: center; margin-top: 40px; padding-top: 20px; border-top: 1px solid #e9ecef; color: #7f8c8d;">
            <p>&copy; 2025 MyMovieDB | Web Programming Project | Istanbul Medipol University</p>
        </footer>
    </div>
</body>
</html>