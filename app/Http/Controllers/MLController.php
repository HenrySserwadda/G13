<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MLController extends Controller
{
    public function salesAnalytics()
    {
        try {
            $script = config('ml.scripts_path') . '/sales_analysis.py';
            $command = escapeshellcmd(config('ml.python_path') . " $script");
            $output = shell_exec($command);
            
            // Check if images were generated
            $images = [
                'monthly_sales' => asset('images/sales_by_month.png'),
                'material_sales' => asset('images/sales_by_material.png'),
                'gender_sales' => asset('images/sales_by_gender.png'),
            ];
            
            // Check if images actually exist
            foreach ($images as $key => $image) {
                $imagePath = public_path(str_replace(asset(''), '', $image));
                if (!file_exists($imagePath)) {
                    // If image doesn't exist, use placeholder
                    $images[$key] = 'https://via.placeholder.com/400x300?text=Chart+Not+Available';
                }
            }
            
        } catch (\Exception $e) {
            // Fallback if Python script fails
            $images = [
                'monthly_sales' => 'https://via.placeholder.com/400x300?text=Monthly+Sales+Chart',
                'material_sales' => 'https://via.placeholder.com/400x300?text=Material+Sales+Chart',
                'gender_sales' => 'https://via.placeholder.com/400x300?text=Gender+Sales+Chart',
            ];
        }
        
        return view('ml.dashboard', compact('images'));
    }
    
    public function getRecommendations($productId)
    {
        $script = config('ml.scripts_path') . '/recommendations.py';
        $command = escapeshellcmd(config('ml.python_path') . " $script $productId");
        $output = shell_exec($command);
        
        $recommendations = json_decode($output, true);
        
        return view('ml.recommendations', compact('recommendations'));
    }
    
    public function trainModels()
    {
        $scripts = [
            'data_processing.py',
            'recommendations.py'
        ];
        
        $results = [];
        
        foreach ($scripts as $script) {
            $command = escapeshellcmd(config('ml.python_path') . " " . config('ml.scripts_path') . "/$script");
            $results[$script] = shell_exec($command);
        }
        
        return response()->json(['status' => 'success', 'results' => $results]);
    }

    public function customChart(Request $request)
    {
        $chartType = $request->input('chartType');
        $xAxis = $request->input('xAxis');
        $xAxis2 = $request->input('xAxis2');
        $yAxis = $request->input('yAxis');

        $script = config('ml.scripts_path') . '/custom_chart.py';
        $python = config('ml.python_path');
        $outputImage = public_path('images/custom_chart.png');

        \Log::info('customChart python_path: ' . $python);
        $cmd = "$python $script --chart_type \"$chartType\" --x_axis \"$xAxis\" --y_axis \"$yAxis\" --output \"$outputImage\"";
        if (!empty($xAxis2)) {
            $cmd .= " --x_axis2 \"$xAxis2\"";
        }
        \Log::info('customChart full command: ' . $cmd);
        $output = shell_exec($cmd . ' 2>&1');
        \Log::info('Custom chart output: ' . $output);

        $imageUrl = asset('images/custom_chart.png');
        return response()->json(['image' => $imageUrl]);
    }
}