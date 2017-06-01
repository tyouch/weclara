<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    protected $connection   = 'mydb';
    protected $table        = 'account';
    protected $primaryKey   = 'id';
    protected $keyType      = 'int';
    public $incrementing    = false;
    public $timestamps      = false;
}
