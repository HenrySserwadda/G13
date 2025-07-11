package com.vendor.validator.Service;

import java.util.ArrayList;
import java.util.List;

import org.springframework.stereotype.Service;

import com.vendor.validator.Model.ValidationResult;
import com.vendor.validator.Model.Vendor;

@Service
public class VendorService {

    public static ValidationResult validate(Vendor v) {
        List<String> reasons = new ArrayList<>();

        if (v.capital < 10_000_000)
            reasons.add("Capital is less than UGX 10,000,000");

        if (v.revenue < 3_000_000)
            reasons.add("Revenue is below UGX 3,000,000 minimum");

        if ((double) v.debt / v.capital > 0.3)
            reasons.add("Debt is high and exceeds 30%");

        if (!v.industrystandards)
            reasons.add("Vendor doesn't meet industrial standards");

        // Validate Business Registration Number (previously license)
        if (v.businessRegistrationNumber == null || v.businessRegistrationNumber.isEmpty()) {
            reasons.add("Missing Business Registration Number");
        } else if (!v.businessRegistrationNumber.matches("BRN: \\d{9}")) { // Example format: BRN: 123456789
            reasons.add("Invalid Business Registration Number format (should be 'BRN: 123456789')");
        }

        // Validate TIN (previously taxCertificate)
        if (v.TIN == null || v.TIN.isEmpty()) {
            reasons.add("Missing Tax Identification Number (TIN)");
        } else if (!v.TIN.matches("\\d{10}")) { // Must be exactly 10 digits
            reasons.add("Invalid TIN format (should be 10 digits)");
        }

        boolean valid = reasons.isEmpty();

        return new ValidationResult(valid, reasons);
    }
}