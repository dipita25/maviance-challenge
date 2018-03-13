var Url = "http://www.ecb.europa.eu/stats/eurofxref/eurofxref-daily.xml";
var amount,currency;
var rate_list = [];
var currency_list = [];
$("#resultat_convert").attr("disabled",true);
$("#resultat_reverse").attr("disabled",true);

var obtenirCurrencies = function(){
    $.ajax({
        type: "GET",
        url: 'http://localhost/maviance/Rest/ApiGetCurrencies.php',
        contentType:"application/json; charset=utf-8",
        dataType: "json",
        success: function(data){
            console.log(data);
            $.each(data,function(key,value){
                $.each(value,function(key2,value2){
                    console.log(value2);
                    $("#currency_list").append("<option>"+value2+"</option>");
                    $("#currency_list_reverse").append("<option>"+value2+"</option>");
                });
            });

            taille = rate_list.length;

            for (var i = 0; i < taille; i++) {
                $("#currency_list").append("<option value="+currency_list[i]+">"+currency_list[i]+"</option>");
            }
            $(".loader").hide();
        },
        error: function(error){
            alert(error);
            $(".loader").hide();
        }
    });
};

obtenirCurrencies();

//recupération des valeurs des devises toutes les 5 minutes
//et appel de la fonction qui declenche coté serveur l'enregistrement des nouvelles valeurs en base de données
setInterval(function(){ 
    obtenirCurrencies();
 }, 300000
);

$("#form_convert").submit(function(event){

    $(".loader").show();

    // Prevent default posting of form - put here to work in case of errors
    event.preventDefault();
    // setup some local variables
    var $form = $(this);

    // Let's select and cache all the fields
    var $inputs = $form.find("input, select, button, textarea");

    // Serialize the data in the form
    var serializedData = $form.serialize();

    console.log(serializedData);
    $.ajax({
        type: "POST",
        url: 'http://localhost/maviance/Rest/ApiConversion.php',
        data:serializedData,
        success: function(donnees){
            $(".loader").hide();
            $("#resultat_convert").fadeIn().val(donnees);
        },
        error: function(error){
            alert(error);
            $(".loader").hide();
        }
    });
});

$("#form_reverse").submit(function(event){

    $(".loader").show();
    $("#choix").fadeIn().html($("#currency_list_reverse option:selected").text());

    // Prevent default posting of form - put here to work in case of errors
    event.preventDefault();
    // setup some local variables
    var $form = $(this);

    // Let's select and cache all the fields
    var $inputs = $form.find("input, select, button, textarea");

    // Serialize the data in the form
    var serializedData = $form.serialize();

    console.log(serializedData);
    $.ajax({
        type: "POST",
        url: 'http://localhost/maviance/Rest/ApiReverse.php',
        data:serializedData,
        success: function(donnees){
            $(".loader").hide();
            $("#resultat_reverse").fadeIn().val(donnees);
        },
        error: function(error){
            alert(error);
            $(".loader").hide();
        }
    });
});