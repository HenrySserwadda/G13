<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class VendorController extends Controller
{
    public function validateFile(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:pdf|max:2048'
        ]);

        try {
            // Send the file to your Java backend
            $response = Http::attach(
                'file', 
                file_get_contents($request->file('file')),
                $request->file('file')->getClientOriginalName()
            )->post('http://localhost:8080/validate-file');

            $data = $response->json();

            if ($response->successful()) {
                // Handle list of results from Java backend
                return view('vendor.result', [
                    'success' => true,
                    'results' => $data, // Pass the entire results array
                    'error' => null
                ]);
            } else {
                return view('vendor.result', [
                    'success' => false,
                    'results' => [],
                    'error' => $data['error'] ?? 'Validation failed'
                ]);
            }
        } catch (\Exception $e) {
            return view('vendor.result', [
                'success' => false,
                'results' => [],
                'error' => 'Failed to connect to validation service: ' . $e->getMessage()
            ]);
        }
    }
}