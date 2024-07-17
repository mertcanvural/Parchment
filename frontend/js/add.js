document.addEventListener('DOMContentLoaded', function() {
    fetch('../../backend/public/get_decks.php')
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
            } else {
                console.error('Failed to fetch decks:', data.message);
            }
        })
        .catch(error => console.error('Error:', error));
});

function populateDecksDropdown(decks) {
    const deckSelect = document.getElementById('deck');
    deckSelect.innerHTML = '';
    decks.forEach(deck => {
        const option = document.createElement('option');
        option.value = deck.DeckID;
        option.textContent = deck.DeckName;
        deckSelect.appendChild(option);
    });
}

document.getElementById('add-card-form').addEventListener('submit', function(event) {
    event.preventDefault();

    const deckId = document.getElementById('deck').value;
    const question = document.getElementById('question').value;
    const answer = document.getElementById('answer').value;

    fetch('../../backend/public/add_card.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            deckId: deckId,
            question: question,
            answer: answer
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            alert('Card added successfully!');
            document.getElementById('add-card-form').reset();
        } else {
            alert(data.message);
        }
    })
    .catch(error => console.error('Error:', error));
});
