package com.vendor.validator.Util;

import java.io.File;
import java.util.ArrayList;
import java.util.HashMap;
import java.util.List;
import java.util.Map;

import org.apache.pdfbox.Loader;
import org.apache.pdfbox.pdmodel.PDDocument;
import org.apache.pdfbox.pdmodel.interactive.form.PDAcroForm;
import org.apache.pdfbox.pdmodel.interactive.form.PDField;

import com.vendor.validator.Model.Vendor;

public class VendorParser {

    public static List<Vendor> parse(File pdfFile) throws Exception {
        Map<String, String> fields = extractFields(pdfFile);

        Vendor vendor = new Vendor(
                fields.get("name"),
                fields.get("CEO"),
                fields.get("email"),
                Integer.parseInt(fields.get("phone").trim().replace(",", "")),
                Integer.parseInt(fields.get("capital").trim().replace(",", "")),
                Integer.parseInt(fields.get("revenue").trim().replace(",", "")),
                Integer.parseInt(fields.get("debt").trim().replace(",", "")),
                Integer.parseInt(fields.get("experience").trim().replace(",", "")),
                Boolean.parseBoolean(fields.get("industrystandards")),
                fields.get("businessregistrationnumber"), // Changed from license (boolean)
                fields.get("tin") // Changed from taxCertificate (boolean)
        );

        List<Vendor> vendors = new ArrayList<>();
        vendors.add(vendor);
        return vendors;
    }

    private static Map<String, String> extractFields(File pdfFile) throws Exception {
        Map<String, String> fieldValues = new HashMap<>();

        try (PDDocument document = Loader.loadPDF(pdfFile)) {
            PDAcroForm form = document.getDocumentCatalog().getAcroForm();
            if (form == null) {
                throw new Exception("No form fields found in the PDF.");
            }

            for (PDField field : form.getFields()) {
                String key = field.getFullyQualifiedName().toLowerCase(); // make key lowercase
                String value = field.getValueAsString();
                fieldValues.put(key, value);
            }
        }

        return fieldValues;
    }
}