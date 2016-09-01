var URL = document.location.protocol+"//"+document.location.host+"/2016_IMY320_FantasticSix/web/wp-admin/admin-ajax.php";
jQuery(document).ready(function($){
   
    $("#role").change(function (evt) {       
        $.post(URL, {
            action  :   "create_member_form",
            type    :   evt.target.value
        }, function(response) {
            if (evt.target.value === "") {
                $("#extra_fields").html("");
            } else {
                $("#extra_fields").html(response);
            }            
        });       
    });    
});

function validateDOB() {
    if (!dobValidation(jQuery("#dateOfBirth").val())) {
        jQuery("#dateOfBirth").css("color", "#ff0000");
    } else {
        jQuery("#dateOfBirth").css("color", "#000");
    }
}

function showCountryList(){
    sendRequest({ action : "create_country_list", continent : jQuery("#continent").val()}, "countryDiv");
}

function showProvinceList(){
    sendRequest({ action : "create_province_list", country_id : jQuery("#countryID").val()}, "provinceDiv");
}

function showCityList() {
    sendRequest({ action : "create_city_list", province_id : jQuery("#provinceID").val()}, "cityDiv");
}

function showPostCodeInput() {
    sendRequest({ action : "create_postcode_input" }, "postCodeDiv");
}

function sendRequest(jsonObj, populateHTMLElementID) {
    jQuery.post(URL, jsonObj, function(response){
        jQuery("#"+populateHTMLElementID).html(response);
    });
}

function dobValidation(dob) {
    var dateRegEx = /^\d{4}\-\d{2}\-\d{2}$/;
    return dateRegEx.test(dob);
}
