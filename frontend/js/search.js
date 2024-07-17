document.addEventListener('DOMContentLoaded', function () {
    const searchForm = document.getElementById('search-form');
    const searchResults = document.getElementById('search-results');

    searchForm.addEventListener('submit', function (event) {
        event.preventDefault();
        const query = document.getElementById('search-query').value.trim();
        searchResults.innerHTML = '';

        if (query === '') {
            searchResults.innerHTML = '<p>Keyword is required</p>';
            return;
        }

        fetch(`../../backend/public/search_decks.php?query=${query}`)
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    const table = document.createElement('table');
                    table.classList.add('table', 'table-striped', 'mt-3');
                    const thead = document.createElement('thead');
                    thead.innerHTML = `
                        <tr>
                            <th>Deck Name</th>
                            <th>Front</th>
                            <th>Back</th>
                            <th></th>
                        </tr>
                    `;
                    table.appendChild(thead);
                    const tbody = document.createElement('tbody');
                    data.decks.forEach(deck => {
                        const row = document.createElement('tr');
                        row.innerHTML = `
                            <td>${deck.DeckName}</td>
                            <td>${deck.Front}</td>
                            <td>${deck.Back}</td>
                            <td><a href="edit_card.html?cardId=${deck.CardID}" class="btn btn-secondary btn-sm">Edit</a></td>
                        `;
                        tbody.appendChild(row);
                    });
                    table.appendChild(tbody);
                    searchResults.appendChild(table);
                } else {
                    searchResults.innerHTML = `<p>${data.message}</p>`;
                }
            })
            .catch(error => console.error('Error:', error));
    });
});