package com.vendor.validator.Model;

public class Vendor {

    public String name;
    public String CEO;
    public String email;
    public int phone;
    public int capital;
    public int revenue;
    public int debt;
    public int experience;
    public boolean industrystandards;
    public String businessRegistrationNumber;  // Changed from boolean license
    public String TIN;  // Changed from boolean taxCertificate

    public Vendor() {}

    public Vendor(String name, String CEO, String email, int phone, int capital, int revenue, int debt, int experience,
                  boolean industrystandards, String businessRegistrationNumber, String TIN) {
        this.name = name;
        this.CEO = CEO;
        this.email = email;
        this.phone = phone;
        this.capital = capital;
        this.revenue = revenue;
        this.debt = debt;
        this.experience = experience;
        this.industrystandards = industrystandards;
        this.businessRegistrationNumber = businessRegistrationNumber;
        this.TIN = TIN;
    }

    @Override
    public String toString() {
        return String.format(
            "Company Name: %s\nCEO: %s\nEmail: %s\nContact: %d\nCapital: %d\nRevenue: %d\nDebt: %d\nExperience: %d\nIndustry Standards: %b\nBusiness Registration Number: %s\nTIN: %s\n",
            name, CEO, email, phone, capital, revenue, debt, experience, industrystandards, businessRegistrationNumber, TIN);
    }
}