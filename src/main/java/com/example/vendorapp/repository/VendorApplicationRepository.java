package com.example.vendorapp.repository;

import com.example.vendorapp.model.VendorApplication;
import org.springframework.data.jpa.repository.JpaRepository;
import com.example.vendorapp.model.ApplicationStatus;
import com.example.vendorapp.model.VendorApplication;
import org.springframework.data.jpa.repository.JpaRepository;
import java.time.LocalDateTime;
import java.util.List;

public interface VendorApplicationRepository extends JpaRepository<VendorApplication, Long> {
    List<VendorApplication> findByStatus(ApplicationStatus status);
    List<VendorApplication> findByVisitScheduledDateBetween(LocalDateTime start, LocalDateTime end);
}