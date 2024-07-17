document.addEventListener('DOMContentLoaded', function () {
    fetchCategories();
});

function fetchCategories() {
    fetch('/backend/public/get_categories.php')
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                const languages = ['Arabic', 'Chinese', 'English', 'French', 'German', 'Hebrew', 'Japanese', 'Korean', 'Russian', 'Spanish'];
                const categories = data.data;

                const languagesDiv = document.getElementById('languages');
                const artScienceDiv = document.getElementById('art-science');

                categories.forEach(category => {
                    const categoryElement = document.createElement('a');
                    categoryElement.href = `decks_in_category.html?category=${category.CategoryID}`;
                    categoryElement.innerText = category.CategoryName;

                    if (languages.includes(category.CategoryName)) {
                        languagesDiv.appendChild(categoryElement);
                    } else {
                        artScienceDiv.appendChild(categoryElement);
                    }
                });
            } else {
                console.error('Failed to load categories');
            }
        })
        .catch(error => console.error('Error fetching categories:', error));
}