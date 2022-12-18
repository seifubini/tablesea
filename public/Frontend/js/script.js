
$(document).ready(function(){

    //javascript code for making navbar transparent and not transparent.

    var nav = document.querySelector('nav');
    window.addEventListener('scroll', function() {
        if(window.pageYOffset>100){
            nav.classList.add('nav_blue','shadow');
        }
        else{
            nav.classList.remove('nav_blue','shadow');
        }
    });

    $(function(){
        const get_all_restaurants = $("#get_all_restaurants").val();

        $.ajax({
            url: get_all_restaurants,
            type: 'get',
            dataType: 'json',
            success:function(all_restaurants){

                var options = {
                    data: all_restaurants,
                    getValue: "name",
                    list: {
                        match: {
                            enabled: true
                        }
                    }
                };

                $("#search-form").easyAutocomplete(options);
                $(".eac-item").css('color', '#000000');

            }

        });
    });

    $(function(){
        var dtToday = new Date();

        var month = dtToday.getMonth() + 1;
        var day = dtToday.getDate();
        var year = dtToday.getFullYear();
        
        var nowTime = new Date(Date.now());
        var hour = nowTime.getHours();
        var min = nowTime.getMinutes();
        var sec = nowTime.getSeconds();
        
        if(month < 10)
            month = '0' + month.toString();
        if(day < 10)
            day = '0' + day.toString();

        var maxDate = year + '-' + month + '-' + day;
        var currentTime =hour+':'+min+':'+sec;

        $('#reservation_date').attr('min', maxDate);
        $("#reservation_date").val(maxDate);
        const toaster = $('#toast');
        $("#reservation_time").change(function () {
            var reservation_date = $("#reservation_date").val();
            var reservation_time = $("#reservation_time").val();

            if(reservation_date == maxDate){
                if (reservation_time < currentTime){
                    $(toaster).css('display', 'flex');
                    $('#toast_message').text('You cannot reserve a past time.');
                    $(toaster).toast('show');
                    setTimeout(function() {
                        $(toaster).hide('blind', {}, 500)
                    }, 5000);
                    $("#reservation_time").val("");
                }

            }
        });
        $("#reservation_date").change(function () {
            var reservation_date = $("#reservation_date").val();
            if (reservation_date < maxDate){
                $(toaster).css('display', 'flex');
                    $('#toast_message').text('You cannot reserve a past date.');
                    $(toaster).toast('show');
                    setTimeout(function() {
                        $(toaster).hide('blind', {}, 500)
                    }, 5000);
                $("#reservation_date").val("");
            }

        });

    });

    
});

//seting current date by default.
$('#reservation_date').val(new Date().toJSON().slice(0,10));


/**$(function () {
  $('.bs-timepicker').timepicker();
});*/



