package com.vendor.validator.Service;

import com.vendor.validator.Model.ValidationResult;
import com.vendor.validator.Model.Vendor;
import org.springframework.stereotype.Service;

import java.util.ArrayList;
import java.util.List;

@Service
public class VendorService {

    public static ValidationResult validate(Vendor v) {
        List<String> reasons = new ArrayList<>();

        if (v.capital < 10_000_000)
            reasons.add("Capital is less than UGX 10,000,000");

        if (v.revenue < 3_000_000)
            reasons.add("Revenue is below UGX 3,000,000 minimum");

        if ((double) v.debt / v.capital > 0.4)
            reasons.add("Debt ratio exceeds 40%");

        if (v.blacklisted)
            reasons.add("Vendor is blacklisted");

        if (!v.license)
            reasons.add("Vendor lacks required license");

        if (!v.taxCertificate)
            reasons.add("Missing tax compliance certificate");

        boolean valid = reasons.isEmpty();

        return new ValidationResult(valid, reasons);
    }
}
