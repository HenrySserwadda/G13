<?php

return [
    'scripts_path' => base_path('ml-scripts'),
    'python_path' => env('PYTHON_PATH', 'python'),
    'models_path' => storage_path('app/ml-models'),
];