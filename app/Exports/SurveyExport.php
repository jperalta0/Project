<?php

namespace App\Exports;

use App\Respondent_profile;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class SurveyExport implements FromView
{
   	private $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function view(): View
    {
        return view('admin.surveys.survey', [
            'data' => $this->data
        ]);
    }
}