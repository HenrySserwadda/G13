package com.vendor.validator.Util;

import com.vendor.validator.Model.Vendor;
import org.apache.pdfbox.Loader;
import org.apache.pdfbox.pdmodel.PDDocument;
import org.apache.pdfbox.pdmodel.interactive.form.PDAcroForm;
import org.apache.pdfbox.pdmodel.interactive.form.PDField;

import java.io.File;
import java.util.ArrayList;
import java.util.HashMap;
import java.util.List;
import java.util.Map;

public class VendorParser {

    public static List<Vendor> parse(File pdfFile) throws Exception {
        Map<String, String> fields = extractFields(pdfFile);

        Vendor vendor = new Vendor(
                fields.get("name"),
                Integer.parseInt(fields.get("capital")),
                Integer.parseInt(fields.get("revenue")),
                Integer.parseInt(fields.get("debt")),
                Integer.parseInt(fields.get("experience")),
                Boolean.parseBoolean(fields.get("blacklisted")),
                Boolean.parseBoolean(fields.get("license")),
                Boolean.parseBoolean(fields.get("taxCertificate"))
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
                fieldValues.put(field.getFullyQualifiedName(), field.getValueAsString());
            }
        }

        return fieldValues;
    }
}
