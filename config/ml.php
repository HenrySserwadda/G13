<?php

return [
    'python_path' => env('PYTHON_PATH', 'python'),
    'scripts_path' => base_path('ml-scripts'),
    'models_path' => storage_path('app/ml-models'),
];