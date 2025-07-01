package com.example.vendorapp;

import org.springframework.boot.SpringApplication;
import org.springframework.boot.autoconfigure.SpringBootApplication;
import org.springframework.scheduling.annotation.EnableScheduling;

@SpringBootApplication
@EnableScheduling
public class VendorAppApplication {
    public static void main(String[] args) {
        SpringApplication.run(VendorAppApplication.class, args);
    }
}