 /*----------------------------------
#Popup delete category
----------------------------------*/
const deleteButtons1 = document.querySelectorAll('.delete');
const popup1 = document.querySelector('.popup');
const cancelPopupButton1 = document.querySelector('.cancel-popup');
const deletePopupButton1 = document.querySelector('.delete-popup');

deleteButtons1.forEach(button => {
    button.addEventListener('click', function(event) {
        event.preventDefault(); // Prevent the default link behavior
        const imageID = this.getAttribute('gallery_id');

        // Show the popup
        popup1.classList.remove('hidden-popup');

        // Attach event listeners to the cancel and delete buttons
        cancelPopupButton1.addEventListener('click', function() {
            // Hide the popup
            popup1.classList.add('hidden-popup');
        });

        deletePopupButton1.addEventListener('click', function() {
            // Redirect to the delete page with the course ID
            window.location.href = `../controllers/delete_category.php?category_id=${imageID}`;
        });
    });
});
