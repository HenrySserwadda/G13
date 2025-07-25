<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DURABAG Vendor Application</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'media',
            theme: {
                extend: {
                    // Custom font family for Inter
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    },
                }
            }
        }
    </script>
    <!-- Google Fonts - Inter -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        /* Custom styles for the modal */
        .modal {
            display: none; /* Hidden by default */
            position: fixed; /* Stay in place */
            z-index: 1000; /* Sit on top */
            left: 0;
            top: 0;
            width: 100%; /* Full width */
            height: 100%; /* Full height */
            overflow: auto; /* Enable scroll if needed */
            background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
            justify-content: center;
            align-items: center;
        }

        .modal-content {
            background-color: #fefefe;
            margin: auto;
            padding: 20px;
            border-radius: 0.5rem; /* rounded-lg */
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05); /* shadow */
            width: 90%;
            max-width: 400px;
            text-align: center;
            position: relative;
        }

        .dark .modal-content {
            background-color: #1f2937; /* dark:bg-gray-800 */
            color: #d1d5db; /* dark:text-gray-200 */
        }

        .close-button {
            position: absolute;
            top: 10px;
            right: 15px;
            color: #aaa;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
        }

        .close-button:hover,
        .close-button:focus {
            color: #000;
            text-decoration: none;
            cursor: pointer;
        }

        .dark .close-button:hover,
        .dark .close-button:focus {
            color: #fff;
        }

        /* Hide the default file input */
        #wholesalerpdf {
            display: none;
        }
    </style>
