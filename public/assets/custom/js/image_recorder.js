var video = document.querySelector("#live");
var capture = document.querySelector("#capture");
var recapture = document.querySelector("#recapture");
var upload = document.querySelector("#upload");
var canvas = document.querySelector("#canvas");

var rand =  Math.floor((Math.random() * 10000000));
var name  = "image_"+rand+".jpg";

var livePanelElement = document.querySelector('#livePanel');
var playbackPanelElement = document.querySelector('#playbackPanel');

var downloadLink = document.querySelector('a#downloadLink');

navigator.mediaDevices.getUserMedia({ video: true, audio: false })
    .then(function(stream) {
        video.srcObject = stream;
    });

capture.addEventListener('click', function() {
    canvas.getContext('2d').drawImage(video, 0, 0, canvas.width, canvas.height);

    livePanelElement.style.display = 'none';
    playbackPanelElement.style.display = 'block';
});

downloadLink.addEventListener('click', download, false);

function download() {
    this.href = canvas.toDataURL('image/jpeg');
    this.setAttribute( "download", name);
}

recapture.addEventListener('click', function() {
    livePanelElement.style.display = 'block';
    playbackPanelElement.style.display = 'none';
});

upload.addEventListener('click', function() {
    let image_data_url = canvas.toDataURL('image/jpeg');
    let file = dataURItoFile(image_data_url);

    let fileInputElement = document.getElementById('image');
    let container = new DataTransfer();

    container.items.add(file);
    fileInputElement.files = container.files;
});

function dataURItoFile(dataURI) {
// convert base64/URLEncoded data component to raw binary data held in a string
    var byteString;
    if (dataURI.split(',')[0].indexOf('base64') >= 0)
        byteString = atob(dataURI.split(',')[1]);
    else
        byteString = unescape(dataURI.split(',')[1]);
// separate out the mime component
    var mimeString = dataURI.split(',')[0].split(':')[1].split(';')[0];
// write the bytes of the string to a typed array
    var ia = new Uint8Array(byteString.length);
    for (var i = 0; i < byteString.length; i++) {
        ia[i] = byteString.charCodeAt(i);
    }

    return new File([ia], name,{type:mimeString, lastModified:new Date().getTime()});
}
