<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <link rel="shortcut icon" href="/images/favicon.ico" type="image/x-icon">
    <?php if (!isset($_SERVER['HTTP_USER_AGENT']) || stripos($_SERVER['HTTP_USER_AGENT'], 'Lighthouse') === false) { ?>
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;900&family=Roboto:wght@400;500;700;900&display=swap" rel="stylesheet">
    <?php } ?>

    @stack('before-styles')
    <link rel="stylesheet" href="/css/app.css">
    <meta name="description" content="{{ (!empty($data['description'])) ? $data['description'] : '' }}">
    <meta name="keywords" content="{{ (!empty($data['keywords'])) ? $data['keywords'] : '' }}">
    <title>{{ (!empty($data['title'])) ? $data['title'] : '' }}</title>

<?php if (!isset($_SERVER['HTTP_USER_AGENT']) || stripos($_SERVER['HTTP_USER_AGENT'], 'Lighthouse') === false) { ?>

<!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-KEZNNYFF8D"></script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());

      gtag('config', 'G-KEZNNYFF8D');
    </script>
	<!-- Google Tag Manager -->
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','GTM-N3MCT8Z');</script>
<!-- End Google Tag Manager -->
<?php } ?>
</head>
<body>
<!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-N3MCT8Z"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->
    <div class="wrapper">
        <div class="overlay"></div>
        @include('includes.menu-mb')
        @include('includes.header')
        @yield('content')
        @include('includes.footer')
    </div>
    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
<script src="/js/app.js"></script>
    @stack('after-scripts')
    <script src="/js/checkout.js"></script>



    <?php if($_SERVER['REQUEST_URI'] == '/checkout') { ?>
    <link href="/css/jquery-ui.css" rel="stylesheet">
<script src="/js/jquery.js"></script>
<script src="/js/autocomplete.js"></script>
<?php } ?>
<script>
$("#autocomplete").autocomplete({
  source: "/get-cities", // url-адрес
  minLength: 1, // минимальное количество для совершения запроса
  select: function (event, ui) {
        var value = ui.item.item;
        $.ajax({
            type: 'GET',
            url: '/get-warehouses',
            data: {ref:value},
            success: function (data) {

                $('#cities').html('');
                $('#cities').fadeIn(0);
                $('.tab2_np').fadeIn(0);
                $('#cities').append("<option>Оберіть відділення</option>");
                for(i=0;i<1000;i++){
                    if(data[i]) {
                        $('#cities').append("<option>"+data[i].Description+"</option>");
                    }
                }
            },
            error: function () {

            }
        });
    }
});

function setPromocode() {
    let promocode = $('input[name="promocode"]').val();
    $.ajax({
        type: 'GET',
        url: '/promocode',
        data: {promocode},
        success: function (data) {
            if(data == 'Промокод не знайдений') {
                $('.error.discount-error').fadeIn(0);
                $('.promo-class').fadeOut(0);
                $('.promo-class-costs').fadeOut(0);
            }
            else if(data.indexOf('%') > -1) {
                let percent = data.substring(0, data.length - 1);
                $('.error.discount-error').fadeOut(0);
                $('.promo-class').fadeIn(0);
                $('.promo-class-costs').fadeIn(0);
                $('.promo-class-costs').html(percent+' %');
                let old_cost = $('#old_cost').html();
                if(parseInt(old_cost*(1-percent/100)) <= 0) {
                    $('#old_cost').html('0');
                }  
                else {
                    $('#old_cost').html(parseInt(old_cost*(1-percent/100)));
                }
            }
            else {
                $('.error.discount-error').fadeOut(0);
                $('.promo-class').fadeIn(0);
                $('.promo-class-costs').fadeIn(0);
                $('.promo-class-costs').html(data+' грн');
                let old_cost = $('#old_cost').html();
                if(old_cost - data <= 0) {
                    $('#old_cost').html('0');
                }  
                else {
                    $('#old_cost').html(old_cost - data);
                }
            }
        },
        error: function () {

        }
    });
}

