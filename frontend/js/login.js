// login.js
document.addEventListener('DOMContentLoaded', function () {
    fetch('../../backend/public/get_navbar.php')
        .then(response => response.text())
        .then(data => {
            document.getElementById('navbar-placeholder').innerHTML = data;
        });

    document.getElementById('login-form').addEventListener('submit', (event) => {
        event.preventDefault();

        const username = document.getElementById('username').value;
        const password = document.getElementById('password').value;

        fetch('../../backend/public/login.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ username, password })
        })
        .then(response => response.json())
        .then(result => {
            if (result.status === 'success') {
                window.location.href = '../pages/decks.html';
            } else {
                alert('Login failed: ' + result.message);
            }
        });
    });
});
