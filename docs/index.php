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

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" integrity="sha512-xodZBNTC5n17Xt2atTPuE1HxjVMSvLVW9ocqUKLsCC5CXdbqCmblAshOMAS6/keqq/sMZMZ19scR4PsZChSR7A==" crossorigin="" />
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js" integrity="sha512-XQoYMqMTK8LvdxXYG3nZ448hOEQiglfqkJs1NOQV44cWnUrBc8PkAOcXy20w0vlaXaVUearIOBhiXZ5V3ynxwA==" crossorigin=""></script>

    <title>Location Chronicles</title>
</head>

<body>
    <div id="header">
        <ul id="navbarmenu">
            <li><a href="./" class="navbaritem" id="selected">Home</a></li>
            <li><a href="news.php" class="navbaritem navanimate">News</a></li>
        </ul>
    </div>

    <main>
        <div id="homegrid">
            <div id="intro">
                <!-- <img id="logo" src="images/logo.png" alt="Location Chronicles Logo"> -->
                <h1>Location Chronicles</h1>
                <p>A project carried out by Matthew Kenely as part of his ARI2201 Individual Assigned Practical Task at the
                    University of Malta.</p>

                <p>The aim of this project is to provide Maltese users with a platform which facilitates the recognition of where news articles took place geographically, as well as the ability to filter a <a href="./news.php">database of news articles</a> based on geographical locations, either tailored to their location or to whichever village they select.</p>
            </div>

            <div id="detectorsection">
                <h3>Article Location Detector</h3>
                <div id="drop_zone" ondrop="dropHandler(event);" ondragover="dragOverHandler(event);" ondragleave="dragLeaveHandler(event)" ondragend="dragLeaveHandler(event)">
                    <form action="javascript:getLocation()" method="POST" id="articleinput" ondrop="console.log('drop')">
                        <input id="locationinput" type="text" name="url" placeholder="Submit a URL or drop a file">
                        <div id="submit">
                            <input type="submit" value="→">
                        </div>
                    </form>
                </div>

                <div id="resultfeedback">
                    <div id="result"></div>

                    <div id="feedbackSection">
                        <div id='feedbackButtons'>
                            <button onclick='submitFeedback(true)'>
                                <img id='thumbsUpButton' src='./images/thumbsup.png' alt='Thumbs Up'>
                            </button>
                            <button onclick='showCorrectLocationInput()'>
                                <img id='thumbsDownButton' src='./images/thumbsdown.png' alt='Thumbs Down'>
                            </button>
                        </div>
                        <form id='correctLocationForm' action="javascript:submitFeedback(false)" style='display: none;'>
                            <input type='text' id='correctLocationInput' placeholder='Enter correct location'>
                            <div id="correctSubmit">
                                <input type="submit" value="→">
                            </div>
                        </form>
                    </div>

                </div>

                <div id="map"></div>
            </div>


            <!-- <div id="universities">
            <a href="https://www.um.edu.mt/ict/ai" target="_blank"><img id="umlogo" src="images/umlogo.png"
                    alt="University of Malta Logo"></a>
        </div> -->
        </div>
    </main>

    <footer>
        <div id="footer">
            <p><a href="disclaimer.php">Disclaimer</a></p>
        </div>
    </footer>
</body>


