<?php
    // if (isset($detail)) {
    //     $task = 'Edit';
    // }else{
        $task = 'View';
        $back = url('admin/heat-tickets');
    // }

?>
@extends('layouts.master')
@section('title', 'Heat Tickets')
@section('content')
<style type="text/css">
    .ibox-content label.col-sm-2 {
    font-weight: 800;
}
.col-form-label {
    padding: 0;
}
.ibox-content form > .row {
    padding: 5px 30px;
}
.wid_flex > .row {
    width: 33%;
}
.dasd.form-group {
    display: none;
}
.dasd {
    border: 1px solid #ddd;
    margin: 4px 0;
    padding: 2px 30px;
}

#box_reply_ticket {
    background-color: aliceblue;
}
</style>
<div class="row wrapper page-heading">
    <div class="col-lg-10">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{$back}}">Heat Tickets</a>
            </li>
            <li class="breadcrumb-item active">
                <strong>{{$task}} Heat Ticket</strong>
            </li>
        </ol>
        <h2>{{$task}} Heat Ticket</h2>
    </div>
    <div class="col-lg-2">

    </div>
</div>
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox ">
                <div class="ibox-title">
                    <h5><b>Heat Ticket Detail N° {{$detail['id']}}
                    <label class="">
                        @if($detail['status']=='N')
                            <span class="label label-danger">New</span>
                        @elseif($detail['status']=='O')
                            <span class="label label-yellow">Open</span>
                        @elseif($detail['status']=='C')
                            <span class="label label-primary">Closed</span>
                        @elseif($detail['status']=='W')
                            <span class="label label-info">Waiting Response</span>
                        @else
                            <span class="label label-warning">Deferred</span>
                        @endif
                    </label>
                    </b></h5>
                </div>
                <div class="ibox-content">
                    
                    <form method="post" action="" id="form" enctype="multipart/form-data">
                        
                        <div class="row">
                            <div class="col-lg-5 col-md-12 col-sm-12">
                                <div class="row mb-1">
                                    <label class="col-lg-3 col-md-3 col-sm-6 col-form-label"><b>Created by</b></label>
                                    <div class="col-lg-6 col-md-8 col-sm-6">
                                        <label class="col-form-label">
                                            {{ucfirst($detail['user']['last_name'].' '.$detail['user']['first_name'])}}
                                        </label>
                                    </div>
                                </div>
                                <div class="row mb-1">
                                    <label class="col-lg-3 col-md-3 col-sm-6 col-form-label"><b>Date</b> </label>
                                    <div class="col-lg-6 col-md-8 col-sm-6">
                                        <label class="col-form-label">
                                            {{date('m-d-Y h:i A',strtotime($detail['created_at']))}}
                                        </label>
                                    </div>
                                    
                                </div>
                                <div class="row mb-1">
                                    <label class="col-lg-3 col-md-3 col-sm-6 col-form-label"><b>Last updated</b> </label>
                                    <div class="col-lg-5 col-md-8 col-sm-6">
                                        <label class="col-form-label">
                                            {{date('m-d-Y h:i A',strtotime($detail['updated_at']))}}
                                        </label>
                                    </div>
                                </div>
                                <div class="mb-1 row">
                                    <label class="col-lg-3 col-md-3 col-sm-6 col-form-label"><b>Company</b> </label>
                                    <div class="col-lg-5 col-md-8 col-sm-6">
                                        <label class="col-form-label">
                                        @if($detail['user_company']['type']=='A')
                                            {{ucfirst($detail['user_company']['agency']['agency_name'] ?? '')}}
                                        @else
                                            {{ucfirst($detail['user_company']['contractor']['name'] ?? '')}}
                                        @endif
                                        </label>
                                    </div>
                                </div>
                                <div class="row mb-1">
                                    <label class="col-lg-3 col-md-3 col-sm-6 col-form-label"><b>Subject</b> </label>
                                    <div class="col-lg-5 col-md-8 col-sm-6">
                                        <label class="col-form-label">{{ucfirst($detail['subject'])}} </label>
                                    </div>
                                </div>
                                <div class="mb-1 row">
                                    <label class="col-lg-3 col-md-3 col-sm-6 col-form-label"><b>Message</b> </label>
                                    <div class="col-lg-9 col-md-8 col-sm-6">
                                        <label class="col-form-label">{!!ucfirst($detail['body_text'])!!}</label>
                                    </div>
                                </div>
                                
                            </div>
                            <div class="col-lg-7 col-md-12 col-sm-12">
                                <div class="row">
                            @if(count($detail['attachments'])>0)
                                
                                <label class="col-lg-9 col-md-3 col-sm-6 col-form-label"><b>Attachments  [<a class="showhide menu_points" type="hide">Hide</a> / <a class="showhide menu_points" type="show">Display</a>]</b></label>
                                <div class="col-lg-9 col-md-12 col-sm-12">
                                   
                                   <div class="attachment">
                                    <div class="row">
                                    @foreach($detail['attachments'] as $value)
                                    
                                        <div class="col-lg-6 col-md-6 col-sm-12">
                                    <div class="file-box">
                                        <div class="file">
                                        <?php
                                            $exp = explode('.',$value['name']);
                                            $extension = $exp['1'];
                                            $image = DefaultImage;
                                            if (!empty($value['name'])) {
                                                if (file_exists(HeatTicketBasePath.'/'.$value['name'])) {
                                                    $image = HeatTicketPath.'/'.$value['name'];
                                                }
                                            }
                                            
                                        ?>
                                        <a href="{{$image}}" target="blank">
                                            <span class="corner"></span>

                                            @if($extension=='jpg'||$extension=='jpeg'||$extension=='png')
                                                <div class="image">

                                                    <img alt="image" class="img-fluid" src="{{$image}}">
                                                </div>
                                            @else
                                                <div class="icon">
                                                    <i class="fa fa-file"></i>
                                                </div>
                                            @endif
                                            
                                        </a>
                                        </div>
                                    </div>
                                        </div>
                                    
                                    @endforeach
                                </div>
                                    </div>
                                </div>
                            
                        @endif
                                </div>
                            </div>
                          </div>

                          <div id="app">
                            <div class="col" style="display:none" id="box_reply_ticket">
                                <div class="" style="padding:15px;">
                                <div class="form-group row">
                                    <div class="">
                                        <h2><b>Reply to this Ticket</b></h2>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-lg-2 col-md-4 col-sm-12 col-form-label">
                                        <b>Message</b>
                                    </label>
                                    <div class="col-lg-7 col-md-8 col-sm-12">
                                        <textarea class="form-control" name="response"></textarea>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-lg-2 col-md-4 col-sm-12 col-form-label">
                                        <b>
                                            Status
                                        </b>
                                    </label>
                                    <div class="col-lg-7 col-md-8 col-sm-12">
                                        <select name="status" class="form-control" id="status">
                                            <option value="">Select Status</option>
                                            <option value="N" {{$detail['status']=='N'?'selected':''}}>New</option>
                                            <option value="O" {{$detail['status']=='O'?'selected':''}}>Open</option>
                                            <option value="C" {{$detail['status']=='C'?'selected':''}}>Closed</option>
                                            <option value="W" {{$detail['status']=='W'?'selected':''}}>Waiting Response</option>
                                            <option value="D" {{$detail['status']=='D'?'selected':''}}>Deferred</option>
                                            
                                        </select>
                                    </div>
                                </div>
                                <div class="pic-outer text-left p-top">
                                    <label class="col-sm-2 col-form-label">Attachments</label>
                                    <div class="form-group row form-group-8">
                                        <div class="write_review_modal_image_container rvw_upld">                          
                                            <div class="photo-container">
                                                <div class="photos-sec">
                                                    <div class="flex">
                                                    </div>
                                                </div>
                                                <div class="add-phots">
                                                    <a href="javascript:void(0);" class="btn-up-phots" id="upld-gal">Upload Image <i class="fa fa-file-image-o"></i></a>
                                                    <input type="file" name="image[]" class="review_modal_image id_proof_document rem0">
                                                    <span class="doc_error" style="color: red;"></span>
                                                </div>
                                                <span class="img-error" id="img-error" style="color:red"></span>
                                            </div>                                                      
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="hr-line-dashed"></div>
                                <div class="form-group center_grp">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        <button class="btn btn-primary btn-sm submit" type="submit">
                                            Send Response <i class="fa fa-send"></i>
                                        </button>
                                        <a href="" 
                                            class="btn btn-secondary btn-sm" 
                                            onclick="box_visible_bl(false);return false;" >
                                            Close
                                        </a>
                                </div>
                                </div>
                            </div>
                            @php
                                $path = url('/').'/'.HeatTicketResponseBasePath;
                            @endphp
                            <ticket-response :path-response="{{ json_encode($path) }}"></ticket-response>
                          </div>   
                        <a href="{{$back}}" class="btn btn- btn-sm btn-back">Back <i class="fa fa-arrow-left"></i></a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('scripts')
