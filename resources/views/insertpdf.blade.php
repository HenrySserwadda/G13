<x-guest-layout>
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f3f4f6;
            min-height: 100vh;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
        }
        .download-container {
            background-color: #ffffff;
            padding: 3rem;
            border-radius: 0.75rem;
            text-align: center;
        }
        .download-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background-color: #3b82f6; /* blue-500 */
            color: white;
            padding: 1rem 2rem;
            border-radius: 0.5rem;
            font-weight: 600;
            text-decoration: none;
            transition: background-color 0.3s ease;
            cursor: pointer;
        }
        .download-btn:hover {
            background-color: #2563eb; /* blue-600 */
        }
        .download-btn svg {
            margin-right: 0.75rem;
        }
        
        /* Header Styles */
        .header-container {
            text-align: center;
            padding: 1.5rem 0;
            background-color: #ffffff;
           
            width: 100%;
        }

        .logo-container {
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 0.5rem;
        }

        .logo {
            width: 50px;
            height: 50px;
            margin-right: 15px;
            border-radius: 50%;
        }

        .company-name {
            font-size: 2rem;
            font-weight: 700;
            color: #1e3a8a;
        }

        /* Main Content */
        .main-container {
            flex: 1;
            width: 100%;
            max-width: 1200px;
            margin: 0 auto;
            padding: 1rem;
            display: flex;
            flex-direction: column;
        }

        .content-wrapper {
            display: flex;
            flex-direction: column;
            flex: 1;
        }

        .welcome-message {
            background-color: #ffffff;
            padding: 2rem;
            border-radius: 0.5rem;
            
            margin-bottom: 2rem;
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            width: 100%;
        }

        .welcome-message p {
            font-size: 1.1rem;
            line-height: 1.6;
            color: #4b5563;
            margin-bottom: 1.5rem;
            text-align: left;
            width: 100%;
        }

        .welcome-message h2 {
            width: 100%;
            font-size: 1.5rem;
            margin-bottom: 1rem;
        }

          .download-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 16px 32px;
        font-size: 16px;
        font-weight: 600;
        color: white;
        background-color: #3b82f6;
        border-radius: 5000px; /* Creates pill shape */
        transition: all 0.3s;
        text-decoration: none;
        box-shadow: 0 6px 23px rgba(0, 0, 0, 0.15);
        animation: fade-up 0.5s 0.4s backwards;
        position: relative;
        overflow: hidden;
    }

    .download-btn:hover {
        transform: scale(1.05);
        background-color: #2563eb;
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
    }

    .download-btn svg {
        margin-right: 8px;
        width: 20px;
        height: 20px;
    }

    /* Animation keyframes */
    @keyframes fade-up {
        0% {
            opacity: 0;
            transform: translateY(20px);
        }
        100% {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes fade-down {
        0% {
            opacity: 0;
            transform: translateY(-20px);
        }
        100% {
            opacity: 1;
            transform: translateY(0);
        }
    }

        .download-btn:hover {
            background-color: #2563eb;
        }

        /* Form Container */
        .form-container {
            width: 100%;
            max-width: 800px;
            margin: 0 auto;
            background-color: white;
            padding: 2rem;
            border-radius: 0.5rem;
            box-shadow: 3px 4px 35px rgba(0,0,0,0.1);
        }

        /* Modal styles (keep existing modal styles) */
        /* ... existing modal styles ... */

        /* Adjust file input styling */
        #wholesalerpdf {
            width: 100%;
            margin-top: 0.5rem;
            padding: 0.5rem;
            border: 1px solid #d1d5db;
            border-radius: 0.375rem;
            background-color: #f9fafb;
        }

        /* Button styles */
        .primary-button-mimic {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 100%;
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: 0.375rem;
            background-color: #4f46e5;
            color: white;
            font-weight: 600;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .primary-button-mimic:hover {
            background-color: #4338ca;
        }

        /* Back button */
        .back-button {
            display: inline-block;
            margin-top: 2rem;
            padding: 0.75rem 1.5rem;
            background-color: #3b82f6;
            color: white;
            border-radius: 0.375rem;
            text-decoration: none;
            font-weight: 600;
            transition: background-color 0.3s;
        }

        .back-button:hover {
            background-color: #2563eb;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .company-name {
                font-size: 1.5rem;
            }
            
            .welcome-message, .form-container {
                padding: 1.5rem;
            }
        }
        .spinner {
    border: 3px solid rgba(255, 255, 255, 0.3);
    border-radius: 50%;
    border-top: 3px solid #ffffff;
    width: 20px;
    height: 20px;
    animation: spin 1s linear infinite;
    display: none; /* Hidden by default */
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

.spinner.visible {
    display: inline-block;
}

    </style>

    <div class="header-container">
        <div class="logo-container">
            <img src="https://placehold.co/50x50/3b82f6/ffffff?text=DB" alt="DURABAG Logo" class="logo">
            <h1 class="company-name">DURABAG</h1>
        </div>
    </div>

    <div class="main-container">
        <div class="content-wrapper">
            <div class="welcome-message">
                <h2 style="font-size: 1.5rem; font-weight: bold; margin-bottom: 1.5rem; text-align: center;">Welcome Valued Vendors</h2>
                <p>At DURABAG, we value honesty and integrity in all our business relationships. We're committed to building long-term partnerships with vendors who share our commitment to quality and ethical business practices.</p>
                <p>We're delighted to have the opportunity to do business with you. Our vendor application process is designed to be simple and straightforward, ensuring we can start our partnership on the right foot.</p>
                <p>To begin the application process, please download our vendor form below, fill it out completely, save it and Submit it in the form below. We'll review your application promptly and get back to you with next steps.</p>
                <a href="Vendor-Validation-Form.pdf" class="download-btn" download>
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>    
                Download Vendor Form</a>
                <p class="text-gray-600 text-sm mt-4" style="text-align: center; margin-top: 0.5rem; font-size: 0.875rem; color: #6b7280;">Click the button above to download the PDF form.</p>
            </div>
        
            <div class="form-container">
                <h2 style="font-size: 1.5rem; font-weight: bold; margin-bottom: 1.5rem; text-align: center;">PDF Submission</h2>
                
                <form method="POST" enctype="multipart/form-data" action="http://localhost:8080/validate-file">
                    @csrf
                    
                    <div style="margin-bottom: 1.5rem;">
                        <label for="wholesalerpdf" style="display: block; font-weight: 600; margin-bottom: 0.5rem;">Insert PDF</label>
                        <input 
                            id="wholesalerpdf" 
                            class="block w-full text-sm text-gray-500
                                   file:mr-4 file:py-2 file:px-4
                                   file:rounded-md file:border-0
                                   file:text-sm file:font-semibold
                                   file:bg-blue-50 file:text-blue-700
                                   hover:file:bg-blue-100"
                            type="file" 
                            name="file" 
                            required 
                            autofocus 
                        />
                        <p style="margin-top: 0.5rem; font-size: 0.875rem; color: #6b7280;">Only PDF files are accepted.</p>
                    </div>

                    <div style="margin-top: 2rem;">
                        <button type="submit" class="primary-button-mimic" id="submitButton">
                            <span id="buttonText">Submit Application</span>
                            <div id="loadingSpinner" class="spinner ml-2 hidden"></div>
                        </button>
                    </div>
                </form>
                
                <!-- Success Modal -->
                <div id="successModal" class="success-modal">
                    <!-- ... existing success modal content ... -->
                </div>
                
                <!-- Error Modal -->
                <div id="errorModal" class="error-modal">
                    <!-- ... existing error modal content ... -->
                </div>
            </div>

            <div style="text-align: center; margin-top: 2rem;">
                <a href="http://127.0.0.1:8000/dashboard/customer#" class="back-button">← Customer Dashboard</a>
            </div>
        </div>
    </div>

    <script>
        // Keep existing JavaScript functionality
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

            function showErrorModal(title, message) {
                errorModalTitle.textContent = title;
                errorModalMessage.textContent = message;
                errorModal.style.display = 'block';
            }
document.querySelector('.download-btn').addEventListener('click', function(e) {
    e.preventDefault();
    fetch('/pdf/Vendor-Validation-Form.pdf')
        .then(response => {
            if (!response.ok) throw new Error('File not found');
            return response.blob();
        })
        .then(blob => {
            const url = URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = 'DURABAG_Vendor_Form.pdf';
            a.click();
            URL.revokeObjectURL(url);
        })
        .catch(error => {
            alert('Failed to download: ' + error.message);
            console.error('Download error:', error);
        });
});
            function resetUI() {
                fileInput.value = '';
                buttonText.textContent = 'Submit Application';
                loadingSpinner.classList.add('hidden');
                submitButton.disabled = false;
                loadingSpinner.classList.add('hidden');
                if (successModal) successModal.style.display = 'none';
                if (errorModal) errorModal.style.display = 'none';
            }

            pdfForm.addEventListener('submit', function(e) {
    const file = fileInput.files[0];
    
    if (!file) {
        e.preventDefault(); // Block submission
        showErrorModal('No File Selected', 'Please select a file to upload.');
        return;
    }

    // Validate file type
    if (!(file.type === "application/pdf" || file.name.toLowerCase().endsWith('.pdf'))) {
        e.preventDefault(); // Block submission
        showErrorModal('Invalid File Type', 'Only PDF files are accepted.');
        return;
    }

    // ✅ File is okay, show loading spinner and let it submit
    buttonText.textContent = 'Submitting...';
    loadingSpinner.classList.remove('hidden');
    submitButton.disabled = true;
});


            if (doneButton) {
                doneButton.addEventListener('click', () => {
                    if (successModal) successModal.style.display = 'none';
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
    </script>
</x-guest-layout>