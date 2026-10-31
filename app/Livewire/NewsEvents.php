<?php

namespace App\Livewire;

use App\Models\Event;
use App\Models\NewsArticle;
use Illuminate\Contracts\View\View;
use Livewire\Component;
use Livewire\WithPagination;

class NewsEvents extends Component
{
    use WithPagination;

    public function render(): View
    {
        return view('livewire.news-events', [
            'upcoming' => Event::published()->upcoming()->with('media')->limit(6)->get(),
            'past' => Event::published()->past()->with('media')->limit(6)->get(),
            'news' => NewsArticle::latestPublished()->with('media')->paginate(9),
        ])->title('الأخبار والفعاليات')
            ->layoutData(['description' => 'آخر أخبار المؤسسة وفعالياتها القادمة والسابقة.']);
    }
}
