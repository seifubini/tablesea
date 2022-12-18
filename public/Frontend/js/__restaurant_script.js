//javascript code for making navbar transparent and not transparent.
$(document).ready(function(){
    
var nav = document.querySelector('nav');
window.addEventListener('scroll', function() {
    if(window.pageYOffset>100){
        nav.classList.add('nav_blue','shadow');
    }
    else{
        nav.classList.remove('nav_blue','shadow');
    }
});

//seting current date by default.
$('#reservation_date').val(new Date().toJSON().slice(0,10));

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

    if($("#reserve_data").val() == "set")
    {
        var date = $("#reserved_date").val();
        var time = $("#reserved_time").val();
        var num_people = $("#num_of_people").val();

        $('#reservation_date').val(date);
        $('#reservation_time').val(time);
        $('#number_of_people').val(num_people);
    }
    else{
        $('#reservation_date').val(maxDate);
    }

    const toaster = $('div .form-row #toast');
    $("#reservation_time").change(function () {
        var reservation_date = $("#reservation_date").val();
        var reservation_time = $("#reservation_time").val();

        if(reservation_date == maxDate){
            if (Date.parse("1-1-2000 " + currentTime) > Date.parse("1-1-2000 " + reservation_time)){
                $(toaster).css('display', 'flex');
                $('#toast_message').text('You cannot reserve a past time.');
                $(toaster).toast('show');
                setTimeout(function() {
                    $(toaster).hide('blind', {}, 500);
                }, 5000);
                $(toaster).css('display', 'none');
                
                $("#reservation_time").val("");
                
                //window.location.reload();
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
                    $(toaster).hide('blind', {}, 500);
                }, 5000);
                $(toaster).css('display', 'none');
                
            $("#reservation_date").val("");
            
            //window.location.reload();
        }
    });
    
});

});

