<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HeatTicket extends Model
{
    public function user(){
    	return $this->hasOne('App\User','id','created_by');
    }
    public function attachments(){
    	return $this->hasMany('App\HeatTicketAttachment','heat_ticket_id','id');
    }
    public function responses(){
    	return $this->hasMany('App\HeatTicketResponse','heat_ticket_id','id');
    }
    public function user_company(){
    	return $this->hasOne('App\UserCompany','id','from_users_companies_id');
    }
}
