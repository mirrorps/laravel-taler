<?php

namespace Mirrorps\LaravelTaler\Tests\Unit;

use Mirrorps\LaravelTaler\Inventory\InventoryManager;
use Mirrorps\LaravelTaler\Tests\Fakes\FakeInventoryClientFactory;
use Mirrorps\LaravelTaler\Tests\TestCase;
use stdClass;
use Taler\Api\Dto\RelativeTime;
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

class InventoryManagerTest extends TestCase
{
    public function test_it_proxies_category_calls_to_the_sdk_inventory_client(): void
    {
        $headers = ['X-Test' => 'inventory'];
        $list = new CategoryListResponse(categories: []);
        $asyncList = new stdClass();
        $categoryId = 42;
        $categoryView = new CategoryProductList(name: 'Cat', name_i18n: null, products: []);
        $asyncCategory = new stdClass();
        $createRequest = new CategoryCreateRequest(name: 'Beverages');
        $created = new CategoryCreatedResponse(category_id: 7);
        $asyncCreated = new stdClass();
        $asyncVoid = new stdClass();

        $inventoryClient = $this->createMock(InventoryClient::class);
        $inventoryClient->expects($this->once())
            ->method('getCategories')
            ->with($headers)
            ->willReturn($list);
        $inventoryClient->expects($this->once())
            ->method('getCategoriesAsync')
            ->with($headers)
            ->willReturn($asyncList);
        $inventoryClient->expects($this->once())
            ->method('getCategory')
            ->with($categoryId, $headers)
            ->willReturn($categoryView);
        $inventoryClient->expects($this->once())
            ->method('getCategoryAsync')
            ->with($categoryId, $headers)
            ->willReturn($asyncCategory);
        $inventoryClient->expects($this->once())
            ->method('createCategory')
            ->with($createRequest, $headers)
            ->willReturn($created);
        $inventoryClient->expects($this->once())
            ->method('createCategoryAsync')
            ->with($createRequest, $headers)
            ->willReturn($asyncCreated);
        $inventoryClient->expects($this->once())
            ->method('updateCategory')
            ->with($categoryId, $createRequest, $headers);
        $inventoryClient->expects($this->once())
            ->method('updateCategoryAsync')
            ->with($categoryId, $createRequest, $headers)
            ->willReturn($asyncVoid);
        $inventoryClient->expects($this->once())
            ->method('deleteCategory')
            ->with($categoryId, $headers);
        $inventoryClient->expects($this->once())
            ->method('deleteCategoryAsync')
            ->with($categoryId, $headers)
            ->willReturn($asyncVoid);

        $sdk = $this->createMock(SdkTaler::class);
        $sdk->expects($this->exactly(10))
            ->method('inventory')
            ->willReturn($inventoryClient);

        $factory = new FakeInventoryClientFactory($sdk);
        $manager = new InventoryManager($factory);

        $this->assertSame($list, $manager->getCategories($headers));
        $this->assertSame($asyncList, $manager->getCategoriesAsync($headers));
        $this->assertSame($categoryView, $manager->getCategory($categoryId, $headers));
        $this->assertSame($asyncCategory, $manager->getCategoryAsync($categoryId, $headers));
        $this->assertSame($created, $manager->createCategory($createRequest, $headers));
        $this->assertSame($asyncCreated, $manager->createCategoryAsync($createRequest, $headers));
        $manager->updateCategory($categoryId, $createRequest, $headers);
        $this->assertSame($asyncVoid, $manager->updateCategoryAsync($categoryId, $createRequest, $headers));
        $manager->deleteCategory($categoryId, $headers);
        $this->assertSame($asyncVoid, $manager->deleteCategoryAsync($categoryId, $headers));
        $this->assertSame(1, $factory->makeCalls);
    }

