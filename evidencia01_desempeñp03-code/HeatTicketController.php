<?php

namespace Modules\Admin\Http\Controllers\heatTicket;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\HeatTicket, App\HeatTicketResponse, App\HeatTicketResponseAttachment;
use Session;

class HeatTicketController extends Controller
{
    public function Index(Request $request){

        $details = HeatTicket::with('user_company.agency','user_company.contractor','user_company.user')
                            ->withCount('attachments')
                            ->orderBy('updated_at','desc')
                            ->get()->toArray();
        // echo "<pre>";print_r($details);die;
        $page = 'Heat_Ticket';
        return view('admin::heatTicket.index',compact('page','details'));
    }

    public function View(Request $request,$id){

        $id = base64_decode($id);
        if ($request->isMethod('post')) {
            $data = $request->all();

            $detail = new HeatTicketResponse();

            $detail->heat_ticket_id      = $id;
            $detail->response         = $data['response'];
            $detail->user_company_id         = '0';
            $detail->created_by         = '0';

            if($detail->save()){

                if (!empty($_FILES)) {
                    foreach ($_FILES['image']['name'] as $key => $value) {
                        if(!empty($_FILES['image']['name'][$key])){
                            $HeatTicketResponseAttachment = new HeatTicketResponseAttachment;

                            $HeatTicketResponseAttachment->heat_ticket_response_id=$detail->id;
                            $filename                   = $_FILES['image']['name'][$key];
                            $tmp_name                   = $_FILES['image']['tmp_name'][$key];
                            $file_ext                   = pathinfo($_FILES['image']['name'][$key]);
                            $extension                  = $file_ext['extension'];

                            $time = time();
                            $num = rand(1111,9999);
                            $str = str_random(2);
                            $random_number = $str.$time.$num;
                            $new_name                   = $random_number.'.'.$extension;
                            $destination                = HeatTicketResponseBasePath.'/'.$new_name;
                            if ($extension == 'jpg'|| $extension == 'jpeg'|| $extension == 'png') {
                                move_uploaded_file($tmp_name, $destination);
                                $HeatTicketResponseAttachment->name = $new_name;
                            }
                            $HeatTicketResponseAttachment->save();
                        }
                    }
                }
                
                HeatTicket::where('id',$detail->heat_ticket_id)->update(['status'=>$data['status'],'updated_at'=>now()]);
                return redirect()->back()->with('success','Heat ticket response sent successfully');
            } else{
                return redirect()->back()->with('error',COMMON_ERROR);
            }
                
            
        }
        
        $detail = HeatTicket::with('user_company.agency','user_company.contractor','user','attachments','responses.attachments','responses.user')
                                ->where('id',$id)->first();
        //echo dd($detail );die;                        
        if ($request->input('client')) {
            return $this->TicketResponse($detail);
        }
        $page = 'Heat_Ticket';
        return view('admin::heatTicket.form',compact('page','detail'));
    }

    public function TicketResponse($detail) {
        $records = array();
        $fullname = "";
        $time_zone = Session::get('time_zone');
        if (is_null($time_zone)) $time_zone = '6';
        foreach ($detail['responses'] as $key => $value) {

                $delete_url = url('admin/heat-ticket/response/delete/'.base64_encode($value['id']));
                $action = '
                        <a href="'.$delete_url.'" title="Delete" class="del_btn btn-white btn btn-xs">Delete</a>
                    ';
                $fullname = is_null($detail['user']) ? 'Admin' : "{$detail['user']['first_name']} {$detail['user']['last_name']}";
                $records[] = array(  
                    'id'            => $value['id'],
                    'last_name'     => $fullname,
                    'reply_on'         => date('m-d-Y h:i A',strtotime('-'.$time_zone.' hour',strtotime($value['created_at']))),
                    'message'         => $value['response'],
                    'action'        => $action,
                    'user' => $detail['responses'][$key]['user'],
                    'attachments' => $detail['responses'][$key]['attachments'],
                );
                
            }
        return $records;
    }

    public function Delete($id){

        $id = base64_decode($id);
        $delete  = HeatTicketResponse::where('id',$id )->delete();

        if ($delete) {
            return redirect()->back()->with('success','Response deleted successfully');
        }else{
            return redirect()->back()->with('error',COMMON_ERROR);
        }
    }
}