//check restaurant and table availability
$(document).ready(function(){

    var dtToday = new Date();

    var month = dtToday.getMonth() + 1;
    var day = dtToday.getDate();
    var year = dtToday.getFullYear();
    
    var nowTime = new Date(Date.now());
    var hour = nowTime.getHours();
    var min = nowTime.getMinutes();
    var sec = nowTime.getSeconds();
        
    if(month < 10)
        month = '0' + month.toString();
    if(day < 10)
        day = '0' + day.toString();

    var maxDate = year + '-' + month + '-' + day;
    var currentTime =hour+':'+min+':'+sec;

    $('#check_button').click(function() {
        
        document.getElementById('cover-spin').style.display = "flex";

        setTimeout(function(){
            $('#cover-spin').css('display', 'none');
        }, 2000);

        const check_url = $("#check_url").val();
        const _token = $("input#_token").val();
        const reservation_date = $("#reservation_date").val();
        const reservation_time = $("#reservation_time").val();
        const number_of_people = $("#number_of_people").val();
        const Restaurant_name = $("#search-form").val();
        const toaster = $('#toast');

        if(reservation_date === ""){
            $(toaster).css('display', 'flex');
            $('#toast_message').text('Reservation date is required!');
            $(toaster).toast('show');
            setTimeout(function() {
                $(toaster).hide('blind', {}, 500)
            }, 5000);
        }
        if(reservation_time === ""){
            $(toaster).css('display', 'flex');
            $('#toast_message').text('Reservation time is required!');
            $(toaster).toast('show');
            setTimeout(function() {
                $(toaster).hide('blind', {}, 500)
            }, 5000);
        }
        if(number_of_people === ""){
            $(toaster).css('display', 'flex');
            $('#toast_message').text('Number of people is required!');
            $(toaster).toast('show');
            setTimeout(function() {
                $(toaster).hide('blind', {}, 500)
            }, 5000);
        }
        if(Restaurant_name === ""){
            $(toaster).css('display', 'flex');
            $('#toast_message').text('Restaurant name is required!');
            $(toaster).toast('show');
            setTimeout(function() {
                $(toaster).hide('blind', {}, 500)
            }, 5000);
        }
        if(reservation_date == maxDate){
            if (reservation_time < currentTime){
                $(toaster).css('display', 'flex');
                $('#toast_message').text('You cannot reserve a past time.');
                $(toaster).toast('show');
                setTimeout(function() {
                    $(toaster).hide('blind', {}, 500)
                }, 5000);
                $("#reservation_time").val("");
            }

        }
        if(reservation_date < maxDate){
            $(toaster).css('display', 'flex');
            $('#toast_message').text('You cannot reserve a past date.');
            $(toaster).toast('show');
            setTimeout(function() {
                $(toaster).hide('blind', {}, 500)
            }, 5000);
            $("#reservation_date").val("");
            
        }
        else{

            $.ajax({

                url:check_url,

                method:"POST",

                data: {

                    _token: _token,

                    reservation_date: reservation_date,

                    reservation_time: reservation_time,

                    number_of_people: number_of_people,

                    Restaurant_name: Restaurant_name

                },
                success:function(availability){
                    //success data
                    const value = availability.value;
                    const message = availability.message;

                    //if restaurant not found
                    if(value === "restaurant not found")
                    {
                        $(toaster).css('display', 'flex');
                        $('#toast_message').text('Restaurant not found in our system.');
                        $(toaster).toast('show');
                        setTimeout(function() {
                            $(toaster).hide('blind', {}, 500)
                        }, 5000);
                    }
                    if(value === "closed")
                    {
                        $(toaster).css('display', 'flex');
                        $('#toast_message').text('Restaurant is closed, try another date or time.');
                        $(toaster).toast('show');
                        setTimeout(function() {
                            $(toaster).hide('blind', {}, 500)
                        }, 5000);
                    }
                    if (value === "full") {
                        $(toaster).css('display', 'flex');
                        $('#toast_message').text('Restaurant is fully booked, try another date or time.');
                        $(toaster).toast('show');
                        setTimeout(function() {
                            $(toaster).hide('blind', {}, 500)
                        }, 5000);
                    } else {

                        //if table is not booked
                        if (value === "empty") {
                            const route = availability.route;
                            $(toaster).css('display', 'flex');
                            $('#toast_message').text('Tables are available on the selected Restaurant.');
                            $(toaster).toast('show');
                            setTimeout(function() {
                                $(toaster).hide('blind', {}, 500)
                            }, 5000);

                            window.location.replace(route);

                        }
                        //if table is booked execute this
                        if (value === "booked") {

                            $(toaster).css('display', 'flex');
                            $('#toast_message').text('Restaurant is fully booked, try another date or time.');
                            $(toaster).toast('show');
                            setTimeout(function() {
                                $(toaster).hide('blind', {}, 500)
                            }, 5000);
                            window.location.reload();
                        }

                    }

                },
            });
        }

    });

});

// jquery code to change navbar brand img on scroll.
$(document).ready(function() {

    $(window).scroll(function() {
    var nav_img = $('.navbar-brand img');
    if ($(document).scrollTop() < 100) {

        var blue_logo = $("#blue_logo").val();
        nav_img.attr('src', blue_logo);
        nav_img.attr("height","81px");
        nav_img.attr("width","65px");

    } else {

        var white_logo = $("#white_logo").val();
        nav_img.attr('src', white_logo);
        nav_img.attr("height","81px");
        nav_img.attr("width","65px");
    }
    });
});


// jquery code to change button color on scroll.
$(document).ready(function(){
    $(window).scroll(function(){
        var scroll = $(window).scrollTop();
        if (scroll > 100) {
          $(".btn_style1").css("background" , "#4d4d4d");
          $(".btn_style1").css("color" , "#fff");
        }

        else{
            $(".btn_style1").css("background" , "#0065A3");
            $(".btn_style1").css("color" , "#fff");
        }
    });

    $('.owl-carousel').owlCarousel({
        margin: 15,
        nav: true,
        autoplay:true,
        autoplayTimeout:6000,
        loop:true,
        dots:false,

        navText: [],
        responsive: {
            0: {
                items: 1
            },
            600: {
                items: 2
            },
            1000: {
                items: 3
            }
        }

    });


    //carousel script code

    var owl = $('.owl-carousel');
    owl.owlCarousel();
    // Go to the next item
    $('.owlnext').click(function() {
        owl.trigger('next.owl.carousel');

    });
    // Go to the previous item
    $('.owlprev').click(function() {
        // With optional speed parameter
        // Parameters has to be in square bracket '[]'
        owl.trigger('prev.owl.carousel', [300]);
    });

    $("[data-trigger]").on("click", function(){
        var trigger_id =  $(this).attr('data-trigger');
        $(trigger_id).toggleClass("show");
        $('body').toggleClass("offcanvas-active");
    });

    // close button
    $(".btn-close").click(function(e){
        $(".navbar-collapse").removeClass("show");
        $("body").removeClass("offcanvas-active");
    });

  });

      // Initialize and add the map
      /**function initMap() {

        const uluru = { lat: 9.031925, lng: 38.765226 };

        const map = new google.maps.Map(document.getElementById("map"), {
          zoom: 18,
          center: uluru,
        });

        const marker = new google.maps.Marker({
          position: uluru,
          map: map,
        });
      }*/


