<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Services\MLProductService;
use App\Services\UserMLService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class MLController extends Controller
{
    protected $mlProductService;
    protected $userMLService;

    public function __construct(MLProductService $mlProductService, UserMLService $userMLService)
    {
        $this->mlProductService = $mlProductService;
        $this->userMLService = $userMLService;
    }

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

        // Add dynamic chart data
        $python = config('ml.python_path', 'python');
        $customScript = config('ml.scripts_path', base_path('ml-scripts')) . '/custom_chart.py';
        $output = [];
        $return_var = 0;
        $cmd = "$python $customScript --chart_type bar --x_axis Month --y_axis Sales --json";
        exec($cmd, $output, $return_var);
        $chartData = json_decode(implode('', $output), true);
        if (!$chartData || !isset($chartData['type'])) {
            $chartData = [
                'type' => 'bar',
                'labels' => [],
                'values' => []
            ];
        }
        
        return view('ml.dashboard', compact('images', 'chartData'));
    }
    
    public function getRecommendations($productId)
    {
        $script = config('ml.scripts_path') . '/recommendations.py';
        $command = escapeshellcmd(config('ml.python_path') . " $script $productId");
        $output = shell_exec($command);
        
        $recommendations = json_decode($output, true);
        
        return view('ml.recommendations', compact('recommendations'));
    }

    /**
     * Get personalized recommendations for the authenticated user
     */
    public function getPersonalizedRecommendations(Request $request)
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['error' => 'User not authenticated'], 401);
        }

        try {
            // Get user preferences
            $preferences = $this->userMLService->analyzeUserPreferences($user);
            
            // Call Python script with user preferences
            $python = config('ml.python_path', 'python');
            $script = config('ml.scripts_path', base_path('ml-scripts')) . '/recommendations.py';
            
            $args = '';
            if ($preferences['gender'] && $preferences['gender'] !== 'null') {
                $args .= ' --gender "' . escapeshellarg($preferences['gender']) . '"';
            }
            if (!empty($preferences['preferred_styles'])) {
                $args .= ' --styles "' . escapeshellarg(implode(',', $preferences['preferred_styles'])) . '"';
            }
            if (!empty($preferences['preferred_colors'])) {
                $args .= ' --colors "' . escapeshellarg(implode(',', $preferences['preferred_colors'])) . '"';
            }
            if (!empty($preferences['preferred_materials'])) {
                $args .= ' --materials "' . escapeshellarg(implode(',', $preferences['preferred_materials'])) . '"';
            }
            if (
                isset($preferences['price_range']) &&
                isset($preferences['price_range']['avg']) &&
                $preferences['price_range']['avg'] > 0
            ) {
                $priceRange = json_encode($preferences['price_range']);
                $args .= ' --price_range "' . escapeshellarg($priceRange) . '"';
            }
            
            $limit = $request->input('limit', 10);
            $args .= ' --limit ' . $limit;
            
            // Call the Python script with --get_personalized and user preferences
            $cmd = "$python $script --get_personalized$args";
            $output = [];
            $return_var = 0;
            exec($cmd, $output, $return_var);
            
            $jsonLine = null;
            foreach ($output as $line) {
                if (strpos(trim($line), '[') === 0 || strpos(trim($line), '{') === 0) {
                    $jsonLine = $line;
                    break;
                }
            }
            
            $mlProducts = $jsonLine ? json_decode($jsonLine, true) : [];
            
            // If we got ML products, create/update them in the database
            if (!empty($mlProducts)) {
                $databaseProducts = $this->mlProductService->createOrUpdateMLProducts($mlProducts);
                
                // Convert database products to the format expected by the frontend
                $products = array_map(function($product) {
                    return [
                        'id' => $product['id'],
                        'name' => $product['name'],
                        'description' => $product['description'],
                        'price' => $product['price'],
                        'quantity' => $product['quantity'],
                        'image' => $product['image'],
                        'style' => $product['ml_style'],
                        'color' => $product['ml_color'],
                        'gender' => $product['ml_gender'],
                        'is_ml_generated' => true,
                        'is_personalized' => true
                    ];
                }, $databaseProducts);
            } else {
                // Fallback: try to get personalized products from database
                $products = $this->userMLService->getPersonalizedRecommendations($user, $limit);
                // Ensure is_personalized is set for all fallback products
                $products = array_map(function($product) {
                    $product['is_personalized'] = true;
                    return $product;
                }, $products);
            }
            
            return response()->json([
                'products' => $products,
                'user_preferences' => $preferences,
                'is_personalized' => true
            ]);
            
        } catch (\Exception $e) {
            Log::error("Error getting personalized recommendations: " . $e->getMessage());
            return response()->json(['error' => 'Failed to get personalized recommendations'], 500);
        }
    }

    /**
     * Get user's purchase history and preferences summary
     */
    public function getUserProfile(Request $request)
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['error' => 'User not authenticated'], 401);
        }

        try {
            $preferences = $this->userMLService->analyzeUserPreferences($user) ?? [];
            $purchaseSummary = $this->userMLService->getUserPurchaseSummary($user) ?? [];

            // Provide defaults if missing
            $preferences = array_merge([
                'purchase_frequency' => 0,
                'gender' => null,
                'preferred_styles' => [],
                'preferred_colors' => [],
                'preferred_materials' => [],
                'price_range' => ['min' => 0, 'max' => 0, 'avg' => 0],
            ], $preferences);

            $purchaseSummary = array_merge([
                'total_spent' => 0,
                'average_order_value' => 0,
            ], $purchaseSummary);

            return response()->json([
                'preferences' => $preferences,
                'purchase_summary' => $purchaseSummary
            ]);
            
        } catch (\Exception $e) {
            Log::error("Error getting user profile: " . $e->getMessage());
            return response()->json(['error' => 'Failed to get user profile'], 500);
        }
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

        $script = config('ml.scripts_path', base_path('ml-scripts')) . '/custom_chart.py';
        $python = config('ml.python_path', 'python');
        $output = [];
        $return_var = 0;

        $cmd = "$python $script --chart_type \"$chartType\" --x_axis \"$xAxis\" --y_axis \"$yAxis\" --json";
        if (!empty($xAxis2)) {
            $cmd .= " --x_axis2 \"$xAxis2\"";
        }
        exec($cmd, $output, $return_var);
        Log::info('Custom chart raw output:', $output);
        // Only decode the JSON line from the output
        $jsonLine = null;
        foreach ($output as $line) {
            if (strpos(trim($line), '{') === 0) {
                $jsonLine = $line;
                break;
            }
        }
        $chartData = $jsonLine ? json_decode($jsonLine, true) : null;

        // If error, pass it through
        if (!$chartData || isset($chartData['error'])) {
            return response()->json(['chartData' => null, 'error' => $chartData['error'] ?? 'No chart data returned from Python script.']);
        }

        return response()->json(['chartData' => $chartData]);
    }

    public function dashboard()
    {
        $python = config('ml.python_path', 'python');
        $script = config('ml.scripts_path', base_path('ml-scripts')) . '/custom_chart.py';
        $output = [];
        $return_var = 0;

        // Example: bar chart, x_axis=Month, y_axis=Sales (customize as needed)
        $cmd = "$python $script --chart_type bar --x_axis Month --y_axis Sales --json";
        exec($cmd, $output, $return_var);

        $chartData = json_decode(implode('', $output), true);
        if (!$chartData || !isset($chartData['type'])) {
            $chartData = [
                'type' => 'bar',
                'labels' => [],
                'values' => []
            ];
        }

        // Always provide $images (use placeholders if not set)
        $images = [
            'monthly_sales' => asset('images/sales_by_month.png'),
            'material_sales' => asset('images/sales_by_material.png'),
            'gender_sales' => asset('images/sales_by_gender.png'),
        ];
        foreach ($images as $key => $image) {
            $imagePath = public_path(str_replace(asset(''), '', $image));
            if (!file_exists($imagePath)) {
                $images[$key] = 'https://via.placeholder.com/400x300?text=Chart+Not+Available';
            }
        }

        return view('ml.dashboard', compact('images', 'chartData'));
    }

    public function predictSales(Request $request)
    {
        $monthsAhead = $request->input('months_ahead', 1);
        $python = config('ml.python_path', 'python');
        $script = base_path('ml-scripts/sales_forecast.py');
        $cmd = "$python $script --months_ahead $monthsAhead";

        $filters = [
            'material' => 'material',
            'size' => 'size',
            'compartments' => 'compartments',
            'laptop_compartment' => 'laptop_compartment',
            'waterproof' => 'waterproof',
            'style' => 'style',
            'color' => 'color',
            'month' => 'month',
            'gender' => 'gender',
        ];
        foreach ($filters as $param => $arg) {
            $value = $request->input($param);
            if ($value !== null && $value !== '') {
                $cmd .= " --$arg \"$value\"";
            }
        }

        $cmd .= " --json";
        $output = [];
        $return_var = 0;
        exec($cmd, $output, $return_var);
        // Find the first line that looks like JSON and decode it
        $jsonLine = null;
        foreach ($output as $line) {
            if (strpos(trim($line), '{') === 0) {
                $jsonLine = $line;
                break;
            }
        }
        $data = $jsonLine ? json_decode($jsonLine, true) : null;
        return response()->json($data);
    }

    /**
     * Serve product data for ML recommendations, filtered by user preferences.
     * GET /api/products-for-ml?gender=male&styles=Puffer,Classic&colors=Black,Blue
     */
    public function productsForML(Request $request)
    {
        $python = config('ml.python_path', 'python');
        $script = config('ml.scripts_path', base_path('ml-scripts')) . '/recommendations.py';
        $gender = $request->query('gender');
        $styles = $request->query('styles'); // comma-separated
        $colors = $request->query('colors'); // comma-separated

        $args = '';
        if ($gender) {
            $args .= ' --gender "' . escapeshellarg($gender) . '"';
        }
        if ($styles) {
            $args .= ' --styles "' . escapeshellarg($styles) . '"';
        }
        if ($colors) {
            $args .= ' --colors "' . escapeshellarg($colors) . '"';
        }
        
        // Call the Python script with --get_products and filters
        $cmd = "$python $script --get_products$args";
        $output = [];
        $return_var = 0;
        exec($cmd, $output, $return_var);
        
        $jsonLine = null;
        foreach ($output as $line) {
            if (strpos(trim($line), '[') === 0 || strpos(trim($line), '{') === 0) {
                $jsonLine = $line;
                break;
            }
        }
        
        $mlProducts = $jsonLine ? json_decode($jsonLine, true) : [];
        
        // If we got ML products, create/update them in the database
        if (!empty($mlProducts)) {
            $databaseProducts = $this->mlProductService->createOrUpdateMLProducts($mlProducts);
            
            // Convert database products to the format expected by the frontend
            $products = array_map(function($product) {
                return [
                    'id' => $product['id'],
                    'name' => $product['name'],
                    'description' => $product['description'],
                    'price' => $product['price'],
                    'quantity' => $product['quantity'],
                    'image' => $product['image'],
                    'style' => $product['ml_style'],
                    'color' => $product['ml_color'],
                    'gender' => $product['ml_gender'],
                    'is_ml_generated' => true,
                ];
            }, $databaseProducts);
        } else {
            // Fallback: try to get existing ML products from database
            $products = $this->mlProductService->getMLProducts($gender, $styles, $colors);
        }
        
        return response()->json(['products' => $products]);
    }
}