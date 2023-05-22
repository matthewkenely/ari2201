<!DOCTYPE html>
<html lang="en">

<head>
    <script src="script.js"></script>
    <script src="jquery-3.6.3.js"></script>
    <script type="module" src="https://ajax.googleapis.com/ajax/libs/model-viewer/3.0.1/model-viewer.min.js"></script>

    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="styles/styles.css">
    <link rel="stylesheet" href="styles/headerStyles.css">
    <link rel="stylesheet" href="styles/footerStyles.css">
    <link rel="stylesheet" href="styles/indexStyles.css">
    <link rel="stylesheet" href="styles/newsStyles.css">

    <title>Location Chronicles • News</title>
</head>

<body>
    <div id="header">
        <ul id="navbarmenu">
            <li><a href="./" class="navbaritem navanimate">Home</a></li>
            <li><a href="news.php" class="navbaritem" id="selected">News</a></li>
        </ul>
    </div>

    <main>
        <h3>Database Lookup</h3>
        <div id="drop_zone">
            <form action="javascript:databaseLookup()" method="POST" id="articleinput" ondrop="console.log('drop')">
                <!-- <input id="locationinput" type="text" name="url" placeholder="Input a Location"> -->
                <select id="locationinput" type="text" name="url" placeholder="Choose a Location">

                    <div id="submit">
                        <input type="submit" style="display: none">
                    </div>
            </form>
        </div>

        <div id="result"></div>
        </div>

        <div id="newsgrid" class="grid-container">
            <p id="newsbrief">Choose a location from the dropdown list to filter the news article database based on where the articles took place in Malta.</p>
        </div>

        <!-- <div id="universities">
            <a href="https://www.um.edu.mt/ict/ai" target="_blank"><img id="umlogo" src="images/umlogo.png"
                    alt="University of Malta Logo"></a>
        </div> -->
    </main>

    <footer>
        <div id="footer">
            <p><a href="disclaimer.php">Disclaimer</a></p>
        </div>
    </footer>
</body>


<script>
    // get list of villages from ./data/locations.txt
    var villages = [];
    $.ajax({
        url: "./data/locations.txt",
        success: function(data) {
            villages = data.split("\n");

            console.log(villages)

            var select = document.getElementById("locationinput");

            // add default value which is unselectable
            var option = document.createElement("option");
            option.value = "";
            option.text = "Choose a Location";
            select.appendChild(option);


            // Populate the dropdown list
            for (var i = 0; i < villages.length; i++) {
                var option = document.createElement("option");
                option.value = villages[i];
                option.text = villages[i];
                select.appendChild(option);
            }

            select.addEventListener("change", function() {
                document.getElementById("articleinput").submit();
                // remove first element if it is the default value
                if (select.options[0].value == "") {
                    select.removeChild(select.options[0]);
                }
            });
        }
    });



    // Ask the user for permission to access their location data
    navigator.geolocation.getCurrentPosition(function(position) {
        var latitude = position.coords.latitude;
        var longitude = position.coords.longitude;

        var url = `https://nominatim.openstreetmap.org/reverse?lat=${latitude}&lon=${longitude}&format=json`;

        fetch(url)
            .then(response => response.json())
            .then(data => {
                var village = data.address.village;
                if (village) {
                    var location = village;
                    result.innerHTML = "<img id=\"loading\" src='./loading-gif.gif' alt='loading'>";
                    $.ajax({
                        type: "POST",
                        url: "load_grid.php",
                        data: {
                            location: location
                        },
                        success: function(data) {
                            console.log(data);

                            try {
                                data = JSON.parse(data);
                            } catch (e) {
                                console.log(e);
                                // result.innerHTML = "<b>Invalid Location</b>";
                                return;
                            }

                            result.innerHTML = "<b>" + data.location + "</b><hr>";
                            result.animate([{
                                opacity: 0
                            }, {
                                opacity: 1
                            }], {
                                duration: 400
                            })
                            loadGrid(data);
                        }
                    });
                }
            })
            .catch(error => {
                console.error("Error:", error);
                // alert("An error occurred while fetching the location data.");
            });
    });

    function loadGrid(data) {
        let grid = document.getElementById("newsgrid");
        // load articles from csv


        let articles = data.articles;
        console.log(articles);

        var i = 0
        let content = "";

        grid.innerHTML = '';
        articles.forEach(element => {
            if (i % 2 == 0) {
                content += '<div class="row">'
            }

            content += ' \
                <div class="grid-item"> \
                <a href="' + element.link + '" target="_blank"> \
                    <div class="grid-item-image"> \
                        <img src="' + element.image + '"> \
                        <div class="grid-item-description"> \
                        <p><b>' + element.title + '</b></p> \
                        <p>' + element.date + '</p></div> \
                    </div> \
                </a> \
                </div> \
                '

            if (i % 2 != 0) {
                content += '</div>'
            }
            i++;
        });

        grid.innerHTML = content;
        grid.style.opacity = 0;
        grid.animate([{
            opacity: 0
        }, {
            opacity: 1
        }], {
            duration: 400
        })
        grid.style.opacity = 1;
    }

    function databaseLookup(file = null) {
        let grid = document.getElementById("newsgrid");
        document.getElementById("locationinput").style.backgroundColor = "#ffffff";
        document.getElementById("locationinput").style.border = "1px solid #d7d5d5";

        let location = document.getElementById("locationinput").value;
        // expression of all maltese villages

        // loading icon
        result.innerHTML = "<img id=\"loading\" src='./loading-gif.gif' alt='loading'>";
        grid.innerHTML = "";

        $.ajax({
            type: "POST",
            url: "load_grid.php",
            data: {
                location: location
            },
            success: function(data) {
                console.log(data);

                try {
                    data = JSON.parse(data);
                } catch (e) {
                    console.log(e);
                    result.innerHTML = "<b>Invalid Location</b>";
                    return;
                }

                result.innerHTML = "<b>" + data.location + "</b><hr>";
                result.animate([{
                    opacity: 0
                }, {
                    opacity: 1
                }], {
                    duration: 400
                })
                loadGrid(data);
            }
        });
    }

    // loadGrid();
</script>

</html>