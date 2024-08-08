 /*----------------------------------
#Popup delete slides
----------------------------------*/
const deleteButtons11 = document.querySelectorAll('.delete');
const popup11 = document.querySelector('.popup');
const cancelPopupButton11 = document.querySelector('.cancel-popup');
const deletePopupButton11 = document.querySelector('.delete-popup');

deleteButtons11.forEach(button => {
    button.addEventListener('click', function(event) {
        event.preventDefault(); // Prevent the default link behavior
        const imageID = this.getAttribute('gallery_id');

        // Show the popup
        popup11.classList.remove('hidden-popup');

        // Attach event listeners to the cancel and delete buttons
        cancelPopupButton11.addEventListener('click', function() {
            // Hide the popup
            popup11.classList.add('hidden-popup');
        });

        deletePopupButton11.addEventListener('click', function() {
            // Redirect to the delete page with the course ID
            window.location.href = `../controllers/delete_slide.php?id=${imageID}`;
        });
    });
});
