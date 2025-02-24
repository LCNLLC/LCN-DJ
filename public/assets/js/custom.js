// check url link http and https javascript✨👇
document.addEventListener('DOMContentLoaded', function () {
    // Function to validate URL input
    const validateUrlInput = (input) => {
        const errorMessage = input.nextElementSibling; // Span error message
        const urlValue = input.value.trim();

        // Check if the value starts with http:// or https://
        if (/^(http:\/\/|https:\/\/)/.test(urlValue) || urlValue === '') {
            errorMessage.style.display = 'none'; // Hide error if valid or empty
        } else {
            errorMessage.style.display = 'block'; // Show error if invalid
        }
    };

    // Function to add URL validation to new input fields
    const addUrlValidation = () => {
        // Select all the URL input fields
        const urlInputs = document.querySelectorAll('.url-input');
        urlInputs.forEach(input => {
            // Add an event listener to validate URL on input
            input.addEventListener('input', function () {
                validateUrlInput(this);
            });

            // Also validate URL initially
            validateUrlInput(input);
        });
    };

    // Call the function to apply validation to existing inputs
    addUrlValidation();

    // Listen for the "Add New" button click and apply validation to newly added inputs
    document.querySelector('[data-toggle="add-more"]').addEventListener('click', function () {
        // Wait for the new content to be added to DOM
        setTimeout(function () {
            // Apply URL validation to the newly added input fields
            addUrlValidation();
        }, 100); // Delay to ensure the content is added to the DOM
    });
});

// image handle max-size 3mb and show_preview javascript 👇
document.addEventListener('DOMContentLoaded', function () {
    function validateImageInput(fileInputId, previewImageId) {
        const fileInput = document.getElementById(fileInputId);
        const previewImage = document.getElementById(previewImageId);
        const maxSize = 3 * 1024 * 1024; // 3MB in bytes
        const allowedTypes = ['image/jpeg', 'image/png', 'image/jpg'];

        fileInput.addEventListener('change', function (event) {
            const files = event.target.files;

            // Clear any previous preview image
            previewImage.style.display = 'none';
            previewImage.src = '';

            let valid = true;

            // Loop through the selected files
            for (let i = 0; i < files.length; i++) {
                const file = files[i];

                // Check for file size
                if (file.size > maxSize) {
                    alert(`The file "${file.name}" exceeds the 3MB size limit.`);
                    event.target.value = ''; // Clear the input
                    valid = false; // Set validity to false
                    break;
                }

                // Check for allowed file types
                if (!allowedTypes.includes(file.type)) {
                    alert(`The file "${file.name}" is not a valid image. Only JPG, PNG, and JPEG are allowed.`);
                    event.target.value = ''; // Clear the input
                    valid = false; // Set validity to false
                    break;
                }
            }

            // If the file is valid, show the preview
            if (valid && files.length > 0) {
                const file = files[0]; // Take the first file if multiple are selected
                previewImage.src = URL.createObjectURL(file);
                previewImage.style.display = 'block';
            }
        });
    }
    // Apply the validation to both inputs
    validateImageInput('header_logo', 'header_logo_preview');
    validateImageInput('topbar_banner', 'topbar_banner_preview');
    validateImageInput('topbar_banner_medium', 'topbar_banner_medium_preview');
    validateImageInput('topbar_banner_small', 'topbar_banner_small_preview');
    validateImageInput('site_icon', 'site_icon_preview');
});
