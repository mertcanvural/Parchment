<?php
require_once '../config/db.php';
require_once '../config/functions.php';

header('Content-Type: text/html');

if (isLoggedIn()) {
    echo '
    <nav class="navbar navbar-default">
        <div class="container-fluid">
            <div class="navbar-header">
                <a class="navbar-brand" href="../../index.html">Parchment</a>
            </div>
            <ul class="nav navbar-nav">
                <li><a href="../../frontend/pages/decks.html">Decks</a></li>
                <li><a href="../../frontend/pages/add.html">Add</a></li>
                <li><a href="../../frontend/pages/search.html">Search</a></li>
                <li><a href="../../frontend/pages/shared_decks.html">Get Shared Decks</a></li>
                <li><a href="/../../frontend/pages/my_shared_items.html">My Shared Items</a></li>
                <li><a href="../../frontend/pages/stats.html">Progress</a></li>
            </ul>
            <ul class="nav navbar-nav navbar-right">
                <li><a href="../../backend/public/logout.php">Log Out</a></li>
            </ul>
        </div>
    </nav>';
} else {
    echo '
    <nav class="navbar navbar-default">
        <div class="container-fluid">
            <div class="navbar-header">
                <a class="navbar-brand" href="../../index.html">Parchment</a>
            </div>
            <ul class="nav navbar-nav navbar-right">
                <li><a href="../../frontend/pages/login.html">Login</a></li>
                <li><a href="../../frontend/pages/signup.html">Sign Up</a></li>
            </ul>
        </div>
    </nav>';
}

?>
