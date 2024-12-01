<?php

namespace App\Http\Requests;

use App\Models\Event;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class UpdateEventRequest extends FormRequest
{
    public function authorize()
    {
        // You can uncomment this line if you want to authorize based on roles or permissions
        // abort_if(Gate::denies('event_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'name'       => [
                'required',
                'string',
                'max:255',
            ],
            'start_time' => [
                'required',
                'date_format:' . config('panel.date_format') . ' ' . config('panel.time_format'),
            ],
            'end_time'   => [
                'required',
                'date_format:' . config('panel.date_format') . ' ' . config('panel.time_format'),
                'after_or_equal:start_time',
            ],
            'photo'      => [
                'nullable',
                'image', // Harus berupa gambar
                'mimes:jpeg,png,jpg,gif', // Format file yang diperbolehkan
                'max:2048', // Ukuran maksimal 2MB
            ],
            'location'   => [
                'nullable', // Make it nullable or required based on your needs
                'string',   // Ensure it's a string
                'max:255',  // Adjust max length if needed
            ],
        ];
    }
}
