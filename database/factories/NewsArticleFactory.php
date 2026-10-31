<?php

namespace Database\Factories;

use App\Models\NewsArticle;

/**
 * @extends ArabicFactory<NewsArticle>
 */
class NewsArticleFactory extends ArabicFactory
{
    public function definition(): array
    {
        $title = $this->title();

        return [
            'title' => $title,
            'slug' => $this->slugFor($title),
            'excerpt' => $this->sentence(),
            'body' => '<p>'.$this->paragraph().'</p>',
            'is_featured' => false,
            ...$this->publishedAttributes(),
        ];
    }
}
