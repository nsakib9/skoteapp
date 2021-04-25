@extends('layouts.main')
@section('main.content')
<div class="flash-container">
  @if(Session::has('message'))
  <div class="alert text-center participant-alert " style="    background: #1fbad6 !important;color: #fff !important;margin-bottom: 0;" role="alert">
    <a href="#" class="alert-close text-white" data-dismiss="alert">&times;</a>
    {!! Session::get('message') !!}
  </div>
  @endif
</div>





{{-- new design page  --}}

<section class="signup-rider">
  <div class="content-signupdrive " >
    @include('user.otp_popup')
      <div class="join-page" style="margin-top: 25px">
          <div class="layout layout--join layout--hero driver_banner_join">
              <div class="layout__item layout--join__left-item secondary-font text-white hidden--portable float--left" style="padding:0px;">
                  <!--<div class="bit bit&#45;&#45;logo text&#45;&#45;center" data-reactid="6" style="padding: 0px !important;">
                      <a href="http://3.88.170.115:82">
                          <img class="white_logo" src="http://3.88.170.115:82/images/logo.png" style="width: 109px; height: 50px;background-size: contain;">
                      </a>
                  </div>-->
                  <h1 class="push--bottom" style="line-height: 1.14; font-weight: 200; text-align: left; margin-bottom: 48px; letter-spacing: -0.02em; font-size: 50px ! important;">Zcon needs partners like you.
                  </h1>
                  <p style="width: 70%; font-weight: 400; text-align: left;">Drive With Zcon and earn great money as an independent contractor. Get paid weekly just for helping our community of riders get rides around town. Be your own boss and get paid in fares for driving on your own schedule.
                  </p>
              </div>
              <div class="layout__item layout--join__hero-item soft-gutter z-30" style="padding-top:0 !important;">
                  <div class="layout" style=" margin-left: -40px;">
                      <div class="layout__item driver-signup-form-container">
                                      {{ Form::open(array('url' => 'driver_register','class' => 'layout layout--flush driver-signup-form-join-legacy','id'=>'form')) }}
            {{csrf_field()}}
            {!! Form::hidden('request_type', '', ['id' => 'request_type' ]) !!}
            {!! Form::hidden('otp', '', ['id' => 'otp' ]) !!}
                              <input type="hidden" name="_token" value="YgYceUMPY1xDEqysyhv7WpjFlwsgNlRWfx3lu9dN">
                              <input id="request_type" name="request_type" type="hidden" value="">
                              <input id="otp" name="otp" type="hidden" value="">
                              <input type="hidden" name="user_type" value="Driver">
                              <div name="driver-signup-form-scroll">
                              </div>
                              <div class="layout__item one-whole push-tiny--ends" style="margin-top:12px; ">
                                  <div class="layout__item one-whole">
                                  </div>
                              </div>
							  
							  
<div class="layout__item one-whole createacc" >
              <a class="btn btn--primary btn--full" href="{{ url('signin_driver')}}" style="background-color: white !important;border:none;padding:12px;" >
                <span class="micro" style="line-height: 2.4; padding-left: 5px; font-size: 11px; text-transform: uppercase;">Already have an account?</span>
              </a>
              <h6 style="margin: 12px 0px; font-weight: 500; font-size: 16px; letter-spacing: 0.005em;" >User Create Account
              </h6>
            </div>							  
							  
							  
                               <input type="hidden" name="_token" value="{{ csrf_token() }}">
                              <div class="mobile-code">
                                  <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 push-tiny--bottom--legacy pull-left" id="pad-sm-right" style="padding:0px;">
                                      <div class="_style_3EKecM">
                                           {!! Form::text('last_name', '', ['class' => '_style_3vhmZK','placeholder' => trans('Lastname'),'id' => 'lname' ]) !!}
                                      </div>
                                      <span class="text-danger last_name_error">{{ $errors->first('last_name') }}</span>
                                  </div>
                                  <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 legacy-one-half push-tiny--bottom--legacy" id="pad-sm-right" style="padding:0px;">
                                      <div class="_style_3EKecM">
                                          {!! Form::text('email', '', ['class' => '_style_3vhmZK','placeholder' => trans('Email') ]) !!}
                                      </div>
                                     <span class="text-danger email_error">{{ $errors->first('email') }}</span>

                                  </div>
                              </div>
