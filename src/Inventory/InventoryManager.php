<?php

namespace Mirrorps\LaravelTaler\Inventory;

use Mirrorps\LaravelTaler\Contracts\CreatesTalerClients;
use Taler\Api\Inventory\Dto\CategoryCreateRequest;
use Taler\Api\Inventory\Dto\CategoryCreatedResponse;
use Taler\Api\Inventory\Dto\CategoryListResponse;
use Taler\Api\Inventory\Dto\CategoryProductList;
use Taler\Api\Inventory\Dto\FullInventoryDetailsResponse;
use Taler\Api\Inventory\Dto\GetProductsRequest;
use Taler\Api\Inventory\Dto\InventorySummaryResponse;
use Taler\Api\Inventory\Dto\LockRequest;
use Taler\Api\Inventory\Dto\ProductAddDetail;
use Taler\Api\Inventory\Dto\ProductDetail;
use Taler\Api\Inventory\Dto\ProductPatchDetail;
use Taler\Api\Inventory\InventoryClient;
use Taler\Taler as SdkTaler;

class InventoryManager
{
    protected ?SdkTaler $client = null;

    public function __construct(protected CreatesTalerClients $factory)
    {
    }

    public function client(): SdkTaler
    {
        return $this->client ??= $this->factory->make();
    }

    public function api(): InventoryClient
    {
        return $this->client()->inventory();
    }

    /**
     * @param array<string, string> $headers
     * @return CategoryListResponse|array<string, mixed>
     *
     * @throws \Taler\Exception\TalerException
     * @throws \Throwable
     */
    public function getCategories(array $headers = []): CategoryListResponse|array
    {
        return $this->api()->getCategories($headers);
    }

    /**
     * @param array<string, string> $headers
     *
     * @throws \Taler\Exception\TalerException
     * @throws \Throwable
     */
    public function getCategoriesAsync(array $headers = []): mixed
    {
        return $this->api()->getCategoriesAsync($headers);
    }

    /**
     * @param array<string, string> $headers
     * @return CategoryProductList|array<string, mixed>
     *
     * @throws \Taler\Exception\TalerException
     * @throws \Throwable
     */
    public function getCategory(int $categoryId, array $headers = []): CategoryProductList|array
    {
        return $this->api()->getCategory($categoryId, $headers);
    }

    /**
     * @param array<string, string> $headers
     *
     * @throws \Taler\Exception\TalerException
     * @throws \Throwable
     */
    public function getCategoryAsync(int $categoryId, array $headers = []): mixed
    {
        return $this->api()->getCategoryAsync($categoryId, $headers);
    }

    /**
     * @param array<string, string> $headers
     * @return CategoryCreatedResponse|array<string, mixed>
     *
     * @throws \Taler\Exception\TalerException
     * @throws \Throwable
     */
    public function createCategory(CategoryCreateRequest $request, array $headers = []): CategoryCreatedResponse|array
    {
        return $this->api()->createCategory($request, $headers);
    }

    /**
     * @param array<string, string> $headers
     *
     * @throws \Taler\Exception\TalerException
     * @throws \Throwable
     */
    public function createCategoryAsync(CategoryCreateRequest $request, array $headers = []): mixed
    {
        return $this->api()->createCategoryAsync($request, $headers);
    }

    /**
     * @param array<string, string> $headers
     *
     * @throws \Taler\Exception\TalerException
     * @throws \Throwable
     */
    public function updateCategory(int $categoryId, CategoryCreateRequest $request, array $headers = []): void
    {
        $this->api()->updateCategory($categoryId, $request, $headers);
    }

    /**
     * @param array<string, string> $headers
     *
     * @throws \Taler\Exception\TalerException
     * @throws \Throwable
     */
    public function updateCategoryAsync(int $categoryId, CategoryCreateRequest $request, array $headers = []): mixed
    {
        return $this->api()->updateCategoryAsync($categoryId, $request, $headers);
    }

    /**
     * @param array<string, string> $headers
     *
     * @throws \Taler\Exception\TalerException
     * @throws \Throwable
     */
    public function deleteCategory(int $categoryId, array $headers = []): void
    {
        $this->api()->deleteCategory($categoryId, $headers);
    }

