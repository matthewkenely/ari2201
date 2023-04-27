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
    <link rel="stylesheet" href="styles/indexStyles.css">

    <title>Location Chronicles</title>
</head>

<body>
    <div id="header">
        <ul id="navbarmenu">
            <li><a href="./" class="navbaritem" id="selected">Home</a></li>
            <li><a href="news.html" class="navbaritem navanimate">News</a></li>
        </ul>
    </div>

    <main>
        <div id="intro">
            <img id="logo" src="images/logo.png" alt="Location Chronicles Logo">
            <h1>Location Chronicles</h1>
            <p>A project carried out by Matthew Kenely as part of his ARI2201 Individual Assigned Practical Task at the
                University of Malta.</p>
        </div>

        <h3>Article Location Detector</h3>
        <div id="drop_zone" ondrop="dropHandler(event);" ondragover="dragOverHandler(event);" ondragleave="dragLeaveHandler(event)" ondragend="dragLeaveHandler(event)">
            <form action="javascript:getLocation()" method="POST" id="articleinput" ondrop="console.log('drop')">
                <input id="locationinput" type="text" name="url" placeholder="https://www.example.com">
                <!-- <input type="submit" value="Submit"> -->
            </form>
        </div>

        <div id="result"></div>

        <!-- <div id="universities">
            <a href="https://www.um.edu.mt/ict/ai" target="_blank"><img id="umlogo" src="images/umlogo.png"
                    alt="University of Malta Logo"></a>
        </div> -->
    </main>
</body>

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
                    console.log(data)
                    result.innerHTML = data;
                }
            });
        }
        else if (article.match(regex)) {
            result.innerHTML = "Detecting location...";

            $.ajax({
                type: "POST",
                url: "get_location.php",
                data: {
                    url: article
                },
                success: function(data) {
                    console.log(data)
                    result.innerHTML = data;
                }
            });

        } else {
            result.innerHTML = "Please input a valid URL";
        }


    }

    function dropHandler(ev) {
        console.log("File(s) dropped");

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
        console.log("File(s) in drop zone");

        // Prevent default behavior (Prevent file from being opened)
        ev.preventDefault();

        // Make drop zone glow
        document.getElementById("locationinput").style.border = "1px solid #000000";

    }

    function generalLeave(ev) {
        console.log("File(s) out of drop zone");

        // Prevent default behavior (Prevent file from being opened)
        ev.preventDefault();

        // Make drop zone glow
        document.getElementById("locationinput").style.border = "1px solid #d7d5d5";
    }

    html = document.documentElement;
    html.addEventListener("dragover", generalDrag);
    html.addEventListener("dragleave", generalLeave);

    function dragOverHandler(ev) {
        console.log("File(s) in drop zone");

        // Prevent default behavior (Prevent file from being opened)
        ev.preventDefault();

        // Make drop zone glow
        document.getElementById("locationinput").style.backgroundColor = "#f1f1f1";
    }

    function dragLeaveHandler(ev) {
        console.log("File(s) out of drop zone");

        // Prevent default behavior (Prevent file from being opened)
        ev.preventDefault();

        // Make drop zone glow
        document.getElementById("locationinput").style.backgroundColor = "#ffffff";
    }
    
</script>

</html>