<?php 
require_once "assets/php/includes/start_session.php" ;
require_once "assets/php/includes/functions.php";
confirm_logged_in();
$root = "http://localhost/webplay/PricePointFrontEnd/" ?>

<!doctype html>
<html lang="en">

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="description" content="">
        <meta name="author" content="">

        <title>PricePoint Phamarcy</title>


        <!-- jQuery core -->
        <script src="./vendors/jquery/jquery-3.1.1.min.js"></script>
        
        <!-- jQueryui core -->
        <link rel="stylesheet" href="./vendors/jquery-ui-1.12.1/jquery-ui.css">
        <script src="./vendors/jquery-ui-1.12.1/jquery-ui.js"></script>

        <!-- Bootstrap core-->
        <link rel="stylesheet" href="./vendors/bootstrap4-alpha/css/bootstrap.min.css">
        <link rel="stylesheet" href="./vendors/font-awesome-4.7.0/css/font-awesome.min.css">
        <script src="./vendors/bootstrap4-alpha/js/bootstrap.min.js"></script>
        
        
        <!-- Hamburger css -->
        <link href="./vendors/hamburgers/dist/hamburgers.css" rel="stylesheet">
        
        <!-- list core js -->
        <script src="./vendors/List/List.js"></script>

        <!-- Custom styles for this template -->
        <link href="./assets/css/index.css" rel="stylesheet">
        <script src="./assets/js/index.js"></script>
    </head>
    
    <body>
        <div id="wrapper">
            <div id="sidebar"class="">

                <div class="sidecon p-4 pt-5">
                <div id="hacker-list" class="">

                    <input class="search" placeholder="Search" id = "mysearch" onfocus = "$('.list .actparent').toggleClass('actparent');"/>
                    <span class="shp-cart ml-5 fa fa-shopping-cart" style = "transform: scale(2,2);" onclick="saddtocart()"></span>
                    <div class="centerrowdiv mt-3">
                    <button class="sort btn btn-warning btn-pill" data-sort="product_name">
                        Name
                    </button>
                    <button class="sort btn btn-success btn-pill" data-sort="product_unitprice">
                        Cost
                    </button>
                    </div>
                    <ul class="list">
                    </ul>
                </div>
                <div class="mt-2 px-3" id="productinfo">
                    
                    <div class="procon deactivate row justify-content-between p-1 px-3" data-toggle = "modal" data-target = "#stocklist" onclick="getstock();">
                        <h4 class="align-self-center">Stock</h4>
                        <span id="stock" class="align-self-center"></span>
                    </div>
                    
                    <div class="procon deactivate row justify-content-between mt-3 p-1 px-3" data-toggle = "modal" data-target = "#stocklist" onclick="getstock();">
                        <h4 class="align-self-center">Exp date</h4>
                        <span id="expdate" class="align-self-center"></span>
                    </div>
                    
                </div>
                </div>
            </div>
            <div id="main" class="container-fluid nopadding">
                <nav class="fixed-top navbar py-3 px-sm-4 d-flex flex-row justify-content-between boxshod" >
                    <div class="d-inline-flex d-flex float-right float-sm-left">
                        <button id="dataToggler" class="m-sm-1 mr-1 mt-1 p-sm-1 hamburger hamburger--arrowturn" type="button">
                            <span class="hamburger-box">
                                <span class="hamburger-inner"></span>
                            </span>
                        </button>
                        <!--p id="dataToggler" class="m-sm-1 mr-1 mt-1 p-sm-1"><a href="#"><i style="color:#222; transform: scale(1.3,1); opacity:.7;" class="fa fa-arrow-left fa-2x"></i></a></p-->
                        <h1 class="ml-sm-3 align-self-center" style="font-size: 1.8rem; font-weight:600;" href="#">PricePoint Pharmacy</h1>
                    </div>
                    
                    <div class="row mr-5">
                        
                        <img class="rounded-circle mr-2 align-self-center" src="assets/img/avatar.png">
                        <h6 class="align-self-center bold salesref" nam="<?php echo $_SESSION['username']; ?>">
                            <?php echo $_SESSION['username']; ?>
                        </h6>
                        <a id="logout" href="assets/php/logout.php"><button class = "btn ml-4 btn-purp">
                            Log out
                        </button></a>
                    </div>
                </nav>
                
                <div class="row tableinfo justify-content-between mx-5"><h5 class="bold">Total Products = &nbsp; <span id="totalno" class="bold align-self-center"></span></h5><h5 class="bold align-self-center">Total = &nbsp; <span id="totalAmt" class="bold"></span></h5>
                    <button class = "btn btn-success align-self-center" data-toggle = "modal" data-target = "#printtable" id="scopytable"  onclick="printtab();"><i class="fa fa-print"></i>
                        Print
                    </button>
                    <!--<button class = "btn btn-success align-self-center"  onclick="printNow();"><i class="fa fa-print"></i>
                        Print
                    </button>-->
                </div>
                
                <div class="maincon">
                    
                <form autocomplete="off" role="form" method="post" id="used_form" class="mx-5 my-4" action="FormPost.php">
                    <div class="table-responsive salestable">
                    <table class="table table-striped table-bordered table-sm" width="100%">
                        <col width="3%">
                        <col width="3%">
                        <col width="34%">
                        <col class="exprem" width="10%">
                        <col width="30%">
                        <col width="10%">
                        <col width="10%">
                        <thead>
                            <tr>
                                <th class="hd">S/N</th>
                                <th class="hd">QUANTITY</th>
                                <th class="hd">ITEM</th>
                                <th class="hd exprem">EXPIRY DATE</th>
                                <th class="hd">DESCRIPTION</th>
                                <th class="hd unithd"  data-bool = "false" >UNIT PRICE</th>
                                <th class="hd">AMOUNT</th>
                            </tr>
                        </thead>
                        <tbody id="candidates" class="cand">
                            <tr class="rowCasing rowCasing1" pos ="1">
                                <td class="td sn text-center" onclick="removerow('1')">1</td>
                                <td class="td text-center"><input type = "number" pos = "1" id="qty1" class="inp quants" orig = "" onclick = "inpsel($(this));"  onkeyup="getTotal($(this))" /></td>
                                <td class="td text-center"><input pos = "1" id = "product1" class="inp saleproducts " onclick = "inpsel($(this));" str="pro" name = "product_name" onfocus = "getpos($(this))" /></td>
                                <td class="td exprem"><input onchange = "showqty($(this));"  pos = "1" id = "expdate1"  class="inp expdate f-13" name = "expirydate"/></td>
                                <td class="td text-center" ><p id="description1" class = "desp"></p></td>
                                <td class="td text-center" onclick = "altprice($(this)); getTotal($(this));" pos = "1"><p class = "unitprice" data-price = "" data-pricename = "retail" id="unitprice1" pos = "1"></p ><div id = "pricesystem1" class = "badge-danger pricesystem badge badge-pill" pos = "1">wholesale</div></td>
                                <td class="td text-center"><p id="totalprice1" class="totalno"></p></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                </form>
                </div>
                
            </div>   
        </div>
        <div class="modal fade" id="printtable" role="dialog" >
            <div class="modal-dialog modal-lg">

                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title ml-3">Print Preview</h5>
                        <button type="button" class="close" id="closeInvoice" data-dismiss="modal" onclick="emptytabel();">×</button>
                    </div>
                    <div class="modal-body nopadding">

                        <form role="form" method="post" id="Invoice_form">
                            <div class="Invoicediv">
                                
                                <div class="row mx-3" id="InvoiceHeader">
                                    <div class="col-4">
                                        <img  class = "" width = 200px height = 105px src = "assets/img/logo.png"/>
                                        <div class="ml-2" style="font-size:12px;">
