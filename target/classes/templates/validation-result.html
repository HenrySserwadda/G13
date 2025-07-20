<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DURABAG | Validation Results</title>
    <script>
        // Tailwind CSS configuration for dark mode
        tailwind.config = {
            darkMode: 'media', // Enables dark mode based on user's OS preference
        }
    </script>
    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        /* Base body styles for light mode */
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f3f4f6; /* Light mode default */
            color: #1f2937; /* gray-800 */
        }

        /* Dark mode body styles */
        @media (prefers-color-scheme: dark) {
            body {
                background-color: #1a202c; /* dark gray-900 */
                color: #e2e8f0; /* gray-100 */
            }
        }

        /* Styles for table status badges */
        .status-valid {
            background-color: #dcfce7; /* green-100 */
            color: #166534; /* green-800 */
            display: inline-flex;
            padding: 0.25rem 0.75rem; /* px-3 py-1 */
            border-radius: 9999px; /* rounded-full */
            font-size: 0.875rem; /* text-sm */
            font-weight: 500; /* font-medium */
        }
        .status-invalid {
            background-color: #fee2e2; /* red-100 */
            color: #b91c1c; /* red-700 */
            display: inline-flex;
            padding: 0.25rem 0.75rem; /* px-3 py-1 */
            border-radius: 9999px; /* rounded-full */
            font-size: 0.875rem; /* text-sm */
            font-weight: 500; /* font-medium */
        }

        /* Popup styles */
        .popup {
            display: none; /* Hidden by default */
            position: fixed;
            left: 50%;
            top: 50%;
            transform: translate(-50%, -50%);
            padding: 30px; /* Increased padding as per original popup styling */
            border-radius: 12px; /* Increased border-radius as per original popup styling */
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1); /* Increased shadow as per original popup styling */
            z-index: 1000;
            text-align: center;
            max-width: 400px;
            width: 90%;
            opacity: 0; /* For transition effect */
            transition: opacity 0.3s ease-in-out;
        }
        .popup.show {
            opacity: 1; /* Show state */
        }

        /* Light mode popup colors */
        .popup.success {
            background-color: #f0fdf4; /* green-50 */
            color: #166534; /* green-800 */
            border: 2px solid #22c55e; /* green-500 */
        }
        .popup.error {
            background-color: #fef2f2; /* red-50 */
            color: #991b1b; /* red-800 */
            border: 2px solid #ef4444; /* red-500 */
        }

        /* Dark mode popup colors */
        @media (prefers-color-scheme: dark) {
            .popup.success {
                background-color: #102e1c; /* Darker green for dark mode success */
                color: #a7f3d0; /* Light green text */
                border-color: #34d399; /* Green border */
            }
            .popup.error {
                background-color: #3f1515; /* Darker red for dark mode error */
                color: #fecaca; /* Light red text */
                border-color: #ef4444; /* Red border */
            }
        }

        .popup-icon {
            font-size: 3.5rem;
            margin-bottom: 1rem;
            line-height: 1; /* Adjust line height to prevent extra space */
        }
        .popup-title {
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }
        .popup-message {
            font-size: 1rem;
            line-height: 1.5;
        }
    </style>
