<!DOCTYPE html>
<html>
    <head>
        <title><?php echo $this->title; ?></title>        
        <base href="<?php echo APP_ROOT; ?>">        
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta lang="pt-br">
        <meta name="description" content="">
        <meta name="keywords" content="">
        <meta name="robots" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="icon" type="image/x-icon" href="content/site/assets/img/favicon.ico">
        <link rel='stylesheet' type='text/css' href="http://fonts.googleapis.com/css?family=Abel">
        <link rel='stylesheet' type='text/css' href="http://fonts.googleapis.com/css?family=Pontano+Sans">
        <link rel='stylesheet' type='text/css' href="http://fonts.googleapis.com/css?family=Alegreya+Sans:300,400,500,700">
        <link rel='stylesheet' type='text/css' href="http://fonts.googleapis.com/css?family=Roboto:400,300,500">
        <link rel='stylesheet' type='text/css' href="http://fonts.googleapis.com/css?family=Roboto+Condensed:400,300">
        <link rel='stylesheet' type='text/css' href="http://fonts.googleapis.com/css?family=Dosis:300,400,500,600">        
        <link rel="stylesheet" type="text/css" href="content/site/assets/ext/chosen/chosen.css">
        <link rel="stylesheet" type="text/css" href="content/site/assets/ext/animate/animate.css">
        <link rel="stylesheet" type="text/css" href="content/site/assets/ext/bootstrap/css/bootstrap.min.css">
        <link rel="stylesheet" type="text/css" href="content/site/assets/ext/owl-carousel/owl.carousel.css">
        <link rel="stylesheet" type="text/css" href="content/site/assets/ext/owl-carousel/owl.theme.css">
        <link rel="stylesheet" type="text/css" href="content/site/assets/ext/owl-carousel/owl.transitions.css">
        <link rel="stylesheet" type="text/css" href="content/site/assets/ext/font-awesome/css/font-awesome.css">
        <link rel="stylesheet" type="text/css" href="content/site/assets/ext/material-kit/css/material-kit.css">
        <link rel="stylesheet" type="text/css" href="content/site/assets/css/main.css">        
    </head>
    <body>        
        <?php $this->render(); ?>
        <script type="text/javascript" src="content/site/assets/ext/jquery/jquery.min.js"></script>
        <script type="text/javascript" src="content/site/assets/ext/bootstrap/js/bootstrap.min.js"></script>    
        <script type="text/javascript" src="content/site/assets/ext/owl-carousel/owl.carousel.min.js"></script>
        <script type="text/javascript" src="content/site/assets/ext/isotope/isotope.pkgd.min.js"></script>
        <script type="text/javascript" src="content/site/assets/ext/wow//wow.min.js"></script>

        <script>
            new WOW().init();
        </script>

<script>
    $(document).ready(function () {
        $("#starting-slider").owlCarousel({
            autoPlay: 8000,
            navigation: false, // Show next and prev buttons
            slideSpeed: 700,
            paginationSpeed: 1000,
            singleItem: true
        });
    });
</script>

<script>
    $(function() {
        // init Isotope
        var $container = $('.isotope').isotope
        ({
            itemSelector: '.element-item',
            layoutMode: 'fitRows'
        });


        // bind filter button click
        $('#filters').on('click', 'button', function ()
        {
            var filterValue = $(this).attr('data-filter');
            // use filterFn if matches value
            $container.isotope({filter: filterValue});
        });

        // change is-checked class on buttons
        $('.button-group').each(function (i, buttonGroup)
        {
            var $buttonGroup = $(buttonGroup);
            $buttonGroup.on('click', 'button', function ()
            {
                $buttonGroup.find('.is-checked').removeClass('is-checked');
                $(this).addClass('is-checked');
            });
        });
    });
</script>

<script>
    $(document).ready(function() {
        $(".tipo-pesquisa").click(function() {
            id = $(this).attr("id");
            $("ul.search-opcao li a").removeClass("active");
            $("ul.search-opcao li a#" + id).addClass("active");

            $(".service-type").addClass("hidden");
            $(".service-type").prop("disabled", true);

            $("#option_" + id).removeClass("hidden");
            $("#option_" + id).prop("disabled", false);

            $("#selected-type").val(id);
        });
    });
</script>

<script>
    <?php if (!isset($view_user_type) || $view_user_type == '') { ?>
        $(document).ready(
            function() {
                setTimeout(function(){$('#modal-select-user').modal({backdrop: 'static', keyboard: false});}, 1000);
            }
        );
    <?php } ?>
</script>
        
        
        
    </body>
</html>
