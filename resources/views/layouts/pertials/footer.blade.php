<footer class="footer">
    <div class="container">
        <div class="row">
            <div class="col-sm-6 col-md-3 mb-xs-30 mb-sm-30">
                <div class="footer-block">
                    
                    {!!$footer?$footer->leftColumn:''!!}
                    {{--  <h5 class="text-bold text-uppercase">Company</h5>
                    <ul class="list-unstyled">
                        <li><a href="/about">About</a></li>
                        <li><a href="/pricing">Pricing</a></li>
                        <li><a href="/admin/login">Login</a></li>
                        <li><a href="/admin/login">Register</a></li>
                        <li><a href="/contact">Contact Us</a></li>
                    </ul>  --}}
                </div>
            </div>
            <div class="col-sm-6 col-md-3 mb-xs-30 mb-sm-30">
                <div class="footer-block">
                    {!!$footer? $footer->middleColumn: ''!!}
                    {{--  <h5 class="text-bold text-uppercase">Need Help?</h5>
                    <ul class="list-unstyled">
                        <li><a href="/contact">Contact</a></li>
                        <li><a href="#">FAQs</a></li>
                        <li><a href="#">Privacy Policy</a></li>
                        <li><a href="#">Terms of Use</a></li>
                        <li><a href="#">LET’S KEEP IN TOUCH</a></li>
                    </ul>  --}}
                </div>
            </div>
            <div class="col-sm-12 col-md-6 mb-xs-30">
                {!!$footer? $footer->rightColumn : ''!!}
                {{--  <div class="footer-block">
                    <div class="flex mb-10">
                        <h5 class="text-bold text-uppercase">LET’S KEEP IN TOUCH</h5>
                        <div class="language-select dropdown">
                            <button class="dropdown-toggle" type="button" data-toggle="dropdown"
                                    aria-expanded="true">
                                <img src="http://3.88.170.115:82/front_assets/img/flag.png" alt="" class="flag">
                                <span>En</span>
                                <i class="fa fa-chevron-down"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-right">
                                <li>
                                    <a href="#">
                                        <img src="{{asset('front_assets/img/flag.png')}}" alt="" class="flag">
                                        <span>De</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="#">
                                        <img src="{{asset('front_assets/img/flag.png')}}" alt="" class="flag">
                                        <span>Sp</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <p>We will take care of you. Stay updated with our social links
                    </p>

                    <div class="flex mt-30">
                        <ul class="list-unstyled social">
                            <li>
                                <a href="#">
                                    <i class="fa fa-facebook"></i>
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                    <i class="fa fa-instagram"></i>
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                    <i class="fa fa-twitter"></i>
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                    <i class="fa fa-youtube-play"></i>
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                    <i class="fa fa-pinterest"></i>
                                </a>
                            </li>
                        </ul>
                        <div class="stores">
                             <a href="#"><img src="{{asset('front_assets/img/app-store.png')}}" alt=""></a>
                            <a href="#"><img src="{{asset('front_assets/img/play-store.png')}}" alt=""></a>
                        </div>
                    </div>
                </div>  --}}
            </div>
        </div>
    </div>
    <div class="copyright text-center">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    {!! $footer? $footer->bottomRow : ''!!}
                    {{--  <p>All Rights Reserved Zcon Services, LLC, 2021 | Powered by <a href="#">Ameltek</a>, <a href="#">Inc</a>.</p>  --}}
                </div>
            </div>
        </div>
    </div>
</footer>
<script>
    window.onscroll = function() {myFunction()};

    var sticker = document.getElementById("sticker");
	var sticker_body = document.querySelector("body");
    var sticky = sticker.offsetTop;

    function myFunction() {
        if (window.pageYOffset >= 10) {
		
            sticker.classList.add("sticky");
        	sticker_body.classList.add("stickysc");
        } else {
        
            sticker.classList.remove("sticky");
        // $('#sticker').fadeIn(1000);
        	sticker_body.classList.remove("stickysc");
        }
    };
           


</script>
<script src="js/function.js" type="text/javascript"></script>
<script src="https://kit.fontawesome.com/c860a299a7.js" crossorigin="anonymous"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.1/dist/umd/popper.min.js" integrity="sha384-SR1sx49pcuLnqZUnnPwx6FCym0wLsk5JZuNx2bPPENzswTNFaQU1RDvt3wT4gWFG" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/js/bootstrap.min.js" integrity="sha384-j0CNLUeiqtyaRmlzUHCPZ+Gy5fQu0dQ6eZ/xAww941Ai1SxSY+0EQqNXNE6DZiVc" crossorigin="anonymous"></script>
<script src="{{asset('front_assets/js/scripts.js')}}"></script>






{!! Html::script('js/jquery-1.11.3.js') !!}
  {!! Html::script('js/jquery-ui.js') !!}

  {!! Html::script('js/angular.js') !!}
  {!! Html::script('js/angular-sanitize.js') !!}
  <script>
    var app = angular.module('App', ['ngSanitize']);
    var APP_URL = {!! json_encode(url('/')) !!};
    var LOGIN_USER_TYPE = '{!! LOGIN_USER_TYPE !!}';
    var STRIPE_PUBLISH_KEY = "{{ payment_gateway('publish','Stripe') }}";
  </script>

  {!! Html::script('js/common.js?v='.$version) !!}
  {!! Html::script('js/user.js?v='.$version) !!}
  {!! Html::script('js/main.js?v='.$version) !!}
  {!! Html::script('js/bootstrap.min.js') !!}
  {!! Html::script('js/jquery.bxslider.min.js') !!}
  {!! Html::script('js/jquery.sliderTabs.min.js') !!}
  {!! Html::script('js/responsiveslides.js?v='.$version) !!}

<script>
    function myFunction() {
      document.getElementById("myDropdown").classList.toggle("show");
    }
    
    // Close the dropdown if the user clicks outside of it
    window.onclick = function(event) {
      if (!event.target.matches('.ddropdown-menu-right')) {
        var dropdowns = document.getElementsByClassName(".dropbtn");
        var i;
        for (i = 0; i < dropdowns.length; i++) {
          var openDropdown = dropdowns[i];
          if (openDropdown.classList.contains('show')) {
            openDropdown.classList.remove('show');
          }
        }
      }
    }
</script>


</body>

</html>
   
