package com.example.vendorapp.model;

import jakarta.persistence.*;
import org.hibernate.annotations.CreationTimestamp;
import org.hibernate.annotations.UpdateTimestamp;

import java.util.Date;

@Entity
public class VendorApplication {
    @Id
    @GeneratedValue(strategy = GenerationType.IDENTITY)
    private Long id;

    private String companyName;
    private String contactPerson;
    private String email;
    private String phone;

    // Financial Information
    private Integer yearsInBusiness;
    private Double annualRevenue;
    private Double debtRatio;

    // Reputation Information
    private Boolean hasLegalIssues;
    private Integer customerRating;
    private String references;

    // Regulatory Information
    private Boolean hasRequiredLicenses;
    private Boolean compliesWithIndustryStandards;
    private String certifications;

    @Enumerated(EnumType.STRING)
    private ApplicationStatus status;

    @Lob
    private byte[] originalPdf;

    @Temporal(TemporalType.TIMESTAMP)
    private Date visitScheduledDate;
    private String visitNotes;

    @CreationTimestamp
    @Temporal(TemporalType.TIMESTAMP)
    private Date createdAt;

    @UpdateTimestamp
    @Temporal(TemporalType.TIMESTAMP)
    private Date updatedAt;

    // Constructors
    public VendorApplication() {
        this.status = ApplicationStatus.SUBMITTED;
    }

    // Getters and Setters

    public Long getId() {
        return id;
    }

    public void setId(Long id) {
        this.id = id;
    }

    public String getCompanyName() {
        return companyName;
    }

    public void setCompanyName(String companyName) {
        this.companyName = companyName;
    }

    public String getContactPerson() {
        return contactPerson;
    }

    public void setContactPerson(String contactPerson) {
        this.contactPerson = contactPerson;
    }

    public String getEmail() {
        return email;
    }

    public void setEmail(String email) {
        this.email = email;
    }

    public String getPhone() {
        return phone;
    }

    public void setPhone(String phone) {
        this.phone = phone;
    }

    public Integer getYearsInBusiness() {
        return yearsInBusiness;
    }

    public void setYearsInBusiness(Integer yearsInBusiness) {
        this.yearsInBusiness = yearsInBusiness;
    }

    public Double getAnnualRevenue() {
        return annualRevenue;
    }

    public void setAnnualRevenue(Double annualRevenue) {
        this.annualRevenue = annualRevenue;
    }

    public Double getDebtRatio() {
        return debtRatio;
    }

    public void setDebtRatio(Double debtRatio) {
        this.debtRatio = debtRatio;
    }

    public Boolean getHasLegalIssues() {
        return hasLegalIssues;
    }

    public void setHasLegalIssues(Boolean hasLegalIssues) {
        this.hasLegalIssues = hasLegalIssues;
    }

    public Integer getCustomerRating() {
        return customerRating;
    }

    public void setCustomerRating(Integer customerRating) {
        this.customerRating = customerRating;
    }

    public String getReferences() {
        return references;
    }

    public void setReferences(String references) {
        this.references = references;
    }

    public Boolean getHasRequiredLicenses() {
        return hasRequiredLicenses;
    }

    public void setHasRequiredLicenses(Boolean hasRequiredLicenses) {
        this.hasRequiredLicenses = hasRequiredLicenses;
    }

    public Boolean getCompliesWithIndustryStandards() {
        return compliesWithIndustryStandards;
    }

    public void setCompliesWithIndustryStandards(Boolean compliesWithIndustryStandards) {
        this.compliesWithIndustryStandards = compliesWithIndustryStandards;
    }

    public String getCertifications() {
        return certifications;
    }

    public void setCertifications(String certifications) {
        this.certifications = certifications;
    }

    public ApplicationStatus getStatus() {
        return status;
    }

    public void setStatus(ApplicationStatus status) {
        this.status = status;
    }

    public byte[] getOriginalPdf() {
        return originalPdf;
    }

    public void setOriginalPdf(byte[] originalPdf) {
        this.originalPdf = originalPdf;
    }

    public Date getVisitScheduledDate() {
        return visitScheduledDate;
    }

    public void setVisitScheduledDate(Date visitScheduledDate) {
        this.visitScheduledDate = visitScheduledDate;
    }

    public String getVisitNotes() {
        return visitNotes;
    }

    public void setVisitNotes(String visitNotes) {
        this.visitNotes = visitNotes;
    }

    public Date getCreatedAt() {
        return createdAt;
    }

    public void setCreatedAt(Date createdAt) {
        this.createdAt = createdAt;
    }

    public Date getUpdatedAt() {
        return updatedAt;
    }

    public void setUpdatedAt(Date updatedAt) {
        this.updatedAt = updatedAt;
    }
}