</head>
<body class="bg-gray-100 dark:bg-gray-900 min-h-screen flex flex-col font-sans">

    <!-- Header -->
    <div class="bg-white dark:bg-gray-800 shadow px-4 py-6 rounded-b-lg">
        <div class="flex items-center justify-center max-w-4xl mx-auto">
            <img src="images/logo.png" class="w-12 h-12 rounded-full mr-4 shadow-md" alt="DURABAG Logo">
            <h1 class="text-3xl font-bold text-blue-900 dark:text-blue-300">DURABAG</h1>
        </div>
    </div>

    <!-- Main Container -->
    <div class="max-w-4xl w-full mx-auto mt-10 px-4 py-6 flex-grow">
        <!-- Welcome Message -->
        <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-lg mb-8 text-gray-700 dark:text-gray-200">
            <h2 class="text-2xl font-bold mb-4 text-center text-blue-800 dark:text-blue-400">Welcome Valued Vendors</h2>
            <p class="mb-3 leading-relaxed">At DURABAG, we value honesty and integrity in all our business relationships. We are committed to fostering strong, transparent, and mutually beneficial partnerships with our vendors.</p>
            <p class="mb-3 leading-relaxed">We're delighted to have the opportunity to do business with you and look forward to a successful collaboration. Your partnership is crucial to our shared success.</p>
            <p class="mb-4 leading-relaxed">To begin the application process, please download our comprehensive vendor validation form below. This form will guide you through the necessary steps to become a verified DURABAG vendor.</p>

            <div class="text-center">
                <a href="/pdf/Vendor-Validation-Form.pdf" class="inline-flex items-center px-8 py-4 bg-blue-600 hover:bg-blue-700 text-white rounded-full font-semibold shadow-lg transition duration-300 ease-in-out transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-75">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    Download Vendor Form
                </a>
                <p class="mt-3 text-sm text-gray-600 dark:text-gray-400">Click to download the PDF form.</p>
            </div>
        </div>

        <!-- Form Container -->
        <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-lg text-gray-700 dark:text-gray-200">
            <h2 class="text-2xl font-bold text-center mb-6 text-indigo-800 dark:text-indigo-400">PDF Submission</h2>
            <form id="pdfSubmissionForm" method="POST" action="http://localhost:8080/validate-file" enctype="multipart/form-data">
                <div class="mb-6">
                    <label for="wholesalerpdf" class="block font-semibold mb-2 text-lg">Insert PDF</label>

                    <!-- Hidden file input -->
                    <input id="wholesalerpdf" name="file" type="file" accept="application/pdf" required autofocus />

                    <!-- Custom styled "Choose File" button -->
                    <label for="wholesalerpdf" id="customFileInputButton" class="inline-flex items-center justify-center px-6 py-3 bg-blue-200 text-blue-800 font-semibold rounded-lg cursor-pointer hover:bg-blue-300 transition duration-150 ease-in-out shadow-md dark:bg-blue-700 dark:text-blue-100 dark:hover:bg-blue-600 border border-gray-300 dark:border-gray-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-4 4 4 4-4V5h-2a1 1 0 100 2h2v6.586l-2.293-2.293a1 1 0 00-1.414 0L9 13.586l-1.293-1.293a1 1 0 00-1.414 0L4 14.586V5h12v10zM10 8a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd" />
                        </svg>
                        <span id="fileInputText">Choose File</span>
                    </label>

                    <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">Only PDF files are accepted. Maximum file size: 5MB.</p>
                    <p id="selectedFileName" class="mt-2 text-sm text-gray-700 dark:text-gray-300 italic"></p>
                </div>

                <div class="mt-8 text-center">
                    <button type="submit" id="submitButton" class="inline-flex items-center justify-center px-8 py-4 bg-indigo-600 text-white font-semibold rounded-lg hover:bg-indigo-700 transition duration-300 ease-in-out transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-opacity-75 disabled:opacity-50 disabled:cursor-not-allowed">
                        <span id="buttonText">Submit Application</span>
                        <div id="loadingSpinner" class="ml-3 hidden border-2 border-white border-t-indigo-400 rounded-full w-6 h-6 animate-spin"></div>
                    </button>
                </div>
            </form>
        </div>

        <!-- Back Button -->
        <div class="text-center mt-8 mb-6">
            <a href="http://127.0.0.1:8000/dashboard/customer#" class="inline-block px-8 py-3 bg-blue-500 text-white font-medium rounded-lg hover:bg-blue-600 transition duration-300 ease-in-out transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:ring-opacity-75">
                &larr; Customer Dashboard
            </a>
        </div>
    </div>

    <!-- Custom Message Box Modal -->
    <div id="messageModal" class="modal">
        <div class="modal-content">
            <span class="close-button" onclick="closeModal()">&times;</span>
            <p id="modalMessage" class="text-lg font-semibold text-gray-800 dark:text-gray-100 mb-4"></p>
            <button onclick="closeModal()" class="px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition duration-200">OK</button>
        </div>
    </div>

    <script>
        // Function to show the custom modal
        function showModal(message) {
            document.getElementById('modalMessage').textContent = message;
            document.getElementById('messageModal').style.display = 'flex'; // Use flex to center
        }

        // Function to close the custom modal
        function closeModal() {
            document.getElementById('messageModal').style.display = 'none';
        }

        document.addEventListener('DOMContentLoaded', () => {
            const pdfSubmissionForm = document.getElementById('pdfSubmissionForm');
            const submitButton = document.getElementById('submitButton');
            const buttonText = document.getElementById('buttonText');
            const loadingSpinner = document.getElementById('loadingSpinner');
            const fileInput = document.getElementById('wholesalerpdf');
            const selectedFileNameDisplay = document.getElementById('selectedFileName');
            const fileInputText = document.getElementById('fileInputText');

            // Update the displayed file name when a file is selected
            fileInput.addEventListener('change', function() {
                if (this.files && this.files.length > 0) {
                    selectedFileNameDisplay.textContent = `Selected file: ${this.files[0].name}`;
                    fileInputText.textContent = 'Change File'; // Change button text after selection
                } else {
                    selectedFileNameDisplay.textContent = '';
                    fileInputText.textContent = 'Choose File'; // Reset button text if no file
                }
            });

            pdfSubmissionForm.addEventListener('submit', function (e) {
                const file = fileInput.files[0];

                // Validate file type
                if (!file || !(file.type === "application/pdf" || file.name.toLowerCase().endsWith('.pdf'))) {
                    e.preventDefault(); // Prevent form submission
                    showModal("Please upload a valid PDF file.");
                    return;
                }

                // Optional: Validate file size (e.g., 5MB limit)
                const MAX_FILE_SIZE_MB = 5;
                const MAX_FILE_SIZE_BYTES = MAX_FILE_SIZE_MB * 1024 * 1024;
                if (file.size > MAX_FILE_SIZE_BYTES) {
                    e.preventDefault(); // Prevent form submission
                    showModal(`File size exceeds the limit of ${MAX_FILE_SIZE_MB}MB.`);
                    return;
                }

                // Show loading state
                buttonText.textContent = 'Submitting...';
                loadingSpinner.classList.remove('hidden');
                submitButton.disabled = true; // Disable button to prevent multiple submissions
            });
        });
    </script>
</body>
</html>
