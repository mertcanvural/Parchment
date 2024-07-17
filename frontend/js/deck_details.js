document.addEventListener('DOMContentLoaded', function () {
    const urlParams = new URLSearchParams(window.location.search);
    const deckId = urlParams.get('deckId');

    if (!deckId) {
        alert('Deck ID is required');
        return;
    }

    fetch(`../../backend/public/get_shared_deck_details.php?deckId=${deckId}`)
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                displayDeckDetails(data.data, deckId);
            } else {
                alert(data.message);
            }
        })
        .catch(error => console.error('Error:', error));
});

function displayDeckDetails(data, deckId) {
    const container = document.getElementById('deck-details-container');
    container.innerHTML = '';

    const deckTitle = document.getElementById('deck-title');
    deckTitle.textContent = data.Title;

    const deckDescription = document.createElement('p');
    deckDescription.textContent = data.Description;
    container.appendChild(deckDescription);

    const sampleTitle = document.createElement('h3');
    sampleTitle.textContent = 'Sample Cards';
    container.appendChild(sampleTitle);

    const sampleTable = document.createElement('table');
    sampleTable.className = 'table';
    const tableHead = `<thead>
        <tr>
            <th>Front</th>
            <th>Back</th>
        </tr>
    </thead>`;
    sampleTable.innerHTML = tableHead;

    const tableBody = document.createElement('tbody');
    data.SampleCards.forEach(card => {
        const row = `<tr>
            <td>${card.Front}</td>
            <td>${card.Back}</td>
        </tr>`;
        tableBody.innerHTML += row;
    });
    sampleTable.appendChild(tableBody);
    container.appendChild(sampleTable);

    const rateTitle = document.createElement('h3');
    rateTitle.textContent = 'Rate This Deck';
    container.appendChild(rateTitle);

    const likeButton = document.createElement('button');
    likeButton.textContent = 'Like';
    likeButton.className = 'btn btn-success';
    likeButton.onclick = function() { submitRating(deckId, 1); };
    container.appendChild(likeButton);

    const dislikeButton = document.createElement('button');
    dislikeButton.textContent = 'Dislike';
    dislikeButton.className = 'btn btn-danger';
    dislikeButton.onclick = function() { submitRating(deckId, -1); };
    container.appendChild(dislikeButton);

    const copyButton = document.createElement('button');
    copyButton.textContent = 'Get this deck';
    copyButton.className = 'btn btn-primary';
    copyButton.addEventListener('click', function() {
        fetch(`/../../backend/public/copy_shared_deck.php?deckId=${deckId}`, {
            method: 'POST'
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                alert('Deck copied to your local decks.');
            } else {
                alert(data.message);
            }
        })
        .catch(error => console.error('Error:', error));
    });
    container.appendChild(copyButton);
}

function submitRating(deckId, rating) {
    fetch(`/../../backend/public/rate_shared_deck.php?deckId=${deckId}&rating=${rating}`, {
        method: 'POST'
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            alert('Thank you for your rating.');
        } else {
            alert(data.message);
        }
    })
    .catch(error => console.error('Error:', error));
}