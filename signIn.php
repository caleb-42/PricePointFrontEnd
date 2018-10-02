<?php $root = "http://localhost/webplay/PricePointFrontEnd/" ?>
<?php   include_once("assets/php/login.php");?>
<!doctype html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>PricePoint Phamarcy</title>


        <!-- jQuery core -->
        <script src="./vendors/jquery/jquery-3.1.1.min.js"></script>

        <!-- Bootstrap core-->
        <link rel="stylesheet" href="./vendors/bootstrap4-alpha/css/bootstrap.min.css">
        <link rel="stylesheet" href="./vendors/font-awesome-4.7.0/css/font-awesome.min.css">
        <script src="./vendors/bootstrap4-alpha/js/bootstrap.min.js"></script>

        <!-- Custom styles for this template -->
        <link href="./assets/css/signIn.css" rel="stylesheet">
        <script src="./assets/js/signIn.js"></script>

    </head>

    <body>
        <div class="container">

            <form autocomplete="off" class="form-signin" method="POST" action="<?php $_SERVER['PHP_SELF']; ?>">
                <br>
                <div id = "mgTitle" class = "mb-4">
                    <img  class = "" width = 65% height = 60% src = "assets/img/logo.png"/>
                    <!--<h5 class="form-signin-heading">SALES PANEL</h5>-->
                </div><!-- /mgTitle -->
                <div class="row mb-4"><label for="userName" class = "align-self-center">Username</label>
                    <input type="text" id="userName" class="form-control" required autofocus name="username"></div>
                <div class="row">
                    <label for="pass" class = "align-self-center">Password</label>
                <input type="password" id="pass" class="form-control" required name="password">
                </div>

                <button class="btn btn-lg btn-purp mt-5" type="submit" onclick="login();">Sign in</button>
            </form>

            <div class="row mt-5 pt-3 justify-content-center">
                <img id="sendGif" class="dropOpacity align-self-start" src="assets/img/sending2.gif" width="100px" height="100px" style="position: absolute;"/>
                <p id="wang" class=" align-self-end" style="opacity:0; font-size:17px; margin-left:10px; margin-top:20px; font-weight: 700;" ><?php echo "<script type = 'text/javascript'>
            jQuery(function(){
            jQuery('#sendGif').css('visibility', 'hidden');
            if('$output' != ''){
            if ('$output' != 'Authorization Granted') {
                jQuery('#wang').css('color', '#DD2A2A');
            } else {
                jQuery('#wang').css('color', '#2FC143');
            }
            jQuery('#wang').text('$output').fadeTo('slow', 1).delay(1000)
            .fadeTo('slow', 0);
            jQuery('.btn').prop('disabled', false);}
            });</script>"?></p>
            </div>

        </div> <!-- /container -->
    </body>
    <script>
        function login(){
            /*$(".btn").prop('disabled', true);*/
            if($("#userName").val() != ""){
                $("#sendGif").css("visibility", "visible");
            }
        }

    </script>
</html>
