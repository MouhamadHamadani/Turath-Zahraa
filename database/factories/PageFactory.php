<?php

namespace Database\Factories;

use App\Models\Page;

/**
 * @extends ArabicFactory<Page>
 */
class PageFactory extends ArabicFactory
{
    public function definition(): array
    {
        $title = $this->title(3);

        return [
            'slug' => $this->slugFor($title),
            'title' => $title,
            'body' => '<p>'.$this->paragraph().'</p>',
            'status' => 'published',
            'sort_order' => 0,
        ];
    }
}
