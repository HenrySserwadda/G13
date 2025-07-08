<x-guest-layout>
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f3f4f6; /* Light gray background */
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
        }
        /* Optional: Add some basic styling for the loading spinner */
        .spinner {
            border: 4px solid rgba(0, 0, 0, 0.1);
            border-left-color: #3b82f6; /* Blue spinner */
            border-radius: 50%;
            width: 24px;
            height: 24px;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        /* Styles for the success modal */
        .success-modal {
            display: none; /* Hidden by default */
            position: fixed; /* Stay in place */
            z-index: 1000; /* Sit on top */
            left: 50%;
            top: 50%;
            transform: translate(-50%, -50%); /* Center the modal */
            width: 90%; /* Max width */
            max-width: 400px;
            background-color: #ffffff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
            text-align: center;
            border: 2px solid #34d399; /* Green border */
        }

        .success-modal .icon-circle {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background-color: #34d399; /* Green background for circle */
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .success-modal .icon-circle svg {
            width: 36px;
            height: 36px;
            fill: #ffffff; /* White tick */
        }

        .success-modal h3 {
            font-size: 1.5rem;
            font-weight: bold;
            color: #10b981; /* Darker green for message */
            margin-bottom: 15px;
        }

        .success-modal p {
            color: #4b5563; /* Gray text for description */
        }

        .success-modal .done-button {
            background-color: #10b981; /* Green for DONE button */
            color: white;
            padding: 10px 25px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 1rem;
            font-weight: 600;
            margin-top: 25px;
            transition: background-color 0.3s ease, transform 0.2s ease;
        }

        .success-modal .done-button:hover {
            background-color: #059669; /* Darker green on hover */
            transform: translateY(-2px);
        }

        /* Styling for the file input button (mimicking x-text-input) */
        #wholesalerpdf {
            /* For the actual file input element (which typically shows "Choose File" / "Browse") */
            color: #4b5563; /* Default text color for "No file chosen" */
            background-color: #f9fafb; /* Light gray background */
            border: 1px solid #d1d5db; /* Light border */
            border-radius: 0.375rem; /* rounded-md */
            padding: 0.5rem 0.75rem; /* py-2 px-3 */
            cursor: pointer;
            box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05); /* shadow-sm */
            transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
        }

        #wholesalerpdf:focus {
            border-color: #6366f1; /* indigo-500 */
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.2); /* ring-indigo-500 with opacity */
        }

        /* Specific styles for the file button part */
        #wholesalerpdf.file\:mr-4::-webkit-file-upload-button,
        #wholesalerpdf.file\:mr-4::file-selector-button {
            margin-right: 1rem; /* mr-4 */
            padding-top: 0.5rem; /* py-2 */
            padding-bottom: 0.5rem; /* py-2 */
            padding-left: 1rem; /* px-4 */
            padding-right: 1rem; /* px-4 */
            border-radius: 0.375rem; /* rounded-md */
            border-width: 0; /* border-0 */
            font-size: 0.875rem; /* text-sm */
            font-weight: 600; /* font-semibold */
            background-color: #eff6ff; /* bg-blue-50 */
            color: #1d4ed8; /* text-blue-700 */
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        #wholesalerpdf.hover\:file\:bg-blue-100::-webkit-file-upload-button:hover,
        #wholesalerpdf.hover\:file\:bg-blue-100::file-selector-button:hover {
            background-color: #dbeafe; /* bg-blue-100 */
    
 

        }
     #wholesalerpdf{   box-shadow: 0 0 2px 3px #6366f1, 0 0 0 1px #fff; /* Indigo glow with white outline */
    border: 1px solidrgb(116, 119, 251); /* Optional: solid border for more definition */
    transition: box-shadow 0.3s, border-color 0.3s;

    border-color:rgba(248, 248, 248, 0.76);
    box-shadow: 0 0 4px rgb(89, 123, 249);
     }

        /* Mimic x-primary-button */
        .primary-button-mimic {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 100%;
            padding: 0.5rem 1rem; /* py-2 px-4 */
            border-width: 1px;
            border-color: transparent;
            border-radius: 0.375rem; /* rounded-md */
            box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05); /* shadow-sm */
            font-size: 0.875rem; /* text-sm */
            font-weight: 500; /* font-medium */
            color: #ffffff;
            background-color: #4f46e5; /* indigo-600 */
            transition: background-color 0.15s ease-in-out, border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
        }

        .primary-button-mimic:hover {
            background-color: #4338ca; /* indigo-700 */
        }

        .primary-button-mimic:focus {
            outline: 2px solid transparent;
            outline-offset: 2px;
            box-shadow: 0 0 0 2px #ffffff, 0 0 0 4px #6366f1; /* ring-2 ring-offset-2 ring-indigo-500 */
        }

        .primary-button-mimic:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }

        /* Custom Error Modal Styles */
        .error-modal {
            display: none;
            position: fixed;
            z-index: 1001;
            left: 50%;
            top: 50%;
            transform: translate(-50%, -50%);
            width: 90%;
            max-width: 400px;
            background-color: #ffffff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
            text-align: center;
            border: 2px solid #ef4444; /* Red border for error */
        }

        .error-modal .icon-circle {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background-color: #ef4444; /* Red background for circle */
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .error-modal .icon-circle svg {
            width: 36px;
            height: 36px;
            fill: #ffffff; /* White X */
        }

        .error-modal h3 {
            font-size: 1.5rem;
            font-weight: bold;
            color: #dc2626; /* Darker red for message */
            margin-bottom: 15px;
        }

        .error-modal p {
            color: #4b5563; /* Gray text for description */
        }

        .error-modal .close-button {
            background-color: #ef4444; /* Red for close button */
            color: white;
            padding: 10px 25px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 1rem;
            font-weight: 600;
            margin-top: 25px;
            transition: background-color 0.3s ease, transform 0.2s ease;
        }

        .error-modal .close-button:hover {
            background-color: #b91c1c; /* Darker red on hover */
            transform: translateY(-2px);
        }
        #glowForm {
    box-shadow: 0 0 12px 4px #6366f1, 0 0 0 4px #fff; /* Indigo glow with white outline */
    border: 2px solid #6366f1; /* Optional: solid border for more definition */
    transition: box-shadow 0.3s, border-color 0.3s;
}
#glowForm:focus-within {
    box-shadow: 0 0 24px 8px #6366f1, 0 0 0 4px #fff;
    border-color: #4f46e5;
}
    </style>
    <div class="max-w-md mx-auto bg-white p-6 rounded-lg shadow-md">
        <h2 class="text-2xl font-bold mb-6 text-center text-gray-800">PDF Submission</h2>
        
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" enctype="multipart/form-data" action="http://localhost:8080/validate-file">
        <!-- Notice that we POST directly to Java backend -->
        @csrf

        <!-- PDF Upload -->
       
        <div>
        
            <x-input-label for="wholesalerpdf" :value="__('Insert PDF')" />
            <x-text-input 
            id="wholesalerpdf" 
            
        class="block w-full text-sm text-gray-500
               file:mr-4 file:py-2 file:px-4
               file:rounded-md file:border-0
               file:text-sm file:font-semibold
               file:bg-blue-50 file:text-blue-700
               hover:file:bg-blue-100 focus:outline-none focus:ring focus:border-blue-300"
        type="file" 
        name="file" 
        required 
        autofocus 
            />
            <p class="mt-2 text-sm text-gray-500">Only PDF files are accepted.</p>
            <!-- Note: name="file" must match @RequestParam("file") in Java -->
    </div>
        <!-- Success Modal -->
    <div id="successModal" class="success-modal">
        <div class="icon-circle">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 00-1.414 0L9 11.586 6.707 9.293a1 1 0 00-1.414 1.414L9 14.414l7.121-7.121a1 1 0 000-1.414z" clip-rule="evenodd" />
            </svg>
        </div>
        <h3>Application has been Submitted Successfully.</h3>
        <p class="text-gray-700">View Results or Wait for a Confirmation Email.</p>
        <button id="doneButton" class="done-button">VIEW</button>
    </div>
 <!-- Error Modal -->
 <div id="errorModal" class="error-modal">
        <div class="icon-circle">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
            </svg>
        </div>
        <h3 id="errorModalTitle">Submission Failed!</h3>
        <p id="errorModalMessage" class="text-gray-700">An unknown error occurred.</p>
        <button id="errorCloseButton" class="close-button">Close</button>
    </div>
   
        <div class="flex items-center justify-end mt-4">
    <x-primary-button class="primary-button-mimic ms-3" id="submitButton">
        <span id="buttonText">Submit Application</span>
        <div id="loadingSpinner" class="spinner ml-2 hidden"></div>
    </x-primary-button>
