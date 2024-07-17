document.addEventListener('DOMContentLoaded', function () {
    const urlParams = new URLSearchParams(window.location.search);
    const categoryId = urlParams.get('category');

    if (!categoryId) {
        alert('Category ID is required');
        return;
    }

    fetch(`../../backend/public/get_decks_in_category.php?category=${categoryId}`)
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                displayDecks(data.decks);
                document.getElementById('category-title').textContent = data.categoryName; // Set the category name dynamically
            } else {
                alert(data.message);
            }
        })
        .catch(error => console.error('Error:', error));
});

function displayDecks(decks) {
    const container = document.getElementById('decks-container');
    container.innerHTML = '';

    if (decks.length === 0) {
        container.innerHTML = '<p>No shared decks available in this category.</p>';
        return;
    }

    const table = document.createElement('table');
    table.className = 'table';

    const thead = document.createElement('thead');
    const headerRow = document.createElement('tr');
    ['Title', 'Ratings', 'Last Modified', 'Cards'].forEach(text => {
        const th = document.createElement('th');
        th.textContent = text;
        headerRow.appendChild(th);
    });
    thead.appendChild(headerRow);
    table.appendChild(thead);

    const tbody = document.createElement('tbody');
    decks.forEach(deck => {
        const row = document.createElement('tr');

        const titleCell = document.createElement('td');
        const titleLink = document.createElement('a');
        titleLink.href = `deck_details.html?deckId=${deck.SharedDeckID}`;
        titleLink.textContent = deck.Title;
        titleCell.appendChild(titleLink);

        const ratingCell = document.createElement('td');
        ratingCell.textContent = deck.Rating;

        const modifiedCell = document.createElement('td');
        modifiedCell.textContent = deck.LastModified;

        const cardsCell = document.createElement('td');
        cardsCell.textContent = deck.Cards;

        row.appendChild(titleCell);
        row.appendChild(ratingCell);
        row.appendChild(modifiedCell);
        row.appendChild(cardsCell);

        tbody.appendChild(row);
    });

    table.appendChild(tbody);
    container.appendChild(table);
}