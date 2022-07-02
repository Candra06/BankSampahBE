<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PengumpulanSampah extends Model
{
    use HasFactory;

    protected $table = 'pengumpulan_sampah';
    protected $fillable = ['user_id', 'jumlah', 'point'];

    public function users()
    {
        return $this->belongsTo(User::class);
    }

}
