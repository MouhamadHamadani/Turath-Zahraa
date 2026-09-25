<?php

use App\Livewire\AboutUs;
use App\Livewire\Explore;
use App\Livewire\Landing;
use Illuminate\Support\Facades\Route;

Route::get('/', Landing::class)->name('home');
Route::get('/about-us', AboutUs::class)->name('about-us');
Route::get('/explore', Explore::class)->name('explore');