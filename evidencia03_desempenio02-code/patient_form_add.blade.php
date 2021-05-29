@section('customStyles')
    <style>
        .card-header {
            color: #0050db !important;
            padding: 0px !important;
            background-color: #f1f1f1;
            cursor: pointer;
        }

        .card-body {
            background-color: #fbfbfb;
        }

        .card-body label:not(.error) {
            color: black;
        }

        .select2-close-mask {
            z-index: 2099;
        }

        .select2-dropdown {
            z-index: 3051;
        }

    </style>
@endsection
<div class="wrapper wrapper-content animated fadeInRight">
    <!-- new form -->
    <form method="post" action="{{ route('agency.patient.add') }}" id="profile_form" enctype="multipart/form-data">
        <div class="accordion" id="accordionExample">
            <div class="row no-gutters">
                <div class="col-12 col-sm-6 col-md-8"></div>
                <div class="col-6 col-md-4" style="text-align: right;">
                    <a href="" id="expand-accordion">Expand</a> |
                    <a href="" id="collapse-accordion">Collapse</a>
                </div>
            </div>
            <div class="card">
                <div class="card-header" id="headingOne" data-toggle="collapse" id="collapseOne__btn"
                    data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                    <div class="row">
                        <div class="col-md-4">
                            <h3 class="mb-0">
                                <button class="btn btn-link" type="button" data-toggle="collapse" id="collapseOne__btn"
                                    data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                    {{ $title }}
                                </button>
                            </h3>
                        </div>

                    </div>
                </div>
                {{-- ------------------------------------------ --}}

                <div id="collapseOne" class="collapse show collapse-dinamic" aria-labelledby="headingOne"
                    data-parent="#accordionExample">
                    <div class="card-body">
                        <div class="form-row">
                            @if ($type == 'C')
                                <div class="form-group col-md-4">
                                    <label class="agency_id">Agency {{ $agency_selected }} </label>
                                    <select name="agency_id" class="form-control"
                                        onchange="selectAgencyFromContractor(this);"
                                        {{ $task == 'Edit' ? 'disabled' : '' }}>
                                        <option value="">Select Agency</option>
                                        @foreach ($agencies as $value)
                                            <option value="{{ $value['id'] }}"
                                                {{ $agency_selected == $value['id'] ? 'selected' : '' }}>
                                                {{ ucfirst($value->agency_name) }}</option>
                                        @endforeach
                                    </select>
                                    <span class="star_abs">*</span>
                                </div>
                            @endif
                            <div class="form-group col-md-4">
                                @if (count((array) $company_programs) > 1)
                                    {{-- -------------------- --}}
                                    <label for="programs_id">Programs *</label>
                                    <select name="programs_id" id="programs_id" class="form-control"
                                        onchange="displayElements()">
                                        <option value="">Select Program</option>
                                        @foreach ($company_programs as $value)
                                            <option value="{{ $value['id'] }}">
                                                {{ ucfirst($value->name) }}
                                            </option>
                                        @endforeach
                                    </select>
                                    {{-- ---------------------- --}}
                                @elseif(count((array) $company_programs) == 1)
                                    @php
                                        $key = null;
                                        foreach ($company_programs as $cp) {
                                            $key = $cp->id;
                                        }
                                    @endphp
                                    <input type="hidden" name="programs_id" id="programs_id"
                                        value="{{ $key !== null ? @$company_programs[$key]->id : '' }}">
                                @else
                                    <p>Nothing Pograms</p>
                                @endif
                            </div>
                        </div>
                        <div class="form-row">

                            <div class="form-group col-md-4">
                                <div class="form-row">
                                    <div class="col-sm-12 col-md-10">
                                        <label for="first_name">First Name *</label>
                                        <input name="first_name" type="text" class="form-control"
                                            placeholder="First Name" value="{{ @$detail->first_name }}">
                                    </div>
                                    <div class="col-sm-12 col-md-2">
                                        <label for="middle_initial">M.I.</label>
                                        <input name="middle_initial" type="text" class="form-control" placeholder="M.I"
                                            value="{{ @$detail->middle_initial }}">
                                    </div>
                                </div>
                            </div>

                            <div class="form-group col-md-4">
                                <label for="last_name">Last Name *</label>
                                <input name="last_name" type="text" class="form-control" placeholder="Last Name"
                                    value="{{ @$detail->last_name }}">
                            </div>
                            <div class="form-group col-md-4">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label for="sex">Gender *</label>
                                        <br>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="sex" id="maleSex"
                                                value="M" {{ @$detail->sex == 'M' ? 'checked' : '' }}
                                                {{ !isset($detail) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="maleSex">Male</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="sex" id="femaleSex"
                                                value="F" {{ @$detail->sex == 'F' ? 'checked' : '' }}>
                                            <label class="form-check-label" for="femaleSex">Female</label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="marital_status_idx">Marital Status </label>
                                        <select name="marital_status_idx" class="form-control">
                                            <option value="">Select Status</option>
                                            @foreach ($marital_status_list as $value)
                                                <option value="{{ $value['id'] }}"
                                                    {{ @$detail->marital_status_idx == $value['id'] ? 'selected' : '' }}>
                                                    {{ ucfirst($value->option) }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                            </div>
                        </div>
                        {{-- ------------------------------ --}}
                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label for="date_of_birth">Date of Birth *</label>
                                <input type="date" class="form-control" placeholder="Date of Birth" name="date_of_birth"
                                    value="{{ @$detail->date_of_birth }}">
                            </div>
                            <div class="form-group col-md-4">
                                <label for="middle_initial">Soc Sec Number</label>
                                <input type="text" class="form-control" placeholder="Soc Sec Number" name="soc_sec_nbr"
                                    value="{{ @$detail->soc_sec_nbr }}" data-mask="999-99-9999"
                                    data-inputmask="'mask': '999-99-9999'">
                            </div>
                            <div class="form-group col-md-4">
                                <label for="dnr_attachment">MRN or Case#</label>
                                <input name="mrn_case_nbr" type="text" placeholder="MRN or Case #" class="form-control"
                                    value="{{ @$detail->mrn_case_nbr }}">
                            </div>
                        </div>
                        {{-- --------------------------------- --}}
                        <div class="form-row">
                            <div class="form-group col-md-12">
                                <label for="address">Address *</label>
                                <input type="text" class="form-control" placeholder="Address" name="address"
                                    value="{{ @$detail['address'] }}" id="address">
                                <input type="hidden" class="form-control" name="latitude"
                                    value="{{ @$detail['geo_latitud'] }}" id="latitude">
                                <input type="hidden" class="form-control" name="longitude"
                                    value="{{ @$detail['geo_longitud'] }}" id="longitude">
                                <div id="map" style="display: none;"></div>
                            </div>
                        </div>
                        {{-- --------------------------------------- --}}
                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label for="city">City *</label>
                                <input name="city" type="text" class="form-control" placeholder="City"
                                    value="{{ @$detail->city }}">
                            </div>
                            <div class="form-group col-md-4">
                                <label for="state">State *</label>
                                <select name="state" class="form-control">
                                    <option value="">Select State</option>
                                    @if (is_iterable($states) && count((array) $states) >= 1)
                                        @foreach ($states as $value)
                                            <option value="{{ $value['id'] }}" @if (isset($agency_state[0]->state_id)) {{ $value['id'] == $agency_state[0]->state_id ? 'selected' : '' }}>
                                                    {{ $value['state_name_long'] }}
                            @else 
                                                    
                                                    {{ $value['state_name_long'] }} @endif </option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="zip_code">Zip Code *</label>
                                <input name="zip_code" type="text" class="form-control" placeholder="Zip Code"
                                    value="{{ @$detail->zip_code }}">
                            </div>
                        </div>
                        {{-- ------------------------------------ --}}
                        {{-- <div class="form-row">
                            <div class="form-group col-md-6">
                                <h2><b>{{ $title }} Patient</b></h2>
                            </div>
                        </div> --}}
                        {{-- --------------------------- --}}
                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label for="primary_phone">Primary Phone *</label>
                                <input name="primary_phone" type="text" class="form-control" placeholder="Primary Phone"
                                    value="{{ @$detail->primary_phone }}" data-mask="(999) 999-9999"
                                    data-inputmask="'mask': '(999) 999-9999'">
                            </div>
                            <div class="form-group col-md-4">
                                <label for="secondary_phone">Secondary Phone</label>
                                <input name="secondary_phone" type="text" class="form-control"
                                    placeholder="Secondary Phone" value="{{ @$detail->secondary_phone }}"
                                    data-mask="(999) 999-9999" data-inputmask="'mask': '(999) 999-9999'">
                            </div>
                            <div class="form-group col-md-4">
                                <label for="email">Email *</label>
                                <input name="email" type="text" class="form-control" placeholder="Email"
                                    value="{{ @$detail->email }}">
                            </div>

                        </div>

                        {{-- ----------------------------------------------- --}}
                        <div class="form-row">
                            <div class="col-md-4">
                                <label for="agency_patient_status_idx">Patient Status</label>
                                <select name="agency_patient_status_idx" class="form-control">
                                    <option value="">Select Status</option>
                                    @foreach ($status_patient_list as $value)
                                        <option value="{{ $value['id'] }}"
                                            {{ @$detail->agency_patient_status_idx == $value['id'] ? 'selected' : '' }}>
                                            {{ $value['option'] }}</option>
                                    @endforeach
                                </select>
                            </div>
                            {{-- ----------- --}}
                            <div class="form-group col-md-8" id="DNR-section">
                                <label for="dnr_orders">DNR</label><br>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="dnr_orders" id="dnr_orders_yes"
                                        value="1">
                                    <label class="form-check-label" for="dnr_orders_yes">Yes</label>
                                </div>

                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="dnr_orders" id="dnr_orders_no"
                                        value="0" checked>
                                    <label class="form-check-label" for="dnr_orders_no">No</label>
                                </div>
                                <a href="" id="dnr_attachment__click">Upload DNR Order</a>
                                <br>
                                <span class="dnr_attachment__span"></span>
                                <input id="dnr_attachment" class="d-none" type="file" name="dnr_attachment"
                                    class="form-control">
                            </div>
                            {{-- -- --}}
                        </div>
                    </div>
                </div>
            </div>
            <div class="card" id="insurance-section__card">
                <div class="card-header" id="headingInsurance" data-toggle="collapse" data-target="#collapseInsure"
                    aria-expanded="false" aria-controls="collapseInsure">
                    <h3 class="mb-0">
                        <button class="btn btn-link collapsed" type="button" data-toggle="collapse"
                            data-target="#collapseInsure" aria-expanded="false" aria-controls="collapseInsure">
                            Insurance
                        </button>
                    </h3>
                </div>
                <div id="collapseInsure" class="collapse show collapse-dinamic" aria-labelledby="headingInsurance"
                    data-parent="#accordionExample">
                    <div class="card-body">
                        <div class="form-row">
                            <div class="form-group col-md-3">
                                <label for="insurance_type_temp">Insurance Type</label>
                                <br>
                                <select name="insurance_type_temp" id="insurance_type_temp" class="form-control"
                                    onchange="insurance_other(this)">
                                    <option value=""></option>
                                    <option value="Medicare" selected>Medicare</option>
                                    <option value="Medicaid">Medicaid</option>
                                    <option value="Private">Private</option>
                                    <option value="HMO">HMO</option>
                                    <option value="Other">Other</option>
                                </select>
                                <span class="mt-1 badge badge-success">
                                    <a href="#!" onclick="addNewInsurance(event)" style="color:white;">
                                        Add New Insurance
                                    </a>
                                </span>
                            </div>
                            <div class="form-group col-md-3">
                                <label for="insurance_id_temp">Insurance ID</label>
                                <br>
                                <input name="insurance_id_temp" type="text" class="form-control"
                                    placeholder="Insurance Id" value="" id="insurance_id_temp">
                            </div>
                            <div class="form-group col-md-3">
                                <label for="primary_secondary_temp">Primary / Secondary</label>
                                <br>
                                <select name="primary_secondary_temp" id="primary_secondary_temp" class="form-control">
                                    <option value=""></option>
                                    <option value="Primary">Primary</option>
                                    <option value="Secondary">Secondary</option>
                                    <option value="Tertiary">Tertiary</option>
                                    <option value="Other">Other</option>
                                </select>
                            </div>
                            <div class="form-group col-md-3 d-none" id="insurance_name__section">
                                <label for="insurance_name_temp">Insurance Name</label>
                                <input name="insurance_name_temp" id="insurance_name_temp" type="text"
                                    class="form-control" placeholder="Insurance Name" value="">
                            </div>
                        </div>
                        <div class="form-row" id="insurance-section__new">

                        </div>

                    </div><!-- / End Physician Section -->
                </div>
            </div>
            <!-- Emergency Contact -->
            <div class="card" id="emergencyContact-section__card">
                <div class="card-header" id="headingEmergencyContact" data-toggle="collapse"
                    data-target="#collapseEmergencyContact" aria-expanded="false"
                    aria-controls="collapseEmergencyContact">
                    <h3 class="mb-0">
                        <button class="btn btn-link collapsed" type="button" data-toggle="collapse"
                            data-target="#collapseEmergencyContact" aria-expanded="false"
                            aria-controls="collapseEmergencyContact">
                            Emergency Contact
                        </button>
                    </h3>
                </div>
                <div id="collapseEmergencyContact" class="collapse show collapse-dinamic"
                    aria-labelledby="headingEmergencyContact" data-parent="#accordionExample">
                    <div class="card-body">
                        <div class="form-row">
                            <div class="form-group col-md-3">
                                <label for="contact_name_temp">Name</label>
                                <input type="text" id="contact_name_temp" name="contact_name_temp"
                                    placeholder="Contact Name" class="form-control">
                                <span class="mt-1 badge badge-success">
                                    <a href="#!" onclick="addNewEmergencyContact(event)" style="color:white;">Add New
                                        Contact</a>
                                </span>
                            </div>
                            <div class="form-group col-md-3">
                                <label for="relationship_temp">Relationship</label>
                                <br>
                                <select name="relationship_temp" id="relationship_temp" class="form-control">
                                    <option value=""></option>
                                    <option value="Caregiver">Caregiver</option>
                                    <option value="Father">Father</option>
                                    <option value="Mother">Mother</option>
                                    <option value="Son">Son</option>
                                    <option value="Daughter">Daughter</option>
                                    <option value="Brother">Brother</option>
                                    <option value="Sister">Sister</option>
                                    <option value="Friend">Friend</option>
                                    <option value="Other">Other</option>
                                </select>
                            </div>
                            <div class="form-group col-md-3">
                                <label for="contact_phone_temp">Phone</label>
                                <input name="contact_phone_temp" type="text" class="form-control"
                                    placeholder="Contact Phone" value="" data-mask="(999) 999-9999"
                                    data-inputmask="'mask': '(999) 999-9999'" id="contact_phone_temp">
                            </div>
                            <div class="form-group col-md-3">
                                <label for="contact_email_temp">Email
                                </label>

                                <input name="contact_email_temp" type="email" class="form-control"
                                    placeholder="Contact Name" value="" id="contact_email_temp">

                            </div>
                        </div>
                        <div class="form-row" id="emergencyContact-section__new">

                        </div>

                    </div><!-- / End Physician Section -->
                </div>
            </div>
            <!-- emergency contacts -->
            <div class="card">
                <div class="card-header" id="headingTwo" data-toggle="collapse" data-target="#collapseTwo"
                    aria-expanded="false" aria-controls="collapseTwo">
                    <h3 class="mb-0">
                        <button class="btn btn-link collapsed" type="button" data-toggle="collapse"
                            data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                            {{
                                $type == 'C' ? Str::title('Therapy referral') : Str::title('Agency Intake (admission)')
                            }}
                        </button>
                    </h3>
                </div>
                <div id="collapseTwo" class="collapse show collapse-dinamic" aria-labelledby="headingTwo"
                    data-parent="#accordionExample">
                    <div class="card-body">
                        <!-- Referral Intake Section -->
                        {{-- }}
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <h2><b>Referral Intake</b></h2>
                            </div>
                        </div>{{ --}}
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="referral_date">
                                    {{
                                        $type == 'C' ? Str::title('Theraphy referral date')
                                        : Str::title('Admission date')
                                    }}
                                </label>
                                <input name="referral_date" type="date" class="form-control" placeholder="Referral Date"
                                    value="">
                            </div>
                            @if ($type == 'A')
                            <div class="form-group col-md-6">
                                <label for="referral_sources_id">Referral Source</label>
                                <input name="referral_sources_id" type="number" class="form-control"
                                    placeholder="Referral Source" value="" maxlength="11">
                            </div>
                            @endif
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-12">
                                <label for="notes">
                                    {{ $type == 'C' ? Str::title('special order notes') 
                                        : Str::title('Admission notes') }}
                                </label>
                                <textarea name="referral_notes" class="form-control"></textarea>
                            </div>
                        </div>
                        <!-- / End Referral Intake Section -->
                    </div>
                </div>
            </div>
            <div class="card" id="physician-section__card">
                <div class="card-header" id="headingThree" data-toggle="collapse" data-target="#collapseThree"
                    aria-expanded="false" aria-controls="collapseThree">
                    <h3 class="mb-0">
                        <button class="btn btn-link collapsed" type="button" data-toggle="collapse"
                            data-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                            Physician
                        </button>
                    </h3>
                </div>
                <div id="collapseThree" class="collapse show collapse-dinamic" aria-labelledby="headingThree"
                    data-parent="#accordionExample">
                    <div class="card-body">
                        <!-- Physician Section -->
                        {{-- }}
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <h2><b>Physician</b></h2>
                            </div>
                        </div> {{ --}}

                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="referral_doctor_office_id_temp">Referral Doctor</label>
                                <input name="referral_doctor_office_id_temp" type="text" class="form-control"
                                    placeholder="Referral Doctor" value="" id="referral_doctor_office_id_temp" disabled>
                                <input name="referral_doctor_office_id" type="hidden" class="form-control"
                                    placeholder="Referral Doctor" value="" id="referral_doctor_office_id">
                                <span class="mt-1 badge badge-success">
                                    <a href="#!" onclick="viewSelectDoctor('referral_doctor_office_id')"
                                        style="color:white;">
                                        Select Doctor
                                    </a>
                                </span>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="treating_doctor_office_id_temp">Treating Doctor [PCP]</label>
                                <input name="treating_doctor_office_id_temp" type="text" class="form-control"
                                    placeholder="Referral Source" value="" disabled id="treating_doctor_office_id_temp">
                                <input name="treating_doctor_office_id" type="hidden" class="form-control"
                                    placeholder="Referral Source" value="" id="treating_doctor_office_id">
                                <span class="mt-1 badge badge-success">
                                    <a href="#!" onclick="viewSelectDoctor('treating_doctor_office_id')"
                                        style="color:white;">
                                        Select Doctor
                                    </a>
                                </span>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="diagnosis_1">Primary Diagnosis</label>
                                <input name="diagnosis_1" type="text" class="form-control" placeholder="Referral Date"
                                    value="">
                            </div>
                            <div class="form-group col-md-6">
                                <label for="diagnosis_2">Secondary Diagnosis</label>
                                <input name="diagnosis_2" type="text" class="form-control" placeholder="Referral Source"
                                    value="">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-12">
                                <label for="physician_instructions">Physician Intructions</label>
                                <textarea name="physician_instructions" class="form-control"></textarea>
                            </div>
                        </div>
                    </div><!-- / End Physician Section -->
                </div>
            </div>
            <!-- Discipline needed -->
            @if($type == 'C')
            <div class="card">
                <div class="card-header" id="headingFive" data-toggle="collapse" data-target="#collapseFive"
                    aria-expanded="false" aria-controls="collapseFive">
                    <h3 class="mb-0">
                        <button class="btn btn-link collapsed" type="button" data-toggle="collapse"
                            data-target="#collapseFive" aria-expanded="false" aria-controls="collapseFive">
                            Disciplines Needed
                        </button>
                    </h3>
                </div>
                
                <div id="collapseFive" class="collapse show collapse-dinamic" aria-labelledby="headingFive"
                    data-parent="#accordionExample">
                    <div class="card-body">

                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label for="soc_date">Disciplines</label>
                                <select class="js-example-placeholder-multiple js-states form-control" 
                                multiple="multiple" name="contractor_referral_discipline_id[]">
                                @if(count( (array) $discipline_list) > 0 )
                                    @foreach ($discipline_list as $discipline)
                                        <option value="{{$discipline->id}}">
                                            {{ $discipline->name }}
                                        </option>
                                    @endforeach
                                @endif
                                </select>

                            </div>
                        </div>
                    </div><!-- / End Start of Care-Episode Section -->
                </div>
            </div>
            @endif
            <div class="card">
                <div class="card-header" id="headingFour" data-toggle="collapse" data-target="#collapseFour"
                    aria-expanded="false" aria-controls="collapseFour">
                    <h3 class="mb-0">
                        <button class="btn btn-link collapsed" type="button" data-toggle="collapse"
                            data-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                            Start of Care-Episode
                        </button>
                    </h3>
                </div>
                <div id="collapseFour" class="collapse show collapse-dinamic" aria-labelledby="headingFour"
                    data-parent="#accordionExample">
                    <div class="card-body">

                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label for="soc_date">SOC / IPC *</label>
                                <input name="soc_date" type="date" class="form-control" placeholder="SOC/IPC" value=""
                                    onchange="startOfCareValue(this)">
                            </div>
                            <div class="form-group col-md-4">
                                <label for="start_date">Episode From</label>
                                <input name="start_date" type="date" class="form-control d-none"
                                    placeholder="Referral Source" value="">
                                <input name="start_date_temp" type="date" class="form-control"
                                    placeholder="Referral Source" value="">
                            </div>
                            <div class="form-group col-md-4">
                                <label for="end_date">Episode Thru</label>
                                <input name="end_date" type="date" class="form-control d-none"
                                    placeholder="Referral Doctor" value="" required>
                                <input name="end_date_temp" type="date" class="form-control"
                                    placeholder="Referral Doctor" value="">
                            </div>
                        </div>
                    </div><!-- / End Start of Care-Episode Section -->
                </div>
            </div>
            <div class="card" id="notes-section__card">
                <div class="card-header" id="headingNotes" data-toggle="collapse" data-target="#collapsePatientNotes"
                    aria-expanded="false" aria-controls="collapseNotes">
                    <h3 class="mb-0">
                        <button class="btn btn-link collapsed" type="button" data-toggle="collapse"
                            data-target="#collapsePatientNotes" aria-expanded="false" aria-controls="collapsePatientNotes">
                            Patient Notes
                        </button>
                    </h3>
                </div>
                <div id="collapsePatientNotes" class="collapse show collapse-dinamic" aria-labelledby="headingInsurance"
                    data-parent="#accordionExample">
                    <div class="card-body">
                        <div class="form-row mt-2">
                            <div class="form-group col-md-12">
                                <label for="notes">Notes</label>
                                <textarea name="notes" class="form-control"></textarea>
                            </div>
                        </div>
                    </div><!-- / End Physician Section -->
                </div>
            </div>
        </div> <!-- end accordition -->

        <!-- actions buttons -->
        <div class="form-row mt-3">
            <div class="form-group">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <button class="btn btn-primary btn-sm submit" type="submit">Save changes</button>
                <a href="{{ url('user/agency/patients' . '?filter=' . @$_GET['filter']) }}"
                    class="btn btn- btn-sm btn-back">Back</a>
            </div>
        </div> <!-- end actions buttons -->
        <div class="modal fade" id="selectDoctor-modal" role="dialog" aria-labelledby="exampleModalLabel"
            aria-hidden="true" style="overflow:hidden;">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">
                            Doctor List
                        </h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="doctor_action" id="doctor_action">
                        <select class="form-control select2_demo_2" name="doctor_list_select"
                            style="width: 100%">
                            <option value=""></option>
                        </select>
                        <span class="star_abs">*</span>
                        <label for="doctor_list_select" generated="true" class="error"></label>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" onclick="selectDoctor()">Select</button>
                    </div>
                </div>
            </div>
        </div>
    </form> <!-- end new form -->
</div>

@section('patient_form__script')
    <script type="application/javascript">
        /** 
         ** Global Varibles Section
         **/
        var programs_list = @json($company_programs);
        var programs_id = $('#programs_id');
        var DOCTOR_OFFICE_ROUTE = '{{ route('patient.get_doctor_office') }}'
        //-------------------------------------------------------

        $('#profile_form').validate({
            ignore: [],
            groups: {
                addresslatitude: "address latitude"
            },
            rules: {
                first_name: {
                    required: true,
                    maxlength: 25,
                    // regex:/^[a-zA-z ]+$/,
                },
                last_name: {
                    required: true,
                    maxlength: 25,
                    // regex:/^[a-zA-z ]+$/,
                },
                middle_initial: {
                    // required:true,
                    maxlength: 1,
                    regex: /^[a-zA-z0-9]+$/,
                },
                date_of_birth: {
                    required: true,
                    maxlength: 25,
                    // regex:/^[a-zA-z0-9]+$/,
                },
                sex: {
                    required: true,
                },
                marital_status_idx: {
                    required: false,
                },
                soc_sec_nbr: {
                    // required:true,
                    minlength: 10,
                    maxlength: 11,
                    regex: /^[0-9 ()-]+$/,
                },
                address: {
                    required: true,
                    maxlength: 255,
                    // regex:/^[a-zA-z ]+$/,
                },
                city: {
                    required: true,
                    maxlength: 20,
                    regex: /^[a-zA-z ]+$/,
                },
                state: {
                    required: true,
                },

                agency_patient_status_idx: {
                    required: true,
                },
                zip_code: {
                    required: true,
                    minlength: 5,
                    maxlength: 5,
                    regex: /^[0-9 ]+$/,
                },
                primary_phone: {
                    required: true,
                    minlength: 10,
                    maxlength: 14,
                    regex: /^[0-9 ()-]+$/,
                },
                secondary_phone: {
                    // required:true,
                    minlength: 10,
                    maxlength: 14,
                    regex: /^[0-9 ()-]+$/,
                },
                fax: {
                    // required:true,
                    minlength: 10,
                    maxlength: 14,
                    regex: /^[0-9 ()-]+$/,
                },
                email: {
                    required: false,
                    email: true,
                    maxlength: 80,
                    remote: {
                        data: {
                            id: '{{ @base64_encode($detail->id) }}'
                        },
                        url: '{{ url('user/patient/validate-email') }}'
                    }
                },
                latitude: {
                    required: true,
                },
                programs_id: {
                    required: true,
                },
                mrn_case_nbr: {
                    maxlength: 15
                },
                notes: {

                },
                dnr_attachment: {
                    required: {
                        depends: function() {
                            console.log($('input[name=dnr_orders]').val())
                            if ($('input[name=dnr_orders]:checked').val() == '1') {
                                return true;
                            } else {
                                return false;
                            }
                        }
                    }
                },
                dnr_orders: {

                },
                // referral intake section
                referral_date: {
                    required: true
                },
                referral_sources_id: {
                    required: false
                },
                // Start of care section
                soc_date: {
                    required: true
                },
                start_date: {
                    required: true
                },
                end_date: {
                    required: true
                },
            },
            messages: {
                email: {
                    remote: 'This email already exist'
                },
                userID: {
                    remote: 'This user id already exist'
                },
                latitude: {
                    required: 'Please select location from suggestions'
                },
                dnr_attachment: {
                    required: 'Upload DNR Order is required'
                }
            },
        });

        $('#expand-accordion').click(function(event) {
            event.preventDefault();
            $('.collapse').addClass('show')
        });

        $('#collapse-accordion').click(function(event) {
            event.preventDefault();
            $('.collapse').removeClass('show')
        });

        /** 
         **  DNR Section 
         **  ---------------------------------------
         */
        $('#dnr_attachment').on('change', function() {
            let fileName = $(this).val().split('\\').pop();
            //alert(fileName)
            $('.dnr_attachment__span').html(`「${fileName}」`);
        });

        $('#dnr_attachment__click').on('click', function(event) {
            event.preventDefault();
            $('#dnr_attachment').trigger('click');
        });

        $('input[name=dnr_orders]').on('change', function(event) {
            dnr_orders_actions($(this).val());
        })

        function dnr_orders_actions(value) {

            var dnr_orders = value;

            if (dnr_orders == '1') {
                $('#dnr_attachment__click').removeClass('d-none');
            } else {
                $('#dnr_attachment__click').addClass('d-none')
                $('#dnr_attachment').val('');
                $('.dnr_attachment__span').html('');
            }
            if (typeof dnr_orders == "undefined") {
                $('#dnr_orders_no').trigger('click');
            }
        }

        function displayElements() {
            if (programs_id.val() !== '') {
                var option = programs_list[programs_id.val()];
                if (option.display_dnr == 'No') {
                    $('#dnr_orders_no').trigger('click');
                    $('#DNR-section').addClass('d-none');
                } else if (option.display_dnr == 'Yes') {
                    $('#DNR-section').removeClass('d-none');
                }

                if (option.doctor_required == 'No') {
                    $(`#physician-section__card`).addClass('d-none');
                } else {
                    $(`#physician-section__card`).removeClass('d-none');
                }
                console.log(programs_list[programs_id.val()]);
            } else {
                $('#DNR-section').addClass('d-none');
                $(`#physician-section__card`).addClass('d-none');
            }
        }
        /** 
         **  End DNR Section 
         **  ---------------------------------------
         */

        function startOfCareValue(that) {

            if (!programs_id.val() == '') {
                var start_date = $('input[name=start_date]');
                var end_date = $('input[name=end_date]');
                var start_date_temp = $('input[name=start_date_temp]');
                var end_date_temp = $('input[name=end_date_temp]');
                start_date.val(that.value);
                start_date_temp.val(that.value);
                var data = programs_list[programs_id.val()];
                //console.log(data)
                if (data.calendar_view == '60 Days') {
                    function calDays() {
                        let end_date_temp = new Date(that.value);
                        let day = 60;
                        end_date_temp.setDate(end_date_temp.getDate() + day);
                        return end_date_temp;
                    }
                    end_date.val($.datepicker.formatDate('yy-mm-dd', calDays()));
                    end_date_temp.val($.datepicker.formatDate('yy-mm-dd', calDays()));
                    end_date.addClass('d-none');
                    end_date_temp.removeClass('d-none');
                    start_date_temp.prop('disabled', true);
                    end_date_temp.prop('disabled', true);
                } else if (data.calendar_view == 'Open') {
                    function calYear() {
                        let end_date_temp = new Date(that.value);
                        let day = 1;
                        end_date_temp.setFullYear(end_date_temp.getFullYear() + 1);
                        end_date_temp.setDate(end_date_temp.getDate());
                        return end_date_temp;
                    }
                    end_date.val($.datepicker.formatDate('yy-mm-dd', calYear()));
                    end_date_temp.val($.datepicker.formatDate('yy-mm-dd', calYear()))
                    end_date.removeClass('d-none');
                    end_date_temp.addClass('d-none');
                    start_date_temp.prop('disabled', true);
                    end_date_temp.prop('disabled', false);
                }
            } else {
                alert('Program is required');
                $('#programs_id').focus();
                return false;
            }
        }

        function insurance_other(that) {
            if (that.value == 'Other') {
                $('#insurance_name__section').removeClass('d-none');
                $('#insurance_name').prop('required', true);
            } else {
                $('#insurance_name__section').addClass('d-none');
                $('#insurance_name').prop('required', false);
                $('#insurance_name').prop('value', '');
            }
        }

        var newContactEmergency = [];
        var newInsurance = [];

        function addNewEmergencyContact(event) {
            event.preventDefault();

            var contact_name_temp = $('#contact_name_temp');
            var relationship_temp = $('#relationship_temp');
            var contact_phone_temp = $('#contact_phone_temp');
            var contact_email_temp = $('#contact_email_temp');
            //console.table(newContactEmergency)

            var data_temp = {
                name: contact_name_temp.val(),
                relationship: relationship_temp.val(),
                phone: contact_phone_temp.val(),
                email: contact_email_temp.val()
            };

            if (data_temp.name != "" && data_temp.relationship != "" &&
                data_temp.phone != "" && data_temp.email != "") {
                newContactEmergency.push(data_temp);
                contact_name_temp.val('');
                relationship_temp.val('');
                contact_phone_temp.val('');
                contact_email_temp.val('');
                getEmergencyContact(newContactEmergency);
            } else {
                swal(`Error`, `All items of section are required`, 'error')
            }

        }

        function getEmergencyContact(data) {
            var section = $('#emergencyContact-section__new');
            var template = '';
            newContactEmergency.forEach((element, index) => {
                template += emergencyContact_template(element, index);
            });
            console.table(newContactEmergency);
            section.html(template);
        }

        function emergencyContact_template(element, index) {
            var relationship_items = [
                'Caregiver', 'Father', 'Mother',
                'Son', 'Daughter', 'Brother', 'Sister',
                'Friend', 'Other'
            ];

            var temp = `
                            <div class="form-group col-md-3">
                                <input type="text" name="contact_name[]" class="form-control" 
                                value="${element.name}" required>
                                <span class="mt-1 badge badge-default">
                                        <a href="#!" onclick="deleteEmergenyContact(${index})"
                                            style="color:black;">
                                            Delete
                                            <i class="fa fa-trash-o" aria-hidden="true"></i>
                                        </a>
                                </span>
                            </div>
                            <div class="form-group col-md-3">
                                    <select name="relationship[]" class="form-control" required
                                        id='relationship${index}'>
                            `
            relationship_items.forEach((i) => {
                if (i == element.relationship) {
                    temp += `
                                <option value="${i}" selected>${i}</option>
                            `
                } else {
                    temp += `
                                <option value="${i}">${i}</option>
                            `
                }
            });
            temp += `</select>
                        </div>
                        <div class="form-group col-md-3">
                            <input name="contact_phone[]" type="text" class="form-control"
                                    placeholder="Contact Phone" value="${element.phone}" data-mask="(999) 999-9999" 
                                    data-inputmask="'mask': '(999) 999-9999'" required>
                            </div>
                        <div class="form-group col-md-3">
                            <input name="contact_email[]" type="email" class="form-control"
                                placeholder="Contact Name" value="${element.email}" required>
                        </div>`;

            return temp;
        }

        function deleteEmergenyContact(index) {
            newContactEmergency.splice(index, 1);
            getEmergencyContact(newContactEmergency);
            swal('Delete', 'Contact Emergency Deleted', 'success');
        }

        function addNewInsurance(event) {
            event.preventDefault();

            var insurance_type_temp = $('#insurance_type_temp');
            var insurance_id_temp = $('#insurance_id_temp');
            var primary_secondary_temp = $('#primary_secondary_temp');
            var insurance_name_temp = $('#insurance_name_temp');
            //console.table(newContactEmergency)

            var data_temp = {
                insurance_type: insurance_type_temp.val(),
                insurance_id: insurance_id_temp.val(),
                primary_secondary: primary_secondary_temp.val(),
                name: insurance_name_temp.val()
            };

            if (data_temp.insurance_type != "" && data_temp.insurance_id != "" &&
                data_temp.primary_secondary) {
                newInsurance.push(data_temp);
                insurance_type_temp.val('');
                insurance_id_temp.val('');
                primary_secondary_temp.val('');
                insurance_name_temp.val('');
                getInsurance(newInsurance);
                console.log(data_temp);
            } else {
                swal(`Error`, `All items of section are required`, 'error')
            }
        }

        function getInsurance(data) {
            var section = $('#insurance-section__new');
            var template = '';
            newInsurance.forEach((element, index) => {
                template += insurance_template(element, index);
            });
            console.table(newInsurance);
            section.html(template);
        }

        function insurance_template(element, index) {
            var insurance_type_items = [
                'Medicare', 'Medicaid', 'Private',
                'HMO', 'Other'
            ];
            var primary_secondary_items = [
                'Primary', 'Secondary', 'Tertiary',
                'Other'
            ];
            var template = `
                            <div class="form-group col-md-3">
                                <select name="insurance_type[]" class="form-control"
                                    onchange="insurance_other(this, ${index})">`
            insurance_type_items.forEach((i) => {
                if (element.insurance_type == i) {
                    template += `
                                <option value="${i}" selected>${i}</option>
                            `
                } else {
                    template += `
                                <option value="${i}" >${i}</option>
                            `
                }
            });
            template += `
                                </select>
                                <span class="mt-1 badge badge-default">
                                        <a href="#!" onclick="deleteInsurance(${index})"
                                            style="color:black;">
                                            Delete
                                            <i class="fa fa-trash-o" aria-hidden="true"></i>
                                        </a>
                                </span>
                            </div>
                            <div class="form-group col-md-3">
                                <input name="insurance_id[]" type="text" class="form-control" 
                                    placeholder="Insurance Id" value="${element.insurance_id}" required>
                            </div>
                            <div class="form-group col-md-3">
                                <select name="primary_secondary[]" class="form-control" required>
                    `
            primary_secondary_items.forEach((i) => {
                if (element.primary_secondary == i) {
                    template += `
                                <option value="${i}" selected>${i}</option>
                            `
                } else {
                    template += `
                                <option value="${i}" >${i}</option>
                            `
                }
            });
            template += `</select>
                        </div>
                    `;
            if (element.name !== "") {
                template += `
                                <div class="form-group col-md-3" id="insurance_name__section${index}">
                                    <input name="insurance_name[]" id="insurance_name${index}" 
                                        type="text" class="form-control" placeholder="Insurance Name" 
                                    value="${element.name}">
                                </div>
                        `;
            } else {
                template += `
                                <div class="form-group col-md-3">
                                    <input name="insurance_name[]" id="insurance_name${index}" 
                                        type="hidden" class="form-control" placeholder="Insurance Name" 
                                    value="">
                                </div>
                        `;
            }

            return template;
        }

        function deleteInsurance(index) {
            newInsurance.splice(index, 1);
            getInsurance(newInsurance);
            swal('Delete', 'Insurance Deleted', 'success');
        }

        function viewSelectDoctor(tag_id) {
            var modal_id = $('#selectDoctor-modal');
            $('#doctor_action').val(tag_id);
            modal_id.modal('show');
        }

        $('#selectDoctor-modal').on('show.bs.modal', function(event) {
            var temp_option = '';
            var modal = $(this);
            $('#patient_doctor_list').html('');
            $.get(DOCTOR_OFFICE_ROUTE, function(response) {
                response.forEach(element => {
                    temp_option += `
                                                        <option value='${btoa(element.doctor.id)}'>${element.doctor.first_name} ${element.doctor.last_name}</option>
                                                    `;
                });
                $('select[name=doctor_list_select]').html(temp_option)
            });
        });

        function selectDoctor() {
            var doctor_action = $('#doctor_action');

            var doctor_list_select = $('select[name=doctor_list_select');
            var doctor_list_option = $('select[name=doctor_list_select] option:selected').text();

            $(`#${doctor_action.val()}` + '_temp').val(doctor_list_option);
            $(`#${doctor_action.val()}`).val(doctor_list_select.val());

            $('#selectDoctor-modal').modal('hide');
        }

        function selectAgencyFromContractor(that) {
            window.location.href = '{{ route('agency.patient.add') }}' + `/${btoa(that.value)}`
        }

    
        $(document).ready(function() {
            displayElements();
            dnr_orders_actions({{ @$detail->dnr_orders }});
            $(".js-example-placeholder-multiple").select2({
                placeholder: "Select a dicipline"
            });
        });

    </script>
@endsection
