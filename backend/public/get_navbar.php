<?php
require 'functions.php';

header('Content-Type: text/html');

if (isLoggedIn()) {
    echo '
    <nav class="navbar navbar-default">
        <div class="container-fluid">
            <div class="navbar-header">
                <a class="navbar-brand" href="../index.php">Parchment</a>
            </div>
            <ul class="nav navbar-nav">
                <li><a href="decks.html">Decks</a></li>
                <li><a href="add.html">Add</a></li>
                <li><a href="search.html">Search</a></li>
                <li><a href="get_shared_decks.html">Get Shared Decks</a></li>
                <li><a href="my_shared_items.html">My Shared Items</a></li>
                <li><a href="stats.html">Progress</a></li>
            </ul>
            <ul class="nav navbar-nav navbar-right">
                <li><a href="../logout.php">Log Out</a></li>
            </ul>
        </div>
    </nav>';
} else {
    echo '
    <nav class="navbar navbar-default">
        <div class="container-fluid">
            <div class="navbar-header">
                <a class="navbar-brand" href="index.php">Parchment</a>
            </div>
            <ul class="nav navbar-nav navbar-right">
                <li><a href="frontend/login.html">Login</a></li>
                <li><a href="frontend/signup.html">Sign Up</a></li>
            </ul>
        </div>
    </nav>';
}
?>