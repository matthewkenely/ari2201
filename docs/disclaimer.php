<!DOCTYPE html>
<html lang="en">

<head>
    <script src="script.js"></script>
    <script src="jquery-3.6.3.js"></script>

    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="styles/styles.css">
    <link rel="stylesheet" href="styles/headerStyles.css">
    <link rel="stylesheet" href="styles/footerStyles.css">
    <link rel="stylesheet" href="styles/indexStyles.css">
    <link rel="stylesheet" href="styles/disclaimer.css">

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" integrity="sha512-xodZBNTC5n17Xt2atTPuE1HxjVMSvLVW9ocqUKLsCC5CXdbqCmblAshOMAS6/keqq/sMZMZ19scR4PsZChSR7A==" crossorigin="" />
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js" integrity="sha512-XQoYMqMTK8LvdxXYG3nZ448hOEQiglfqkJs1NOQV44cWnUrBc8PkAOcXy20w0vlaXaVUearIOBhiXZ5V3ynxwA==" crossorigin=""></script>

    <title>Location Chronicles</title>
</head>

<body>
    <div id="header">
        <ul id="navbarmenu">
            <li><a href="./" class="navbaritem navanimate">Home</a></li>
            <li><a href="news.php" class="navbaritem navanimate">News</a></li>
        </ul>
    </div>

    <main>
        <div id="disclaimer">
            <h3>Disclaimer</h3>
            <p>This application does not store any personally identifiable information. However, it utilises user location data, which has the potential to track user behavior. Robust security measures have been implemented to discard this information after use.</p>
            <p>The application extracts location information from news articles, which may not always be accurate. Efforts have been made to train an accurate Named Entity Recognition (NER) model. However, please be aware that there might be limitations or errors in the provided information. Users are advised to verify the information from reliable sources.</p>
            <p>The application may retrieve news articles that contain sensitive information, such as crime or tragedy. While articles which may be deemed inappropriate are filtered out by default, please be aware that this filter has been developed subjectively and, as such, the application cannot guarantee complete avoidance of sensitive content.</p>
            <p>By using this application, you acknowledge and accept the above-mentioned conditions regarding privacy, accuracy of information, sensitivity.</p>
        </div>
    </main>
</body>

<footer>
    <div id="footer">
        <p><a href="disclaimer.php">Disclaimer</a></p>

    </div>
</footer>

