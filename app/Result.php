<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Result extends Model
{
     protected $table = 'results';
     public $timestamps = false;
     //protected $fillable = array('page_title','url','content','description','portal','date','likes','shares','last_update');
}
