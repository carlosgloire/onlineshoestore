/*----------------------------------
#Popup delete shoe
----------------------------------*/
 const deleteButtons = document.querySelectorAll('.delete');
 const popup = document.querySelector('.popup');
 const cancelPopupButton = document.querySelector('.cancel-popup');
 const deletePopupButton = document.querySelector('.delete-popup');

 deleteButtons.forEach(button => {
     button.addEventListener('click', function(event) {
         event.preventDefault(); // Prevent the default link behavior
         const imageID = this.getAttribute('gallery_id');

         // Show the popup
         popup.classList.remove('hidden-popup');

         // Attach event listeners to the cancel and delete buttons
         cancelPopupButton.addEventListener('click', function() {
             // Hide the popup
             popup.classList.add('hidden-popup');
         });

         deletePopupButton.addEventListener('click', function() {
             // Redirect to the delete page with the course ID
             window.location.href = `../controllers/delete_shoe.php?shoe_id=${imageID}`;
         });
     });
 });

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