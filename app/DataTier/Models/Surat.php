<?php
namespace App\DataTier\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use App\DataTier\Models\User;

class Surat extends Model
{
    use HasFactory;

    protected $table = 'surats';

    protected $fillable = ['user_id', 'jenis_surat', 'keterangan'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
