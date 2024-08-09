document.addEventListener('DOMContentLoaded', (event) => {
    const openPopup = document.getElementById('open');
    const popup = document.querySelector('.popup');
    const cancelPopup = document.querySelector('.cancel-popup');

    openPopup.addEventListener('click', () => {
        popup.classList.remove('hidden-popup');
    });

    cancelPopup.addEventListener('click', () => {
        popup.classList.add('hidden-popup');
    });

    // Check for errors on page load and keep popup open if there are any
    if (document.querySelector('.error')) {
        popup.classList.remove('hidden-popup');
    }
});
