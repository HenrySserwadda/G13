package com.example.vendorapp.validation;

import com.example.vendorapp.model.VendorApplication;
import org.springframework.stereotype.Service;

@Service
public class VendorValidationService {
    
    public boolean validateFinancialStability(VendorApplication application) {
        // Minimum 2 years in business
        if (application.getYearsInBusiness() < 2) {
            return false;
        }
        
        // Minimum annual revenue of $100,000
        if (application.getAnnualRevenue() < 100000) {
            return false;
        }
        
        // Debt ratio less than 0.5
        if (application.getDebtRatio() > 0.5) {
            return false;
        }
        
        return true;
    }
    
    public boolean validateReputation(VendorApplication application) {
        // No legal issues
        if (application.getHasLegalIssues()) {
            return false;
        }
        
        // Minimum customer rating of 4 (out of 5)
        if (application.getCustomerRating() < 4) {
            return false;
        }
        
        // At least 2 references
        if (application.getReferences() == null || 
            application.getReferences().split(",").length < 2) {
            return false;
        }
        
        return true;
    }
    
    public boolean validateRegulatoryCompliance(VendorApplication application) {
        // Must have required licenses
        if (!application.getHasRequiredLicenses()) {
            return false;
        }
        
        // Must comply with industry standards
        if (!application.getCompliesWithIndustryStandards()) {
            return false;
        }
        
        // Must have at least one certification
        if (application.getCertifications() == null || 
            application.getCertifications().isEmpty()) {
            return false;
        }
        
        return true;
    }
    
    public boolean validateApplication(VendorApplication application) {
        return validateFinancialStability(application) &&
               validateReputation(application) &&
               validateRegulatoryCompliance(application);
    }
}