<!--
                              <div class="layout__item one-whole push-tiny--bottom--legacy">
                                  <div class="_style_3EKecM">
                                      <input class="_style_3vhmZK" placeholder="Email" name="email" type="text" value="">
                                  </div>
                                  <span class="text-danger email_error"></span>
                              </div>
							  -->
                              <div class="mobile-code">
                                  <div class="layout col-md-12 layout--flush float mobile-container left two-char" >
								  
                
                <div id="select-title-stage">{{old('country_code')!=null ? '+'.old('country_code') : '+880' }}</div>
                <input type="hidden" name="country_code" value="{{ old('country_code',(isset($country_code) ? $country_code : '')) }}">
                <div class="select select--xl" ng-init="old_country_code={{old('country_code')!=null? old('country_code') : '1'}}">
                  <label for="mobile-country"><div class="flag US"></div></label>
                  <select name="country_code" tabindex="-1" id="mobile_country" class="square borderless--right">
                    @foreach($country as $key => $value)
                    <option value="{{$value->phone_code}}" {{ ($value->id == (old('country_id')!=null? old('country_id') : '1')) ? 'selected' : ''  }} data-value="+{{ $value->phone_code}}" data-id="{{ $value->id }}">{{ $value->long_name}}
                    </option>
                    @endforeach
                    {!! Form::hidden('country_id', old('country_id'), array('id'=>'country_id')) !!}
                  </select>
                  <span class="text-danger country_code_error">{{ $errors->first('country_code') }}</span>
                  
                </div>
              </div>
                                  
								  <div class="layout__item one-whole push-tiny--bottom--legacy" >
                <div class="_style_3EKecM" ng-init="old_mobile_number='{{old('mobile_number')!=null?old('mobile_number'):''}}'">
                  {!! Form::tel('mobile_number', isset($phone_number)?$phone_number:'', ['class' => '_style_3vhmZK','placeholder' => trans('Mobile Number'),'id' => 'mobile' ]) !!}
                </div>
                <!-- <span class="text-danger mobile-text-danger" style="display: none">Mobile Number is required</span>            -->
                <span class="text-danger mobile_number_error">{{ $errors->first('mobile_number') }}</span>
              </div>
                              </div>
                              <div class="layout__item one-whole push-tiny--bottom--legacy" >
              <div class="_style_3EKecM" >
                {!! Form::password('password', array('class' => '_style_3vhmZK','placeholder' => trans('Password'),'id' => 'password') ) !!}
              </div>
              <span class="text-danger password_error">{{ $errors->first('password') }}</span>
              
            </div>
                              <div class="layout__item one-whole push-tiny--bottom--legacy" >
              <div >
                <div style="position:relative;" >
                  <div style="background-color:#FFFFFF;border-color:#E5E5E4;border-style:solid;border-width:1px;box-sizing:border-box;height:auto;flex-wrap:wrap;margin-bottom:24px;transition:all 400ms ease;margin:0;border-radius:3px;-moz-box-sizing:border-box;-webkit-flex-wrap:wrap;-ms-flex-wrap:wrap;-webkit-transition:all 400ms ease;-webkit-box-lines:multiple;" class="_style_3jmRTe" >
                    <div class="autocomplete-input-container">
                      <div class="autocomplete-input">
                        {!! Form::text('home_address', '', ['class' => '_style_3vhmZK','placeholder' => trans('Profile_city'),'id' => 'home_address','autocomplete' => 'false','style' => 'width:']) !!}
                      </div>
                      <ul class="autocomplete-results home_address">
                      </ul>
                    </div>
                    
                    
                    <input type="hidden" name="city" id='city' value="">
                    <input type="hidden" name="state" id="state" value="">
                    <input type="hidden" name="country" id="country" value="">
                    <input type="hidden" name="address_line1" id="address_line1" value="">
                    <input type="hidden" name="address_line2" id="address_line2" value="">
                    <input type="hidden" name="postal_code" id="postal_code">
                    <input type="hidden" name="latitude" id="latitude" value="">
                    <input type="hidden" name="longitude" id="longitude" value="">
                  </div>
                  <span class="text-danger home_address_error">{{ $errors->first('home_address') }}</span>
                  <div style="box-sizing:border-box;border:1px solid #E5E5E4;position:absolute;width:100%;background:#FFFFFF;z-index:1000;visibility:hidden;-moz-box-sizing:border-box;" >
                    <div style="max-height:300px;overflow:auto;" >
                      <div aria-live="assertive" >
                        <div style="font-family:ff-clan-web-pro, &quot;Helvetica Neue&quot;, Helvetica, sans-serif;font-weight:400;font-size:14px;line-height:24px;padding:8px 18px;border-bottom:1px solid #E5E5E4;" class="_style_1cBulK" >No results
                        </div>
                      </div>
                    </div>
                    
                  </div>
                </div>
              </div>
            </div>

							  
                              <div class="layout__item one-whole push-tiny--bottom--legacy" >
              <div class="_style_3EKecM" >
                {!! Form::text('referral_code','', array('class' => '_style_3vhmZK text-uppercase','placeholder' => trans('Referral_code'),'id' => 'referral_code') ) !!}
              </div>
              <span class="text-danger referral_code_error">{{ $errors->first('home_address') }}</span>
            </div>
                              <div class="layout__item one-whole push-small--bottom" >
              <input type="hidden" name="step" value="basics">
              <button name="step" value="basics" class="btn--arrow btn--full error-retry-btn _style_3CjDXv" id="submit-btn" ng-click="showPopup('send_otp');" type="button"  style="box-sizing:border-box;text-decoration:none;color:#FFFFFF;display:inline-block;vertical-align:middle;text-align:center;margin:0;cursor:pointer;overflow:visible;background-color:#11939A;font-family:ff-clan-web-pro, &quot;Helvetica Neue&quot;, Helvetica, sans-serif;font-weight:600;font-size:14px;padding:11px 20px;border-radius:0px;border:2px solid #11939A;text-transform:uppercase;outline:none;line-height:18px;position:relative;transition:all 400ms ease;-moz-box-sizing:border-box;-webkit-transition:all 400ms ease;" >SUBMIT
              </button>
            </div>
			<div class="layout__item one-whole">
              <p class="legal flush">By proceeding, I agree that Zcon or its representatives may contact me by email, phone, or SMS (including by automated means) at the email address or number I provide, including for marketing purposes.</p>
            </div>

                              <span>
          </span>
                              <input type="hidden" name="code" id="code" />
            {{ Form::close() }}
                      </div>
                  </div>
              </div>
          </div>
          <div class="three-section-join ">
              <div class="container">
                  <div class="portable-space clearfix">
                      <div class="top-section-sub clearfix row" style="margin-top:10px;">
						  <div class="col-md-4 col-xs-12 text-center" >
            <div class="top-section-sub-cont" >
              <div class="" style="height:100px;width:100px;" >
                <img src="images/icon/money_good.jpg" style="vertical-align:middle;height:100px;width:100px;" >
              </div>
              <div class="sub-top" >
                <h4 >Make good money.
                </h4>
                <div class="" >
                  <p>Got a car? Turn it into a money machine. The city is buzzing and Zcon makes it easy for you  to cash in on the action. Plus, you've already got everything you need to get started.
                                          </p>
                </div>
              </div>
            </div>
          </div>
			 <div class="col-md-4 col-xs-12 text-center" >
            <div class="top-section-sub-cont" >
              <div class="" style="height:100px;width:100px;" >
                <img src="images/icon/drive_when.jpg" style="vertical-align:middle;height:100px;width:100px;" >
              </div>
              <div class="sub-top" >
                <h4 >Drive when you want.</h4>
                <div class="" >
                   <p>Need something outside the 9 to 5? As an independent contractor with Zcon, you’ve got freedom and flexibility to drive whenever you have time. Set your own schedule, so you can be there for all of life’s most important moments.
                                          </p>
                </div>
              </div>
            </div>
          </div>			  
						  
			<div class="col-md-4 col-xs-12 text-center" >
            <div class="top-section-sub-cont" >
              <div class="" style="height:100px;width:100px;">
                <img src="images/icon/no_office.jpg" style="vertical-align:middle;height:100px;width:100px;" >
              </div>
              <div class="sub-top" >
                <h4 >No office, no boss.</h4>
                <div class="" >
                  <p>Whether you’re supporting your family or saving for something big, Zcon gives you the freedom to get behind the wheel when it makes sense for you. Choose when you drive, where you go, and who you pick up.
                                          </p>
                </div>
              </div>
            </div>
          </div>			  
						  
                          
                      </div>
                  </div>
              </div>
          </div>
		  
		  

		  
          <div class="bg-uber-white-footer">
              <div class="col-md-12">
                  <div class="layout">
                      <div class="copyrightsection col-md-6 text-left hide">
                          <p>© 2021 Zcon
                          </p>
                      </div>
                      <div class="col-md-3 col-sm-6 col-xs-2 privacy text-left hide">
                          <a href="#" class="">
                              <!-- react-text: 110 -->Privacy
                              <!-- /react-text -->
                          </a>
                      </div>
                      <div class="col-md-3 col-sm-6 col-xs-6 termss text-left hide">
                          <a href="#" class="">
                              <!-- react-text: 113 -->Terms
                              <!-- /react-text -->
                          </a>
                      </div>
                  </div>
              </div>
          </div>
      </div>
  </div>
</section>









<style>
.logo-link
{
display: none;
}
.funnel
{
height: 0px !important;
}

</style>
@stop
