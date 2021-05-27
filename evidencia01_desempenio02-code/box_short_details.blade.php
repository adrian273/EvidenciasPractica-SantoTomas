<div class="ibox ">
    <div class="ibox-title">
        <h3 class="text-success font-bold">{{ ucfirst(@$patient->first_name) . ' ' . ucfirst(@$patient->last_name) }}
            {{--<small style="color: black;">
                <a class="collapse-link" id="more_or_less" onclick="moreOrLess()">
                    <span id="text_moreorless"> Less </span> <i class="fa fa-chevron-up"></i>
                </a>
            </small>--}}
            <br>
            @if ($type == 'C')
                <small class="text-warning font-bold">
                    @foreach ($agencies as $value)
                        @if (@$patient->agency_id == $value['id'])
                            {{ Str::title($value->agency_name) }}
                        @endif
                    @endforeach
                </small>
            @endif
        </h3>
        <span></span>
        <input type="hidden" value="1" name="val_moreorless" id="val_moreorless">
        <div class="ibox-tools">
            <a class="collapse-link" id="more_or_less" onclick="moreOrLess()">
                <span id="text_moreorless"> Less... </span> <i class="fa fa-chevron-up"></i>
            </a>
            <a class="dropdown-toggle btn btn-default" data-toggle="dropdown" href="#">
                Actions <i class="fa fa-ellipsis-v" aria-hidden="true"></i>
            </a>
            <ul class="dropdown-menu dropdown-user">
                <li>
                    <a href="#" class="dropdown-item">
                        Add New Certification
                    </a>
                </li>
                <li>
                    <a href="#" class="dropdown-item">
                        Add Admission (New SOC)
                    </a>
                    <div class="dropdown-divider"></div>
                </li>
                <li>
                    <a href="#" class="dropdown-item">
                        Previous Certification
                    </a>
                </li>
                <li>
                    <a href="#" class="dropdown-item">
                        Display all Certifications
                    </a>
                </li>
                <li>
                    <a href="#" class="dropdown-item">
                        Change Pt Status
                    </a>
                    <div class="dropdown-divider"></div>
                </li>
                <li>
                    <a href="#" class="dropdown-item">
                        Edit Admission Date
                    </a>
                </li>
                <li>
                    <a href="#" class="dropdown-item">
                        Edit Certification Date
                    </a>
                </li>
                <li>
                    <a href="#" class="dropdown-item">
                        Select Certification
                    </a>
                </li>
                <li>
                    <a href="#" class="dropdown-item">
                        Delete Certification
                    </a>
                </li>
            </ul>

        </div>
    </div>
    <div class="ibox-content">
        <div class="row">
            <div class="col-md-4 col-sm-12">
                <label>
                    <b>MRN/Case#:</b>
                    <span> {{ @$patient->mrn_case_nbr }} </span>
                </label>
            </div>
            <div class="col-md-4 col-sm-12">
                <label>
                    <b>Status:</b>
                    <span class="badge badge-green"> Current </span>
                </label>
            </div>
            <div class="col-md-4 col-sm-12">
                <label>
                    <b>Pt,Phone:</b>
                    <span> {{ @$patient->primary_phone }} </span>
                </label>
            </div>
            <div class="w-100"></div>
            <div class="col-md-4 col-sm-12">
                <label for="">
                    <b>Date of Birth:</b>
                    <span>
                        {{ \Carbon\Carbon::parse(@$patient->date_of_birth)->format('m-d-Y') }}
                    </span>
                </label>
            </div>
            <div class="col-md-4 col-sm-12">
                <label>
                    <b>Medicare:</b> <span> ABC999999999 </span>
                </label>
            </div>
            <div class="col-md-4 col-sm-12">
                <span><b>Physician:</b> 
                    {{ 
                        @$physician->full_name != null ? 
                        Str::title(@$physician->full_name) : 'nothing' 
                    }}
                </span>
            </div>
            <div class="w-100"></div>
            <div class="col-md-4 col-sm-12">
                <span><b>SOC(IPC):</b> 
                    {{ \Carbon\Carbon::parse($lastAdmission->soc_date)->format('m-d-Y') }}
                </span>
            </div>
            <div class="col-md-4 col-sm-12">
                <span><b>Episode:</b> 
                    {{ \Carbon\Carbon::parse($lastAdmission->episodes[0]['start_date'])->format('m-d-Y') }}
                     / 
                    {{ \Carbon\Carbon::parse($lastAdmission->episodes[0]['end_date'])->format('m-d-Y') }}
                </span>
                <a href="">
                    <i class="fa fa-angle-left" aria-hidden="true"></i>
                    Prev
                </a>
                <a href="" class="ml-1">
                    Next <i class="fa fa-angle-right" aria-hidden="true"></i>
                </a>
            </div>
            <div class="col-md-4 col-sm-12">
                <span><b></b> </span>
            </div>
        </div>
    </div>
</div>
