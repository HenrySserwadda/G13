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
import org.springframework.ui.Model;
import org.springframework.web.bind.annotation.*;
import org.springframework.web.multipart.MultipartFile;
import org.springframework.stereotype.Controller;
import org.springframework.mail.javamail.JavaMailSender;
import java.time.format.DateTimeFormatter;


import java.io.File;
import java.time.LocalDate;
import java.util.*;

@Controller
public class VendorController {

    @Autowired private VendorService vendorService;
    @Autowired private VisitSchedulerService visitSchedulerService;
    @Autowired private EmailService emailService;

    @GetMapping("/form")
    public String showForm() {
        return "form";
    }

    @PostMapping(value = "/validate-file", consumes = MediaType.MULTIPART_FORM_DATA_VALUE)
    public String validateVendors(@RequestParam("file") MultipartFile file, Model model) {
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
    LocalDate visitDate = visitSchedulerService.generateVisitDate(v);
    DateTimeFormatter formatter = DateTimeFormatter.ofPattern("dd/MM/yyyy");
    String formattedDate = visitDate.format(formatter);
    result.put("visitDate", formattedDate);
}

                response.add(result);
            }

            model.addAttribute("results", response);
            return "validation-result";
        } catch (Exception e) {
            model.addAttribute("error", e.getMessage());
            return "validation-result";
        }
    }
}


