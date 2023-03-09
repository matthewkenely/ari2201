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
            <li><a href="news" class="navbaritem navanimate">News</a></li>
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
        <form action="https://mkenely.com/ari2201/detector" method="POST" target="_blank" id="articleinput">
            <input type="text" placeholder="https://www.example/com">
        </form>

        <div id="universities">
            <a href="https://www.um.edu.mt/ict/ai" target="_blank"><img id="umlogo" src="images/umlogo.png"
                    alt="University of Malta Logo"></a>
        </div>
    </main>
</body>

<script>
    $.post("./detector", function (data) {
        $(".result").html(data);
    });
</script>

</html>