</div>

    <script>
document.addEventListener('DOMContentLoaded', () => {
    const pdfForm = document.querySelector('form');
    const submitButton = document.getElementById('submitButton');
    const buttonText = document.getElementById('buttonText');
    const loadingSpinner = document.getElementById('loadingSpinner');
    const fileInput = document.getElementById('wholesalerpdf');

    const successModal = document.getElementById('successModal');
    const doneButton = document.getElementById('doneButton');

    const errorModal = document.getElementById('errorModal');
    const errorModalTitle = document.getElementById('errorModalTitle');
    const errorModalMessage = document.getElementById('errorModalMessage');
    const errorCloseButton = document.getElementById('errorCloseButton');

    // Store the view URL from the backend
    let viewUrl = null;

    function showErrorModal(title, message) {
        errorModalTitle.textContent = title;
        errorModalMessage.textContent = message;
        errorModal.style.display = 'block';
    }

    function resetUI() {
        fileInput.value = '';
        buttonText.textContent = 'Submit Application';
        loadingSpinner.classList.add('hidden');
        submitButton.disabled = false;
        if (successModal) successModal.style.display = 'none';
        if (errorModal) errorModal.style.display = 'none';
        viewUrl = null;
    }

    /*pdfForm.addEventListener('submit', async (e) => {
        e.preventDefault();

        buttonText.textContent = 'Submitting...';
        loadingSpinner.classList.remove('hidden');
        submitButton.disabled = true;

        try {
            const formData = new FormData(pdfForm);
            const response = await fetch(pdfForm.action, {
                method: 'POST',
                body: formData
            });

            const data = await response.json();

            if (response.ok && data.success) {
                // Store the view URL from the backend response
                viewUrl = data.viewUrl || 'http://localhost:8080/validate-file';
                if (successModal) successModal.style.display = 'block';
            } else {
                showErrorModal('Submission Failed!', data.error || 'Submission failed.');
            }
        } catch (error) {
            showErrorModal('Connection Error', 'Failed to connect to the server.');
        } finally {
            if ((!successModal || successModal.style.display === 'none') && 
                (!errorModal || errorModal.style.display === 'none')) {
                resetUI();
            }
        }
    });

    if (doneButton) {
        doneButton.addEventListener('click', () => {
            if (successModal) successModal.style.display = 'none';
            // Open the view URL in a new tab if available
            if (viewUrl) {
                window.open(viewUrl, '_blank');
            } else {
                window.location.href = 'http://localhost:8080/validate-file';
            }
            resetUI();
        });
    }

    if (errorCloseButton) {
        errorCloseButton.addEventListener('click', () => {
            if (errorModal) errorModal.style.display = 'none';
            resetUI();
        });
    }
});
if (response.ok && data.success) {
    viewUrl = data.viewUrl || 'http://localhost:8080/validate-file';
    if (successModal) successModal.style.display = 'block';
}*/
pdfForm.addEventListener('submit', function(e) {
    e.preventDefault();

    const file = fileInput.files[0];
    if (!file) {
        showErrorModal('No File Selected', 'Please select a file to upload.');
        return;
    }

if (response.ok && data.success) {
    viewUrl = data.viewUrl || 'http://localhost:8080/validate-file';
    if (successModal) successModal.style.display = 'block';
}
    // Check if the file is a PDF
    if (file.type === "application/pdf" || file.name.toLowerCase().endsWith('.pdf')) {
        if (successModal) successModal.style.display = 'block';
        resetUI();
    } else {
        showErrorModal('Invalid File Type', 'Only PDF files are accepted.');
    }
});
</script>

</x-guest-layout>