<script>
    function box_visible_bl(display = false) {
        var box = document.getElementById('box_reply_ticket');
        var btn_reply_ticket = document.getElementById('btn_reply_ticket');
        if (display == false) {
            box.style.display = 'none';
            this.close_reply = false;
            btn_reply_ticket.style.display = 'inherit';
        }
    }
    // $('.attachment').hide();
    $('.showhide').on('click',function(){
        type = $(this).attr('type');
        attachment = $(this).closest('div').find('.attachment');
        // alert(type);
        if (type=='show') {
            attachment.show();
        }else{
            attachment.hide();
        }
    });
</script>
<script type="text/javascript">
    $('[data-toggle="tooltip"]').tooltip();
    var inc_val = 0;
    $(document).on('change','.review_modal_image', function () {
        // alert('aa');
        var input = this;
        var this_addr = $(this);
        var extension = this_addr.val().split('.').pop().toLowerCase();

        var reader = new FileReader();
        reader.onload = function(){
            var dataURL = '';
            if(extension=='jpg' || extension=='jpeg' || extension=='png'){
                dataURL = reader.result;
            }else if(extension=='pdf'){
                dataURL = '{{DefaultPdf}}';
            }else if(extension=='doc'||extension=='docx'){
                dataURL = '{{DefaultDoc}}';
            }else if(extension=='xls'||extension=='xlsx'){
                dataURL = '{{DefaultXls}}';
            }else{
                swal({
                  title: "This file Type is not valid",
                  text: "Only jpg,png,pdf,doc,xls file type is supported'",
                  icon: "warning",
                  buttons: "Ok",
                  dangerMode: true,
                }).then((okay) => {
                    if (okay) {
                        return false;
                    }
                });
                return false;
            }    
            var output = $('.phot-upld img');
            output.src = dataURL;
            var len = $('.photos-sec > .flex > div').length;
            // var fileSize = input.files[0].size / 5024 / 1024; // in MB
            // if(fileSize < 2) {
            if (len <= 4) {
                $('#img-error').text('');
                $('.photos-sec> .flex').append('<div class="flex-item uploaded-section"><div class="phot-upld"><img src="'+ dataURL +'" class="img-responsive modal-upload-image-preview" /><a class="btn btn-remov"  data-toggle="tooltip" title="Delete Image" inpt-id="'+inc_val+'"><i class="fa fa-times"></i></a></div></div>');
                this_addr.off('change');
                inc_val++;
                if (len<=4) {
                    this_addr.after('<input type="file" name="image['+inc_val+']" class="review_modal_image rem'+inc_val+'">');
                }
            }else{
                $('#img-error').text("Maximum 5 files can be uploaded");
            }
            /*}else{
                $('#img-error').text('Maximum file size can be 2 MB');
            }*/                       
            
            //this_addr.after('<input type="file" name="upld-gal-hidden['+inc_val+']" accept="image/*" class="review_modal_image" id="upld-gal-hidden['+inc_val+']">')

            $('.btn-remov').on('click',function(){
                $(this).closest('.uploaded-section').remove();
                var rem_id = $(this).attr('inpt-id');
                $('.rem'+rem_id).remove();
                if ($('.photos-sec > .flex > div').length <5) {
                    $('#img-error').text('');
                }else{
                    $('#img-error').text("Maximum 5 files can be uploaded");
                }
            });
        };        
        reader.readAsDataURL(input.files[0]);
    });
</script>
<script type="text/javascript">
    $('#form').validate({
        rules:{
            response:{
                required:true,
                maxlength:500,
            },
            status:{
                required:true
            },
            name:{
                required:true,
                maxlength:25,
                // regex:/^[a-zA-z0-9 '-.]+$/,
            },
        },
        errorPlacement: function(error, element) {
          if(element.attr("name")=="type"  ) {
            error.appendTo( element.parent().parent("div") );
          } else {
            error.insertAfter(element);
          }
        }
    });
</script>

@endsection