<?php

namespace Modules\FoodCatalog\Tests\Feature;

use Modules\FoodCatalog\Entities\Category;
use Modules\Restaurants\Entities\Food;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CategoryAttachingTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Прикрепление категории к блюду/напитку
     */
    public function testAttach()
    {
        /** @var Category $category */
        $category = factory(Category::class)->create();
        /** @var Food $food */
        $food = factory(Food::class)->create();

        $this->assertFalse($food->categories->contains($category));
        $this->assertEquals(0, $food->categories()->count());

        $response = $this->postJson("/api/food/{$food->id}/categories/attach/{$category->id}");

        $response
            ->assertStatus(200)
            ->assertJson([
                'message' => __('foodcatalog::category.attached'),
            ]);

        $this->assertTrue($food->categories->contains($category));
        $this->assertEquals(1, $food->categories()->count());
    }

    /**
     * Открепление категории к блюду/напитку
     */
    public function testDetach()
    {
        /** @var Category $category */
        $category = factory(Category::class)->create();
        /** @var Food $food */
        $food = factory(Food::class)->create();

        $food->categories()->attach($category);

        $this->assertTrue($food->categories->contains($category));
        $this->assertEquals(1, $food->categories()->count());

        $response = $this->postJson("/api/food/{$food->id}/categories/detach/{$category->id}");

        $response
            ->assertStatus(200)
            ->assertJson([
                'message' => __('foodcatalog::category.detached'),
            ]);

        $this->assertFalse($food->categories->contains($category));
        $this->assertEquals(0, $food->categories()->count());
    }

    /**
     * Смена категории у блюда/напитка
     */
    public function testChange()
    {
        /** @var Category $category1 */
        $category1 = factory(Category::class)->create();
        /** @var Category $category2 */
        $category2 = factory(Category::class)->create();
        /** @var Food $food */
        $food = factory(Food::class)->create();

        $food->categories()->attach($category1);

        $this->assertTrue($food->categories->contains($category1));
        $this->assertFalse($food->categories->contains($category2));
        $this->assertEquals(1, $food->categories()->count());

        $response = $this->postJson("/api/food/{$food->id}/categories/attach/{$category2->id}");

        $response
            ->assertStatus(200)
            ->assertJson([
                'message' => __('foodcatalog::category.attached'),
            ]);

        $this->assertEquals(1, $food->categories()->count());
        $this->assertFalse($food->categories->contains($category1));
        $this->assertTrue($food->categories->contains($category2));
    }
}
