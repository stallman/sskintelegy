const csrf_token = $('meta[name="csrf-token"]').attr('content');

function createAlert(type, message) {
    return $('<div>', {
        class: `alert alert-${type}`,
        html: message
    });
}

function getError() {
    let error = false;
    if ($('#registration [name="password"]').val() !== $('#password_repeat').val()) {
        error = 'Пароли не совпадают';
    }
    return error;
}

function validRegistrationForm() {
    let valid = true;
    let error = getError();
    if (error) {
        valid = false;
        let alert = createAlert('danger', error);
        $('#registration .login-alert').prepend(alert);
    } else {
        $('#registration .login-alert .alert').remove();
    }
    return valid;
}

function sendAuthForm(url, formdata, callback) {
    fetch(url, {
        method: 'POST',
        headers: {
            'Accept': 'application/json',
            'X-CSRF-TOKEN':  csrf_token
        },
        body: formdata
    })
    .then(response => response.json())
    .then(data => {
        callback(data);
    })
    .catch(error => {
        console.log(error);
    });
}


function valid_error_to_string(error_data){
    let string = "";
    Object.keys(error_data).forEach(function(title){
        string+= "<p>";
        string += title + ": ";
        error_data[title].forEach(function(error){
            string+= error;
        });
        string+= "</p>";
    });

    if (string === '<p>captcha: validation.captcha</p>'){
        string = 'Invalid code';
    }

    return string;
}

function handleSendRegistrationForm(response) {
    if (response['valid_error']) {
        $('#registration .alert').remove();
        let message = valid_error_to_string(response['valid_error']);
        let alert = createAlert('danger', message);
        $('#registration .login-alert').prepend(alert);

        let d = new Date();
        $("#reload_captcha img").attr("src", "captcha/default?time="+d.getTime());

    } else if (response.location) {
        window.location = response.location;
    }
}

function handleSendProfileForm(response) {
    if (response['valid_error']) {
        $('#profile .alert').remove();
        let message = valid_error_to_string(response['valid_error']);
        let alert = createAlert('danger', message);
        $('#profile .login-alert').prepend(alert);

    } else if (response.location) {
        window.location = response.location;
    }
}


function handleSendAuthForm(response) {
    $('#login .alert').remove();

    if (response['valid_error']) {
        let message = valid_error_to_string(response['valid_error']);
        let alert = createAlert('danger', message);
        $('#login .login-alert').prepend(alert);

    } else if (response.error) {
        let alert = createAlert('danger', response.error);
        $('#login .login-alert').prepend(alert);

    } else if (response.location) {
        window.location = response.location;
    }
}



$(function () {
    $('.top-lang-switcher a:not(.active)').on('click', function () {
        let lang = $(this).attr('data-lang');
        $.ajax({
            url: '/locale/set/' + lang,
            success: () => {
                window.location.reload();
            }
        })
    })

    $("#registration").submit(function(e){
        e.preventDefault();
        let formdata = new FormData(this);
        sendAuthForm(this.action, formdata, handleSendRegistrationForm);
    });

    $('#login').submit(function(e){
        e.preventDefault();
        let formdata = new FormData(this);
        sendAuthForm(this.action, formdata, handleSendAuthForm);
    });

    $("#profile").submit(function(e){
        e.preventDefault();
        let formdata = new FormData(this);
        sendAuthForm(this.action, formdata, handleSendProfileForm);
    });

    $("#reload_captcha").click(function(){
        d = new Date();
        $("#reload_captcha img").attr("src", "captcha/default?time="+d.getTime());
    });

    $("#order_by_price").click(function(){
        var url = new URL(window.location);
        var sort = $("#order_by_price").attr("sort");
        url.searchParams.set('order', 'price');
        url.searchParams.set('sort', sort);
        window.location = url;
    });

    $("#order_by_pop").click(function(){
        var url = new URL(window.location);
        var sort = $("#order_by_price").attr("sort");
        url.searchParams.set('order', 'buys');
        url.searchParams.set('sort', sort);
        window.location = url;
    });

    $("#search_y").click(function(){
        var url = new URL(window.location);
        url.searchParams.set('min', $("#min").val());
        url.searchParams.set('max', $("#max").val());
        window.location = url;
    });


    $("#reset").click(function(){
        $("#min").attr({"min" : 0, "value": 0});
        $(".range-input .min").attr({"min" : 0, "value": 0});
        $(".range-slider span").css({"left": '0%', "right": '0%'});
        $(".checkbox__content input").removeAttr("checked");
    });

    $('button[data-popup=popup3]').click(function () {
        if (typeof $(this).data('name') !== 'undefined') {
            $('#popup3 #name').html($(this).data('name'));
        }
        if (typeof $(this).data('price') !== 'undefined') {
            $('#popup3 [name="price"]').html($(this).data('price'));
        }
        if (typeof $(this).data('id') !== 'undefined') {
            $('#popup3 [name="id"]').val($(this).data('id'));
        }
    })

    $("#youspent").on("keyup", function() {
        $('#youget').val($("#youspent").val());
    });

    $("#search").on("change", function(e) {
        window.location = '/catalog?q='+$("#search").val();
    });
});