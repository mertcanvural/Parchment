document.addEventListener('DOMContentLoaded', function() {
    fetchDecks();

    const addCardForm = document.getElementById('add-card-form');
    addCardForm.addEventListener('submit', function(event) {
        event.preventDefault();
        addCard();
    });
});

function fetchDecks() {
    fetch('/get_decks.php')
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                const deckSelect = document.getElementById('deck');
                data.decks.forEach(deck => {
                    const option = document.createElement('option');
                    option.value = deck.DeckID;
                    option.textContent = deck.DeckName;
                    deckSelect.appendChild(option);
                });
            }
        });
}

function addCard() {
    const deckId = document.getElementById('deck').value;
    const front = document.getElementById('front').value;
    const back = document.getElementById('back').value;

    fetch('/add_card.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ deckId, front, back })
    })
        .then(response => response.json())
        .then(data => {
            const messageDiv = document.getElementById('message');
            if (data.status === 'success') {
                messageDiv.textContent = 'Added';
                messageDiv.className = 'success';
            } else {
                messageDiv.textContent = data.message;
                messageDiv.className = 'error';
            }
        });
}