// share_deck.js
document.addEventListener('DOMContentLoaded', function () {
    const urlParams = new URLSearchParams(window.location.search);
    const deckId = urlParams.get('deckId');

    if (deckId) {
        document.getElementById('deck-id').value = deckId;

        fetch(`../../backend/public/get_deck_details.php?deckId=${deckId}`)
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    document.getElementById('deck-title').value = data.deck.DeckName;
                    // You can also set other details if needed
                } else {
                    document.getElementById('deck-title').value = 'Deck not found';
                }
            });

        fetch('../../backend/public/get_categories.php')
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    const categorySelect = document.getElementById('deck-category');
                    data.data.forEach(category => {
                        const option = document.createElement('option');
                        option.value = category.CategoryID;
                        option.textContent = category.CategoryName;
                        categorySelect.appendChild(option);
                    });
                } else {
                    const categorySelect = document.getElementById('deck-category');
                    const option = document.createElement('option');
                    option.value = '';
                    option.textContent = 'No categories available';
                    categorySelect.appendChild(option);
                }
            });

        document.getElementById('share-deck-form').addEventListener('submit', function (e) {
            e.preventDefault();
            const formData = new FormData(this);
            const jsonData = {};
            formData.forEach((value, key) => jsonData[key] = value);
            jsonData['deckId'] = deckId;

            fetch('../../backend/public/share_deck.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(jsonData)
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    alert('Deck shared successfully');
                } else {
                    alert('Failed to share deck: ' + data.message);
                }
            });
        });
    } else {
        alert('No deck ID provided');
    }
});