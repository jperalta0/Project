<?php

namespace App\Exports;

use App\Respondent_profile;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class RespondentsExport implements FromView
{
    public function view(): View
    {
        return view('admin.main.respondents', [
            'respondents' => Respondent_profile::orderBy('lastname')->get()
        ]);
    }
}
