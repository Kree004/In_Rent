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

const dropdown = document.querySelector('.drop-sign');
const sign = document.querySelector('.sign');
const dropdownContent = document.querySelector('.drop-form-sign');

// Toggle the dropdown content when the dropdown button is clicked
sign.addEventListener('click', function (event) {
    if (dropdownContent.style.display === 'block') {
        dropdownContent.style.display = 'none';
    } else {
        dropdownContent.style.display = 'block';
    }
});
// Close the dropdown if the user clicks outside of it
window.addEventListener('click', function (event) {
    if (!dropdown.contains(event.target) && !dropdownContent.contains(event.target)) {
        dropdownContent.style.display = 'none';
    }
});

const dropdown1 = document.querySelector('.drop-log');
const log = document.querySelector('.log');
const dropdownContent1 = document.querySelector('.drop-form-log');

// Toggle the dropdown content when the dropdown button is clicked
log.addEventListener('click', function (event) {
    if (dropdownContent1.style.display === 'block') {
        dropdownContent1.style.display = 'none';
    } else {
        dropdownContent1.style.display = 'block';
    }
});
// Close the dropdown if the user clicks outside of it
window.addEventListener('click', function (event) {
    if (!dropdown1.contains(event.target) && !dropdownContent1.contains(event.target)) {
        dropdownContent1.style.display = 'none';
    }
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

        //Tell the user to login
        alert ("You cannot rent until you have logged in. Please log in to rent this item.");

        //         const card = button.closest('.grid-items'); // Find the parent card element
        //         const bikeNameElement = card.querySelector('.bike-name'); // Find the bike name element
        //         const bikeModelElement = card.querySelector('.bike-model'); // Find the bike model element

        //         if (bikeNameElement && bikeModelElement) {
        //             const name = bikeNameElement.textContent.trim(); // Get the name and remove leading/trailing spaces
        //             const model = bikeModelElement.textContent.trim(); // Get the model and remove leading/trailing spaces

        //             const days = parseInt(card.querySelector('.quantity').value); // Get selected number of days
        //             const defaultPrice = parseInt(card.querySelector('.price').getAttribute('data-default-price')); // Get default price
        //             const totalPrice = calculateTotalPrice(days, defaultPrice); // Calculate total price

        //             // Function to calculate total price based on days and default price
        //             function calculateTotalPrice(days, defaultPrice) {
        //                 return days * defaultPrice;
        //             }

        //             // Send data to PHP using AJAX
        //             const data = {
        //                 name: name,
        //                 model: model,
        //                 days: days,
        //                 totalPrice: totalPrice
        //             };

        //             // AJAX request
        //             const xhr = new XMLHttpRequest();
        //             xhr.open('POST', '/InRent/rent.php', true);
        //             xhr.setRequestHeader('Content-type', 'application/json');
        //             xhr.onreadystatechange = function () {
        //                 if (xhr.readyState === XMLHttpRequest.DONE) {
        //                     if (xhr.status === 200) {
        //                         console.log('Data sent successfully:', xhr.responseText);
        //                     } else {
        //                         console.error('Error:', xhr.status);
        //                     }
        //                 }
        //             };
        //             xhr.send(JSON.stringify(data));
        //         } else {
        //             console.error('Bike name or model element not found.');
        //         }
    });
});