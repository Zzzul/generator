<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = ['text', 'email', 'phone', 'password', 'link', 'week', 'colour', 'search', 'photo', 'hide', 'no_ingfo', 'number', 'ranged', 'radio_boolean', 'textarea', 'date', 'bulan', 'waktu', 'tahun', 'tgl_waktu', 'gender', 'user_id'];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = ['text' => 'string', 'email' => 'string', 'phone' => 'string', 'password' => 'string', 'link' => 'string', Y-\WW'colour' => 'string', 'search' => 'string', 'photo' => 'string', 'hide' => 'string', 'no_ingfo' => 'string', 'number' => 'integer', 'ranged' => 'integer', 'radio_boolean' => 'boolean', 'textarea' => 'string', 'date' => 'date:d/m/Y', 'bulan' => 'date:d/m/Y', 'waktu' => 'datetime:H:i', 'tahun' => 'integer', 'tgl_waktu' => 'datetime:d/m/Y H:i', 'created_at' => 'datetime:d/m/Y H:i', 'updated_at' => 'datetime:d/m/Y H:i'];

	public function user()
	{
		return $this->belongsTo(\App\Models\User::class);
	}
}
