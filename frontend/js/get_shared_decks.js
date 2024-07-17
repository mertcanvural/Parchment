document.addEventListener('DOMContentLoaded', function() {
    fetch('../../backend/public/get_categories.php')
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                const categoriesList = document.getElementById('categories-list');
                data.data.forEach(category => {
                    const li = document.createElement('li');
                    const link = document.createElement('a');
                    link.href = `decks_in_category.html?categoryId=${category.CategoryID}`;
                    link.textContent = category.CategoryName;
                    li.appendChild(link);
                    categoriesList.appendChild(li);
                });
            } else {
                console.error('Failed to fetch categories');
            }
        })
        .catch(error => console.error('Error:', error));

    fetch('../../backend/public/get_shared_decks.php')
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                const sharedDecks = document.getElementById('shared-decks');
                data.data.forEach(deck => {
                    const deckDiv = document.createElement('div');
                    deckDiv.classList.add('deck');
                    deckDiv.innerHTML = `
                        <h3>${deck.Title}</h3>
                        <p>${deck.Description}</p>
                        <p>Cards: ${deck.DeckCount}</p>
                        <p>Rating: ${deck.Rating}</p>
                    `;
                    sharedDecks.appendChild(deckDiv);
                });
            } else {
                console.error('Failed to fetch shared decks');
            }
        })
        .catch(error => console.error('Error:', error));
});