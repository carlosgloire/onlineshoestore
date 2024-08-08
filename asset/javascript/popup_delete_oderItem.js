/*----------------------------------
#Popup delete order_item
----------------------------------*/
const deleteButtonsitem = document.querySelectorAll('.delete_item');
const popupitem = document.querySelector('.popup');
const cancelPopupButtonitem = document.querySelector('.cancel-popup');
const deletePopupButtonitem = document.querySelector('.delete-popup');

deleteButtonsitem.forEach(button => {
    button.addEventListener('click', function(event) {
        event.preventDefault(); // Prevent the default link behavior
        const imageID = this.getAttribute('gallery_id');

        // Show the popup
        popupitem.classList.remove('hidden-popup');

        // Attach event listeners to the cancel and delete buttons
        cancelPopupButtonitem.addEventListener('click', function() {
            // Hide the popup
            popupitem.classList.add('hidden-popup');
        });

        deletePopupButtonitem.addEventListener('click', function() {
            // Redirect to the delete page with the course ID
            window.location.href = `../controllers/delete_order.php?order_item_id=${imageID}`;
        });
    });
});