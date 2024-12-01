<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Http\Controllers\Controller;

class SystemCalendarController extends Controller
{
    public $sources = [
        [
            'model'      => '\\App\\Models\\Event',
            'date_field' => 'start_time',
            'end_field'  => 'end_time',
            'field'      => 'name',
            'prefix'     => '',
            'suffix'     => '',
        ],
    ];

    public function index()
    {
        $events = [];

        // Iterasi melalui sumber-sumber data
        foreach ($this->sources as $source) {
            // Ambil semua data dari model yang ditentukan
            foreach ($source['model']::all() as $model) {
                // Debugging: Cek seluruh data model
                \Log::debug("Model Data: ", $model->toArray());

                // Debugging: Cek nilai start_time dan end_time
                \Log::debug("Start Time: " . $model->start_time);
                \Log::debug("End Time: " . $model->end_time);

                // Pastikan start_time dan end_time ada dan tidak null
                if (!$model->start_time || !$model->end_time) {
                    continue;
                }

                // Membangun array events dengan menambahkan lokasi dan koordinat
                $events[] = [
                    'id' => $model->id,
                    'name' => trim($source['prefix'] . " " . $model->{$source['field']} . " " . $source['suffix']),
                    'start' => $model->start_time,
                    'end' => $model->end_time,
                    'location' => $model->location ?? null,  // Tambahkan lokasi
                    'location_lat' => $model->location_lat ?? null, // Latitude
                    'location_lng' => $model->location_lng ?? null, // Longitude
                    'photo' => $model->photo ?? null,
                ];
            }
        }

        // Debugging: Cek hasil events setelah diproses
        \Log::debug("Events Data: ", $events);

        // Mengirim data events ke view
        return view('calendar.calendar', compact('events'));
    }
}