</head>
<body class="bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100">
    <!-- Header -->
    <header class="bg-white dark:bg-gray-800 shadow-md py-6 mb-8">
        <div class="max-w-6xl mx-auto px-4 flex items-center justify-center">
            <img src="https://placehold.co/50x50/3b82f6/ffffff?text=DB" alt="DURABAG Logo" class="w-10 h-10 rounded-full mr-3">
            <h1 class="text-3xl font-extrabold text-blue-800 dark:text-blue-400 tracking-wide">DURABAG</h1>
        </div>
    </header>

    <!-- Main Content -->
    <main class="max-w-6xl mx-auto px-4">
        <!-- Page Title -->
        <h1 class="text-3xl font-bold text-gray-800 dark:text-white mb-6 text-center">Vendor Validation Results</h1>

        <!-- Example Data (Hardcoded for static HTML) -->
        <script>
            // Simulate backend data for demonstration
            const mockData = {
                // Example with an error (uncomment to test error display)
                // error: "Failed to process the uploaded file. Please try again.",
                // result: null,

                // Example with an invalid vendor
                result: {
                    vendor: "DURABAG Vendor",
                    valid: false,
                    reasons: ["Missing business license", "Contact email invalid"],
                    visitDate: "-"
                }

                // Example with a valid vendor (uncomment to test success display)
                /*
                result: {
                    vendor: "DURABAG Vendor",
                    valid: true,
                    reasons: [],
                    visitDate: "2025-07-20"
                }
                */
            };

            // This global variable will be used by the JavaScript below
            window.pageData = mockData;
        </script>

        <!-- Error Display -->
        <div id="errorMessage" class="bg-red-100 dark:bg-red-900 border border-red-400 dark:border-red-700 text-red-700 dark:text-red-100 px-4 py-3 rounded mb-6 hidden">
            <strong class="font-bold">Error:</strong>
            <span id="errorMessageText" class="block sm:inline"></span>
        </div>

        <!-- Welcome Message -->
        <div id="welcomeMessage" class="bg-blue-100 dark:bg-gray-800 border border-blue-300 dark:border-gray-700 rounded-lg p-6 mb-6 hidden">
            <p class="text-black dark:text-blue-300 mb-2">Welcome, valued vendor! We appreciate your interest in partnering with us.</p>
            <p class="text-black dark:text-blue-400">We encourage honesty and transparency throughout this process, as it forms the foundation for a successful and lasting business relationship. We look forward to the possibility of doing great business together!</p>
        </div>

        <!-- Results Table -->
        <div id="resultsTableContainer" class="bg-white dark:bg-gray-900 shadow-md rounded-lg overflow-hidden hidden">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-800">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Vendor</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Reasons</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Visit Date</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-800">
    <tr th:each="result : ${results}" class="hover:bg-gray-50 dark:hover:bg-gray-800">
        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-100"
            th:text="${result.get('vendor')}">
        </td>
        <td class="px-6 py-4 whitespace-nowrap">
            <span th:if="${result.get('valid')}"
                  class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 dark:bg-green-800 text-green-800 dark:text-green-200">
                ✅ Valid
            </span>
            <span th:unless="${result.get('valid')}"
                  class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 dark:bg-red-700 text-red-800 dark:text-red-200">
                ❌ Invalid
            </span>
        </td>
        <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-100">
    <ul th:if="${result.get('reasons') != null and !#lists.isEmpty(result.get('reasons'))}"
        class="list-disc list-inside text-red-600 dark:text-red-300">
        <li th:each="reason : ${result.get('reasons')}" th:text="${reason}"></li>
    </ul>
    <span th:if="${#lists.isEmpty(result.get('reasons'))}" class="text-gray-500 dark:text-gray-400">No issues found</span>
</td>

        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100"
            th:text="${result.get('visitDate')} ?: '-'">
        </td>
    </tr>
