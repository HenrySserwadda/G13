package com.vendor.validator.Service;

import com.vendor.validator.Model.Vendor;
import org.springframework.stereotype.Service;

import java.time.LocalDate;
import java.util.Random;

@Service
public class VisitSchedulerService {
    public LocalDate generateVisitDate(Vendor vendor) {
        Random rand = new Random();
        int daysAhead = rand.nextInt(7) + 1;
        return LocalDate.now().plusDays(daysAhead);
    }
}
