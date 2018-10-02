var json;
var qtyhere;
var values;
var currentrow;
var counter = 1;
$(document).ready(function () {
    genInv("get");
    $("#dataToggler").on("click", function (e) {
        $("#main nav").toggleClass("boxshad boxshod");
        $("#main .tableinfo").toggleClass("chgwt");
        console.log('collap');
        var windowWidth = $(window).width();
        if (windowWidth > 579) {
            console.log('collap');
            e.preventDefault();
            $("#wrapper").toggleClass("toggled");
            /*$(".menunames").toggleClass("collapse in");*/
            /*$('#sidebar').addclass('');*/
        } else {
            var collap = $('#sidebar').css('height');
            (parseInt(collap) > 70) ? collapseSidebar(): openSidebar();
            console.log(collap);
        }
    });

    showProductlist();

    
    
    /*
    $(".paidmoney").on("input", function(){
        var clean = this.value.replace(/[*0-9,]/g,"").replace(/(,.*?/, "$1");
        if(clean !== this.value) this.value = clean;
    });*/
    /*$(".inp").on("click", function (e) {
        (e.target).select();
        console.log(e.target.id)
    })*/
    

    $('.modal').on('hidden.bs.modal', function () {
        $("#InvoiceBody").empty();
        $(".exprem").show();
        console.log("ewere");
    });

    $("#togglecusts").on("click", function (e) {
        e.preventDefault();
        $('.custentry').toggleClass('notvisible visible');
        $('.btnma').toggleClass('notvisible visible');
        $('#custaddress').toggleClass('notvisible visible');
        $('#custphno').toggleClass('notvisible visible');
        valu = $(this).html();
        $(this).html($(this).attr("data-val"));
        $(this).attr("data-val", valu);
        return false;
    });


});

$(document).ready(function () {
    $('.expdate').datepicker({
        dateFormat: "yy-mm-dd"
    });
});

function activateAutoComplete(a) {
    console.log($(a).attr("str"));
    switch ($(a).attr("str")) {
        case "pro":
            console.log(a);
            if ($(a).autocomplete("instance")) {
                $(a).autocomplete({
                    disabled: false
                });
            } else {
                $(a).autocomplete({
                    source: showProductDropDown,
                    select: listItemClick
                });
            }
            break;
        case "text":
            console.log(a);
            if ($(a).autocomplete("instance")) {
                $(a).autocomplete({
                    disabled: false
                });
            } else {
                $(a).autocomplete({
                    source: showcustDropDown,
                    select: custItemClick
                });
            }
            break;
    }


}

function selchang(a){
    console.log("ssd");
    $(a).val() == "Credit" ? $(".paidmoney").val("0").css("visibility", "hidden") : $(".paidmoney").val("").css("visibility", "visible");
}

function  inpsel(a){
    $(a).select();
}

function getpos(a) {
    currentrow = $(a).attr("pos");
    activateAutoComplete($("#product" + currentrow));
}

function printNow() {
    /*$("#InvoiceBody").empty();
    var printArea = $(".salestable").clone();
    console.log(printArea);
    $("#InvoiceBody").css("font-size", "12px");
    $("#InvoiceBody").html(printArea);
    $("#InvoiceBody .rowCasing"+ counter).remove();
    $("#Invoicefooter .amt").html($("#totalAmt").text() + "<br/><br/><br/>" + $("#totalAmt").text());*/
    //var billval = $("#billto").val();
    console.log(checkdata());

    console.log($(".custentry.visible").val());

    if (checkdata()) {
        collect_sales_info();
        var str = $("#invoicenum td:nth-child(2)").html();
        //$("#invoicepaymeth select").replaceWith($("#invoicepaymeth select").val());
        str = str.replace('PP', '1');
        if (str == "199999") {
            str = 1;
        } else {
            str = 1 + Number(str) - 100000;
        }
        var t = "./assets/php/invoiceNoGen.php?c=" + String(str);
        console.log(t);
        $.get(t, function (response) {
            //console.log(response);
            genInv("get");
        });
        location.reload();
        $("#billto").replaceWith("<p id= 'billto' style = 'font-weight: 600;width:300px;'>" + $(".custentry.visible").val() + "</p>");
        $("#togglecusts").css("display", "none");
        data = $(".methpay").find(":selected").attr("value");
        console.log(data);
        $("#invoicepaymeth .float-left").html(data);
        $("#invoicepaid .float-left").html($(".paidmoney").val());
        $('#printtable').modal('hide');
        w = window.open();
        for (var op = 1; op < counter; op++) {
            var cnt = $(".Invoicediv input[id=qty" + op + "]").val();
            $(".Invoicediv input[id=qty" + op + "]").replaceWith(cnt);
            var cat = $(".Invoicediv input[id=product" + op + "]").val();
            $(".Invoicediv input[id=product" + op + "]").replaceWith(cat);
        }
        w.document.write($(".Invoicediv").html());
        w.print();
        w.close();
    } else {
        alert("you failed to fill somefields")
    }

    //$("#billto").val(billval);

}

