package com.example.vendorapp.service;

import com.example.vendorapp.model.ApplicationStatus;
import com.example.vendorapp.model.VendorApplication;
import com.example.vendorapp.repository.VendorApplicationRepository;

import jakarta.mail.MessagingException;

import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.beans.factory.annotation.Value;
import org.springframework.mail.SimpleMailMessage;
import org.springframework.mail.javamail.JavaMailSender;
import org.springframework.scheduling.annotation.Scheduled;
import org.springframework.stereotype.Service;

import java.time.LocalDateTime;
import java.util.Calendar;
import java.util.Date;
import java.util.List;

@Service
public class SchedulingService {
    
    @Autowired
    private VendorApplicationRepository repository;

    @Autowired
    private EmailService emailService;
    
    @Autowired
    private JavaMailSender mailSender;
    
    @Value("${app.visit.scheduling.days-after-approval}")
    private int daysAfterApproval;
    
    public void scheduleVisit(VendorApplication application) {
        Calendar calendar = Calendar.getInstance();
        calendar.add(Calendar.DAY_OF_YEAR, daysAfterApproval);
        Date visitDate = calendar.getTime();
        
        application.setVisitScheduledDate(visitDate);
        application.setStatus(ApplicationStatus.VISIT_SCHEDULED);
        repository.save(application);
        
        sendVisitNotification(application);
         emailService.sendApplicationResult(application);

    }
    
    private void sendVisitNotification(VendorApplication application) {
        SimpleMailMessage message = new SimpleMailMessage();
        message.setTo(application.getEmail());
        message.setSubject("Vendor Application - Facility Visit Scheduled");
        message.setText(String.format(
            "Dear %s,\n\n" +
            "Your vendor application for %s has been approved for the next stage.\n" +
            "We have scheduled a facility visit for %s.\n\n" +
            "Our representative will contact you shortly to confirm details.\n\n" +
            "Best regards,\n" +
            "Vendor Management Team",
            application.getContactPerson(),
            application.getCompanyName(),
            application.getVisitScheduledDate()
        ));
        
        mailSender.send(message);
    }
    
    @Scheduled(fixedRate = 3600000) // Run every hour
    public void processApprovedApplications() {
        List<VendorApplication> approvedApps = repository.findByStatus(ApplicationStatus.APPROVED);
        approvedApps.forEach(this::scheduleVisit);
    }

    @Scheduled(cron = "0 0 9 * * ?") // Runs daily at 9 AM
public void sendDailyVisitReminders() {
    List<VendorApplication> upcomingVisits = repository.findByVisitScheduledDateBetween(
        LocalDateTime.now(), 
        LocalDateTime.now().plusDays(1)
    );
    
    upcomingVisits.forEach(emailService::sendVisitReminder);
}    

}