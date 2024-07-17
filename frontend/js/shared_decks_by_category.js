document.addEventListener('DOMContentLoaded', function () {
    const urlParams = new URLSearchParams(window.location.search);
    const categoryId = urlParams.get('categoryId');
    fetchSharedDecksByCategory(categoryId);
});

function fetchSharedDecksByCategory(categoryId) {
    fetch(`../../backend/public/get_shared_decks_by_category.php?categoryId=${categoryId}`)
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                displaySharedDecks(data.data);
            } else {
                console.error('Failed to fetch shared decks');
            }
        })
        .catch(error => console.error('Error:', error));
}

function displaySharedDecks(decks) {
    const categoryTitle = document.getElementById('category-title');
    categoryTitle.textContent = 'Shared Decks';

    const decksContainer = document.getElementById('shared-decks');
    decksContainer.innerHTML = decks.map(deck => `
        <div class="deck">
            <h2><a href="deck_details.html?deckId=${deck.DeckID}">${deck.Title}</a></h2>
            <p>Rating: ${deck.Rating}</p>
            <p>Last Modified: ${deck.SharedDate}</p>
            <p>Total Cards: ${deck.DeckCount}</p>
        </div>
    `).join('');
}