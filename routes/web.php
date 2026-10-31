<?php

use App\Livewire\AboutUs;
use App\Livewire\Archive;
use App\Livewire\Contact;
use App\Livewire\Heritage;
use App\Livewire\Landing;
use App\Livewire\Library;
use App\Livewire\NewsEvents;
use App\Livewire\PageShow;
use App\Livewire\Search;
use Illuminate\Support\Facades\Route;

Route::get('/', Landing::class)->name('home');
Route::get('/about-us', AboutUs::class)->name('about-us');
Route::get('/heritage', Heritage::class)->name('heritage');

Route::prefix('library')->name('library.')->group(function () {
    Route::get('/books', Library\Books::class)->name('books');
    Route::get('/research', Library\Research::class)->name('research');
    Route::get('/publications', Library\Publications::class)->name('publications');
});

Route::prefix('archive')->name('archive.')->group(function () {
    Route::get('/audio', Archive\Audio::class)->name('audio');
    Route::get('/videos', Archive\Videos::class)->name('videos');
    Route::get('/photos', Archive\Photos::class)->name('photos');
    Route::get('/documents', Archive\Documents::class)->name('documents');
});

Route::get('/news-events', NewsEvents::class)->name('news-events');
Route::get('/contact', Contact::class)->name('contact');
Route::get('/search', Search::class)->name('search');
Route::get('/pages/{slug}', PageShow::class)->name('page.show');
