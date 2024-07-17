document.addEventListener('DOMContentLoaded', function () {
    const navbarPlaceholder = document.getElementById('navbar-placeholder');
    navbarPlaceholder.innerHTML = `
        <nav class="navbar navbar-default">
            <div class="container-fluid">
                <div class="navbar-header">
                    <a class="navbar-brand" href="/../../index.html"><img src="../assets/img/logo.png" alt="Parchment" style="height:24px;"></a>
                </div>
                <ul class="nav navbar-nav">
                    <li><a href="decks.html">Decks</a></li>
                    <li><a href="add.html">Add</a></li>
                    <li><a href="search.html">Search</a></li>
                    <li><a href="shared_decks.html">Get Shared Decks</a></li>
                    <li><a href="my_shared_items.html">My Shared Items</a></li>
                    <li><a href="progress.html">Progress</a></li>
                    <li><a href="import_export.html">Import/Export</a></li>
                    <li><a href="/../../backend/public/logout.php">Log Out</a></li>
                </ul>
            </div>
        </nav>
    `;
});