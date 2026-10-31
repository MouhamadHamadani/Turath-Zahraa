<?php

namespace App\Livewire;

use App\Models\Page;
use Filament\Forms\Components\RichEditor\RichContentRenderer;
use Illuminate\Contracts\View\View;
use Illuminate\Support\HtmlString;
use Livewire\Component;

class PageShow extends Component
{
    public Page $page;

    public function mount(string $slug): void
    {
        $this->page = Page::published()->where('slug', $slug)->firstOrFail();
    }

    public function render(): View
    {
        return view('livewire.page-show', [
            // The renderer sanitizes the editor's HTML.
            'body' => new HtmlString(RichContentRenderer::make($this->page->body)->toHtml()),
        ])->title($this->page->title);
    }
}
