package com.example.vendorapp.service;

import com.example.vendorapp.model.VendorApplication;
import org.apache.pdfbox.pdmodel.PDDocument;
import org.apache.pdfbox.text.PDFTextStripper;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.stereotype.Service;
import org.springframework.web.multipart.MultipartFile;
import com.example.vendorapp.repository.VendorApplicationRepository;
import com.example.vendorapp.model.ApplicationStatus;

import java.io.IOException;
import java.util.regex.Matcher;
import java.util.regex.Pattern;

@Service
public class PdfProcessingService {
    
    @Autowired
    private VendorApplicationRepository repository;
    
    public VendorApplication processPdf(MultipartFile pdfFile) throws IOException {
        try (PDDocument document = PDDocument.load(pdfFile.getInputStream())) {
            PDFTextStripper stripper = new PDFTextStripper();
            String text = stripper.getText(document);
            
            VendorApplication application = extractApplicationData(text);
            application.setOriginalPdf(pdfFile.getBytes());
            application.setStatus(ApplicationStatus.SUBMITTED);
            
            return repository.save(application);
        }
    }
    
    private VendorApplication extractApplicationData(String text) {
        VendorApplication application = new VendorApplication();
        
        // Extract basic information
        application.setCompanyName(extractField(text, "Company Name:(.*)"));
        application.setContactPerson(extractField(text, "Contact Person:(.*)"));
        application.setEmail(extractField(text, "Email:(.*)"));
        application.setPhone(extractField(text, "Phone:(.*)"));
        
        // Extract financial information
        application.setYearsInBusiness(Integer.parseInt(extractField(text, "Years in Business:(.*)", "0")));
        application.setAnnualRevenue(Double.parseDouble(extractField(text, "Annual Revenue:(.*)", "0")));
        application.setDebtRatio(Double.parseDouble(extractField(text, "Debt Ratio:(.*)", "0")));
        
        // Extract reputation information
        application.setHasLegalIssues(Boolean.parseBoolean(extractField(text, "Legal Issues:(.*)", "false")));
        application.setCustomerRating(Integer.parseInt(extractField(text, "Customer Rating:(.*)", "0")));
        application.setReferences(extractField(text, "References:(.*)"));
        
        // Extract regulatory information
        application.setHasRequiredLicenses(Boolean.parseBoolean(extractField(text, "Required Licenses:(.*)", "false")));
        application.setCompliesWithIndustryStandards(Boolean.parseBoolean(extractField(text, "Industry Standards:(.*)", "false")));
        application.setCertifications(extractField(text, "Certifications:(.*)"));
        
        return application;
    }
    
    private String extractField(String text, String pattern) {
        return extractField(text, pattern, "");
    }
    
    private String extractField(String text, String pattern, String defaultValue) {
        Pattern r = Pattern.compile(pattern);
        Matcher m = r.matcher(text);
        if (m.find()) {
            return m.group(1).trim();
        }
        return defaultValue;
    }
}