document.addEventListener('DOMContentLoaded', function () {
    const urlParams = new URLSearchParams(window.location.search);
    const cardId = urlParams.get('cardId');

    if (!cardId) {
        alert('Card ID is required');
        return;
    }

    fetch(`../../backend/public/get_card_details.php?cardId=${cardId}`)
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                document.getElementById('card-id').value = data.card.CardID;
                document.getElementById('deck-name').value = data.card.DeckName;
                document.getElementById('card-front').value = data.card.Front;
                document.getElementById('card-back').value = data.card.Back;
            } else {
                alert(data.message);
            }
        })
        .catch(error => console.error('Error:', error));

    document.getElementById('edit-card-form').addEventListener('submit', function (event) {
        event.preventDefault();

        const cardData = {
            cardId: document.getElementById('card-id').value,
            front: document.getElementById('card-front').value,
            back: document.getElementById('card-back').value
        };

        fetch('../../backend/public/update_card.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(cardData)
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                alert('Card updated successfully');
                window.location.href = 'search.html';
            } else {
                alert(data.message);
            }
        })
        .catch(error => console.error('Error:', error));
    });
});