    public function test_it_proxies_product_and_pos_calls_to_the_sdk_inventory_client(): void
    {
        $headers = ['X-Test' => 'products'];
        $getProductsRequest = new GetProductsRequest(limit: 10, offset: 0);
        $summary = new InventorySummaryResponse(products: []);
        $asyncSummary = new stdClass();
        $productId = 'product-abc';
        $detail = new ProductDetail(
            product_name: 'Item',
            description: 'Desc',
            description_i18n: [],
            unit: 'unit',
            categories: [],
            price: 'EUR:1',
            image: '',
            taxes: null,
            total_stock: 0,
            total_sold: 0,
            total_lost: 0,
            address: null,
            next_restock: null,
            minimum_age: null,
        );
        $asyncDetail = new stdClass();
        $asyncVoid = new stdClass();
        $add = new ProductAddDetail(
            product_id: $productId,
            description: 'Coffee',
            unit: 'cup',
            price: 'EUR:2.50',
            total_stock: 100,
        );
        $patch = new ProductPatchDetail(
            description: 'Coffee',
            unit: 'cup',
            price: 'EUR:2.50',
            total_stock: 99,
        );
        $pos = new FullInventoryDetailsResponse(products: [], categories: []);
        $asyncPos = new stdClass();

        $inventoryClient = $this->createMock(InventoryClient::class);
        $inventoryClient->expects($this->once())
            ->method('getProducts')
            ->with($getProductsRequest, $headers)
            ->willReturn($summary);
        $inventoryClient->expects($this->once())
            ->method('getProductsAsync')
            ->with(null, $headers)
            ->willReturn($asyncSummary);
        $inventoryClient->expects($this->once())
            ->method('getProduct')
            ->with($productId, $headers)
            ->willReturn($detail);
        $inventoryClient->expects($this->once())
            ->method('getProductAsync')
            ->with($productId, $headers)
            ->willReturn($asyncDetail);
        $inventoryClient->expects($this->once())
            ->method('createProduct')
            ->with($add, $headers);
        $inventoryClient->expects($this->once())
            ->method('createProductAsync')
            ->with($add, $headers)
            ->willReturn($asyncVoid);
        $inventoryClient->expects($this->once())
            ->method('updateProduct')
            ->with($productId, $patch, $headers);
        $inventoryClient->expects($this->once())
            ->method('updateProductAsync')
            ->with($productId, $patch, $headers)
            ->willReturn($asyncVoid);
        $inventoryClient->expects($this->once())
            ->method('deleteProduct')
            ->with($productId, $headers);
        $inventoryClient->expects($this->once())
            ->method('deleteProductAsync')
            ->with($productId, $headers)
            ->willReturn($asyncVoid);
        $inventoryClient->expects($this->once())
            ->method('getPos')
            ->with($headers)
            ->willReturn($pos);
        $inventoryClient->expects($this->once())
            ->method('getPosAsync')
            ->with($headers)
            ->willReturn($asyncPos);

        $sdk = $this->createMock(SdkTaler::class);
        $sdk->expects($this->exactly(12))
            ->method('inventory')
            ->willReturn($inventoryClient);

        $factory = new FakeInventoryClientFactory($sdk);
        $manager = new InventoryManager($factory);

        $this->assertSame($summary, $manager->getProducts($getProductsRequest, $headers));
        $this->assertSame($asyncSummary, $manager->getProductsAsync(null, $headers));
        $this->assertSame($detail, $manager->getProduct($productId, $headers));
        $this->assertSame($asyncDetail, $manager->getProductAsync($productId, $headers));
        $manager->createProduct($add, $headers);
        $this->assertSame($asyncVoid, $manager->createProductAsync($add, $headers));
        $manager->updateProduct($productId, $patch, $headers);
        $this->assertSame($asyncVoid, $manager->updateProductAsync($productId, $patch, $headers));
        $manager->deleteProduct($productId, $headers);
        $this->assertSame($asyncVoid, $manager->deleteProductAsync($productId, $headers));
        $this->assertSame($pos, $manager->getPos($headers));
        $this->assertSame($asyncPos, $manager->getPosAsync($headers));
        $this->assertSame(1, $factory->makeCalls);
    }

    public function test_it_proxies_lock_product_calls_to_the_sdk_inventory_client(): void
    {
        $headers = ['X-Test' => 'lock'];
        $productId = 'product-xyz';
        $lockRequest = new LockRequest(
            lock_uuid: '550e8400-e29b-41d4-a716-446655440000',
            duration: new RelativeTime(1_000_000),
            quantity: 2,
        );
        $asyncResponse = new stdClass();

        $inventoryClient = $this->createMock(InventoryClient::class);
        $inventoryClient->expects($this->once())
            ->method('lockProduct')
            ->with($productId, $lockRequest, $headers);
        $inventoryClient->expects($this->once())
            ->method('lockProductAsync')
            ->with($productId, $lockRequest, $headers)
            ->willReturn($asyncResponse);

        $sdk = $this->createMock(SdkTaler::class);
        $sdk->expects($this->exactly(2))
            ->method('inventory')
            ->willReturn($inventoryClient);

        $factory = new FakeInventoryClientFactory($sdk);
        $manager = new InventoryManager($factory);

        $manager->lockProduct($productId, $lockRequest, $headers);
        $this->assertSame($asyncResponse, $manager->lockProductAsync($productId, $lockRequest, $headers));
        $this->assertSame(1, $factory->makeCalls);
    }

    public function test_it_reuses_the_same_sdk_client_instance(): void
    {
        $inventoryClient = $this->createMock(InventoryClient::class);
        $inventoryClient->method('getCategories')->willReturn(new CategoryListResponse(categories: []));

        $sdk = $this->createMock(SdkTaler::class);
        $sdk->expects($this->exactly(2))
            ->method('inventory')
            ->willReturn($inventoryClient);

        $factory = new FakeInventoryClientFactory($sdk);
        $manager = new InventoryManager($factory);

        $manager->getCategories();
        $manager->getCategories();
        $this->assertSame(1, $factory->makeCalls);
    }
}