</tbody>
          </table>
        </div>

        <!-- Status Message -->
        <div id="noteMessage" class="mt-6 bg-yellow-50 dark:bg-gray-800 border border-yellow-300 dark:border-yellow-700 text-yellow-800 dark:text-yellow-300 p-4 rounded-lg shadow hidden">
            <p class="text-gray-700 dark:text-gray-300">Your Status Will be Updated based on validation criteria and a successful Visit to the facility</p>
        </div>

        <!-- Success/Error Popup -->
        <div id="validationPopup" class="popup">
            <div class="mt-3 text-center">
                <div id="popupContent">
                    <!-- Content will be populated by JavaScript -->
                </div>
            </div>
        </div>

        <!-- Back Button -->
        <div class="flex justify-between mt-10">
            <div>
                <a href="http://localhost:8000/insertpdf" class="inline-flex items-center px-4 py-2 bg-blue-600 dark:bg-blue-700 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 dark:hover:bg-blue-800 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    ← Back to form
                </a>
            </div>
            <div>
                <a href="{}" class="inline-flex items-center px-4 py-2 bg-blue-600 dark:bg-blue-700 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 dark:hover:bg-blue-800 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    Dashboard →
                </a>
            </div>
        </div>
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const pageData = window.pageData; // Get the hardcoded data

            const errorMessageDiv = document.getElementById('errorMessage');
            const errorMessageText = document.getElementById('errorMessageText');
            const welcomeMessageDiv = document.getElementById('welcomeMessage');
            const resultsTableContainer = document.getElementById('resultsTableContainer');
            const resultsTableBody = document.getElementById('resultsTableBody');
            const noteMessageDiv = document.getElementById('noteMessage');
            const validationPopup = document.getElementById('validationPopup');
            const popupContent = document.getElementById('popupContent');

            if (pageData.error) {
                // Display error message
                errorMessageText.textContent = pageData.error;
                errorMessageDiv.classList.remove('hidden');
            } else if (pageData.result) { // Check for single result object
                // Display welcome message and results table
                welcomeMessageDiv.classList.remove('hidden');
                resultsTableContainer.classList.remove('hidden');
                noteMessageDiv.classList.remove('hidden');

                // Populate the table with a single row
                const result = pageData.result;
                const row = document.createElement('tr');
                row.className = 'hover:bg-gray-50 dark:hover:bg-gray-800';

                // Vendor Name
                const vendorCell = document.createElement('td');
                vendorCell.className = 'px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-100';
                vendorCell.textContent = result.vendor;
                row.appendChild(vendorCell);

                // Status
                const statusCell = document.createElement('td');
                statusCell.className = 'px-6 py-4 whitespace-nowrap';
                const statusSpan = document.createElement('span');
                if (result.valid) {
                    statusSpan.classList.add('status-valid');
                    statusSpan.textContent = '✅ Valid';
                } else {
                    statusSpan.classList.add('status-invalid');
                    statusSpan.textContent = '❌ Invalid';
                }
                statusCell.appendChild(statusSpan);
                row.appendChild(statusCell);

                // Reasons
                const reasonsCell = document.createElement('td');
                reasonsCell.className = 'px-6 py-4 text-sm text-gray-900 dark:text-gray-100';
                if (result.reasons && result.reasons.length > 0) {
                    const reasonsList = document.createElement('ul');
                    reasonsList.className = 'list-disc list-inside text-red-600 dark:text-red-300';
                    result.reasons.forEach(reason => {
                        const listItem = document.createElement('li');
                        listItem.textContent = reason;
                        reasonsList.appendChild(listItem);
                    });
                    reasonsCell.appendChild(reasonsList);
                } else {
                    const noIssuesSpan = document.createElement('span');
                    noIssuesSpan.className = 'text-gray-500 dark:text-gray-400';
                    noIssuesSpan.textContent = 'No issues found';
                    reasonsCell.appendChild(noIssuesSpan);
                }
                row.appendChild(reasonsCell);

                // Visit Date
                const visitDateCell = document.createElement('td');
                visitDateCell.className = 'px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100';
                visitDateCell.textContent = result.visitDate || '-';
                row.appendChild(visitDateCell);

                resultsTableBody.appendChild(row);

                // Determine popup style and content based on validation results
                if (!result.valid) {
                    validationPopup.classList.add('error');
                    popupContent.innerHTML = `
                        <div class="popup-icon">❌</div>
                        <h3 class="popup-title">Validation Failed</h3>
                        <p class="popup-message">Your application did not pass validation.<br>Please check the reasons and try again.</p>
                    `;
                } else {
                    validationPopup.classList.add('success');
                    popupContent.innerHTML = `
                        <div class="popup-icon">✅</div>
                        <h3 class="popup-title">Success!</h3>
                        <p class="popup-message">Your application has been successfully submitted.<br>You'll receive a confirmation email shortly.</p>
                    `;
                }

                // Show and hide popup
                validationPopup.classList.add('show'); // Add 'show' class to trigger transition
                validationPopup.style.display = 'block'; // Make it visible for transition
                setTimeout(() => {
                    validationPopup.classList.remove('show'); // Remove 'show' to hide with transition
                    setTimeout(() => {
                        validationPopup.style.display = 'none'; // Fully hide after transition
                    }, 300); // Match transition duration
                }, 5000); // Display for 5 seconds
            }
        });
    </script>
</body>
</html>
