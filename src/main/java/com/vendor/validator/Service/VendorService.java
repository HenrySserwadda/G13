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

        if (v.industrystandards)
            reasons.add("Vendor doesn't meet indusrial standards");

        if (!v.license)
            reasons.add("Vendor lacks valid license");

        if (!v.taxCertificate)
            reasons.add("Missing tax Certification documents");

        boolean valid = reasons.isEmpty();

        return new ValidationResult(valid, reasons);
    }
}