function checkdata() {
    if ($("#totalAmt").text() != "" && $("#totalAmt").text() != "0") {
        if ($(".paidmoney").val() != "") {
            if ($("#invoicebalance .float-left").html() != "") {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    } else {
        return false;
    }
}

function checkexpdate() {

    //
}

function collect_sales_info() {
    paid = Number($(".paidmoney").val().replace(",",""));
    outbalmon = Number($("#totalAmt").text()) - paid;
    invoiceno = [];
    custname = [];
    prod = [];
    quants = [];
    expdate = [];
    uniprice = [];
    totprice = [];
    totAmt = [];
    pricesys = [];
    salesref = [];
    salesdate = [];
    paymeth = [];
    paidamt = [];
    outbal = [];
    $(".saleproducts").each(function (index, element) {
        if (invoiceno.length < $("#totalno").html()) {
            invoiceno.push($("#invoicenum td:nth-child(2)").html());
        }
    });
    $(".saleproducts").each(function (index, element) {
        if (custname.length < $("#totalno").html()) {
            custname.push($(".custentry.visible").val());
        }
    });
    $(".saleproducts").each(function (index, element) {
        if (paidamt.length < $("#totalno").html()) {
            paid = $(".paidmoney").val().replace(",","");
            paidamt.push(paid);
        }
    });
    $(".saleproducts").each(function (index, element) {
        if (outbal.length < $("#totalno").html()) {
            outbal.push(String(outbalmon));
        }
    });
    $(".saleproducts").each(function (index, element) {
        if (totAmt.length < $("#totalno").html()) {
            totAmt.push(($("#totalAmt").text()));
        }
    });
    $(".pricesystem").each(function (index, element) {
        if (pricesys.length < $("#totalno").html()) {
            pricesys.push($(element).html());
        }
    });
    $(".saleproducts").each(function (index, element) {
        if (salesref.length < $("#totalno").html()) {
            salesref.push(String($(".salesref").attr("nam")));
        }
    });
    $(".saleproducts").each(function (index, element) {
        if (salesdate.length < $("#totalno").html()) {
            salesdate.push($("#invoicedate td:nth-child(2)").html());
        }
    });
    $(".saleproducts").each(function (index, element) {
        if (paymeth.length < $("#totalno").html()) {
            if ($("#invoicepaymeth select").val()) {
                paymeth.push($("#invoicepaymeth select").val());
            } else {
                paymeth.push("cash");
            }
        }
    });
    $(".saleproducts").each(function (index, element) {
        if (prod.length < $("#totalno").html()) {
            prod.push($(element).val());
        }
    });
    $(".quants").each(function (index, element) {
        if (quants.length < $("#totalno").html()) {
            quants.push($(element).val());
        }
    });
    $(".expdate").each(function (index, element) {
        if (expdate.length < $("#totalno").html()) {
            expdate.push($(element).val());
        }
    });
    $(".unitprice").each(function (index, element) {
        if (uniprice.length < $("#totalno").html()) {
            uniprice.push($(element).html());
        }
    });
    $(".totalno").each(function (index, element) {
        if (totprice.length < $("#totalno").html()) {
            totprice.push($(element).html());
        }
    });

    /*console.log(invoiceno);
    console.log(custname);
    console.log(prod);
    console.log(quants);
    console.log(uniprice);
    console.log(totprice);
    console.log(totAmt);
    console.log(salesref);
    console.log(salesdate);
    console.log(paymeth);*/

    arr = [];
    for (var c = 0; c < invoiceno.length; c++) {
        arr[c] = [];
        arr[c].push(invoiceno[c]);
        arr[c].push(custname[c]);
        arr[c].push(prod[c]);
        arr[c].push(quants[c]);
        arr[c].push(uniprice[c]);
        arr[c].push(totprice[c]);
        arr[c].push(totAmt[c]);
        arr[c].push(paidamt[c]);
        arr[c].push(outbal[c]);
        arr[c].push(pricesys[c]);
        arr[c].push(salesref[c]);
        arr[c].push(salesdate[c]);
        arr[c].push(paymeth[c]);
        arr[c].push(expdate[c]);
    }
    console.log(arr);
    data = JSON.stringify(arr);
    url = "./assets/php/savesalesrecord.php?c=" + data;
    $.get(url, function (resp) {
        //json = JSON.parse(resp);
        console.log(resp);
    });
    //console.log(data);
}

function selcus(a) {
    if (a.attr("str") == "select") {
        console.log($(".custentry").find(":selected").text());
        data = $(".custentry").find(":selected").text();
        url = "./assets/php/getoutbal.php?c=" + data;
        $.get(url, function (resp) {
            //json = JSON.parse(resp);
            $("#invoicebalance .float-left").html(resp);
            console.log(resp);
            console.log($(".custt").val());

        });
        url = "./assets/php/getoutbal.php?c=" + data;
        $.get(url, function (resp) {
            for (var t = 0; t < json.length; t++) {
                if (json[t].customer_name == data) {
                    console.log(resp);
                    $("#custinfo").html(
                        "Address: " + json[t].address + "<br/>Phone no: " + json[t].customer_phone
                    );
                }
            }

        });
    } else {
        console.log($(a).val());
        data = $(a).val();
        url = "./assets/php/getoutbal.php?c=" + data;
        $.get(url, function (resp) {
            //json = JSON.parse(resp);
            $("#invoicebalance .float-left").html(resp);
            console.log(resp);

        });
    }

}

function makecust(a) {
    $(a).animate({
        backgroundColor: "#d9534f"
    }, 100).delay(2000).animate({
        backgroundColor: "#5cb85c"
    }, 100);

    cust = $(".txt").val();
    add = $("#custaddress").val();
    phn = $("#custphno").val();
    if (add == "" && phn == "") {
        url = "./assets/php/getCust.php?c=" + cust;
    } else {
        url = "./assets/php/getCust.php?c=store" + "&add=" + add + "&phn=" + phn + "&nam=" + cust;
    }
    console.log(cust);
    $.get(url, function (resp) {
        console.log(resp);
        if(resp == "have already"){
            alert("customer name already exist");
        }else{
           // $(".custnameget").attr("custId", resp);
            $("#custinfo").html(
                add + "<br/>" + phn
            );
        }

    });
}



function printtab() {
    $(".exprem").hide();
    $("#custinfo").text("");
    $("#billto").replaceWith('<div style = "width:300px;" id = "billto"><select  str = "select" onchange = "selcus($(this));" class="custentry visible form-control my-2" name="customers"></select><input onkeyup = "selcus($(this));" onfocus = "activateAutoComplete($(this))" placeholder = "Customer name" name="cust_name" str = "text" class="custentry custnameget txt notvisible" style="/*width:60%*/" ><input id = "custaddress" class = "mt-1 notvisible" placeholder="address"/><input id = "custphno" class = "mt-1 notvisible" placeholder="phone"/><button class ="btnma btn btn-sm btn-success notvisible ml-2" style="position:absolute; width:50%; " type = "button" onclick = "makecust($(this));">make customer</button></div>');
    $("#invoicepaymeth .float-left").html('<select onchange = "selchang($(this))" class="form-control methpay"  name="paymethod" style="font-size:12px;width:120px; margin-right:50px;"><option value="Cash" disabled selected hidden>Choose method</option><option value="Cash">Cash</option><option value="Cheque">Cheque</option><option value="POS">POS</option><option value="Credit">Credit</option></select>');
    $("#invoicepaid .float-left").html('<input type = "number" class = "paidmoney form-control" style="width:120px;"/>');
    $("#invoicebalance .float-left").html("");

    var t = "./assets/php/getCust.php?c=every";
    $.get(t, function (response) {
        //var json = "";
        if (response != "") {
            json = JSON.parse(response);
            str = "<option value='' disabled selected hidden>select customer</option>";
            for (var u = 0; u < json.length; u++) {
                str += '<option " class = "custt" value="' + json[u].customer_name + '">' + json[u].customer_name + '</option>';
            }
            $(".custentry.visible").html(str);
        }
    });

    $("#togglecusts").css("display", "block");

    var printArea = $(".salestable").clone();
    console.log(printArea);
    $("#InvoiceBody").css("font-size", "12px");
    $("#InvoiceBody").html(printArea);
    $("#InvoiceBody .rowCasing" + counter).remove();
    $("#Invoicefooter .amt").html($("#totalAmt").text() + "<br/><br/><br/>" + $("#totalAmt").text());
    /* var t = "./assets/php/getCust.php?outbal=get";
     $.get(t, function(response){
         var json = "";
         if(response != ""){
             json = JSON.parse(response);
             str = "<option value='' disabled selected hidden>select customer</option>";
             for(var u = 0; u<json.length; u++){
                 str += '<option value="' + json[u].customer_name + '">' + json[u].customer_name + '</option>';
             }
             $(".custentry.visible").html(str);
         }
     });*/
    /*$("#invoicebalance .float-left").html($("#totalAmt").text());*/
}

function genInv(a) {
    console.log("here");
    var t = "./assets/php/invoiceNoGen.php?c=" + String(a);
    $.get(t, function (response) {
        var json = JSON.parse(response);
        /*var resp = ;
        var invno = ;*/
        var invstr = String(100000 + Number(json[0].inv));
        invstr = invstr.replace('1', 'PP');
        $("#invoicenum td:nth-child(2)").html(invstr);
    });

    console.log($("#invoicenum td:nth-child(2)").html());
}

function emptytabel() {
    $("#InvoiceBody").empty();
}

function getTotal(a) {
    currentrow = $(a).attr("pos");
    pro = $("#product" + currentrow).val();
    exp = $("#expdate" + currentrow).val();
    orig = parseInt($("#qty" + currentrow).attr("orig"));
    
    tot = 0;
    $(".expdate").each(function (index, element) {
        prod = $("#product" + $(element).attr("pos")).val();
        qty = $("#qty" + $(element).attr("pos")).val();
        console.log(prod);
        if(pro == prod && exp == $(element).val()){
            tot += parseInt(qty);
        }
    });
    console.log(tot);
    console.log(orig);
    if(tot > orig){
        alert("dont go above stock for this expirydate");
        $("#qty" + currentrow).val(0);
        return 0;
    }
    
    console.log($(a).attr("pos"));

    console.log($("#unitprice" + currentrow).text());
    if ($("#unitprice" + currentrow).text() != "") {
        $("#totalprice" + currentrow).text($("#unitprice" + currentrow).text() * $("#qty" + currentrow).val());
    }
    calculateTotal();
}

function showcustDropDown(request, ponse) {

    //var formdata = $('#used_form').serialize();
    console.log();
    var t = "./assets/php/getCust.php?c=only&nam=" + request.term;
    $.get(t, function (resp) {
        //var json = "";
        data = [];
        if (resp != "") {
            json = JSON.parse(resp);
            for (var a = 0; a < json.length; a++) {
                data.push(json[a].customer_name)
            }
            console.log(data);
            ponse(data);
        }
    });
}

function showProductDropDown(request, response) {

    //var formdata = $('#used_form').serialize();
    var formdata = "product_name=" + $("#product" + currentrow).val();
    var data = [];
    //console.log(formdata);
    $.post("./assets/php/getProduct.php", formdata, function (resp) {
        console.log(resp);
        json = JSON.parse(resp);
        //pro = request.term;
        for (var a = 0; a < json.length; a++) {
            data.push(json[a].product_name)
        }
        console.log(data);
        response(data);

    });
}

function showqty(a) {
    console.log($(a).val(), $(a).attr("pos"));
    pro = $("#product" + $(a).attr("pos")).val();
    var url = "./assets/php/getproductqty.php?nam=" + pro + "&exp=" + $(a).val();

    $.get(url, function (resp) {
        console.log(resp);
        $("#qty" + $(a).attr("pos")).val(resp);
        $("#qty" + $(a).attr("pos")).attr("orig", resp);
        getTotal(a);
    });
}

function getText(w) {
    $(".procon.deactivate").toggleClass("deactivate");
    $("li.actparent").toggleClass("actparent");
    $(w).toggleClass("actparent");
    for (var a = 0; a < values.length; a++) {
        if (values[a].product_name === $(w).find("h5").text()) {
            var url = "./assets/php/getproductqty.php?nam=" + values[a].product_name + "&exp=" + values[a].expiry_date;

            $.get(url, function (resp) {
                $("#stock").text(resp);
            });
            
            values[a].expiry_date == "0000-00-00" ? $("#expdate").html("<span style='color:#E87070;'>NONE / EXPIRED</span>"): $("#expdate").text(values[a].expiry_date);
            console.log(values[a].product_name);
            console.log(values[a].expiry_date);
            console.log(values[a].product_description);
            console.log(values[a].product_wholesaleprice);
        }

    }
}


function saddtocart() {
    console.log($(".saleproducts").is(":focus"));
    altprice($("#unitprice" + counter), "reset");
    var mprocess = "go";
    if($("#stock").text() == "0"){
        return 0;
    }
    
    
    for (var a = 0; a < values.length; a++) {
        /*if (values[a].product_name === $("li.actparent").find("h5").text()) {
        $(".expdate").each(function (index, element) {
            prod = "#product" + $(element).attr("pos");
            console.log($(prod).val());
            if (values[a].expiry_date == $(element).val() && values[a].product_name == $(prod).val() && mprocess == "go") {
                alert("you cant add the same expiry date to cart");
                mprocess = "stop";
            }else if(mprocess != "stop"){
                mprocess = "stop"
                
            }
        });*/
        if (values[a].product_name === $("li.actparent").find("h5").text()) {
            $(".rowCasing" + currentrow + " .unitprice").attr("data-price", values[a].product_retailprice);
            $(".rowCasing" + currentrow + " .saleproducts").val(values[a].product_name);
            $(".rowCasing" + currentrow + " .expdate").val(values[a].expiry_date);
            $(".rowCasing" + currentrow + " .desp").text(values[a].product_description);
            $(".rowCasing" + currentrow + " .unitprice").text(values[a].product_wholesaleprice);

            pro = $("#product" + currentrow).val();
           
            $("#totalprice" + currentrow).text(values[a].product_wholesaleprice * $("#qty" + currentrow).val());
            
            $(".rowCasing" + currentrow + " .quants").attr("orig", $("#stock").text());
            
        }
        if(counter == currentrow){
            counter += 1;
            var str = "";
            str += '<tr class="rowCasing' + counter + '" pos = "1"><td class="td text-center" onclick = "removerow(' + counter + ');">' + counter + '</td><td class="td text-center"><input type = "number" pos = "' + counter + '" id="qty' + counter + '" class="inp quants" onclick = "inpsel($(this));" orig = "" onkeyup="getTotal($(this))" onchange="checkstk($(this))"/></td><td class="td text-center"><input pos = "' + counter + '" id = "product' + counter + '" str="pro" onclick = "inpsel($(this));" class="inp saleproducts" onfocus = "getpos($(this))"/></td><td class="td exprem"><input  onchange = "showqty($(this));" value = "" pos = "' + counter + '" id = "expdate' + counter + '" class="inp expdate " name = "expirydate" /></td><td class="td text-center" ><p class ="desp" id="description' + counter + '"></p></td><td pos = "' + counter + '" class="td text-center"  onclick = "altprice($(this));getTotal($(this));"><p pos = "' + counter + '" class = "unitprice" data-price = "" data-pricename = "retail" id="unitprice' + counter + '"></p><div id = "pricesystem' + counter + '" class = "badge-danger pricesystem badge badge-pill" pos ="' + counter + '">wholesale</div></td><td class="td text-center"><p id="totalprice' + counter + '" class="totalno"></p></td></tr>';


            $('#candidates').append(str);
            $('.expdate').datepicker({
                dateFormat: "yy-mm-dd"
            });


            if ($("#unitprice" + counter).text() != "") {
                $("#totalprice" + counter).text($("#unitprice" + counter).text() * $("#qty" + counter).val());
            }
        }
        calculateTotal();

        $("#totalno").html(counter - 1);

    }
}

function showProductlist() {

    $.get("./assets/php/getProduct.php", function (data, status) {
        //alert("Data: " + data + "\nStatus: " + status);
        //console.log(data);

        /*for(var a = 0; a < json.length; a++){
            resp.push(json[a].product_name)
        }
        console.log(resp);*/

        var options = {
            valueNames: ['product_name', 'product_wholesaleprice', 'product_retailprice'],
            // Since there are no elements in the list, this will be used as template.
            item: '<li class = "mb-4 pro-item" onclick = "getText($(this))"><h5 class="product_name"></h5><div style = "color: #bbb;"><div style = "float:left; width:40%; text-align:left">Wholesale : <span class="product_wholesaleprice"></span></div><div style = "float:right; width:40%;">Retail : <span class="product_retailprice"></span></div></div><div style = "clear:both"></div></li>'
        };

        values = JSON.parse(data);

        console.log(values);

        var userList = new List('hacker-list', options, values);

    });
}

function getstock() {
    $("#stock-list .list").empty();
    urlstr = "./assets/php/getstock.php?c=" + $("li.actparent").find("h5").text();
        $.get(urlstr, function (data, status) {
        //alert("Data: " + data + "\nStatus: " + status);
        //console.log(data);

        /*for(var a = 0; a < json.length; a++){
            resp.push(json[a].product_name)
        }
        console.log(resp);*/
        console.log(data);
        var options = {
            valueNames: ['expirydate', 'stockremain'],
            // Since there are no elements in the list, this will be used as template.
            item: '<li class = "mb-4 stock-item"><div style = "color: #333; float:left; width:50%; text-align:left"><h5 class="expirydate text-center"></h5></div><div style = "color: #333; float:right; width:50%;"><h5 class="stockremain text-center"></h5></div></div><div style = "clear:both"></div></li>'
        };

        val = JSON.parse(data);

        console.log(val);

        var userList = new List('stock-list', options, val);
            totstk = 0
            $(".stockremain").each(function(index,element){
                totstk += parseInt($(element).text());
            });
            $(".totalstk").text(totstk);
    });
}

function custItemClick(event, ui) {
    /*$(".custnameget").autocomplete({
        disabled:true
    });*/
    url = "./assets/php/getoutbal.php?c=" + ui.item.label;
    $.get(url, function (resp) {
        //json = JSON.parse(resp);
        $("#invoicebalance .float-left").html(resp);
        console.log(resp);
        console.log($(".custt").val());
        for (var t = 0; t < json.length; t++) {
            if (json[t].customer_name == ui.item.label) {
                $("#custinfo").html(
                    json[t].address + "<br/>" + json[t].customer_phone
                );
            }
        }

    });

    /*
    console.log(json);
    //pro = $("input[name= product_name]").val();
    console.log(ui.item.label);*/
}

function actexp(a){
   /* if($("#product" + a).val() == ""){
        !hasClass("deactivate") ? $("#expdate"+ a).addClass("deactivate") : null;
    }else{
        hasClass("deactivate") ? $("#expdate"+ a).removeClass("deactivate") : null;
    }*/
}

function listItemClick(event, ui) {
    $("#product" + currentrow).autocomplete({
        disabled: true
    });
    console.log(json);
    //pro = $("input[name= product_name]").val();
    console.log(ui.item.label);
    for (var p = 0; p < json.length; p++) {
        if (json[p].product_name == ui.item.label) {
            $("#description" + currentrow).text(json[p].product_description);
            /*if($("#pricesystem" + $(a).attr("pos")).html();)*/
            $("#unitprice" + currentrow).text(json[p].product_wholesaleprice);
            $("#unitprice" + currentrow).attr("data-price", json[p].product_retailprice);

            $("#totalprice" + currentrow).text(json[p].product_wholesaleprice * $("#qty" + currentrow).val());
        }
    }
    altprice($("#unitprice" + currentrow), "reset");
    if (currentrow == counter) {
        counter += 1;
        var str = "";
        str += '<tr class="rowCasing' + counter + '" pos = "1"><td class="td text-center" onclick = "removerow(' + counter + ');">' + counter + '</td><td class="td text-center"><input type = "number" pos = "' + counter + '" id="qty' + counter + '" class="inp quants" onclick = "inpsel($(this));" orig = "" onkeyup="getTotal($(this))" onchange="checkstk($(this))"/></td><td class="td text-center"><input pos = "' + counter + '" id = "product' + counter + '" str="pro" onclick = "inpsel($(this));" class="inp saleproducts" onfocus = "getpos($(this))"/></td><td class="td exprem"><input  onchange = "showqty($(this));" value = "" pos = "' + counter + '" id = "expdate' + counter + '" class="inp expdate " name = "expirydate" /></td><td class="td text-center" ><p class ="desp" id="description' + counter + '"></p></td><td pos = "' + counter + '" class="td text-center"  onclick = "altprice($(this));getTotal($(this));"><p pos = "' + counter + '" class = "unitprice" data-price = "" data-pricename = "retail" id="unitprice' + counter + '"></p><div id = "pricesystem' + counter + '" class = "badge-danger pricesystem badge badge-pill" pos ="' + counter + '">wholesale</div></td><td class="td text-center"><p id="totalprice' + counter + '" class="totalno"></p></td></tr>';

        

        $('#candidates').append(str);
    }
    $('.expdate').datepicker({
        dateFormat: "yy-mm-dd"
    });

    $("#totalno").html(counter - 1);
}

function removerow(a) {
    if (a == "1") {
        $("#qty1").val("");
        $("#product1").val("");
        $(".desp").text("");
        $(".unitprice").text("");
        $(".totalno").text("");
    } else {
        if(counter == a){
            counter -= 1;
            $('.rowCasing' + a).remove();
        }
    }

}

function calculateTotal() {
    var nek = 0;
    $(".totalno").each(function (index, element) {
        nek += Number($(element).text());
    });
    $("#totalAmt").text(nek);
}

function altprice(b, reset) {
    if (reset) {

    } else {
        reset = "noreset";
    }
    console.log("hello");
    if (reset == "noreset") {
        var a = $(b).find("p");
        if ($(a).text() != "") {
            console.log("noreset");
            c = $(a).text();
            $(a).text($(a).attr("data-price"));
            $(a).attr("data-price", c);
            $("#pricesystem" + $(a).attr("pos")).toggleClass("badge-danger badge-warning");
            c = $("#pricesystem" + $(a).attr("pos")).html();
            $("#pricesystem" + $(a).attr("pos")).html($(a).attr("data-pricename"));
            $(a).attr("data-pricename", c);
        }
    } else {
        console.log("reset");
        $("#pricesystem" + $(b).attr("pos")).hasClass("badge-danger") ? a : $("#pricesystem" + $(b).attr("pos")).toggleClass("badge-danger badge-warning")
        $("#pricesystem" + $(b).attr("pos")).html("wholesale");
        $(b).attr("data-pricename", "retail");
    }
}
