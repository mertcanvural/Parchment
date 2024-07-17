document.addEventListener('DOMContentLoaded', () => {
    const deckId = getQueryParam('deckId');
    fetch(`../../backend/public/get_deck_details_for_edit.php?deckId=${deckId}`)
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                populateEditForm(data.data);
            } else {
                alert(data.message);
            }
        });

    document.getElementById('update-button').addEventListener('click', updateDeck);

    function getQueryParam(param) {
        const urlParams = new URLSearchParams(window.location.search);
        return urlParams.get(param);
    }

    function populateEditForm(deck) {
        const container = document.getElementById('edit-deck-container');
        container.innerHTML = `
            <div class="form-group">
                <label for="deck-name">Deck Name</label>
                <input type="text" class="form-control" id="deck-name" value="${deck.DeckName}">
            </div>
            <div class="form-group">
                <label for="deck-description">Description</label>
                <textarea class="form-control" id="deck-description">${deck.Description}</textarea>
            </div>
            <h2>Cards</h2>
            <div id="cards-container">
                ${deck.Cards.map(card => `
                    <div class="card" data-card-id="${card.CardID}">
                        <div class="form-group">
                            <label for="card-front-${card.CardID}">Front</label>
                            <textarea class="form-control" id="card-front-${card.CardID}">${card.Front}</textarea>
                        </div>
                        <div class="form-group">
                            <label for="card-back-${card.CardID}">Back</label>
                            <textarea class="form-control" id="card-back-${card.CardID}">${card.Back}</textarea>
                        </div>
                    </div>
                `).join('')}
            </div>
        `;
    }

    function updateDeck() {
        const deckId = getQueryParam('deckId');
        const deckName = document.getElementById('deck-name').value;
        const description = document.getElementById('deck-description').value;
        const cards = Array.from(document.querySelectorAll('.card')).map(card => ({
            CardID: card.getAttribute('data-card-id'),
            Front: card.querySelector(`#card-front-${card.getAttribute('data-card-id')}`).value,
            Back: card.querySelector(`#card-back-${card.getAttribute('data-card-id')}`).value
        }));

        fetch('../../backend/public/update_deck.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                deckId,
                deckName,
                description,
                cards: JSON.stringify(cards)
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                alert('Deck updated successfully');
                window.location.href = `deck_details.html?deckId=${deckId}`;
            } else {
                alert(data.message);
            }
        })
        .catch(error => console.error('Error:', error));
    }
});
