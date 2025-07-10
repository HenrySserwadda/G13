package com.vendor.validator.Service;

import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.mail.SimpleMailMessage;
import org.springframework.mail.javamail.JavaMailSender;
import org.springframework.stereotype.Service;

import com.vendor.validator.Model.Vendor;
import com.vendor.validator.Model.ValidationResult;

import java.time.LocalDate;

@Service
public class EmailService {

    @Autowired
    private JavaMailSender mailSender;

    @Autowired
    private VisitSchedulerService visitSchedulerService;

    public void sendValidationResult(Vendor vendor, ValidationResult result) {
        SimpleMailMessage message = new SimpleMailMessage();
        message.setTo(vendor.email);
        message.setSubject("Vendor Validation Results - " + vendor.name);

        String content;

        if (result.isValid()) {
             LocalDate visitDate = visitSchedulerService.generateVisitDate(vendor);


            content = String.format(
                "Dear CEO,\n\n" +
                "Congratulations! Your company %s has been successfully validated as a Vendor or Wholesaler.\n" +
                "You meet all our requirements and are now eligible for business opportunities.\n\n" +
                "We will have a visit to your facility on %s and hope you will be prepared for it.\n\n" +
                "Best regards,\nVendor Management Team",
                vendor.CEO, vendor.name, visitDate
            );

        } else {
            content = String.format(
                "Dear CEO,\n\n" +
                "Thank you for submitting %s for vendor validation.\n" +
                "Unfortunately, your application did not meet all requirements at this time.\n\n" +
                "Reasons for rejection:\n%s\n\n" +
                "Please address these issues and resubmit your application.\n\n" +
                "Best regards,\nVendor Management Team",
                vendor.CEO, 
                vendor.name,
                String.join("\n", result.getReasons())
            );
        }

        message.setText(content);
        mailSender.send(message);
    }
}
