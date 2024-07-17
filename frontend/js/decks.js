// decks.js
// Manages deck creation, loading, renaming, deleting, and sharing.
document.addEventListener('DOMContentLoaded', function () {
    // Handle deck creation
    document.getElementById('create-deck-button').addEventListener('click', function() {
        const deckName = prompt('Enter new deck name:');
        if (deckName) {
            fetch('/../../backend/public/create_deck.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ deckName: deckName })
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    loadDecks();
                } else {
                    alert(data.message);
                }
            });
        }
    });

    // Load the decks
    function loadDecks() {
        fetch('../../backend/public/get_decks.php')
            .then(response => response.json())
            .then(data => {
                const deckList = document.getElementById('deck-list');
                deckList.innerHTML = '';
                if (data.decks.length === 0) {
                    const noDecksMessage = document.createElement('li');
                    noDecksMessage.classList.add('list-group-item', 'text-center');
                    noDecksMessage.textContent = 'No decks available';
                    deckList.appendChild(noDecksMessage);
                } else {
                    data.decks.forEach(deck => {
                        const li = document.createElement('li');
                        li.classList.add('deck-item', 'list-group-item');
                        li.innerHTML = `
                            <span class="deck-name">${deck.DeckName}</span>
                            <div class="deck-actions">
                                <button class="btn btn-success study-deck" data-id="${deck.DeckID}">Study</button>
                                <button class="btn btn-secondary rename-deck" data-id="${deck.DeckID}">Rename</button>
                                <button class="btn btn-danger delete-deck" data-id="${deck.DeckID}">Delete</button>
                                <button class="btn btn-primary share-deck" data-id="${deck.DeckID}">Share</button>
                            </div>
                        `;
                        deckList.appendChild(li);
                    });
                }

                // Handle renaming decks
                document.querySelectorAll('.rename-deck').forEach(button => {
                    button.addEventListener('click', function() {
                        const deckId = this.getAttribute('data-id');
                        const newName = prompt('Enter new deck name:');
                        if (newName) {
                            fetch('../../backend/public/rename_deck.php', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json'
                                },
                                body: JSON.stringify({ deckId: deckId, newName: newName })
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.status === 'success') {
                                    loadDecks();
                                } else {
                                    alert(data.message);
                                }
                            });
                        }
                    });
                });

                // Handle deleting decks
                document.querySelectorAll('.delete-deck').forEach(button => {
                    button.addEventListener('click', function() {
                        const deckId = this.getAttribute('data-id');
                        if (confirm('Are you sure you want to delete this deck?')) {
                            fetch('../../backend/public/delete_deck.php', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json'
                                },
                                body: JSON.stringify({ deckId: deckId })
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.status === 'success') {
                                    loadDecks();
                                } else {
                                    alert(data.message);
                                }
                            });
                        }
                    });
                });

                // Handle studying decks
                document.querySelectorAll('.study-deck').forEach(button => {
                    button.addEventListener('click', function() {
                        const deckId = this.getAttribute('data-id');
                        const deckName = this.closest('.deck-item').querySelector('.deck-name').innerText;
                        window.location.href = `/frontend/pages/study_dashboard.html?deckId=${deckId}&deckName=${deckName}`;
                    });
                });

                // Handle sharing decks
                document.querySelectorAll('.share-deck').forEach(button => {
                    button.addEventListener('click', function() {
                        const deckId = this.getAttribute('data-id');
                        window.location.href = `/frontend/pages/share_deck.html?deckId=${deckId}`;
                    });
                });
            });
    }

    loadDecks();
});