<p>"...<b>D</b>ivinely <b>A</b>ssisted"</p>
                                            <p>29, AIDEYAN ST,<br/> OFF IHAMA ROAD,<br/> GRA, BENIN CITY<br/></p>
                                            <table class="" width="100%">
                                                <col width="50%">
                                                <col width="50%">
                                                <tbody id="invoiceinfo" class="">
                                                    <tr id="invoiceemail">
                                                        <td class="invrow">Email</td>
                                                        <td class="invrow float-left">PricePointng@yahoo.com</td>
                                                    </tr>
                                                    <tr id="invoicetel">
                                                        <td class="invrow">Tel</td>
                                                        <td class="invrow float-left">08097474051</td>
                                                    </tr>
                                                    <tr id="invoicetel">
                                                        <td class="invrow"></td>
                                                        <td class="invrow float-left">08164114324</td>
                                                    </tr>
                                                    <tr id="invoicetel">
                                                        <td class="invrow">Bill To</td>
                                                        <td class="invrow float-left"></td>
                                                    </tr>
                                                    
                                                </tbody>
                                            </table>
                                            <div style="width:100%;">
                                                <div style = "width:200px;" id = "billto">
                                                    
                                                </div>
                                                <div><p id="custinfo" class="mt-1" style="width:250px;font-size:13px; font-weight:600; text-transform: uppercase;"></p></div>
                                                <button class="btn btn-sm my-2" id ="togglecusts" data-val = "Choose Customer">Type Customer</button>
                                            </div>
                                            
                                        </div>
                                    </div>
                                    <div class="offset-4 col-4 mt-2">
                                        <h2 style = "font-weight:700;">INVOICE</h2>
                                        <div style = "width:250px;">
                                            <table class="" width="100%">
                                                <col width="60%">
                                                <col width="40%">
                                                <!--<thead>
                                                    <tr>
                                                        <th class="hd">S/N</th>
                                                        <th class="hd">QUANTITY</th>
                                                    </tr>
                                                </thead>-->
                                                <tbody id="invoiceinfo" class="">
                                                    <tr id="invoicenum">
                                                        <td class="invrow">Invoice Number</td>
                                                        <td class="invrow float-left">ST101165293</td>
                                                    </tr>
                                                    <tr id="invoicedate">
                                                        <td class="invrow">Invoice Date</td>
                                                        <td class="invrow float-left"><?php echo date("Y-m-d");?></td>
                                                    </tr>
                                                    <tr id="invoicepage">
                                                        <td class="invrow">Page</td>
                                                        <td class="invrow float-left">1</td>
                                                    </tr>
                                                    <tr id="invoicebranch">
                                                        <td class="invrow">Branch</td>
                                                        <td class="invrow float-left">BENIN</td>
                                                    </tr>
                                                    <tr id="invoicebalance">
                                                        <td class="invrow" onclick="selcust('bring')">Outstanding<br/> Balance</td>
                                                        <td class="invrow float-left"> 0 </td>
                                                    </tr>
                                                    <tr id="invoicepaymeth">
                                                        <td class="invrow">Payment Method</td>
                                                        <td class="invrow float-left"></td>
                                                    </tr>
                                                    <tr id="invoicepaid">
                                                        <td class="invrow">Paid</td>
                                                        <td class="invrow float-left mt-2"></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                            <!--<div style="display:block;">
                                                <p style="float:left; display:inline;font-size:12px;">Invoice Number &nbsp;</p><span style="float:right; display:inline;font-size:12px;">ST101165293</span>
                                            </div>
                                            <div style="display:block;">
                                                <p style="float:left; display:block;font-size:12px;">Invoice Date &nbsp;</p><span style="float:right; display:inline;font-size:12px;"><?php //echo date("d/m/Y");?></span>
                                            </div>-->
                                        </div>
                                        
                                    </div>
                                </div>
                                <div class="row mx-4 px-3 deactivate" id="InvoiceBody">
                                    
                                </div>
                                <div class="row mx-4 px-3" id="Invoicefooter">
                                    <table class="" width="100%">
                                        <col width="50%">
                                        <col width="30%">
                                        <col width="20%">
                                        <tbody id="invoiceinfo" class="">
                                            <tr id="invoiceacc">
                                                <td class="invrow" ><p>Account Details: <br/>PRICEPOINT NIG LTD<br/> 0068987636<br/> STERLING BANK</p></td>
                                                <td class="invrow float-left" style="text-align:right;">Total Invoice Amount<br/>Payment/Credit Applied<br/><br/><b>TOTAL</b></td>
                                                <td class="invrow float-right amt mr-4"></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    <div class="row justify-content-between w-100">
                                        <div class = "text-center"><p>_______________________</p><p>PricePoint Sales Rep</p></div>
                                        <div class = "text-center"><p>_______________________</p><p>Customer</p></div>
                                    </div>
                                    </div>
                                <!--</div>-->

                                <style>
                                    @media print{
                                        .pricesystem{
                                            display:none;
                                        }
                                        #billto{
                                            text-transform: uppercase;
                                            font-size: 14px;
                                        }
                                        textarea {
                                            border: none;
                                            overflow: auto;
                                            outline: none;

                                            -webkit-box-shadow: none;
                                            -moz-box-shadow: none;
                                            box-shadow: none;
                                        }
                                        .w-100 {
                                            width: 100% !important;
                                        }
                                        .justify-content-between{
                                            -webkit-box-pack: justify !important;
                                            -webkit-justify-content: space-between !important;
                                            -ms-flex-pack: justify !important;
                                            justify-content: space-between !important;
                                        }
                                        .inp{
                                            width:100%;
                                            height:'auto';
                                            border:none !important;
                                            outline-width: 0; 
                                            white-space: normal;
                                            width: 100%;
                                            -webkit-box-shadow: none;
                                            -moz-box-shadow: none;
                                            box-shadow: none;
                                            cursor:text;
                                            resize:none;
                                            text-align: center;
                                            overflow:hidden;
                                            color: black;
                                        }
                                        .text-center{
                                            text-align: center !important;
                                        }
                                        .hd{
                                            text-align: center;
                                            background: rgb(149,187,223);
                                            color: white;
                                        }
                                        .mx-5{
                                            margin-right: 3rem !important;
                                            margin-left: 3rem !important;
                                        }
                                        .my-4{
                                            margin-top: 1.5rem !important;
                                            margin-bottom: 1.5rem !important;
                                        }
                                        .table-sm{
                                            padding: 0.3rem;
                                        }
                                        .gone{
                                            display:none;
                                        }
                                        .table-bordered{
                                            border: 1px solid #aaa;
                                        }

                                        .table-bordered th,
                                        .table-bordered td {
                                            /*border: 1px solid #eceeef;*/
                                        }

                                        .table-bordered td {
                                            /*border: 1px solid #eceeef;*/
                                            font-size: 12px !important;
                                        }

                                        .table-bordered thead th,
                                        .table-bordered thead td {
                                            /*border-bottom-width: 2px;*/
                                        }
                                        .table-striped tbody tr:nth-of-type(odd) {
                                            background-color: rgba(0, 0, 0, 0.05);
                                        }

                                        .table {
                                            width: 100%;
                                            max-width: 100%;
                                            margin-bottom: 1rem;
                                            border-collapse: collapse;
                                        }

                                        .table th{
                                            padding: 0.75rem;
                                            vertical-align: top;
                                            /*border-top: 1px solid #eceeef;*/
                                            border-left: 2px solid #aaa;
                                        }
                                        .table td {
                                            /*padding: 0.75rem;*/
                                            padding: 2px 4px !important;
                                            vertical-align: top;
                                            /*border-top: 1px solid #eceeef;*/
                                            border-left: 2px solid #aaa;
                                        }

                                        .table thead th {
                                            vertical-align: bottom;
                                            border-bottom: 2px solid #aaa;
                                            color: black;
                                        }

                                        .table tbody + tbody {
                                            border-top: 2px solid #aaa;
                                        }
                                        .invrow{
                                            font-size:12px;
                                        }
                                        .row{
                                            display: -webkit-box;
                                            display: -webkit-flex;
                                            display: -ms-flexbox;
                                            display: flex;
                                            -webkit-flex-wrap: wrap;
                                            -ms-flex-wrap: wrap;
                                            flex-wrap: wrap;
                                            /*margin-right: -15px;
                                            margin-left: -15px;*/
                                        }
                                        .mx-3{
                                            margin-right: 1rem !important;
                                            margin-left: 1rem !important;
                                        }
                                        .col-4{
                                            -webkit-box-flex: 0;
                                            -webkit-flex: 0 0 33.333333%;
                                            -ms-flex: 0 0 33.333333%;
                                            flex: 0 0 33.333333%;
                                            max-width: 33.333333%;
                                        }
                                        .col-5 {
                                            -webkit-box-flex: 0;
                                            -webkit-flex: 0 0 41.666667%;
                                            -ms-flex: 0 0 41.666667%;
                                            flex: 0 0 41.666667%;
                                            max-width: 41.666667%;
                                        }

                                        .ml-2{
                                            margin-left: 0.5rem !important;
                                        }
                                        .mr-4{
                                            margin-right: 1.5rem !important;
                                        }
                                        .float-left{
                                            float:left !important;;
                                        }
                                        .float-right{
                                            float:right !important;;
                                        }
                                        .offset-4 {
                                            margin-left: 33.333333%;
                                        }
                                        .mt-2{
                                            margin-top: 0.5rem !important;
                                        }
                                        mx-3{
                                            margin-right: 1rem !important;
                                            margin-left: 1rem !important;
                                        }
                                        .bold{
                                            font-weight: 800;
                                        }
                                    }
                                </style>

                            </div>
                        </form>
                        <div class="modal-footer pull-left w-100">
                            <div class="row justify-content-center w-100 d-flex flex-column">
                                <div class="my-1 align-self-center">

                                    <img id="createloadgif" src="assets/magicload.gif" width="90px" height="60px" class="ml-2" style="position:absolute !important; visibility:hidden;">
                                    <button type="button" class="btn btn-grey " onclick="printNow();" id="btnprintnow">
                                        <i class="fa fa-print"></i>
                                        PRINT
                                    </button>
                                </div>
                                <p id = "createerror" class="align-self-center" style="font-size:15px;position:absolute !important;  text-align:center;font-weight: 600; opacity:0;"></p>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="modal fade" id="stocklist" role="dialog" >
            <div class="modal-dialog modal-lg">

                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title ml-3">Stock List</h5>
                        <button type="button" class="close" id="closestocklist" data-dismiss="modal" onclick="emptytabel();">×</button>
                    </div>
                    <div class="modal-body nopadding">

                        <div id="stock-list" class="w-75 mx-auto">

                            <input class="search invisible" placeholder="Search" id = "mysearch"/>
                            <h4 class="totalstk text-center"></h4>
                            <div class="centerrowdiv mt-3">
                                <button class="sort btn btn-stock btn-pill" data-sort="expirydate">
                                    Expiry date
                                </button>
                                <button class="sort btn btn-stock btn-pill" data-sort="stockremain">
                                    Stock
                                </button>
                            </div>
                            <ul class="list">
                            </ul>
                        </div>
                        
                        <div class="modal-footer w-100 mt-5">
                            <!--<div class="justify-content-center w-100 d-flex flex-column">
                                <div class="py-3 align-self-center">

                                    <img class="createloadgif" src="./assets/img/loader.gif" width="90px" height="60px" class="" style="position:absolute !important; visibility:hidden; margin-bottom:20px;margin-left:5px;">
                                    <button type="button" class="btn btn-success " onclick="addUseraccount($(this));" id="btnadduser">
                                        CREATE
                                    </button>
                                </div>
                                <p id = "adduseroutput" class="mr-3 align-self-center" style="width:50%; font-size:15px;position:absolute !important;  text-align:center;font-weight: 600; opacity:0;"></p>

                            </div>-->
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </body>
    
</html>