<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vendor extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'business_name',
        'description',
        'qr_code_path',
    ];

    /**
     * Get the user that owns the vendor profile.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the menus for the vendor.
     */
    public function menus()
    {
        return $this->hasMany(Menu::class);
    }

    /**
     * Get the orders for the vendor.
     */
    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
