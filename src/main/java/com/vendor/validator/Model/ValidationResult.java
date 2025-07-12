package com.vendor.validator.Model;

import java.util.List;

public class ValidationResult {

    private boolean valid;
    private List<String> reasons;

    public  ValidationResult(boolean valid, List<String> reasons) {
        this.valid = valid;
        this.reasons = reasons;
    }

    public boolean isValid() {
        return valid;
    }

    public List<String> getReasons() {
        return reasons;
    }
}
