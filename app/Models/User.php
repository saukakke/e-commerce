<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = ['name','email','password','role','phone'];
    protected $hidden = ['password','remember_token'];
    protected function casts(): array { return ['email_verified_at'=>'datetime','password'=>'hashed']; }
    public function orders() { return $this->hasMany(Order::class); }
    public function addresses() { return $this->hasMany(Address::class); }
    public function reviews() { return $this->hasMany(Review::class); }
    public function wishlistItems() { return $this->hasMany(Wishlist::class); }
    public function isAdmin(): bool { return $this->role === 'admin'; }
}
