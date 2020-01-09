<?php

namespace Modules\FoodCatalog\Tests\Feature;

use Modules\FoodCatalog\Entities\Tag;
use Modules\Restaurants\Entities\Food;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TagAttachingTest extends TestCase
{
    /**
     * Прикрепление тега к блюду/напитку
     */
    public function testAttach()
    {
        /** @var Tag $tag */
        $tag = factory(Tag::class)->create();
        /** @var Food $food */
        $food = factory(Food::class)->create();

        $this->assertFalse($food->tags->contains($tag));

        $response = $this->postJson("/api/food/{$food->id}/tags/attach/{$tag->id}");

        $response
            ->assertStatus(200)
            ->assertJson([
                'message' => __('foodcatalog::tag.attached'),
            ]);

        $this->assertTrue($food->tags->contains($tag));
    }

    /**
     * Открепление тега к блюду/напитку
     */
    public function testDetach()
    {
        /** @var Tag $tag */
        $tag = factory(Tag::class)->create();
        /** @var Food $food */
        $food = factory(Food::class)->create();

        $food->tags()->attach($tag);

        $this->assertTrue($food->tags->contains($tag));

        $response = $this->postJson("/api/food/{$food->id}/tags/detach/{$tag->id}");

        $response
            ->assertStatus(200)
            ->assertJson([
                'message' => __('foodcatalog::tag.detached'),
            ]);

        $this->assertFalse($food->tags->contains($tag));
    }
}
