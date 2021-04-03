<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HeatTicketResponse extends Model
{
    public function attachments(){
    	return $this->hasMany('App\HeatTicketResponseAttachment','heat_ticket_response_id','id');
    }
    public function user(){
    	return $this->hasOne('App\User','id','created_by');
    }
}
