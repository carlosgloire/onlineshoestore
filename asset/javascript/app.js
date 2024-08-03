document.addEventListener('DOMContentLoaded', () => {
    const homeImage = document.querySelectorAll('.home-bg');
    const circles = document.querySelectorAll('.circle');
    let currentIndex = 0;

    function showItem(index) {
        homeImage[currentIndex].style.display = 'none';
        circles[currentIndex].classList.remove('active');
        homeImage[index].style.display = 'block';
        circles[index].classList.add('active');
        currentIndex = index;
    }

    function showNextItem() {
        const nextIndex = (currentIndex + 1) % homeImage.length;
        showItem(nextIndex);
    }

    function showPreviousItem() {
        const prevIndex = (currentIndex - 1 + homeImage.length) % homeImage.length;
        showItem(prevIndex);
    }

    function goToItem(index) {
        showItem(index);
    }

    // Initial display setup
    homeImage.forEach((item, index) => {
        if (index !== currentIndex) {
            item.style.display = 'none';
        }
    });

    circles.forEach((circle, index) => {
        circle.addEventListener('click', () => {
            goToItem(index);
        });
    });

    // Auto-scroll functionality
    setInterval(showNextItem, 5000);
});

/**************************************
#Categories
***************************************/
document.addEventListener('DOMContentLoaded', function () {
    const categoryItems = document.querySelectorAll('.categories-list ul li');
    const shoeCategories = document.querySelectorAll('.shoes-item');

    categoryItems.forEach(item => {
        item.addEventListener('click', function () {
            categoryItems.forEach(catItem => catItem.classList.remove('active'));
            this.classList.add('active');
            const filter = this.getAttribute('data-filter');
            shoeCategories.forEach(category => {
                if (category.classList.contains(filter)) {
                    category.style.display = 'flex';
                } else {
                    category.style.display = 'none';
                }
            });
        });
    });
});

/**************************************
#Login see password
***************************************/
let passwords = document.querySelector('.password');
let openIcon = document.querySelector('.open');
let closeIcon = document.querySelector('.close');

passwords.setAttribute('type', 'password');

openIcon.onclick = function () {
    passwords.setAttribute('type', 'text');
    openIcon.classList.add('hidden');
    closeIcon.classList.remove('hidden');
}

closeIcon.onclick = function () {
    passwords.setAttribute('type', 'password');
    openIcon.classList.remove('hidden');
    closeIcon.classList.add('hidden');
}