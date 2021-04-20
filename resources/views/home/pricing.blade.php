@extends('layouts.main')
@section('main.content')
        
    <!-- 1st-section -->
    <section class="1st-section my-5">
        <div class="container">
            <div class="row">
                <div class="col-md-6 co-sm-12">
                    <h2 class="">Our approach to pricing</h2>
                    <p class="my-3 lh-lg">Your business is only billed our standard rates for rides and meal delivery. Are you a large business looking for custom solutions? <a href="#">Contact us</a>.</p>
                    <a href="#" class="btn btn-dark rounded-0 py-3">Get Strated for Free</a>
                </div>
            </div>
        </div>
    </section>
    <!-- /1st-section -->

    <!-- 2nd section -->
    <section class="2nd-section">
        <div class="container">
            <div class="row">
                <div class="col-md-6 col-sm-12">
                    <div class="card border-0">
                        <img src="{{asset('front_assets/img/women_rider.jpg')}}" class="card-img-top rounded-0" alt="...">
                        <div class="card-body px-0">
                        <h5 class="card-title">No service fees </h5>
                        <p class="card-text">Customers that sign up directly and don't require custom solutions never pay service fees. Period.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-sm-12">
                    <div class="card border-0">
                        <img src="{{asset('front_assets/img/man_rider.jpg')}}" class="card-img-top rounded-0" alt="...">
                        <div class="card-body px-0">
                        <h5 class="card-title">Standard rates only</h5>
                        <p class="card-text">The prices for rides and meals are the same for business and personal use.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- /2nd section -->

    <!-- 3rd section -->
    <section class="3rd-section">
        <div class="container">
            <div class="row">
                <div class="col-md-12 my-5 pt-5">
                    <h2>Frequently asked questions</h2>
                    <div class="accordion accordion-flush" id="accordionFlushExample">

                        <div class="accordion-item">
                          <h2 class="accordion-header" id="flush-headingOne">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseOne" aria-expanded="false" aria-controls="flush-collapseOne">
                                What expense management systems does Uber for Business integrate with? 
                            </button>
                          </h2>
                          <div id="flush-collapseOne" class="accordion-collapse collapse" aria-labelledby="flush-headingOne" data-bs-parent="#accordionFlushExample">
                            <div class="accordion-body">We integrate with Certify, Chrome River, Expensify, Expensya, Fraedom, Happay, Rydoo, SAP Concur, Serko, Zeno, and Zoho Expense.</div>
                          </div>
                        </div>

                        <div class="accordion-item">
                          <h2 class="accordion-header" id="flush-headingTwo">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseTwo" aria-expanded="false" aria-controls="flush-collapseTwo">
                                What billing options are available? 
                            </button>
                          </h2>
                          <div id="flush-collapseTwo" class="accordion-collapse collapse" aria-labelledby="flush-headingTwo" data-bs-parent="#accordionFlushExample">
                            <div class="accordion-body">The default billing option is pay per trip. Monthly billing is available to accounts that spend more than $2,500 a month.</div>
                          </div>
                        </div>

                        <div class="accordion-item">
                          <h2 class="accordion-header" id="flush-headingThree">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseThree" aria-expanded="false" aria-controls="flush-collapseThree">
                                When do I get charged for a ride or meal?
                            </button>
                          </h2>
                          <div id="flush-collapseThree" class="accordion-collapse collapse" aria-labelledby="flush-headingThree" data-bs-parent="#accordionFlushExample">
                            <div class="accordion-body">After you arrive at your destination or receive your meal delivery, your final cost will be automatically calculated and charged to the payment method you’ve set.</div>
                          </div>
                        </div>

                        <div class="accordion-item">
                            <h2 class="accordion-header" id="flush-headingFour">
                              <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseFour" aria-expanded="false" aria-controls="flush-collapseFour">
                                How do I get a price estimate in the app?
                              </button>
                            </h2>
                            <div id="flush-collapseFour" class="accordion-collapse collapse" aria-labelledby="flush-headingFour" data-bs-parent="#accordionFlushExample">
                              <div class="accordion-body">Open the app and input your destination in the “Where to?” box. The price estimate for each ride option will appear.</div>
                            </div>
                        </div>

                        <div class="accordion-item">
                            <h2 class="accordion-header" id="flush-headingFive">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseFive" aria-expanded="false" aria-controls="flush-collapseFive">
                                    How are prices estimated? 
                                </button>
                            </h2>
                            <div id="flush-collapseFive" class="accordion-collapse collapse" aria-labelledby="flush-headingFive" data-bs-parent="#accordionFlushExample">
                                <div class="accordion-body">In most cities, your cost is calculated up front, before you confirm your ride. In others, you’ll see an estimated price range.</div>
                            </div>
                        </div>
                        
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- /3rd section -->

    <!-- 4th section -->
    <section class="4th-section">
        <div class="container">
            <div class="about-started py-5 text-center">
                <h3 class="lh-base pb-2">Your business is going places. We’re here to help.</h3>
                <a href="#" class="btn btn-dark">Get Started for Free</a>
            </div>
        </div>
    </section>
    <!-- /4th section -->
@endsection