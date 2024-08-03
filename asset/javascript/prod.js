/*----------------------------------
#Changing image position
----------------------------------*/
document.querySelectorAll('.prod-type-container').forEach(item => {
    const bigImage = item.querySelector('.big-image'); // Change to querySelector
    const smallImages = item.querySelectorAll('.small-image'); // Scope to the container

    smallImages.forEach(smallImage => {
        smallImage.onclick = function () {
            bigImage.src = smallImage.src;
        };
    });
});

document.querySelectorAll('.dropdown-content').forEach(content => {
    content.querySelectorAll('input[type="checkbox"]').forEach(checkbox => {
        checkbox.addEventListener('change', function () {
            updateButtonText(content);
        });
    });
});

/*----------------------------------
#Select color
----------------------------------*/

function updateButtonText(content) {
    const checkboxes = content.querySelectorAll('input[type="checkbox"]:checked');
    const selectedValues = Array.from(checkboxes).map(cb => cb.nextSibling.textContent.trim());

    const button = content.previousElementSibling;
    if (selectedValues.length > 0) {
        button.textContent = selectedValues.join(', ');
    } else {
        button.textContent = button.id === 'size-btn' ? 'Select Sizes' : 'Select Colors';
    }
}

window.onclick = function (event) {
    if (!event.target.matches('.dropdown-btn')) {
        document.querySelectorAll('.dropdown-content').forEach(dropdown => {
            if (dropdown.classList.contains('show')) {
                dropdown.classList.remove('show');
            }
        });
    }
};


/*----------------------------------
#Image moving categories
----------------------------------*/

document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.bi-chevron-left').forEach(leftArrow => {
        leftArrow.addEventListener('click', function () {
            const categorieImages = this.nextElementSibling;
            if (categorieImages && categorieImages.classList.contains('categorie-images')) {
                categorieImages.scrollBy({
                    left: -300,
                    behavior: 'smooth'
                });
            }
        });
    });

    document.querySelectorAll('.bi-chevron-right').forEach(rightArrow => {
        rightArrow.addEventListener('click', function () {
            const categorieImages = this.previousElementSibling;
            if (categorieImages && categorieImages.classList.contains('categorie-images')) {
                categorieImages.scrollBy({
                    left: 300,
                    behavior: 'smooth'
                });
            }
        });
    });
});
