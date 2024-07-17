document.addEventListener('DOMContentLoaded', function () {
    const urlParams = new URLSearchParams(window.location.search);
    const deckId = urlParams.get('deckId');
    const deckName = urlParams.get('deckName');

    document.getElementById('deck-name').textContent = deckName;

    fetch(`../../backend/public/get_study_dashboard.php?deckId=${deckId}`)
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                document.getElementById('new-cards').textContent = `New: ${data.newCards}`;
                document.getElementById('learning-cards').textContent = `Learning: ${data.learningCards}`;
                document.getElementById('review-cards').textContent = `To Review: ${data.reviewCards}`;
                document.getElementById('total-cards').textContent = `Total Cards: ${data.totalCards}`;
            } else {
                alert(data.message);
            }
        })
        .catch(error => console.error('Error:', error));

    document.getElementById('study-now').addEventListener('click', function () {
        window.location.href = `/frontend/pages/study.html?deckId=${deckId}&deckName=${deckName}`;
    });
});