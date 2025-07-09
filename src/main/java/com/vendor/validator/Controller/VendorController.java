package com.vendor.validator.Controller;

import com.vendor.validator.Model.ValidationResult;
import com.vendor.validator.Model.Vendor;
import com.vendor.validator.Service.VendorService;
import com.vendor.validator.Service.EmailService;
import com.vendor.validator.Service.VisitSchedulerService;
import com.vendor.validator.Util.VendorParser;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.format.annotation.DateTimeFormat;
import org.springframework.http.*;
import org.springframework.web.bind.annotation.*;
import org.springframework.web.multipart.MultipartFile;
import org.springframework.stereotype.Service;
import org.springframework.mail.javamail.JavaMailSender;

import java.io.File;
import java.time.LocalDate;
import java.util.*;

@RestController
@Service
public class VendorController {

    @Autowired private VendorService vendorService;
    @Autowired private VisitSchedulerService visitSchedulerService;

    @Autowired private EmailService emailService; // Add this

    @PostMapping(value = "/validate-file", consumes = MediaType.MULTIPART_FORM_DATA_VALUE)
    public ResponseEntity<List<Map<String, Object>>> validateVendors(@RequestParam("file") MultipartFile file) {
        try {
            File temp = File.createTempFile("vendors-", ".pdf");
            file.transferTo(temp);

            List<Vendor> vendors = VendorParser.parse(temp);
            List<Map<String, Object>> response = new ArrayList<>();

            for (Vendor v : vendors) {
                Map<String, Object> result = new LinkedHashMap<>();
                result.put("vendor", v.name);

                ValidationResult validation = VendorService.validate(v);
                
                // Send email notification
                emailService.sendValidationResult(v, validation);

                result.put("valid", validation.isValid());
                result.put("reasons", validation.getReasons());
                if (validation.isValid()) {
                    result.put("visitDate", visitSchedulerService.generateVisitDate(v));
                }
                response.add(result);
            }

            return ResponseEntity.ok(response);
        } catch (Exception e) {
            return ResponseEntity.status(HttpStatus.BAD_REQUEST)
                    .body(Collections.singletonList(Map.of("error", e.getMessage())));
        }
    }
}
