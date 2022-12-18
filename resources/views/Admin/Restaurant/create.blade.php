@extends('layouts.backend_header')

@section('content')

<!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Add Restaurant</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{ url('/dashboard')}}">Home</a></li>
              <li class="breadcrumb-item active">Restaurants</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    @if ($errors->any())
                <div class="alert alert-danger alert-dismissible">
                  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                  <h5><i class="icon fas fa-ban"></i> <strong>Whoops!</strong><br><br>
                  There were some problems with your input.</h5>
                  @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
                </div>
          @endif
          @if ($message = Session::get('success'))
    <div class="alert alert-success alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        <h5><i class="icon fas fa-check"></i> Success!</h5>
        <p>{{ $message }}</p>
    </div>
@elseif ($message = Session::get('error'))
    <div class="alert alert-danger alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        <h5><i class="icon fas fa-ban"></i> Error!</h5>
        <p>{{ $message }}</p>
    </div>
@endif

<div class="col-md-8">

            <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">Restaurant Info</h3>
              </div>
              <!-- /.card-header -->
              <!-- form start -->
              <form method="POST" action="{{ route('restaurants.store') }}" enctype="multipart/form-data">
                <!-- /.input group -->
                @csrf
                <div class="card-body">
                  <div class="form-group">
                    <label for="exampleInputEmail1">Restaurant Email</label>
                    <input type="email" class="form-control" id="exampleInputEmail1" name="Restaurant_email" value="{{Auth::user()->email}}"
                    placeholder="{{Auth::user()->email}}" disabled>
                  </div>
                  <div class="form-group">
                    <label for="exampleInputPassword1">Restaurant Name</label>
                    <input type="text" class="form-control" name="Restaurant_name" id="exampleInputName1" placeholder="{{Auth::user()->name}}" >
                  </div>
                  <!-- phone mask -->
                <div class="form-group">
                  <label>Restaurant phone:</label>

                  <div class="input-group">
                    <div class="input-group-prepend">
                      <span class="input-group-text"><i class="fas fa-phone"></i></span>
                    </div>
                    <input type="text" class="form-control" name="Restaurant_phone" required >
                  </div>

                </div>
                <div class="form-group">
                    <label for="exampleInputPassword1">Restaurant Address</label>
                    <input type="text" class="form-control" id="exampleInputName1" required name="Restaurant_address" placeholder="Restaurant Address" >
                  </div>
                    <div class="row form-group">
                        <div class="col-12">
                            <div class="form-group">
                                <label>Restaurant Price Range</label>
                                <select class="form-control select2" name="Restaurant_price_range" style="width: 100%;" required>
                                    <option>Restaurant Price Range</option>
                                    <option value="$">$</option>
                                    <option value="$$">$$</option>
                                    <option value="$$$">$$$</option>
                                    <option value="$$$$">$$$$</option>
                                    <option value="$$$$$">$$$$$</option>
                                    <option value="$$$$$$">$$$$$$</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row form-group">
                        <div class="col-12">
                            <div class="form-group">
                                <label>Restaurant Currency</label>
                                <select class="form-control select2" name="restaurant_currency" style="width: 100%;" required>
                                    <option selected disabled>Restaurant Price Range</option>
                                    <option value="$">$</option>
                                    <option value="ETB">ETB</option>
                                    <option value="€">€</option>
                                    <option value="£">£</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row form-group">
                        <div class="col-12">
                            <div class="form-group">
                                <label>Restaurant Country</label>
                                <select class="form-control select2" name="Restaurant_Country" style="width: 100%;" required>
                                    <option value="Afghanistan">Afghanistan</option>
                                    <option value="Albania">Albania</option>
                                    <option value="Algeria">Algeria</option>
                                    <option value="American Samoa">American Samoa</option>
                                    <option value="Andorra">Andorra</option>
                                    <option value="Angola">Angola</option>
                                    <option value="Anguilla">Anguilla</option>
                                    <option value="Antigua & Barbuda">Antigua & Barbuda</option>
                                    <option value="Argentina">Argentina</option>
                                    <option value="Armenia">Armenia</option>
                                    <option value="Aruba">Aruba</option>
                                    <option value="Australia">Australia</option>
                                    <option value="Austria">Austria</option>
                                    <option value="Azerbaijan">Azerbaijan</option>
                                    <option value="Bahamas">Bahamas</option>
                                    <option value="Bahrain">Bahrain</option>
                                    <option value="Bangladesh">Bangladesh</option>
                                    <option value="Barbados">Barbados</option>
                                    <option value="Belarus">Belarus</option>
                                    <option value="Belgium">Belgium</option>
                                    <option value="Belize">Belize</option>
                                    <option value="Benin">Benin</option>
                                    <option value="Bermuda">Bermuda</option>
                                    <option value="Bhutan">Bhutan</option>
                                    <option value="Bolivia">Bolivia</option>
                                    <option value="Bonaire">Bonaire</option>
                                    <option value="Bosnia & Herzegovina">Bosnia & Herzegovina</option>
                                    <option value="Botswana">Botswana</option>
                                    <option value="Brazil">Brazil</option>
                                    <option value="British Indian Ocean Ter">British Indian Ocean Ter</option>
                                    <option value="Brunei">Brunei</option>
                                    <option value="Bulgaria">Bulgaria</option>
                                    <option value="Burkina Faso">Burkina Faso</option>
                                    <option value="Burundi">Burundi</option>
                                    <option value="Cambodia">Cambodia</option>
                                    <option value="Cameroon">Cameroon</option>
                                    <option value="Canada">Canada</option>
                                    <option value="Canary Islands">Canary Islands</option>
                                    <option value="Cape Verde">Cape Verde</option>
                                    <option value="Cayman Islands">Cayman Islands</option>
                                    <option value="Central African Republic">Central African Republic</option>
                                    <option value="Chad">Chad</option>
                                    <option value="Channel Islands">Channel Islands</option>
                                    <option value="Chile">Chile</option>
                                    <option value="China">China</option>
                                    <option value="Christmas Island">Christmas Island</option>
                                    <option value="Cocos Island">Cocos Island</option>
                                    <option value="Colombia">Colombia</option>
                                    <option value="Comoros">Comoros</option>
                                    <option value="Congo">Congo</option>
                                    <option value="Cook Islands">Cook Islands</option>
                                    <option value="Costa Rica">Costa Rica</option>
                                    <option value="Cote DIvoire">Cote DIvoire</option>
                                    <option value="Croatia">Croatia</option>
                                    <option value="Cuba">Cuba</option>
                                    <option value="Curaco">Curacao</option>
                                    <option value="Cyprus">Cyprus</option>
                                    <option value="Czech Republic">Czech Republic</option>
                                    <option value="Denmark">Denmark</option>
                                    <option value="Djibouti">Djibouti</option>
                                    <option value="Dominica">Dominica</option>
                                    <option value="Dominican Republic">Dominican Republic</option>
                                    <option value="East Timor">East Timor</option>
                                    <option value="Ecuador">Ecuador</option>
                                    <option value="Egypt">Egypt</option>
                                    <option value="El Salvador">El Salvador</option>
                                    <option value="Equatorial Guinea">Equatorial Guinea</option>
                                    <option value="Eritrea">Eritrea</option>
                                    <option value="Estonia">Estonia</option>
                                    <option value="Ethiopia">Ethiopia</option>
                                    <option value="Falkland Islands">Falkland Islands</option>
                                    <option value="Faroe Islands">Faroe Islands</option>
                                    <option value="Fiji">Fiji</option>
                                    <option value="Finland">Finland</option>
                                    <option value="France">France</option>
                                    <option value="French Guiana">French Guiana</option>
                                    <option value="French Polynesia">French Polynesia</option>
                                    <option value="French Southern Ter">French Southern Ter</option>
                                    <option value="Gabon">Gabon</option>
                                    <option value="Gambia">Gambia</option>
                                    <option value="Georgia">Georgia</option>
                                    <option value="Germany">Germany</option>
                                    <option value="Ghana">Ghana</option>
                                    <option value="Gibraltar">Gibraltar</option>
                                    <option value="Great Britain">Great Britain</option>
                                    <option value="Greece">Greece</option>
                                    <option value="Greenland">Greenland</option>
                                    <option value="Grenada">Grenada</option>
                                    <option value="Guadeloupe">Guadeloupe</option>
                                    <option value="Guam">Guam</option>
                                    <option value="Guatemala">Guatemala</option>
                                    <option value="Guinea">Guinea</option>
                                    <option value="Guyana">Guyana</option>
                                    <option value="Haiti">Haiti</option>
                                    <option value="Hawaii">Hawaii</option>
                                    <option value="Honduras">Honduras</option>
                                    <option value="Hong Kong">Hong Kong</option>
                                    <option value="Hungary">Hungary</option>
                                    <option value="Iceland">Iceland</option>
                                    <option value="Indonesia">Indonesia</option>
                                    <option value="India">India</option>
                                    <option value="Iran">Iran</option>
                                    <option value="Iraq">Iraq</option>
                                    <option value="Ireland">Ireland</option>
                                    <option value="Isle of Man">Isle of Man</option>
                                    <option value="Israel">Israel</option>
                                    <option value="Italy">Italy</option>
                                    <option value="Jamaica">Jamaica</option>
                                    <option value="Japan">Japan</option>
                                    <option value="Jordan">Jordan</option>
                                    <option value="Kazakhstan">Kazakhstan</option>
                                    <option value="Kenya">Kenya</option>
                                    <option value="Kiribati">Kiribati</option>
                                    <option value="Korea North">Korea North</option>
                                    <option value="Korea Sout">Korea South</option>
                                    <option value="Kuwait">Kuwait</option>
                                    <option value="Kyrgyzstan">Kyrgyzstan</option>
                                    <option value="Laos">Laos</option>
                                    <option value="Latvia">Latvia</option>
                                    <option value="Lebanon">Lebanon</option>
                                    <option value="Lesotho">Lesotho</option>
                                    <option value="Liberia">Liberia</option>
                                    <option value="Libya">Libya</option>
                                    <option value="Liechtenstein">Liechtenstein</option>
                                    <option value="Lithuania">Lithuania</option>
                                    <option value="Luxembourg">Luxembourg</option>
                                    <option value="Macau">Macau</option>
                                    <option value="Macedonia">Macedonia</option>
                                    <option value="Madagascar">Madagascar</option>
                                    <option value="Malaysia">Malaysia</option>
                                    <option value="Malawi">Malawi</option>
                                    <option value="Maldives">Maldives</option>
                                    <option value="Mali">Mali</option>
                                    <option value="Malta">Malta</option>
                                    <option value="Marshall Islands">Marshall Islands</option>
                                    <option value="Martinique">Martinique</option>
                                    <option value="Mauritania">Mauritania</option>
                                    <option value="Mauritius">Mauritius</option>
                                    <option value="Mayotte">Mayotte</option>
                                    <option value="Mexico">Mexico</option>
                                    <option value="Midway Islands">Midway Islands</option>
                                    <option value="Moldova">Moldova</option>
                                    <option value="Monaco">Monaco</option>
                                    <option value="Mongolia">Mongolia</option>
                                    <option value="Montserrat">Montserrat</option>
                                    <option value="Morocco">Morocco</option>
                                    <option value="Mozambique">Mozambique</option>
                                    <option value="Myanmar">Myanmar</option>
                                    <option value="Nambia">Nambia</option>
                                    <option value="Nauru">Nauru</option>
                                    <option value="Nepal">Nepal</option>
                                    <option value="Netherland Antilles">Netherland Antilles</option>
                                    <option value="Netherlands">Netherlands (Holland, Europe)</option>
                                    <option value="Nevis">Nevis</option>
                                    <option value="New Caledonia">New Caledonia</option>
                                    <option value="New Zealand">New Zealand</option>
                                    <option value="Nicaragua">Nicaragua</option>
                                    <option value="Niger">Niger</option>
                                    <option value="Nigeria">Nigeria</option>
                                    <option value="Niue">Niue</option>
                                    <option value="Norfolk Island">Norfolk Island</option>
                                    <option value="Norway">Norway</option>
                                    <option value="Oman">Oman</option>
                                    <option value="Pakistan">Pakistan</option>
                                    <option value="Palau Island">Palau Island</option>
                                    <option value="Palestine">Palestine</option>
                                    <option value="Panama">Panama</option>
                                    <option value="Papua New Guinea">Papua New Guinea</option>
                                    <option value="Paraguay">Paraguay</option>
                                    <option value="Peru">Peru</option>
                                    <option value="Phillipines">Philippines</option>
                                    <option value="Pitcairn Island">Pitcairn Island</option>
                                    <option value="Poland">Poland</option>
                                    <option value="Portugal">Portugal</option>
                                    <option value="Puerto Rico">Puerto Rico</option>
                                    <option value="Qatar">Qatar</option>
                                    <option value="Republic of Montenegro">Republic of Montenegro</option>
                                    <option value="Republic of Serbia">Republic of Serbia</option>
                                    <option value="Reunion">Reunion</option>
                                    <option value="Romania">Romania</option>
                                    <option value="Russia">Russia</option>
                                    <option value="Rwanda">Rwanda</option>
                                    <option value="St Barthelemy">St Barthelemy</option>
                                    <option value="St Eustatius">St Eustatius</option>
                                    <option value="St Helena">St Helena</option>
                                    <option value="St Kitts-Nevis">St Kitts-Nevis</option>
                                    <option value="St Lucia">St Lucia</option>
                                    <option value="St Maarten">St Maarten</option>
                                    <option value="St Pierre & Miquelon">St Pierre & Miquelon</option>
                                    <option value="St Vincent & Grenadines">St Vincent & Grenadines</option>
                                    <option value="Saipan">Saipan</option>
                                    <option value="Samoa">Samoa</option>
                                    <option value="Samoa American">Samoa American</option>
                                    <option value="San Marino">San Marino</option>
                                    <option value="Sao Tome & Principe">Sao Tome & Principe</option>
                                    <option value="Saudi Arabia">Saudi Arabia</option>
                                    <option value="Senegal">Senegal</option>
                                    <option value="Seychelles">Seychelles</option>
                                    <option value="Sierra Leone">Sierra Leone</option>
                                    <option value="Singapore">Singapore</option>
                                    <option value="Slovakia">Slovakia</option>
                                    <option value="Slovenia">Slovenia</option>
                                    <option value="Solomon Islands">Solomon Islands</option>
                                    <option value="Somalia">Somalia</option>
                                    <option value="South Africa">South Africa</option>
                                    <option value="Spain">Spain</option>
                                    <option value="Sri Lanka">Sri Lanka</option>
                                    <option value="Sudan">Sudan</option>
                                    <option value="Suriname">Suriname</option>
                                    <option value="Swaziland">Swaziland</option>
                                    <option value="Sweden">Sweden</option>
                                    <option value="Switzerland">Switzerland</option>
                                    <option value="Syria">Syria</option>
                                    <option value="Tahiti">Tahiti</option>
                                    <option value="Taiwan">Taiwan</option>
                                    <option value="Tajikistan">Tajikistan</option>
                                    <option value="Tanzania">Tanzania</option>
                                    <option value="Thailand">Thailand</option>
                                    <option value="Togo">Togo</option>
                                    <option value="Tokelau">Tokelau</option>
                                    <option value="Tonga">Tonga</option>
                                    <option value="Trinidad & Tobago">Trinidad & Tobago</option>
                                    <option value="Tunisia">Tunisia</option>
                                    <option value="Turkey">Turkey</option>
                                    <option value="Turkmenistan">Turkmenistan</option>
                                    <option value="Turks & Caicos Is">Turks & Caicos Is</option>
                                    <option value="Tuvalu">Tuvalu</option>
                                    <option value="Uganda">Uganda</option>
                                    <option value="United Kingdom">United Kingdom</option>
                                    <option value="Ukraine">Ukraine</option>
                                    <option value="United Arab Erimates">United Arab Emirates</option>
                                    <option value="United States of America">United States of America</option>
                                    <option value="Uraguay">Uruguay</option>
                                    <option value="Uzbekistan">Uzbekistan</option>
                                    <option value="Vanuatu">Vanuatu</option>
                                    <option value="Vatican City State">Vatican City State</option>
                                    <option value="Venezuela">Venezuela</option>
                                    <option value="Vietnam">Vietnam</option>
                                    <option value="Virgin Islands (Brit)">Virgin Islands (Brit)</option>
                                    <option value="Virgin Islands (USA)">Virgin Islands (USA)</option>
                                    <option value="Wake Island">Wake Island</option>
                                    <option value="Wallis & Futana Is">Wallis & Futana Is</option>
                                    <option value="Yemen">Yemen</option>
                                    <option value="Zaire">Zaire</option>
                                    <option value="Zambia">Zambia</option>
                                    <option value="Zimbabwe">Zimbabwe</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputPassword1">Restaurant City</label>
                        <input type="text" class="form-control" id="exampleInputName1"
                               name="Restaurant_City" placeholder="Restaurant Country" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="exampleInputPassword1">Cuisine Type</label>
                        <select class="form-control select2" name="Restaurant_type" style="width: 100%;" required>
                            <option value="Ethiopian Cuisine">Ethiopian Cuisine</option>
                            <option value="Chinese Cuisine">Chinese Cuisine</option>
                            <option value="Indian Cuisine">Indian Cuisine</option>
                            <option value="Thai Cuisine">Thai Cuisine</option>
                            <option value="Italian Cuisine">Italian Cuisine</option>
                            <option value="French Cuisine">French Cuisine</option>
                            <option value="Middle Eastern Cuisine">Middle Eastern Cuisine</option>
                            <option value="British Cuisine">British Cuisine</option>
                            <option value="German Cuisine">German Cuisine</option>
                            <option value="Nigerian Cuisine">Nigerian Cuisine</option>
                            <option value="American Cuisine">American Cuisine</option>
                            <option value="Japanese Cuisine">Japanese Cuisine</option>
                            <option value="Greek Cuisine">Greek Cuisine</option>
                            <option value="Vitnamese Cuisine">Vitnamese Cuisine</option>
                            <option value="Brazilian Cuisine">Brazilian Cuisine</option>
                            <option value="International Cuisine">International Cuisine</option>
                        </select>
                    </div>
                    
                        <!-- <div class="form-group">
                        <label for="exampleInputPassword1">Restaurant Type</label>
                        <input type="text" class="form-control" id="exampleInputName1"
                               name="Restaurant_type" placeholder="Restaurant Type" required>
                    </div>-->
                    <div class="row form-group">
                        <div class="col-12">
                            <div class="form-group">
                                <label>Reservation Update Type</label>
                                <select class="form-control select2" name="Reservation_update_type" style="width: 100%;" required>
                                    <option value="Automatic">Automatic</option>
                                    <option value="Manual">Manual</option>
                                </select>
                            </div>
                        </div>
                    </div>
                  <!-- <div class="form-group">
                    <label for="exampleInputPassword1">Restaurant Working Hours</label>
                    <input type="text" class="form-control" id="exampleInputName1" required name="Restaurant_hours" placeholder="Restaurant Address" >
                  </div>-->
                  <div class="form-group">
                    <label for="exampleInputFile">Restaurant Photo</label>
                    <div class="input-group">
                      <div class="custom-file">
                        <input type="file" name="Restaurant_photo" class="custom-file-input" id="exampleInputFile">
                        <label class="custom-file-label" for="exampleInputFile">Choose Photo</label>
                      </div>
                    </div>
                  </div>
                    <div class="form-group">
                        <label for="exampleInputFile">Restaurant Logo</label>
                        <div class="input-group">
                            <div class="custom-file">
                                <input type="file" name="Restaurant_logo" class="custom-file-input" id="exampleInputFile">
                                <label class="custom-file-label" for="exampleInputFile">Choose Logo</label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Restaurant Working Days</label>
                        <div class="row input-group" id="working_days">
                            <div class="col-sm-1" style="width: 300px; margin: 0 auto; text-align: center;">
                                <div class="form-group">
                                    <label>Monday</label>
                                    <input class="form-control form-check-input" value="no" name="monday" type="checkbox" id="monday">
                                </div>
                            </div>
                            <div class="col-sm-1" style="width: 300px; margin: 0 auto; text-align: center;">
                                <div class="form-group">
                                    <label>Tuesday</label>
                                    <input class="form-control form-check-input" value="no" name="tuesday" type="checkbox" id="tuesday">
                                </div>
                            </div>
                            <div class="col-sm-1" style="width: 300px; margin: 0 auto; text-align: center;">
                                <div class="form-group">
                                    <label for="exampleInputFile">Wednesday</label>
                                    <input class="form-control form-check-input" value="no" name="wednesday" type="checkbox" id="wednesday">
                                </div>
                            </div>
                            <div class="col-sm-1" style="width: 300px; margin: 0 auto; text-align: center;">
                                <div class="form-group">
                                    <label for="exampleInputFile">Thursday</label>
                                    <input class="form-control form-check-input" value="no" name="thursday" type="checkbox" id="thursday">
                                </div>
                            </div>
                            <div class="col-sm-1" style="width: 300px; margin: 0 auto; text-align: center;">
                                <div class="form-group">
                                    <label for="exampleInputFile">Friday</label>
                                    <input class="form-control form-check-input" value="no" name="friday" type="checkbox" id="friday">
                                </div>
                            </div>
                            <div class="col-sm-1" style="width: 300px; margin: 0 auto; text-align: center;">
                                <div class="form-group">
                                    <label for="exampleInputFile">Saturday</label>
                                    <input class="form-control form-check-input" value="no" name="saturday" type="checkbox" id="saturday">
                                </div>
                            </div>
                            <div class="col-sm-1" style="width: 300px; margin: 0 auto; text-align: center;">
                                <div class="form-group">
                                    <label for="exampleInputFile">Sunday</label>
                                    <input class="form-control form-check-input" value="no" name="sunday" type="checkbox" id="sunday">
                                </div>
                            </div>
                        </div>
                    </div>
                    <br>
                  <input type="hidden" name="user_id" value="{{Auth::user()->id}}">
                    <div class="row form-group">
                        <div class="col-6">
                            <!-- time Picker -->
                            <div class="bootstrap-timepicker">
                                <div class="form-group">
                                    <label>Restaurant Opening Hour</label>
                                    <div class="input-group date" id="timepicker" data-target-input="nearest">
                                        <input type="time" name="restaurant_opening_hour"
                                               class="form-control datetimepicker-input" data-target="#timepicker"/>
                                    </div>
                                    <!-- /.input group -->
                                </div>
                                <!-- /.form group -->
                            </div>
                        </div>
                        <div class="col-6">
                            <!-- time Picker -->
                            <div class="bootstrap-timepicker">
                                <div class="form-group">
                                    <label>Restaurant Closing Hour</label>
                                    <div class="input-group date" id="timepicker" data-target-input="nearest">
                                        <input type="time" name="restaurant_closing_hour"
                                               class="form-control datetimepicker-input" data-target="#timepicker"/>

                                    </div>
                                    <!-- /.input group -->
                                </div>
                                <!-- /.form group -->
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <div class="row">
                            <label>Functioning Hours</label>
                        </div>
                        <div class="row" style="margin: 0 auto; float: left;">
                            <div class="col-sm-3" style="margin: 0 auto; float: left;">
                                <div class="form-group" style="margin: 0 auto; text-align: left;">
                                    <label>Breakfast</label>
                                    <input class="form-control form-check-input" value="yes" type="checkbox" id="breakfast" onchange="myFunction()">
                                </div>
                            </div>
                            <div class="col-sm-1"></div>
                            <div class="col-sm-3" style=" margin: 0 auto; float: left;">
                                <div class="form-group" style="margin: 0 auto; float: left;">
                                    <label>Lunch</label>
                                    <input class="form-control form-check-input" value="yes" type="checkbox" id="lunch" onchange="myFunction()">
                                </div>
                            </div>
                            <div class="col-sm-1"></div>
                            <div class="col-sm-3" style="margin: 0 auto; float: left;">
                                <div class="form-group" style="margin: 0 auto; float: left;">
                                    <label>Dinner</label>
                                    <input class="form-control form-check-input" value="yes" type="checkbox" id="dinner" onchange="myFunction()">
                                </div>
                            </div>
                        </div>
                        <br>
                        <br>
                    </div>
                    <br>
                    <br>
                    
                    <div class="form-group" style="display: none;" id="breakfast_hours">
                        <label>Breakfast Hours</label>
                        <div class="row input-group">
                            <div class="col-6">
                                <!-- time Picker -->
                                <div class="bootstrap-timepicker">
                                    <div class="form-group">
                                        <label>Start</label>
                                        <div class="input-group date" id="timepicker" data-target-input="nearest">
                                            <input type="time" name="breakfast_start_hour"
                                                   class="form-control datetimepicker-input" data-target="#timepicker"/>
                                        </div>
                                        <!-- /.input group -->
                                    </div>
                                    <!-- /.form group -->
                                </div>
                            </div>
                            <div class="col-6">
                                <!-- time Picker -->
                                <div class="bootstrap-timepicker">
                                    <div class="form-group">
                                        <label>End</label>
                                        <div class="input-group date" id="timepicker" data-target-input="nearest">
                                            <input type="time" name="breakfast_end_hour"
                                                   class="form-control datetimepicker-input" data-target="#timepicker"/>
                                        </div>
                                        <!-- /.input group -->
                                    </div>
                                    <!-- /.form group -->
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group" style="display: none;" id="lunch_hours">
                        <label>Lunch Hours</label>
                        <div class="row input-group">
                            <div class="col-6">
                                <!-- time Picker -->
                                <div class="bootstrap-timepicker">
                                    <div class="form-group">
                                        <label>Start</label>
                                        <div class="input-group date" id="timepicker" data-target-input="nearest">
                                            <input type="time" name="lunch_start_hour"
                                                   class="form-control datetimepicker-input" data-target="#timepicker"/>
                                        </div>
                                        <!-- /.input group -->
                                    </div>
                                    <!-- /.form group -->
                                </div>
                            </div>
                            <div class="col-6">
                                <!-- time Picker -->
                                <div class="bootstrap-timepicker">
                                    <div class="form-group">
                                        <label>End</label>
                                        <div class="input-group date" id="timepicker" data-target-input="nearest">
                                            <input type="time" name="lunch_end_hour"
                                                   class="form-control datetimepicker-input" data-target="#timepicker"/>
                                        </div>
                                        <!-- /.input group -->
                                    </div>
                                    <!-- /.form group -->
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group" style="display: none;" id="dinner_hours">
                        <label>Dinner Hours</label>
                        <div class="row input-group">
                            <div class="col-6">
                                <!-- time Picker -->
                                <div class="bootstrap-timepicker">
                                    <div class="form-group">
                                        <label>Start</label>
                                        <div class="input-group date" id="timepicker" data-target-input="nearest">
                                            <input type="time" name="dinner_start_hour"
                                                   class="form-control datetimepicker-input" data-target="#timepicker"/>
                                        </div>
                                        <!-- /.input group -->
                                    </div>
                                    <!-- /.form group -->
                                </div>
                            </div>
                            <div class="col-6">
                                <!-- time Picker -->
                                <div class="bootstrap-timepicker">
                                    <div class="form-group">
                                        <label>End</label>
                                        <div class="input-group date" id="timepicker" data-target-input="nearest">
                                            <input type="time" name="dinner_end_hour"
                                                   class="form-control datetimepicker-input" data-target="#timepicker"/>
                                        </div>
                                        <!-- /.input group -->
                                    </div>
                                    <!-- /.form group -->
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row form-group">
                        <div class="col-6">
                            <div class="form-group">
                                <label>Duration</label>
                                <select class="form-control select2" name="Restaurant_duration" style="width: 100%;">
                                    <option value="30">30min</option>
                                    <option value="60">1hr</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label for="exampleInputPassword1">Restaurant Maximum Capacity</label>
                                <input type="number" class="form-control" name="Restaurant_max_capacity" value="1"
                                       placeholder="Restaurant Maximum Capacity" required>
                            </div>
                        </div>
                    </div>
                    <!-- Color Picker -->
                  <div class="form-group">
                    <label>Restaurant Color</label>
                    <input type="text" name="Restaurant_color" class="form-control my-colorpicker1">
                  </div>
                  <!-- /.form group -->
                  <div class="col-sm-6">
                      <!-- textarea -->
                      <div class="form-group">
                        <label>Restaurant Description</label>
                        <textarea class="form-control" name="Restaurant_description" rows="3"
                                  placeholder="Restaurant Description ..." maxlength = "500"></textarea>
                      </div>
                  </div>
                  <div class="form-group">
                    <label for="exampleInputPassword1">Restaurant GPS Coordinates </label>
                      <span> go to <a href="https://www.google.com/maps" target="_blank">google maps</a> </span>
                    <input type="text" class="form-control" id="exampleInputName1" required name="Restaurant_coordinates" placeholder="Restaurant GPS Coordinates">
                  </div>
                </div>
                <!-- /.card-body -->

                <div class="card-footer">
                  <button type="submit" class="btn btn-primary btn-block">Submit</button>
                </div>
              </form>
            </div>
            <!-- /.card -->

</div>
@endsection

@section('scripts')
<script>
  $(function () {

    //Colorpicker
    $('.my-colorpicker1').colorpicker()

  });

</script>
<script type="text/javascript">

function myFunction() {

    if($('#breakfast').prop('checked')) {
        $('#breakfast_hours').css('display','block');
    } else {
        $('#breakfast_hours').css('display','none');
    }

    if($('#lunch').prop('checked')) {
        $('#lunch_hours').css('display','block');
    } else {
        $('#lunch_hours').css('display','none');
    }

    if($('#dinner').prop('checked')) {
        $('#dinner_hours').css('display','block');
    } else {
        $('#dinner_hours').css('display','none');
    }

}

</script>
@endsection
