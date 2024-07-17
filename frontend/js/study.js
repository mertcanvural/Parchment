let currentIndex = 0;
let cards = [];
let deckId;

document.addEventListener('DOMContentLoaded', function () {
    const urlParams = new URLSearchParams(window.location.search);
    deckId = urlParams.get('deckId');
    const deckName = urlParams.get('deckName');
    document.getElementById('deck-title').textContent = deckName;

    if (!deckId) {
        alert('Deck ID is required');
        return;
    }

    fetchCards(deckId).then(fetchedCards => {
        cards = fetchedCards;
        loadNextCard();
    });

    document.getElementById('show-answer').addEventListener('click', function () {
        document.getElementById('answer-container').style.display = 'block';
        document.getElementById('show-answer').style.display = 'none';
    });

    document.getElementById('easy').addEventListener('click', async function () {
        const card = cards[currentIndex];
        const success = await updateCardStatus(card.CardID, 'easy');
        if (success) {
            currentIndex++;
            loadNextCard();
        }
    });

    document.getElementById('hard').addEventListener('click', async function () {
        const card = cards[currentIndex];
        const success = await updateCardStatus(card.CardID, 'hard');
        if (success) {
            currentIndex++;
            loadNextCard();
        }
    });
});

function fetchCards(deckId) {
    return fetch(`../../backend/public/get_cards_for_study.php?deckId=${deckId}&limit=20`)
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                return data.cards;
            } else {
                alert(data.message);
                return [];
            }
        })
        .catch(error => {
            console.error('Error:', error);
            return [];
        });
}

function loadNextCard() {
    if (currentIndex >= cards.length) {
        fetchCards(deckId).then(fetchedCards => {
            cards = fetchedCards;
            currentIndex = 0;
            if (cards.length === 0) {
                document.getElementById('card-content').innerHTML = '<p>Study session finished. Come back later.</p>';
            } else {
                loadNextCard();
            }
        });
    } else {
        const card = cards[currentIndex];
        document.getElementById('question').textContent = card.Front;
        document.getElementById('answer').textContent = card.Back;
        document.getElementById('answer-container').style.display = 'none';
        document.getElementById('show-answer').style.display = 'block';
    }
}

function updateCardStatus(cardId, action) {
    return fetch('../../backend/public/update_card_status.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ cardId, action, deckId })
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            return true;
        } else {
            alert(data.message);
            return false;
        }
    })
    .catch(error => {
        console.error('Error:', error);
        return false;
    });
}