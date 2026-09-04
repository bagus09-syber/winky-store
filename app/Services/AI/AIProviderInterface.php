<?php

namespace App\Services\AI;

interface AIProviderInterface
{
    public function chat(string $message, array $context = []): array;

    public function analyzeProduct(array $productData): array;

    public function generateProductContent(array $keywords, string $type = 'description'): string;

    public function recommendProducts(array $userContext, int $limit = 10): array;

    public function analyzeSellerInsights(array $sellerData): array;

    public function parseSearchIntent(string $query): array;
}