<script>
    let result = document.getElementById("result");

    function getLocation(file = null) {
        document.getElementById('feedbackButtons').style.display = 'none';


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
                    result.innerHTML = "<b>" + data + "</b>";
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
                    result.innerHTML = "<b>" + data + "</b>";
                    updateMap(data);

                    data = data.replace(/[\u{0080}-\u{FFFF}]/gu, "");
                    data = data.replace(/[^a-zA-Z0-9 ]/g, "");
                    data = data.replace(/\s+/g, ' ');
                    data = data.trim();

                    if (data != 'Unable to detect location') {
                        // User feedback on result
                        document.getElementById('feedbackButtons').style.display = 'flex';
                    }
                }
            });

        } else {
            result.innerHTML = "Please input a valid URL";
        }

    }

    function submitFeedback(isCorrect) {
        // Remove feedback buttons
        document.getElementById('feedbackButtons').style.display = 'none';

        // Remove correct location input
        document.getElementById('correctLocationForm').style.display = 'none';

        var feedback = isCorrect ? 'Thumbs Up' : 'Thumbs Down';
        var correctLocation = isCorrect ? null : document.getElementById('correctLocationInput').value;
        var link = document.getElementById('locationinput').value;
        var predicted = document.getElementById('result').innerText;

        // Send the feedback data to the server or perform any necessary actions
        console.log('Feedback:', feedback);
        console.log('Correct Location:', correctLocation);

        if (feedback == 'Thumbs Up') {
            $.ajax({
                type: "POST",
                url: "feedback.php",
                data: {
                    good: link,
                    correct: predicted
                },
                success: function(data) {
                    console.log(data)
                    // Remove feedback buttons
                    document.getElementById('feedbackButtons').style.display = 'none';

                    setTimeout(function() {
                        alert('Thank you for your feedback!')
                    }, 500);
                }
            });

        } else if (feedback == 'Thumbs Down') {
            if (correctLocation != null) {
                updateMap(correctLocation);
                result.innerHTML = "<b>" + correctLocation + "</b>";
            }

            $.ajax({
                type: "POST",
                url: "feedback.php",
                data: {
                    bad: link,
                    correct: correctLocation
                },
                success: function(data) {
                    console.log(data)

                    // Remove feedback buttons
                    document.getElementById('feedbackButtons').style.display = 'none';

                    // Remove correct location input
                    document.getElementById('correctLocationForm').style.display = 'none';

                    setTimeout(function() {
                        alert('Thank you for your feedback!')
                    }, 500);
                }
            });
        }

    }

    function showCorrectLocationInput() {
        document.getElementById('feedbackButtons').style.display = 'none';
        document.getElementById('correctLocationForm').style.display = 'flex';
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
    function getVillageCoordinates(villageName) {
        var apiUrl = 'https://nominatim.openstreetmap.org/search?format=json&q=' + encodeURIComponent(villageName + ', Malta');

        // Make a GET request to the Nominatim API
        fetch(apiUrl)
            .then(response => response.json())
            .then(data => {
                if (data.length > 0) {
                    var village = data[0];
                    var lat = village.lat;
                    var lon = village.lon;
                    console.log('Coordinates of ' + villageName + ': Latitude = ' + lat + ', Longitude = ' + lon);

                    if (lat != null && lon != null) {
                        map.setView([lat, lon], 15);

                        map.eachLayer(function(layer) {
                            if (layer instanceof L.Marker) {
                                map.removeLayer(layer);
                            }
                        });

                        var marker = L.marker([lat, lon], {
                            icon: mainIcon
                        }).addTo(map);

                        // marker.bindPopup("<b>Article Location</b><br>" + country).openPopup();
                    }
                } else {
                    console.log('Coordinates not found for ' + villageName);
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
    }

    function updateMap(country) {
        getVillageCoordinates(country);
        // var countryCoordinates = null;

        // var lat = null;
        // var lng = null;

        // $.ajax({
        //     type: "GET",
        //     url: "./countries.json",
        //     dataType: "json",
        //     success: function(data) {
        //         var countries = data;
        //         for (var i = 0; i < countries.length; i++) {
        //             if (countries[i]['name'] == country) {
        //                 lat = countries[i]['latitude'];
        //                 lng = countries[i]['longitude'];
        //                 console.log(lat, lng)
        //                 break;
        //             }
        //         }

        //         if (lat != null && lng != null) {
        //             map.setView([lat, lng], 10);

        //             map.eachLayer(function(layer) {
        //                 if (layer instanceof L.Marker) {
        //                     map.removeLayer(layer);
        //                 }
        //             });

        //             var marker = L.marker([lat, lng], {
        //                 icon: mainIcon
        //             }).addTo(map);

        //             // marker.bindPopup("<b>Article Location</b><br>" + country).openPopup();
        //         }
        //     }
        // });
    }

    /* Map API */
    var map = L.map('map').setView([35.9, 14.4368], 11);

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