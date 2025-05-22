<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Lab404\Impersonate\Models\Impersonate;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;
    use Impersonate;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'username',
        'openid',
        'edu_key',
        'uid',
        'group_id',
        'name',
        'email',
        'telephone',
        'password',
        'code',
        'other_code',
        'school',
        'school_id',
        'kind',
        'title',
        'section_id',
        'my_section_id',
        'login_type',
        'admin',
        'disable',
        'logined_at',
        'disabled_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function post()
    {
        return $this->hasMany(Post::class);
    }

    public function  userpowers() {
        return $this->hasMany(UserPower::class);
    }
}
