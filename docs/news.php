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
    <link rel="stylesheet" href="styles/indexStyles.css">

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
                <input id="locationinput" type="text" name="url" placeholder="Input a Location">
                <div id="submit">
                    <input type="submit" value="→">
                </div>
            </form>
        </div>

        <div id="result"></div>
        </div>

        <div id="newsgrid" class="grid-container">
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
    function loadGrid() {
        let grid = document.getElementById("newsgrid");
        // load articles from csv


        // $.getJSON("objects.json", function(data) {
        //     let objects = data.objects;
        //     console.log(objects);

        //     objects.forEach(element => {
        //         grid.innerHTML += ' \
        //             <div class="grid-item" onclick="showObject(\'' + element.id + '\')"> \
        //                 <img class="museumimage" src="' + element.imgpath + '"> \
        //                     <div class="grid-item-description">' + element.title + '</div> \
        //             </div> \
        //             '
        //     });
        // });
    }

    function databaseLookup(file = null) {
        document.getElementById("locationinput").style.backgroundColor = "#ffffff";
        document.getElementById("locationinput").style.border = "1px solid #d7d5d5";

        let location = document.getElementById("locationinput").value;
        // expression of all maltese villages

        result.innerHTML = "Loading articles...";
        // loading icon
        result.innerHTML = "<img id=\"loading\" src='./loading-gif.gif' alt='loading'>";

        $.ajax({
            type: "POST",
            url: "load_grid.php",
            data: {
                location: location
            },
            success: function(data) {
                console.log(data);
                result.innerHTML = "Loading articles in:<b>" + data + "</b>";
                loadGrid(data);
            }
        });
    }

    // loadGrid();
</script>

</html>