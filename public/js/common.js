function ObjecttoParams(obj) {
    var p = [];
    for (var key in obj) {
        p.push(key + '=' + encodeURIComponent(obj[key]));
    }
    return p.join('&');
}
;
var placeSearch, autocomplete;
function initAutocomplete() {
    autocomplete = new google.maps.places.Autocomplete(
            (document.getElementById('autocomplete')),
            {types: []});
    autocomplete.addListener('place_changed', fillInAddress);
}
function fillInAddress() {
    // Get the place details from the autocomplete object.
    var place = autocomplete.getPlace();
    var scope = angular.element(document.getElementById("managelocation")).scope();
    scope.locationData.googlelocation = place.formatted_address;
    scope.locationData.lat = place.geometry.location.lat();
    scope.locationData.lng = place.geometry.location.lng();
}

function getNotification(type, baseUrl) {
    $.ajax({
        method: "POST",
        url: baseUrl + 'dashboard/getNotification',
        data: {type: type}
    }).done(function (response) {
        response = $.parseJSON(response);
        if (response.status == 'success') {
            var notificationHtml = '';
            var counter = 0;
            for (key in response.data) {
                notificationHtml += "<li><a href='#'/> <i class='fa fa-users text-aqua'></i>" + response.data[key].msg + "</a></li>";
                if (response.data[key].updated_date == null) {
                    counter++;
                }
            }
            if(counter>0){
                $("#notificaiton_count").html(counter);
            }
            if(notificationHtml!= '') {
                $("#notificationList li:first").prepend(notificationHtml);
            }
        }
    });
}

function updateNotification(type) {
    if(type=='merchant') {
        var baseUrl = serverMerchantApp;
    }
    $.ajax({
        method: "POST",
        url: baseUrl + 'dashboard/updateNotification',
        data: {type: type}
    }).done(function (response) {
        $("#notificaiton_count").html('');
    });
}
