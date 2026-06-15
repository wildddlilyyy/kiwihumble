<?php

namespace App\Http\Controllers;

use App\Models\SiteSetting;
use Illuminate\Contracts\View\View;

class HomeController
{
    public function __invoke(): View
    {
        return view('home', [
            'title' => SiteSetting::getValue('trip_title', 'KIWI GROUP Humble Graduation Trip'),
            'tripDate' => SiteSetting::getValue('trip_date', '2027-05-29'),
            'timezone' => SiteSetting::getValue('timezone', 'Asia/Taipei'),
        ]);
    }
}
