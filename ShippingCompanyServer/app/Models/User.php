<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;



    const DISP_TYPE = 'Dispatcher';
    const HR_TYPE = 'HREmployee';
    const BOOK_TYPE = 'Booker';
    const CL_TYPE = 'Client';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'empl_type',
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function isDispatcher()
    {
        return ($this->empl_type == DISP_TYPE);
    }

    public function isHREmployee()
    {
        return ($this->empl_type == HR_TYPE);
    }

    public function isBooker()
    {
        return ($this->empl_type == BOOK_TYPE);
    }

    public function isClient()
    {
        return ($this->empl_type == CL_TYPE);
    }
}
