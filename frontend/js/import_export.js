document.addEventListener('DOMContentLoaded', function () {
    loadDecks();

    document.getElementById('export-button').addEventListener('click', function () {
        const deckId = document.getElementById('export-deck').value;
        const format = document.getElementById('export-format').value;

        window.location.href = `/backend/public/export_deck.php?deckId=${deckId}&format=${format}`;
    });

    document.getElementById('import-button').addEventListener('click', function () {
        const fileInput = document.getElementById('import-file');

        if (fileInput.files.length === 0) {
            alert('Please select a file to import');
            return;
        }

        const formData = new FormData();
        formData.append('file', fileInput.files[0]);

        fetch('/backend/public/import_deck.php', {
            method: 'POST',
            body: formData
        })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    alert('Deck imported successfully');
                } else {
                    alert(`Error: ${data.message}`);
                }
            })
            .catch(error => console.error('Error importing deck:', error));
    });

    function loadDecks() {
        fetch('/backend/public/get_decks.php')
            .then(response => response.json())
            .then(data => {
                const exportDeckSelect = document.getElementById('export-deck');
                exportDeckSelect.innerHTML = '';
                data.decks.forEach(deck => {
                    const option = document.createElement('option');
                    option.value = deck.DeckID;
                    option.textContent = deck.DeckName;
                    exportDeckSelect.appendChild(option);
                });
            })
            .catch(error => console.error('Error loading decks:', error));
    }
});