<script>
    let result = document.getElementById("result");

    function getLocation(file = null) {
        document.getElementById("locationinput").style.backgroundColor = "#ffffff";
        document.getElementById("locationinput").style.border = "1px solid #d7d5d5";

        let article = document.getElementById("locationinput").value;
        var expression = /[-a-zA-Z0-9@:%._\+~#=]{1,256}\.[a-zA-Z0-9()]{1,6}\b([-a-zA-Z0-9()@:%_\+.~#?&//=]*)?/gi;
        var regex = new RegExp(expression);

        if (file) {
            result.innerHTML = "Detecting location...";

            $.ajax({
                type: "POST",
                url: "get_location.php",
                data: {
                    text: file
                },
                success: function(data) {
                    data = data.replace(/[\u{0080}-\u{FFFF}]/gu, "");
                    data = data.replace(/[^a-zA-Z0-9 ]/g, "");
                    data = data.replace(/\s+/g, ' ');
                    data = data.trim();
                    result.innerHTML = "Detected location: <b>" + data + "</b>";
                    updateMap(data);
                }
            });
        } else if (article.match(regex)) {
            result.innerHTML = "Detecting location...";
            // loading icon
            result.innerHTML = "<img id=\"loading\" src='./loading-gif.gif' alt='loading'>";

            $.ajax({
                type: "POST",
                url: "get_location.php",
                data: {
                    url: article
                },
                success: function(data) {
                    console.log(data)
                    console.log('HEREEEEE')
                    result.innerHTML = "Detected location: <b>" + data + "</b>";
                    updateMap(data);
                }
            });

        } else {
            result.innerHTML = "Please input a valid URL";
        }

    }

    function dropHandler(ev) {
        // Prevent default behavior (Prevent file from being opened)
        ev.preventDefault();

        if (ev.dataTransfer.items) {
            // Use DataTransferItemList interface to access the file(s)
            [...ev.dataTransfer.items].forEach((item, i) => {
                // If dropped items aren't files, reject them
                if (item.kind === "file") {
                    const file = item.getAsFile();
                    console.log(`… file[${i}].name = ${file.name}`);
                    // pass text document content
                    var reader = new FileReader();
                    reader.onload = function() {
                        var text = reader.result;
                        getLocation(text);
                    };
                    reader.readAsText(file);
                }
            });
        } else {
            // Use DataTransfer interface to access the file(s)
            [...ev.dataTransfer.files].forEach((file, i) => {
                console.log(`… file[${i}].name = ${file.name}`);
            });
        }
    }

    function generalDrag(ev) {
        // Prevent default behavior (Prevent file from being opened)
        ev.preventDefault();

        // Make drop zone glow
        document.getElementById("locationinput").style.border = "1px solid #000000";

    }

    function generalLeave(ev) {
        // Prevent default behavior (Prevent file from being opened)
        ev.preventDefault();

        // Make drop zone glow
        document.getElementById("locationinput").style.border = "1px solid #d7d5d5";
    }

    html = document.documentElement;
    html.addEventListener("dragover", generalDrag);
    html.addEventListener("dragleave", generalLeave);

    function dragOverHandler(ev) {
        // Prevent default behavior (Prevent file from being opened)
        ev.preventDefault();

        // Make drop zone glow
        document.getElementById("locationinput").style.backgroundColor = "#f1f1f1";
    }

    function dragLeaveHandler(ev) {
        // Prevent default behavior (Prevent file from being opened)
        ev.preventDefault();

        // Make drop zone glow
        document.getElementById("locationinput").style.backgroundColor = "#ffffff";
    }
</script>

<script>
    function updateMap(country) {
        // Find country coordinates using countries.csv
        country = country.toString();
        var countryCoordinates = null;

        var lat = null;
        var lng = null;

        $.ajax({
            type: "GET",
            url: "./countries.json",
            dataType: "json",
            success: function(data) {
                var countries = data;
                for (var i = 0; i < countries.length; i++) {
                    if (countries[i]['name'] == country) {
                        lat = countries[i]['latitude'];
                        lng = countries[i]['longitude'];
                        console.log(lat, lng)
                        break;
                    }
                }

                if (lat != null && lng != null) {
                    map.setView([lat, lng], 10);

                    map.eachLayer(function(layer) {
                        if (layer instanceof L.Marker) {
                            map.removeLayer(layer);
                        }
                    });

                    var marker = L.marker([lat, lng], {
                        icon: mainIcon
                    }).addTo(map);

                    // marker.bindPopup("<b>Article Location</b><br>" + country).openPopup();
                }
            }
        });
    }

    /* Map API */
    var map = L.map('map').setView([50.941310889912586, 6.958274487550246], 1);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map);

    var mainIcon = new L.Icon({
        iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-red.png',
        shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
        iconSize: [25, 41],
        iconAnchor: [12, 41],
        popupAnchor: [1, -34],
        shadowSize: [41, 41]
    });

    var smallIcon = new L.Icon({
        iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-blue.png',
        shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
        iconSize: [25, 41],
        iconAnchor: [12, 41],
        popupAnchor: [1, -34],
        shadowSize: [41, 41]
    });

    var request = new XMLHttpRequest();
    var hpath = "http://api.geonames.org/findNearbyWikipediaJSON?formatted=true&lat=50.941310889912586&lng=6.958274487550246&username=matthewkenely&style=full&wikipediaURL=https://en.wikipedia.org/wiki/Cologne&feature=null"
    var api;

    request.open("GET", hpath, false);
    request.send();

    var locations = JSON.parse(request.responseText);
</script>

</html>