//check and book restaurant function
$(document).ready(function(){

    var dtToday = new Date();

    var month = dtToday.getMonth() + 1;
    var day = dtToday.getDate();
    var year = dtToday.getFullYear();
    
    var nowTime = new Date(Date.now());
    var hour = nowTime.getHours();
    var min = nowTime.getMinutes();
    var sec = nowTime.getSeconds();
    
    const toaster = $('div .form-row #toast');
    
    if(month < 10)
        month = '0' + month.toString();
    if(day < 10)
        day = '0' + day.toString();

    var maxDate = year + '-' + month + '-' + day;
    var currentTime =hour+':'+min+':'+sec;

    $("#close_login").click(function () {
        $("#loginModal").hide();
    });

    $("#close_signup").click(function () {
        $("#signupModal").hide();
    });

    $("#close_modal").click(function () {
        $("#modal-signin").hide();
    });

    $("#working_times button").click(function() {
        var hour = $(this).val();

        $("#reservation_time").val(hour);
    });

    $("#modal-default").hide();

    $("#close_button").click(function () {
        $("#modal-default").hide();
    });

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
        const restaurant_id = $("#restaurant_id").val();
        const toaster = $('div .form-row #toast');

        if(reservation_date === ""){
            $(toaster).css('display', 'flex');
                $('#toast_message').text('Reservation date is required!');
                $(toaster).toast('show');
                setTimeout(function() {
                    $(toaster).hide('blind', {}, 500);
                }, 5000);
                $(toaster).css('display', 'none');
        }
        if(reservation_time === ""){
            $(toaster).css('display', 'flex');
                $('#toast_message').text('Reservation time is required!');
                $(toaster).toast('show');
                setTimeout(function() {
                    $(toaster).hide('blind', {}, 500);
                }, 5000);
                $(toaster).css('display', 'none');
        }
        if(number_of_people === ""){
            $(toaster).css('display', 'flex');
                $('#toast_message').text('Number of people is required!');
                $(toaster).toast('show');
                setTimeout(function() {
                    $(toaster).hide('blind', {}, 500);
                }, 5000);
                $(toaster).css('display', 'none');
        }
        if(reservation_date === maxDate){
            if (Date.parse("1-1-2000 " + currentTime) > Date.parse("1-1-2000 " + reservation_time)){
                $(toaster).css('display', 'flex');
                $('#toast_message').text('You cannor reserve a past time!');
                $(toaster).toast('show');
                setTimeout(function() {
                    $(toaster).hide('blind', {}, 500);
                }, 5000);
                $(toaster).css('display', 'none');
                
                //window.location.reload();
            }

        }
        if(reservation_date < maxDate){
                $(toaster).css('display', 'flex');
                $('#toast_message').text('You cannot reserva a past date!');
                $(toaster).toast('show');
                setTimeout(function() {
                    $(toaster).hide('blind', {}, 500);
                }, 5000);
                $(toaster).css('display', 'none');
                
                $("#reservation_date").val("");

        }
        else{

            //alert(reservation_time);

            $.ajax({

                url:check_url,

                method:"POST",

                data: {

                    _token: _token,

                    reservation_date: reservation_date,

                    reservation_time: reservation_time,

                    number_of_people: number_of_people,

                    restaurant_id: restaurant_id

                },
                success:function(availability){
                    //success data
                    const value = availability.value;

                    //if restaurant not found
                    if(value === "restaurant not found")
                    {
                        $(toaster).css('display', 'flex');
                        $('#toast_message').text('Restaurant not found in our system!');
                        $(toaster).toast('show');
                        setTimeout(function() {
                            $(toaster).hide('blind', {}, 500);
                        }, 5000);
                        $(toaster).css('display', 'none');
                    }
                    if(value === "closed")
                    {
                        $(toaster).css('display', 'flex');
                        $('#toast_message').text('Restaurant is closed, try another date or time!');
                        $(toaster).toast('show');
                        setTimeout(function() {
                            $(toaster).hide('blind', {}, 500);
                        }, 5000);
                        $(toaster).css('display', 'none');
                    }
                    if(value === "full")
                    {
                        $(toaster).css('display', 'flex');
                        $('#toast_message').text('Restaurant is fully booked, try another date or time!');
                        $(toaster).toast('show');
                        setTimeout(function() {
                            $(toaster).hide('blind', {}, 500);
                        }, 5000);
                        $(toaster).css('display', 'none');
                    }
                    else{
                        //if table is not booked
                        if(value === "empty")
                        {
                            $(toaster).css('display', 'flex');
                            $('#toast_message').text('Tables are available at the selected restaurant!');
                            $(toaster).toast('show');
                            setTimeout(function() {
                                $(toaster).hide('blind', {}, 500);
                            }, 5000);
                            $(toaster).css('display', 'none');

                            $("#working_times").hide();
                            $("#reserver_additional_message").show();
                            $("#additional_message_area").show();
                            $("#user_inputs").show();
                            $("#reserver_email").show();
                            $("#user_full_name").show();
                            $("#phone_number").show();
                            $("#check_button").hide();
                            $("#book_table").show();
                            
                            
                            $("#phone_number").change( function () {
                                var phone = $("#phone_number").val();
                                var len = phone.length;
                                if(len < 10 || len > 13)
                                {
                                    $(toaster).css('display', 'flex');
                                    $('#toast_message').text('Please enter a valid phone number!');
                                    $(toaster).toast('show');
                                    setTimeout(function() {
                                        $(toaster).hide('blind', {}, 500);
                                    }, 5000);
                                    $(toaster).css('display', 'none');
                                    console.log(len);
                                }
                                else{
                                    console.log(len);
                                    //$("#book_table").show();
                                }
                                
                            });

                            //if table is not booked execute this
                            $("#book_table").click(function () {
                                
                                document.getElementById('cover-spin').style.display = "flex";

                                setTimeout(function(){
                                    $('#cover-spin').css('display', 'none');
                                }, 2000);

                                const reserver_message = $("#reserver_additional_message").val();
                                const _token = $("input#_token").val();
                                const number_of_people = $("#number_of_people").val();
                                const reservation_date = $("#reservation_date").val();
                                const reservation_time = $("#reservation_time").val();
                                const book_url = $("#book_url").val();
                                const restaurant_id = $("#restaurant_id").val();
                                const user_name = $("#user_full_name").val();
                                const phone_number = $("#phone_number").val();
                                const user_email = $("#reserver_email").val();
                                const reservation_type = 'online';
                                
                                if(reservation_date === ""){
                                    $(toaster).css('display', 'flex');
                                    $('#toast_message').text('Reservation date is required!');
                                    $(toaster).toast('show');
                                    setTimeout(function() {
                                        $(toaster).hide('blind', {}, 500);
                                    }, 5000);
                                    $(toaster).css('display', 'none');
                                }
                                if(reservation_time === ""){
                                    $(toaster).css('display', 'flex');
                                    $('#toast_message').text('Reservation time is required!');
                                    $(toaster).toast('show');
                                    setTimeout(function() {
                                        $(toaster).hide('blind', {}, 500);
                                    }, 5000);
                                    $(toaster).css('display', 'none');
                                }
                                if(number_of_people === ""){
                                    $(toaster).css('display', 'flex');
                                    $('#toast_message').text('Number of people is required!');
                                    $(toaster).toast('show');
                                    setTimeout(function() {
                                        $(toaster).hide('blind', {}, 500);
                                    }, 5000);
                                    $(toaster).css('display', 'none');
                                }
                                if(user_name === ""){
                                    $(toaster).css('display', 'flex');
                                    $('#toast_message').text('user name is required!');
                                    $(toaster).toast('show');
                                    setTimeout(function() {
                                        $(toaster).hide('blind', {}, 500);
                                    }, 5000);
                                    $(toaster).css('display', 'none');
                                }
                                if(phone_number === ""){
                                   $(toaster).css('display', 'flex');
                                    $('#toast_message').text('Phone number is required!');
                                    $(toaster).toast('show');
                                    setTimeout(function() {
                                        $(toaster).hide('blind', {}, 500);
                                    }, 5000);
                                    $(toaster).css('display', 'none');
                                }
                                if(user_email === ""){
                                    $(toaster).css('display', 'flex');
                                    $('#toast_message').text('User email is required!');
                                    $(toaster).toast('show');
                                    setTimeout(function() {
                                        $(toaster).hide('blind', {}, 500);
                                    }, 5000);
                                    $(toaster).css('display', 'none');
                                }
                                /**if(reservation_date === maxDate){
                                    if (reservation_time < currentTime){
                                        $(notification_wrapper).show();
                                        $(notification_wrapper).append('<div class="alert alert-light shadow add_margin_to_alert_sm" role="alert"\n' +
                                            'style="border-left:#dc1118 5px solid;">\n' +
                                            '<button type="button" class="close" id="close_alert" data-dismiss="alert" aria-label="Close">\n' +
                                            '<span aria-hidden="true" style="color: #000;">&times;</span>\n' +
                                            '</button>\n' +
                                            '<div class="row">\n' +
                                            '<span style="font-size:25px; margin:10px; color: #dc1118; "><i class="fa fa-check-circle"></i></span>\n' +
                                            '<p style="font-size:25px; color:#000;" class="mb-0 ">\n' +
                                            '<br>\n' +
                                            '<b class="mr-1" style="font-size:25px; color:#000;"> You cannot reserve past time. </b>\n' +
                                            '</p>\n' +
                                            '</div>\n' +
                                            '</div>');
                                        $("#reservation_time").val("");
                                        
                                        window.location.reload();
                                    }

                                    $("#close_alert").click(function () {
                                        $("#toast_alert").css('display', 'none');
                                    });
                                }*/
                                else {
                                    $.ajax({

                                        url: book_url,

                                        method: "POST",

                                        data: {

                                            _token: _token,

                                            reservation_date: reservation_date,

                                            reservation_time: reservation_time,

                                            number_of_people: number_of_people,

                                            reserver_message: reserver_message,

                                            restaurant_id: restaurant_id,

                                            reservation_type: reservation_type,

                                            user_name: user_name,

                                            phone_number: phone_number,

                                            user_email: user_email

                                        },
                                        success: function (booked) {
                                            if(booked.registered === "logged_user")
                                            {
                                                $(toaster).css('display', 'flex');
                                                $('#toast_message').text('Tabel booked successfully.');
                                                $(toaster).toast('show');
                                                setTimeout(function() {
                                                    $(toaster).hide('blind', {}, 500);
                                                }, 5000);
                                                $(toaster).css('display', 'none');

                                                var logged_user_message = "Welcome back, "+user_name+'.';
                                                $("#modal-default-welcome").show();
                                                $("#logged_user_message").text(logged_user_message);
                                            }
                                            if(booked.registered === "yes")
                                            {
                                                $(toaster).css('display', 'flex');
                                                $('#toast_message').text('Tabel booked successfully.');
                                                $(toaster).toast('show');
                                                setTimeout(function() {
                                                    $(toaster).hide('blind', {}, 500);
                                                }, 5000);
                                                $(toaster).css('display', 'none');

                                                var welcome_message = "Welcome back, "+user_name+'.';
                                                $("#loginModal").show();
                                                $("#welcome_message").text(welcome_message);
                                                $("#email").val(user_email);

                                            }
                                            if(booked.registered === "no")
                                            {
                                                $(toaster).css('display', 'flex');
                                                $('#toast_message').text('Tabel booked successfully.');
                                                $(toaster).toast('show');
                                                setTimeout(function() {
                                                    $(toaster).hide('blind', {}, 500);
                                                }, 5000);
                                                $(toaster).css('display', 'none');
                                            
                                                var thank_message = "Only a password left to complete your registration."+user_name+'.';
                                                $("#signupModal").show();
                                                $("#signup_thank_you").text(thank_message);
                                                $("#signup_thank_you").css('font-size', '25px');
                                                $('#register_name').css('display', 'none');
                                                $("#full_name_register").val(user_name);
                                                $('#register_email').css('display', 'none');
                                                $("#email_register").val(user_email);
                                                $('#register_phone').css('display', 'none');
                                                $("#phone_register").val(phone_number);
                                            }
                                            else{
                                                //window.location.reload();
                                            }

                                        }
                                    });
                                }

                            });

                        }
                        //if table is booked execute this
                        if(value === "all_booked"){
                            $(toaster).css('display', 'flex');
                            $('#toast_message').text('All tabels are fully booked, try another date or time.');
                            $(toaster).toast('show');
                            setTimeout(function() {
                                $(toaster).hide('blind', {}, 500);
                            }, 5000);
                            $(toaster).css('display', 'none');

                            var available_hours = $.parseJSON(availability.available_hours);
                            console.log(available_hours);

                            $("#modal-default-wait_list").show();

                            for (var i = 0; i < available_hours.length; i++)
                            {
                                var open_hour = available_hours[i];

                                const timeString12hr = new Date('1970-01-01T' + open_hour + 'Z')
                                    .toLocaleTimeString({},
                                        {timeZone:'UTC',hour12:true,hour:'numeric',minute:'numeric'}
                                    );

                                $("#available_hours").append(
                                    '<div class=\"col-lg-3 col-md-3\">' +
                                    '<button name="hour_button" type="button" class="btn time_btn_style hour">\n' +
                                    timeString12hr+'<span hidden style="display: none;" class="time">'+'_'+open_hour+'</span>\n' +
                                    '</button></div>');

                            }

                            $(".hour").click(function () {
                                var ButtonText = $(this).text();
                                var split_hours = ButtonText.split("_");
                                var select_hour = split_hours[1];
                                var btn_txt = split_hours[0];
                                $("#time_selector").val(select_hour);
                                $("#time_selector").text(btn_txt);
                                $("#time_selector").prop('selected', true);
                                $("#time_selector").css('display', 'block');
                                $("#modal-default-wait_list").hide();
                                $("#available_hours").empty();
                            });

                            $('.close').click( function(){
                                $("#modal-default-wait_list").hide();
                                $("#available_hours").empty();
                                //window.location.reload();
                            });

                            $("#other_time_btn").click( function () {
                                $("#modal-default-wait_list").hide();
                                $("#available_hours").empty();
                                window.location.reload();
                            });
                            
                            //to get into waiting list
                            $("#waiting_list_btn").click( function () {
                                $("#modal-default-wait_list").hide();
                                $('#modal-default-wait_list').empty();
                                $("#reserver_additional_message").show();
                                $("#additional_message_area").show();
                                $("#user_inputs").show();
                                $("#reserver_email").show();
                                $("#user_full_name").show();
                                $("#phone_number").show();
                                $("#check_button").hide();
                                $("#book_table").show();
                                $("#book_table").click( function () {
                                    
                                    document.getElementById('cover-spin').style.display = "flex";

                                    setTimeout(function(){
                                        $('#cover-spin').css('display', 'none');
                                    }, 2000);
                                    
                                    $("#phone_number").change( function () {
                                        var phone = $("#phone_number").val();
                                        var len = phone.length; 
                                    });
                                    
                                    if(len < 10 || len > 13)
                                    {
                                        $(toaster).css('display', 'flex');
                                        $('#toast_message').text('Please enter a valid phone number!');
                                        $(toaster).toast('show');
                                        setTimeout(function() {
                                            $(toaster).hide('blind', {}, 500);
                                        }, 5000);
                                        $(toaster).css('display', 'none');
                                        console.log(len);
                                    }
                                    else{
                                        console.log(len);
                                        //$("#book_table").show();
                                    }

                                    const reserver_message = $("#reserver_additional_message").val();
                                    const _token = $("input#_token").val();
                                    const number_of_people = $("#number_of_people").val();
                                    const reservation_date = $("#reservation_date").val();
                                    const reservation_time = $("#reservation_time").val();
                                    const book_url = $("#book_url").val();
                                    const restaurant_id = $("#restaurant_id").val();
                                    const user_name = $("#user_full_name").val();
                                    const phone_number = $("#phone_number").val();
                                    const user_email = $("#reserver_email").val();
                                    const reservation_type = 'online';

                                    if(reservation_date === ""){
                                        $(toaster).css('display', 'flex');
                                        $('#toast_message').text('Reservation date is required!');
                                        $(toaster).toast('show');
                                        setTimeout(function() {
                                            $(toaster).hide('blind', {}, 500);
                                        }, 5000);
                                        $(toaster).css('display', 'none');
                                    }
                                    if(reservation_time === ""){
                                        $(toaster).css('display', 'flex');
                                        $('#toast_message').text('Reservation time is required!');
                                        $(toaster).toast('show');
                                        setTimeout(function() {
                                            $(toaster).hide('blind', {}, 500);
                                        }, 5000);
                                        $(toaster).css('display', 'none');
                                    }
                                    if(number_of_people === ""){
                                        $(toaster).css('display', 'flex');
                                        $('#toast_message').text('Number of people is required!');
                                        $(toaster).toast('show');
                                        setTimeout(function() {
                                            $(toaster).hide('blind', {}, 500);
                                        }, 5000);
                                        $(toaster).css('display', 'none');
                                    }
                                    if(user_name === ""){
                                        $(toaster).css('display', 'flex');
                                        $('#toast_message').text('user name is required!');
                                        $(toaster).toast('show');
                                        setTimeout(function() {
                                            $(toaster).hide('blind', {}, 500);
                                        }, 5000);
                                        $(toaster).css('display', 'none');
                                    }
                                    if(phone_number === ""){
                                        $(toaster).css('display', 'flex');
                                        $('#toast_message').text('Phone number is required!');
                                        $(toaster).toast('show');
                                        setTimeout(function() {
                                            $(toaster).hide('blind', {}, 500);
                                        }, 5000);
                                        $(toaster).css('display', 'none');
                                    }
                                    if(user_email === ""){
                                        $(toaster).css('display', 'flex');
                                        $('#toast_message').text('User email is required!');
                                        $(toaster).toast('show');
                                        setTimeout(function() {
                                            $(toaster).hide('blind', {}, 500);
                                        }, 5000);
                                        $(toaster).css('display', 'none');
                                    }
                                    else {
                                        $.ajax({

                                            url: book_url,

                                            method: "POST",

                                            data: {

                                                _token: _token,

                                                reservation_date: reservation_date,

                                                reservation_time: reservation_time,

                                                number_of_people: number_of_people,

                                                reserver_message: reserver_message,

                                                restaurant_id: restaurant_id,

                                                reservation_type: reservation_type,

                                                user_name: user_name,

                                                phone_number: phone_number,

                                                user_email: user_email

                                            },
                                            success: function (booked) {
                                                if(booked.registered === "logged_user")
                                                {
                                                    $(toaster).css('display', 'flex');
                                                    $('#toast_message').text('Tabel booked successfully.');
                                                    $(toaster).toast('show');
                                                    setTimeout(function() {
                                                        $(toaster).hide('blind', {}, 500);
                                                    }, 5000);
                                                    $(toaster).css('display', 'none');

                                                    var logged_user_message = "Welcome back, "+user_name+'.';
                                                    $("#modal-default-welcome").show();
                                                    $("#logged_user_message").text(logged_user_message);
                                                }
                                                if(booked.registered === "yes")
                                                {
                                                    $(toaster).css('display', 'flex');
                                                    $('#toast_message').text('Tabel booked successfully.');
                                                    $(toaster).toast('show');
                                                    setTimeout(function() {
                                                        $(toaster).hide('blind', {}, 500);
                                                    }, 5000);
                                                    $(toaster).css('display', 'none');

                                                    var welcome_message = "Welcome back, "+user_name+'.';
                                                    $("#loginModal").show();
                                                    $("#welcome_message").text(welcome_message);
                                                    $("#email").val(user_email);

                                                }
                                                if(booked.registered === "no")
                                                {
                                                    $(toaster).css('display', 'flex');
                                                    $('#toast_message').text('Tabel booked successfully.');
                                                    $(toaster).toast('show');
                                                    setTimeout(function() {
                                                        $(toaster).hide('blind', {}, 500);
                                                    }, 5000);
                                                    $(toaster).css('display', 'none');

                                                    var thank_message = "Only a password left to complete your registration."+user_name+'.';
                                                    $("#signupModal").show();
                                                    $("#signup_thank_you").text(thank_message);
                                                    $("#signup_thank_you").css('font-size', '25px');
                                                    $('#register_name').css('display', 'none');
                                                    $("#full_name_register").val(user_name);
                                                    $('#register_email').css('display', 'none');
                                                    $("#email_register").val(user_email);
                                                    $('#register_phone').css('display', 'none');
                                                    $("#phone_register").val(phone_number);
                                                }
                                                else{
                                                    //window.location.reload();
                                                }

                                            }
                                        });
                                    }
                                });
                            });
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
    })
});


//carousel script code

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

    
