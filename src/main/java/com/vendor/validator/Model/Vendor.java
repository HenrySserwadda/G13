package com.vendor.validator.Model;

public class Vendor {

    public String name;
    public int capital;
    public int revenue;
    public int debt;
    public int experience;
    public boolean blacklisted;
    public boolean license;
    public boolean taxCertificate;

    public Vendor(String name, int capital, int revenue, int debt, int experience,
                  boolean blacklisted, boolean license, boolean taxCertificate) {
        this.name = name;
        this.capital = capital;
        this.revenue = revenue;
        this.debt = debt;
        this.experience = experience;
        this.blacklisted = blacklisted;
        this.license = license;
        this.taxCertificate = taxCertificate;
    }

    @Override
    public String toString() {
        return String.format("Vendor: %s\nCapital: %d\nRevenue: %d\nDebt: %d\nExperience: %d\nBlacklisted: %b\nLicense: %b\nTaxCertificate: %b\n",
                name, capital, revenue, debt, experience, blacklisted, license, taxCertificate);
    }
}
