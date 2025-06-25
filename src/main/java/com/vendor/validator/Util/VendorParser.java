package com.vendor.validator.Util;

import com.vendor.validator.Model.Vendor;
import org.apache.pdfbox.Loader;
import org.apache.pdfbox.pdmodel.PDDocument;
import org.apache.pdfbox.text.PDFTextStripper;

import java.io.*;
import java.util.ArrayList;
import java.util.List;

public class VendorParser {
    public static List<Vendor> parse(File pdfFile) {
        try {
            String text = extractTextFromPDF(pdfFile);
            return parseVendorText(text);
        } catch (IOException e) {
            throw new RuntimeException("Failed to parse PDF", e);
        }
    }

    private static String extractTextFromPDF(File file) throws IOException {
        try (PDDocument document = Loader.loadPDF(file)) {
            PDFTextStripper stripper = new PDFTextStripper();
            return stripper.getText(document);
        }
    }

    private static List<Vendor> parseVendorText(String content) {
        List<Vendor> vendors = new ArrayList<>();
        String[] lines = content.split("\n");

        String name = "";
        int capital = 0, revenue = 0, debt = 0, experience = 0;
        boolean blacklisted = false, license = false, taxCertificate = false;
        int fieldCount = 0;

        for (String line : lines) {
            line = line.trim();
            if (line.isEmpty()) {
                if (fieldCount == 8) {
                    vendors.add(new Vendor(name, capital, revenue, debt, experience, blacklisted, license, taxCertificate));
                }
                fieldCount = 0;
                continue;
            }

            String[] parts = line.split(":");
            if (parts.length < 2) continue;

            String key = parts[0].trim();
            String value = parts[1].trim();

            switch (key) {
                case "Name": name = value; fieldCount++; break;
                case "Capital": capital = Integer.parseInt(value); fieldCount++; break;
                case "Revenue": revenue = Integer.parseInt(value); fieldCount++; break;
                case "Debt": debt = Integer.parseInt(value); fieldCount++; break;
                case "Experience": experience = Integer.parseInt(value); fieldCount++; break;
                case "Blacklisted": blacklisted = Boolean.parseBoolean(value); fieldCount++; break;
                case "License": license = Boolean.parseBoolean(value); fieldCount++; break;
                case "TaxCertificate": taxCertificate = Boolean.parseBoolean(value); fieldCount++; break;
            }
        }

        if (fieldCount == 8) {
            vendors.add(new Vendor(name, capital, revenue, debt, experience, blacklisted, license, taxCertificate));
        }

        return vendors;
    }

}
