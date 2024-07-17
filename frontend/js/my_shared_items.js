document.addEventListener('DOMContentLoaded', function () {
    fetchSharedItems();
});

function fetchSharedItems() {
    fetch('../../backend/public/get_my_shared_items.php')
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                displaySharedItems(data.data);
            } else {
                alert('Failed to load shared items.');
            }
        })
        .catch(error => console.error('Error fetching shared items:', error));
}

function displaySharedItems(items) {
    const tableBody = document.querySelector('#shared-items-table tbody');
    tableBody.innerHTML = '';

    if (items.length === 0) {
        const noDataRow = document.createElement('tr');
        noDataRow.innerHTML = '<td colspan="5" class="text-center">No shared items found.</td>';
        tableBody.appendChild(noDataRow);
        return;
    }

    items.forEach(item => {
        const row = document.createElement('tr');

        const titleCell = document.createElement('td');
        titleCell.textContent = item.Title;
        row.appendChild(titleCell);

        const thumbsUpCell = document.createElement('td');
        thumbsUpCell.textContent = item.Rating;
        row.appendChild(thumbsUpCell);

        const modifiedCell = document.createElement('td');
        modifiedCell.textContent = item.SharedDate;
        row.appendChild(modifiedCell);

        const downloadsCell = document.createElement('td');
        downloadsCell.textContent = item.Downloads || 0; // Assuming you have a Downloads field
        row.appendChild(downloadsCell);

        const actionCell = document.createElement('td');
        const removeButton = document.createElement('button');
        removeButton.textContent = 'Remove';
        removeButton.className = 'btn btn-danger';
        removeButton.onclick = function () {
            removeSharedItem(item.DeckID);
        };
        actionCell.appendChild(removeButton);
        row.appendChild(actionCell);

        tableBody.appendChild(row);
    });
}

function removeSharedItem(deckId) {
    if (!confirm('Are you sure you want to remove this shared item?')) {
        return;
    }

    fetch('../../backend/public/remove_shared_item.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ deckId: deckId })
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            fetchSharedItems();
        } else {
            alert(data.message);
        }
    })
    .catch(error => console.error('Error removing shared item:', error));
}