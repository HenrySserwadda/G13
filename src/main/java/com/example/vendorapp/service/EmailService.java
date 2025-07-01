package com.example.vendorapp.service;

import com.example.vendorapp.model.VendorApplication;
import com.example.vendorapp.model.ApplicationStatus;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.mail.SimpleMailMessage;
import org.springframework.mail.javamail.JavaMailSender;
import org.springframework.stereotype.Service;

@Service
public class EmailService {

    @Autowired
    private JavaMailSender mailSender;

    /**
     * Sends application result and visit details (if approved)
     */
    public void sendApplicationResult(VendorApplication application) {
        SimpleMailMessage message = new SimpleMailMessage();
        message.setTo(application.getEmail());
        message.setSubject("Vendor Application Status: " + application.getCompanyName());

        if (application.getStatus() == ApplicationStatus.APPROVED) {
            message.setText(String.format(
                "Dear %s,\n\n" +
                "Congratulations! Your application for %s has been APPROVED.\n\n" +
                "Next Steps:\n" +
                "1. Facility Visit Scheduled: %s\n" +
                "2. Our team will contact you within 48 hours\n\n" +
                "Best regards,\n" +
                "Vendor Management Team",
                application.getContactPerson(),
                application.getCompanyName(),
                application.getVisitScheduledDate()
            ));
        } else {
            message.setText(String.format(
                "Dear %s,\n\n" +
                "Your application for %s has been REVIEWED.\n\n" +
                "Status: %s\n" +
                "Note: %s\n\n" +
                "Best regards,\n" +
                "Vendor Management Team",
                application.getContactPerson(),
                application.getCompanyName(),
                application.getStatus(),
                "Please contact support for details" // Customize rejection reason
            ));
        }

        mailSender.send(message);
    }

    /**
     * Sends visit reminder 24 hours before scheduled date
     */
    public void sendVisitReminder(VendorApplication application) {
        SimpleMailMessage message = new SimpleMailMessage();
        message.setTo(application.getEmail());
        message.setSubject("REMINDER: Facility Visit for " + application.getCompanyName());
        message.setText(String.format(
            "Dear %s,\n\n" +
            "This is a reminder about your scheduled visit:\n\n" +
            "Date: %s\n" +
            "Location: [Your Facility Address]\n\n" +
            "Preparation Checklist:\n" +
            "- Business licenses\n" +
            "- Financial records\n" +
            "- Safety certifications\n\n" +
            "Best regards,\n" +
            "Vendor Management Team",
            application.getContactPerson(),
            application.getVisitScheduledDate()
        ));

        mailSender.send(message);
    }
}