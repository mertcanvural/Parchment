document.addEventListener('DOMContentLoaded', function () {
    fetch('../../backend/public/get_categories.php')
        .then(response => response.json())
        .then(data => {
            console.log('Categories Data:', data); // Debugging line
            if (data.status === 'success') {
                const container = document.getElementById('categories-container');
                const languages = ['Arabic', 'Chinese', 'English', 'French', 'German', 'Hebrew', 'Japanese', 'Korean', 'Russian', 'Spanish'];
                const languagesHTML = [];
                const artsAndScienceHTML = [];

                data.categories.forEach(category => {
                    const categoryElement = `<li><a href="shared_decks.html?category=${category.CategoryName}">${category.CategoryName}</a></li>`;
                    if (languages.includes(category.CategoryName)) {
                        languagesHTML.push(categoryElement);
                    } else {
                        artsAndScienceHTML.push(categoryElement);
                    }
                });

                container.innerHTML = `
                    <h2>Languages</h2>
                    <ul>${languagesHTML.join('')}</ul>
                    <h2>Art & Science</h2>
                    <ul>${artsAndScienceHTML.join('')}</ul>
                `;
            } else {
                console.error('Error fetching categories:', data.message);
            }
        })
        .catch(error => {
            console.error('Fetch error:', error);
        });
});