    /**
     * @param array<string, string> $headers
     *
     * @throws \Taler\Exception\TalerException
     * @throws \Throwable
     */
    public function deleteCategoryAsync(int $categoryId, array $headers = []): mixed
    {
        return $this->api()->deleteCategoryAsync($categoryId, $headers);
    }

    /**
     * @param array<string, string> $headers
     *
     * @throws \Taler\Exception\TalerException
     * @throws \Throwable
     */
    public function createProduct(ProductAddDetail $details, array $headers = []): void
    {
        $this->api()->createProduct($details, $headers);
    }

    /**
     * @param array<string, string> $headers
     *
     * @throws \Taler\Exception\TalerException
     * @throws \Throwable
     */
    public function createProductAsync(ProductAddDetail $details, array $headers = []): mixed
    {
        return $this->api()->createProductAsync($details, $headers);
    }

    /**
     * @param array<string, string> $headers
     *
     * @throws \Taler\Exception\TalerException
     * @throws \Throwable
     */
    public function updateProduct(string $productId, ProductPatchDetail $details, array $headers = []): void
    {
        $this->api()->updateProduct($productId, $details, $headers);
    }

    /**
     * @param array<string, string> $headers
     *
     * @throws \Taler\Exception\TalerException
     * @throws \Throwable
     */
    public function updateProductAsync(string $productId, ProductPatchDetail $details, array $headers = []): mixed
    {
        return $this->api()->updateProductAsync($productId, $details, $headers);
    }

    /**
     * @param array<string, string> $headers
     * @return InventorySummaryResponse|array<string, mixed>
     *
     * @throws \Taler\Exception\TalerException
     * @throws \Throwable
     */
    public function getProducts(?GetProductsRequest $request = null, array $headers = []): InventorySummaryResponse|array
    {
        return $this->api()->getProducts($request, $headers);
    }

    /**
     * @param array<string, string> $headers
     *
     * @throws \Taler\Exception\TalerException
     * @throws \Throwable
     */
    public function getProductsAsync(?GetProductsRequest $request = null, array $headers = []): mixed
    {
        return $this->api()->getProductsAsync($request, $headers);
    }

    /**
     * @param array<string, string> $headers
     * @return ProductDetail|array<string, mixed>
     *
     * @throws \Taler\Exception\TalerException
     * @throws \Throwable
     */
    public function getProduct(string $productId, array $headers = []): ProductDetail|array
    {
        return $this->api()->getProduct($productId, $headers);
    }

    /**
     * @param array<string, string> $headers
     *
     * @throws \Taler\Exception\TalerException
     * @throws \Throwable
     */
    public function getProductAsync(string $productId, array $headers = []): mixed
    {
        return $this->api()->getProductAsync($productId, $headers);
    }

    /**
     * @param array<string, string> $headers
     *
     * @throws \Taler\Exception\TalerException
     * @throws \Throwable
     */
    public function deleteProduct(string $productId, array $headers = []): void
    {
        $this->api()->deleteProduct($productId, $headers);
    }

    /**
     * @param array<string, string> $headers
     *
     * @throws \Taler\Exception\TalerException
     * @throws \Throwable
     */
    public function deleteProductAsync(string $productId, array $headers = []): mixed
    {
        return $this->api()->deleteProductAsync($productId, $headers);
    }

    /**
     * @param array<string, string> $headers
     * @return FullInventoryDetailsResponse|array<string, mixed>
     *
     * @throws \Taler\Exception\TalerException
     * @throws \Throwable
     */
    public function getPos(array $headers = []): FullInventoryDetailsResponse|array
    {
        return $this->api()->getPos($headers);
    }

    /**
     * @param array<string, string> $headers
     *
     * @throws \Taler\Exception\TalerException
     * @throws \Throwable
     */
    public function getPosAsync(array $headers = []): mixed
    {
        return $this->api()->getPosAsync($headers);
    }

    /**
     * @param array<string, string> $headers
     *
     * @throws \Taler\Exception\TalerException
     * @throws \Throwable
     */
    public function lockProduct(string $productId, LockRequest $request, array $headers = []): void
    {
        $this->api()->lockProduct($productId, $request, $headers);
    }

    /**
     * @param array<string, string> $headers
     *
     * @throws \Taler\Exception\TalerException
     * @throws \Throwable
     */
    public function lockProductAsync(string $productId, LockRequest $request, array $headers = []): mixed
    {
        return $this->api()->lockProductAsync($productId, $request, $headers);
    }
}
