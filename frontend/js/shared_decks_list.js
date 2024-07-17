document.addEventListener('DOMContentLoaded', () => {
    const category = getQueryParam('category');
    fetch(`/backend/public/get_decks_in_category.php?category=${category}`)
        .then(response => response.json())
        .then(decks => {
            displayDecks(decks, category);
        });

    function getQueryParam(param) {
        const urlParams = new URLSearchParams(window.location.search);
        return urlParams.get(param);
    }

    function displayDecks(decks, categoryName) {
        const container = document.getElementById('decks');
        const categoryNameElem = document.getElementById('category-name');
        categoryNameElem.textContent = categoryName;

        if (decks.length === 0) {
            container.innerHTML = '<p>This category currently has no shared decks.</p>';
            return;
        }

        const table = document.createElement('table');
        table.className = 'table';

        const thead = document.createElement('thead');
        const headerRow = document.createElement('tr');

        const headers = ['Title', 'Ratings', 'Last Modified', 'Cards'];
        headers.forEach(headerText => {
            const th = document.createElement('th');
            th.textContent = headerText;
            headerRow.appendChild(th);
        });

        thead.appendChild(headerRow);
        table.appendChild(thead);

        const tbody = document.createElement('tbody');

        decks.forEach(deck => {
            const row = document.createElement('tr');

            const titleCell = document.createElement('td');
            const titleLink = document.createElement('a');
            titleLink.href = `deck_details.html?deckId=${deck.DeckID}`;
            titleLink.textContent = deck.Title;
            titleCell.appendChild(titleLink);
            row.appendChild(titleCell);

            const ratingCell = document.createElement('td');
            ratingCell.textContent = deck.Rating;
            row.appendChild(ratingCell);

            const modifiedDateCell = document.createElement('td');
            modifiedDateCell.textContent = deck.SharedDate;
            row.appendChild(modifiedDateCell);

            const cardsCell = document.createElement('td');
            cardsCell.textContent = deck.DeckCount;
            row.appendChild(cardsCell);

            tbody.appendChild(row);
        });

        table.appendChild(tbody);
        container.appendChild(table);
    }
});

document.addEventListener('DOMContentLoaded', () => {
    fetch('../../backend/public/get_shared_decks.php')
        .then(response => response.json())
        .then(categories => {
            const container = document.getElementById('sharedDecksContainer');
            container.innerHTML = '';

            categories.forEach(category => {
                const categoryDiv = document.createElement('div');
                categoryDiv.className = 'category';

                const categoryName = document.createElement('h2');
                categoryName.textContent = category.CategoryName;
                categoryDiv.appendChild(categoryName);

                fetch(`/backend/public/get_decks_in_category.php?category=${category.CategoryID}`)
                    .then(response => response.json())
                    .then(decks => {
                        if (decks.length === 0) {
                            const noDecksMessage = document.createElement('p');
                            noDecksMessage.textContent = 'This category currently has no shared decks.';
                            categoryDiv.appendChild(noDecksMessage);
                        } else {
                            const ul = document.createElement('ul');
                            decks.forEach(deck => {
                                const li = document.createElement('li');
                                const link = document.createElement('a');
                                link.href = `deck_details.html?deckId=${deck.DeckID}`;
                                link.textContent = deck.Title;
                                li.appendChild(link);
                                ul.appendChild(li);
                            });
                            categoryDiv.appendChild(ul);
                        }
                    });

                container.appendChild(categoryDiv);
            });
        });
});
