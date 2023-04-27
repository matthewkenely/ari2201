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
        <form action="javascript:getLocation()" id="articleinput" ondrop="console.log('drop')">
            <input id="locationinput" type="text" placeholder="https://www.example.com">
        </form>
        
        <div id="museumgrid" class="grid-container">
            <div class="grid-item" onclick="showObject('1')">
                <img src="images/objects/1.jpg" alt="Object 1">
                <div class="grid-item-text">
                    <h3>Object 1</h3>
                    <p>Object 1 description</p>
                </div>
            </div>
            <div class="grid-item" onclick="showObject('2')">
                <img src="images/objects/2.jpg" alt="Object 2">
                <div class="grid-item-text">
                    <h3>Object 2</h3>
                    <p>Object 2 description</p>
                </div>
        </div>

        <!-- <div id="universities">
            <a href="https://www.um.edu.mt/ict/ai" target="_blank"><img id="umlogo" src="images/umlogo.png"
                    alt="University of Malta Logo"></a>
        </div> -->
    </main>
</body>

<script>
    let fullscreen = document.getElementById("fullscreenviewer");

    function showObject(objectref) {
        let objviewer = document.getElementById("objviewer");
        let objdescription = document.getElementById("objdescription");

        $.getJSON("objects.json", function (data) {
            let objects = data.objects;
            console.log(objects);
            let object = objects.find(obj => obj.id == objectref);

            objviewer.innerHTML = ' \
            <div id = "model"> \
                <model-viewer src="' + object.objpath + '" skybox-image="./images/spruit_sunrise.jpg" \
                    camera-controls disable-pan auto-rotate auto-rotate-delay="2000" interaction-prompt="none" \
                    camera-orbit="180deg 60deg 105%" camera-target="0m 0m 0m" exposure="5" shadow-intensity="1" \
                    shadow-softness="1" loading="lazy"> \
                    <div id="lazy-load-poster" slot="poster"></div> \
                    <div class="progress-bar hide" slot="progress-bar"> \
                        <div class="update-bar"></div> \
                    </div> \
                </model-viewer> \
            </div > \
            ';

            objdescription.innerHTML = ' \
            <div id="textcontent"> \
                <div id = "links"> \
                    <a href="' + object.wikipedia + '" \
                        target="_blank">Wikipedia</a> \
                        </div > \
                        <p>'+ object.description + '</p> \
                    </div > \
            ';

            fullscreen.style.display = "block";
        });


    }

    function hideObject() {
        fullscreen.style.display = "none";
    }

    function fadeOutFullscreen() {
        fullscreen.animate({
            opacity: 0
        }, 400, function () {
            $(document).css("display", "none");
        });

        setTimeout(function () {
            fullscreen.style.display = "none";
        }, 300);
    }

    document.onkeydown = function (evt) {
        evt = evt || window.event;
        if (evt.keyCode == 27 || evt.keyCode == 8) {
            fadeOutFullscreen()
        }
    };

    let description = document.getElementById("objdescription");
    function collapse() {
        // Extended
        if (description.style.width) {
            description.style.width = null;

            // Collapsed
        } else {
            description.style.width = "25%";
        }
    }

    function loadGrid() {
        let grid = document.getElementById("museumgrid");
        $.getJSON("objects.json", function (data) {
            let objects = data.objects;
            console.log(objects);

            objects.forEach(element => {
                grid.innerHTML += ' \
                    <div class="grid-item" onclick="showObject(\'' + element.id + '\')"> \
                        <img class="museumimage" src="'+ element.imgpath + '"> \
                            <div class="grid-item-description">' + element.title + '</div> \
                    </div> \
                    '
            });
        });
    }

    // loadGrid();

</script>

</html>

