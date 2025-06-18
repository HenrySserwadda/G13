protected $middlewareGroups = [
    'web' => [
        // ...
        \App\Http\Middleware\ShareUnreadMessagesCount::class,
    ],
];