// shared_item_detail.js
document.addEventListener('DOMContentLoaded', function () {
    const urlParams = new URLSearchParams(window.location.search);
    const deckId = urlParams.get('deckId');

    if (!deckId) {
        alert('Deck ID is required');
        return;
    }

    fetch(`../../backend/public/get_shared_item_detail.php?deckId=${deckId}`)
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                displayDeckDetail(data.deck);
            } else {
                alert(data.message);
            }
        })
        .catch(error => console.error('Error:', error));
});

function displayDeckDetail(deck) {
    document.getElementById('deck-title').textContent = deck.Title;
    document.getElementById('deck-description').textContent = deck.Description;

    const sampleCards = document.getElementById('sample-cards');
    sampleCards.innerHTML = '<h3>Sample (from 1 notes)</h3>';
    
    deck.Cards.forEach(card => {
        const front = document.createElement('div');
        front.textContent = card.Front;

        const back = document.createElement('div');
        back.textContent = card.Back;

        sampleCards.appendChild(front);
        sampleCards.appendChild(back);
    });

    document.getElementById('download-button').addEventListener('click', function () {
        // Handle the download functionality here
    });

    document.getElementById('remove-button').addEventListener('click', function () {
        fetch(`../../backend/public/remove_shared_item.php?deckId=${deck.DeckID}`, {
            method: 'POST'
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                alert('Deck removed successfully');
                window.location.href = 'my_shared_items.html';
            } else {
                alert(data.message);
            }
        })
        .catch(error => console.error('Error:', error));
    });
}