package com.vendor.validator.Service;

import com.vendor.validator.Model.Vendor;
import org.springframework.stereotype.Service;

@Service
public class VendorService {
    public boolean isValid(Vendor vendor) {
        if (vendor.capital < 10000) return false;
        if (vendor.revenue < 3000) return false;
        if (((double) vendor.debt / vendor.capital) > 0.4) return false;
        if (vendor.blacklisted) return false;
        if (!vendor.license || !vendor.taxCertificate) return false;
        return true;
    }
}