function checkoutSend(that) {
        let delivery = $('input[name="delivery"]:checked').val();
        let city = $('input[name="city"]').val();
       let warehouse = $('select[name="warehouse"]').val();
       let promocode = $('input[name="promocode"]').val();
    

        if(delivery == 'Нова Пошта (самовивіз з відділення)') {
           
           if($('input[name="city"]').val() == '') {
            console.log('city error');
            $('input[name="city"]').css('border', '1px solid #ff0000');
            $('html,body').animate({scrollTop: 0});
            return false;
           }
           
           if($('select[name="warehouse"]').val() == '' || $('select[name="warehouse"]').val() == 'Оберіть відділення') {
            console.log('warehouse error');
            $('select[name="warehouse"]').css('border', '1px solid grey');
            $('html,body').animate({scrollTop: 0});
            return false;
           }
           
        }
        
        let surname_np = $('input[name="surname_np"]').val();
        let name_np = $('input[name="name_np"]').val();
        let email_np = $('input[name="email_np"]').val();
        let phone_np = $('input[name="phone_np"]').val();
        let payment_np = $('input[name="payment_np"]:checked').val();

        let surname = $('input[name="surname"]').val();
        let name = $('input[name="name"]').val();
        let email = $('input[name="email"]').val();
        let phone = $('input[name="phone"]').val();
        let citys = $('input[name="citys"]').val();
        let address = $('input[name="address"]').val();
        let comments = $('textarea[name="comments"]').val();
        let payment = $('input[name="payment"]:checked').val();

        $.ajax({
            type: 'GET',
            url: '/checkout-send',
            data: {delivery, promocode, city, warehouse, surname, name, email, phone, citys, address, comments, surname_np, name_np, email_np, phone_np, payment, payment_np},
            success: function (data) {
                if(data.redirect == 'true') {
                    location.href = data.url;
                }
                else {
                    $('.purchase').html('<div>' + data + '</div>');
                }
                //$('.modal').addClass('active');
            },
            error: function () {

            }
        });
    }

    function addToCart(that) {
        let product_id = that.attr('data-product_id');
        let quantity = $('#quantity_'+product_id).html() ? $('#quantity_'+product_id).html() : 1;
        $.ajax({
            type: 'GET',
            url: '/add-to-cart',
            data: {product_id, quantity},
            success: function (data) {
                console.log(data);
                $('.modal').addClass('active');
            },
            error: function () {

            }
        });
    }
    function addToCartColor(that) {
        let product_id = that.attr('data-product_id');
        let quantity = $('#quantity_'+product_id).html() ? $('#quantity_'+product_id).html() : 1;
        let color = $('.colors a.active span').html() ? $('.colors a.active span').html() : '';

        if(color == '') {
        	$('.product-info h4').css('color', '#ff0000');
        	$('.product-info h4').html('Оберіть колір:');
        	$('.colors').css('border', '1px solid #ff0000');
        	$('.colors').css('padding', '3px');
        }
        else {
        	$('.product-info h4').css('color', '#000000');
        	$('.product-info h4').html('Доступні варіанти:');
        	$('.colors').css('border', 'none');
        	$('.colors').css('padding', '0');

			$.ajax({
	            type: 'GET',
	            url: '/add-to-cart',
	            data: {product_id, quantity, color},
	            success: function (data) {
	                console.log(data);
	                $('.modal').addClass('active');
	            },
	            error: function () {

	            }
	        });
        }
    }
    function changeCart(product_id, quantity) {
        $.ajax({
            type: 'GET',
            url: '/change-cart',
            data: {product_id, quantity},
            success: function (data) {
                //console.log(data);
                location.reload();
            },
            error: function () {

            }
        });
    }
    function changeColorCart(product_id, color, quantity) {
        $.ajax({
            type: 'GET',
            url: '/change-cart',
            data: {product_id, color, quantity},
            success: function (data) {
                //console.log(data);
                location.reload();
            },
            error: function () {

            }
        });
    }
    function deleteCart(product_id) {
        $.ajax({
            type: 'GET',
            url: '/delete-from-cart',
            data: {product_id},
            success: function (data) {
                location.reload();
            },
            error: function () {

            }
        });
    }
    function addToWishlist(product_id, that) {
        $.ajax({
            type: 'GET',
            url: '/add-to-wishlist',
            data: {product_id},
            success: function (data) {
                console.log(data);
                that.addClass('active');
            },
            error: function () {

            }
        });
    }
    function deleteWishlist(product_id) {
        $.ajax({
            type: 'GET',
            url: '/delete-from-wishlist',
            data: {product_id},
            success: function (data) {
                location.reload();
            },
            error: function () {

            }
        });
    }
</script>
</body>
</html>
