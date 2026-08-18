function initFeedbackMap(lat, lng) {
    var point = {lat: lat, lng: lng};
    var map = new google.maps.Map(document.getElementById('map'), {zoom: 4, center: point});
    var marker = new google.maps.Marker({position: point, map: map});
}