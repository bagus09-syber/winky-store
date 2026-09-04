<?php

namespace App\Services\AI;

use App\Services\AI\Providers\DevelopmentAIProvider;
use Illuminate\Support\Facades\Facade;

class AIService
{
    protected AIProviderInterface $provider;

    public function __construct()
    {
        $driver = config('ai.driver', 'development');

        $this->provider = match ($driver) {
            'openai' => $this->createOpenAIProvider(),
            'gemini' => $this->createGeminiProvider(),
            'ollama' => $this->createOllamaProvider(),
            default => new DevelopmentAIProvider(),
        };
    }

    public function chat(string $message, array $context = []): array
    {
        return $this->provider->chat($message, $context);
    }

    public function analyzeProduct(array $productData): array
    {
        return $this->provider->analyzeProduct($productData);
    }

    public function generateProductContent(array $keywords, string $type = 'description'): string
    {
        return $this->provider->generateProductContent($keywords, $type);
    }

    public function recommendProducts(array $userContext, int $limit = 10): array
    {
        return $this->provider->recommendProducts($userContext, $limit);
    }

    public function analyzeSellerInsights(array $sellerData): array
    {
        return $this->provider->analyzeSellerInsights($sellerData);
    }

    public function parseSearchIntent(string $query): array
    {
        return $this->provider->parseSearchIntent($query);
    }

    private function createOpenAIProvider(): AIProviderInterface
    {
        return new DevelopmentAIProvider();
    }

    private function createGeminiProvider(): AIProviderInterface
    {
        return new DevelopmentAIProvider();
    }

    private function createOllamaProvider(): AIProviderInterface
    {
        return new DevelopmentAIProvider();
    }
}
