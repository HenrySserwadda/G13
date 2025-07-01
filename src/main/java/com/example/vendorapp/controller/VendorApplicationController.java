package com.example.vendorapp.controller;

import com.example.vendorapp.model.VendorApplication;
import com.example.vendorapp.model.ApplicationStatus;
import com.example.vendorapp.service.PdfProcessingService;
import com.example.vendorapp.service.SchedulingService;
import com.example.vendorapp.validation.VendorValidationService;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.core.io.InputStreamResource;
import org.springframework.core.io.Resource;
import org.springframework.http.HttpStatus;
import org.springframework.http.MediaType;
import org.springframework.http.ResponseEntity;
//import org.springframework.ui.Model;
import org.springframework.web.bind.annotation.*;
import org.springframework.web.multipart.MultipartFile;

import java.io.File;
import java.io.FileInputStream;
import java.io.IOException;

@RestController
@RequestMapping("/api/vendor")
public class VendorApplicationController {
    
    private final PdfProcessingService pdfProcessingService;
    private final VendorValidationService validationService;
    private final SchedulingService schedulingService;
    private static final String UPLOAD_DIR = "E:\\Java Projects\\Uploads\\";

    @Autowired
    public VendorApplicationController(
            PdfProcessingService pdfProcessingService,
            VendorValidationService validationService,
            SchedulingService schedulingService) {
        this.pdfProcessingService = pdfProcessingService;
        this.validationService = validationService;
        this.schedulingService = schedulingService;
        
        // Ensure upload directory exists
        new File(UPLOAD_DIR).mkdirs();
    }

    // Simple PDF upload endpoint
    @PostMapping(path = "/upload-pdf", consumes = MediaType.MULTIPART_FORM_DATA_VALUE)
    public ResponseEntity<?> uploadPdf(@RequestPart("file") MultipartFile file) {
        if (file.isEmpty()) {
            return ResponseEntity.badRequest().body("Please select a file to upload");
        }

        String fileName = file.getOriginalFilename();
        try {
            File dest = new File(UPLOAD_DIR + fileName);
            file.transferTo(dest);
            return ResponseEntity.ok("File uploaded successfully: " + fileName);
        } catch (IOException e) {
            return ResponseEntity.status(HttpStatus.INTERNAL_SERVER_ERROR)
                    .body("Failed to upload file: " + e.getMessage());
        }
    }

    // Full vendor application processing endpoint
    @PostMapping(path = "/submit-application", consumes = MediaType.MULTIPART_FORM_DATA_VALUE)
    public ResponseEntity<?> handleVendorApplication(
            @RequestParam("file") MultipartFile file) {
        
        if (file.isEmpty()) {
            return ResponseEntity.badRequest().body("Please select a file to upload");
        }

        try {
            // Save the file first
            String fileName = file.getOriginalFilename();
            File dest = new File(UPLOAD_DIR + fileName);
            file.transferTo(dest);
            
            // Process the application
            VendorApplication application = pdfProcessingService.processPdf(file);
            
            // Validate application
            boolean isValid = validationService.validateApplication(application);
            application.setStatus(isValid ? ApplicationStatus.APPROVED : ApplicationStatus.REJECTED);
            
            // If approved, schedule visit
            if (isValid) {
                schedulingService.scheduleVisit(application);
                return ResponseEntity.ok(new ApplicationResponse("Application approved! Visit scheduled.", application));
            } else {
                return ResponseEntity.ok(new ApplicationResponse("Application rejected based on validation criteria.", application));
            }
            
        } catch (Exception e) {
            return ResponseEntity.status(HttpStatus.INTERNAL_SERVER_ERROR)
                    .body("Error processing application: " + e.getMessage());
        }
    }

    // Endpoint to retrieve uploaded PDF
    @GetMapping("/pdf/{filename:.+}")
    public ResponseEntity<Resource> getPdf(@PathVariable String filename) throws IOException {
        File file = new File(UPLOAD_DIR + filename);
        if (!file.exists() || file.isDirectory()) {
            return ResponseEntity.notFound().build();
        }
        InputStreamResource resource = new InputStreamResource(new FileInputStream(file));
        return ResponseEntity.ok()
                .contentType(MediaType.APPLICATION_PDF)
                .body(resource);
    }

    // Helper class for consistent response format
    private static class ApplicationResponse {
        private final String message;
        private final VendorApplication application;

        public ApplicationResponse(String message, VendorApplication application) {
            this.message = message;
            this.application = application;
        }

        // Getters
        public String getMessage() { return message; }
        public VendorApplication getApplication() { return application; }
    }
}