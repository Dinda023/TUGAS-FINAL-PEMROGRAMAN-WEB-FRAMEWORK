<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Event extends Model
{
    use SoftDeletes;

    protected $table = 'events';

    protected $dates = [
        'end_time',
        'start_time',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'name',
        'end_time',
        'event_id',
        'start_time',
        'photo',
        'location',  // Add location here
        'location_lat', // Add latitude field
        'location_lng', // Add longitude field
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function events()
    {
        return $this->hasMany(Event::class, 'event_id', 'id');
    }

    /**
     * Accessor untuk start_time.
     * Mengembalikan start_time dalam format ISO 8601.
     */
    public function getStartTimeAttribute($value)
    {
        return $value ? Carbon::parse($value)->toIso8601String() : null;
    }

    /**
     * Mutator untuk start_time.
     * Mengubah nilai start_time menjadi format Y-m-d H:i:s.
     */
    public function setStartTimeAttribute($value)
    {
        $this->attributes['start_time'] = $value ? Carbon::parse($value)->format('Y-m-d H:i:s') : null;
    }

    /**
     * Accessor untuk end_time.
     * Mengembalikan end_time dalam format ISO 8601.
     */
    public function getEndTimeAttribute($value)
    {
        return $value ? Carbon::parse($value)->toIso8601String() : null;
    }

    /**
     * Mutator untuk end_time.
     * Mengubah nilai end_time menjadi format Y-m-d H:i:s.
     */
    public function setEndTimeAttribute($value)
    {
        $this->attributes['end_time'] = $value ? Carbon::parse($value)->format('Y-m-d H:i:s') : null;
    }

    /**
     * Relasi ke event induk (jika ada).
     */
    public function event()
    {
        return $this->belongsTo(Event::class, 'event_id');
    }

    /**
     * Simpan model tanpa memicu event.
     *
     * @param  array  $options
     * @return bool
     */
    public function saveQuietly(array $options = [])
    {
        return static::withoutEvents(function () use ($options) {
            return parent::save($options);
        });
    }
}
