// Change color on scroll
window.addEventListener('scroll', function () {
    var navbar = document.querySelector('.nav-container');
    // Change color based on scroll position
    if (window.scrollY > 500) { // Adjust the scroll threshold as needed
        navbar.style.backgroundColor = 'rgba(0, 0, 0, 0.3)'; // New background color when scrolled
    } else {
        navbar.style.backgroundColor = 'transparent'; // Initial background color when not scrolled
    }
});

// const dropdown = document.querySelector('.drop-sign');
// const sign = document.querySelector('.sign');
// const dropdownContent = document.querySelector('.drop-form-sign');

// // Toggle the dropdown content when the dropdown button is clicked
// sign.addEventListener('click', function (event) {
//     if (dropdownContent.style.display === 'block') {
//         dropdownContent.style.display = 'none';
//     } else {
//         dropdownContent.style.display = 'block';
//     }
// });
// // Close the dropdown if the user clicks outside of it
// window.addEventListener('click', function (event) {
//     if (!dropdown.contains(event.target) && !dropdownContent.contains(event.target)) {
//         dropdownContent.style.display = 'none';
//     }
// });

// const dropdown1 = document.querySelector('.drop-log');
// const log = document.querySelector('.log');
// const dropdownContent1 = document.querySelector('.drop-form-log');

// Toggle the dropdown content when the dropdown button is clicked
// log.addEventListener('click', function (event) {
//     if (dropdownContent1.style.display === 'block') {
//         dropdownContent1.style.display = 'none';
//     } else {
//         dropdownContent1.style.display = 'block';
//     }
// });
// // Close the dropdown if the user clicks outside of it
// window.addEventListener('click', function (event) {
//     if (!dropdown1.contains(event.target) && !dropdownContent1.contains(event.target)) {
//         dropdownContent1.style.display = 'none';
//     }
// }); 

document.addEventListener('DOMContentLoaded', () => {
    const logoutLink = document.getElementById('logout-link');
    const confirmationBox = document.getElementById('confirmation-box');
    const confirmLogout = document.getElementById('confirm-logout');
    const cancelLogout = document.getElementById('cancel-logout');

    logoutLink.addEventListener('click', (event) => {
        event.preventDefault(); // Prevent default link behavior
        confirmationBox.style.display = 'block'; // Show the confirmation box
    });

    confirmLogout.addEventListener('click', () => {
        // Redirect to logout or handle logout logic here
        window.location.href = 'logout.php'; // Update with your logout URL
    });

    cancelLogout.addEventListener('click', () => {
        confirmationBox.style.display = 'none'; // Hide the confirmation box
    });
});


function navigateToSection(sectionClass) {
    const section = document.getElementById(sectionClass);
    if (section) {
        section.scrollIntoView({ behavior: 'smooth' });
    }
}

// Get all the quantity input fields
const quantityInputs = document.querySelectorAll('.quantity');

// Loop through each input field and attach the event listener
quantityInputs.forEach((input) => {
    input.addEventListener('input', () => {
        const card = input.closest('.grid-items'); // Find the parent card element
        const priceSpan = card.querySelector('.price'); // Find the price span within the same card

        const days = parseInt(input.value);
        const defaultPrice = parseInt(priceSpan.getAttribute('data-default-price')); // Get the default price stored as a data attribute

        if (!isNaN(days) && !isNaN(defaultPrice)) {
            const totalPrice = calculateTotalPrice(days, defaultPrice); // Function to calculate total price based on days and default price
            priceSpan.textContent = totalPrice; // Format the total price to two decimal places
        } else {
            priceSpan.textContent = defaultPrice; // Reset price if input is invalid
        }
    });
});

// Function to calculate total price based on days and default price
function calculateTotalPrice(days, defaultPrice) {
    return (days * defaultPrice); // Calculate total price without formatting
}

//Button animation
const cardButtons = document.querySelectorAll('.card-btn');

cardButtons.forEach(button => {
    button.addEventListener('click', function () {
        button.classList.add('jump');

        //Remove the jump class after the animation finishes
        setTimeout(function () {
            button.classList.remove('jump');
        }, 500); // Adjust this time to match the animation duration

        const card = button.closest('.grid-items'); // Find the parent card element
        const bikeNameElement = card.querySelector('.bike-name'); // Find the bike name element
        const bikeModelElement = card.querySelector('.bike-model'); // Find the bike model element

        if (bikeNameElement && bikeModelElement) {
            const name = bikeNameElement.textContent.trim(); // Get the name and remove leading/trailing spaces
            const model = bikeModelElement.textContent.trim(); // Get the model and remove leading/trailing spaces

            const days = parseInt(card.querySelector('.quantity').value); // Get selected number of days
            const defaultPrice = parseInt(card.querySelector('.price').getAttribute('data-default-price')); // Get default price
            const totalPrice = calculateTotalPrice(days, defaultPrice); // Calculate total price

            // Function to calculate total price based on days and default price
            function calculateTotalPrice(days, defaultPrice) {
                return days * defaultPrice;
            }

            // Send data to PHP using AJAX
            const data = {
                name: name,
                model: model,
                days: days,
                totalPrice: totalPrice
            };

            // AJAX request
            // First AJAX request to rent.php
            const xhr1 = new XMLHttpRequest();
            xhr1.open('POST', '/InRent/rent.php', true);
            xhr1.setRequestHeader('Content-type', 'application/json');
            xhr1.onreadystatechange = function () {
                if (xhr1.readyState === XMLHttpRequest.DONE) {
                    if (xhr1.status === 200) {
                        // Second AJAX request to recipt.php
                        const xhr2 = new XMLHttpRequest();
                        xhr2.open('POST', '/InRent/recipt.php', true);
                        xhr2.setRequestHeader('Content-type', 'application/json');
                        xhr2.onreadystatechange = function () {
                            if (xhr2.readyState === XMLHttpRequest.DONE) {
                                if (xhr2.status === 200) {
                                    // Successful requests
                                    window.location.href = '/InRent/recipt.php';
                                } else {
                                    console.error('Error in recipt.php:', xhr2.status);
                                }
                            }
                        };
                        xhr2.send(JSON.stringify(data));
                    } else {
                        console.error('Error in rent.php:', xhr1.status);
                    }
                }
            };
            xhr1.send(JSON.stringify(data));
        }
    });
});

// window.addEventListener('beforeunload', function () {
//     navigator.sendBeacon('logout.php');
// });