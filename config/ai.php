<?php

return [

    /*
    |--------------------------------------------------------------------------
    | AI Driver
    |--------------------------------------------------------------------------
    |
    | This option controls the default AI provider used for AI features.
    | Supported: "development", "openai", "gemini", "ollama"
    |
    */

    'driver' => env('AI_DRIVER', 'development'),

    /*
    |--------------------------------------------------------------------------
    | OpenAI Configuration
    |--------------------------------------------------------------------------
    */

    'openai' => [
        'api_key' => env('OPENAI_API_KEY'),
        'model' => env('OPENAI_MODEL', 'gpt-4o-mini'),
        'max_tokens' => env('OPENAI_MAX_TOKENS', 1000),
        'temperature' => env('OPENAI_TEMPERATURE', 0.7),
    ],

    /*
    |--------------------------------------------------------------------------
    | Gemini Configuration
    |--------------------------------------------------------------------------
    */

    'gemini' => [
        'api_key' => env('GEMINI_API_KEY'),
        'model' => env('GEMINI_MODEL', 'gemini-2.0-flash'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Ollama Configuration
    |--------------------------------------------------------------------------
    */

    'ollama' => [
        'url' => env('OLLAMA_URL', 'http://localhost:11434'),
        'model' => env('OLLAMA_MODEL', 'llama3'),
    ],

    /*
    |--------------------------------------------------------------------------
    | AI Features
    |--------------------------------------------------------------------------
    */

    'features' => [
        'chat' => env('AI_FEATURE_CHAT', true),
        'recommendations' => env('AI_FEATURE_RECOMMENDATIONS', true),
        'search' => env('AI_FEATURE_SEARCH', true),
        'seller_assistant' => env('AI_FEATURE_SELLER_ASSISTANT', true),
        'admin_insights' => env('AI_FEATURE_ADMIN_INSIGHTS', true),
    ],

    /*
    |--------------------------------------------------------------------------
    | Rate Limiting
    |--------------------------------------------------------------------------
    */

    'rate_limit' => [
        'per_minute' => env('AI_RATE_LIMIT', 30),
        'per_hour' => env('AI_RATE_LIMIT_HOUR', 200),
    ],

    /*
    |--------------------------------------------------------------------------
    | Cache TTL (seconds)
    |--------------------------------------------------------------------------
    */

    'cache_ttl' => [
        'trending' => 300,
        'popular' => 600,
        'recommendations' => 900,
    ],

];
