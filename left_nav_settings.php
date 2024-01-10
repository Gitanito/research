<?php
include_once "header.php";

if (isset($_POST['styling'])) {
    setcookie('backendstyle', $_POST['styling'], time()+60*60*24*365, '/');
    echo "<script>window.top.location.href='index.php?left=left_nav_settings.php';</script>";
}

?>
<div class="card">
        <div class="card-header">
            <ul class="nav nav-tabs card-header-tabs">
                <li class="nav-item">
                    <a class="nav-link" href="left_nav_topics.php">Themen</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="left_nav_pages.php">Index</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" href="#"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-gear" viewBox="0 0 16 16">
                            <path d="M8 4.754a3.246 3.246 0 1 0 0 6.492 3.246 3.246 0 0 0 0-6.492M5.754 8a2.246 2.246 0 1 1 4.492 0 2.246 2.246 0 0 1-4.492 0"/>
                            <path d="M9.796 1.343c-.527-1.79-3.065-1.79-3.592 0l-.094.319a.873.873 0 0 1-1.255.52l-.292-.16c-1.64-.892-3.433.902-2.54 2.541l.159.292a.873.873 0 0 1-.52 1.255l-.319.094c-1.79.527-1.79 3.065 0 3.592l.319.094a.873.873 0 0 1 .52 1.255l-.16.292c-.892 1.64.901 3.434 2.541 2.54l.292-.159a.873.873 0 0 1 1.255.52l.094.319c.527 1.79 3.065 1.79 3.592 0l.094-.319a.873.873 0 0 1 1.255-.52l.292.16c1.64.893 3.434-.902 2.54-2.541l-.159-.292a.873.873 0 0 1 .52-1.255l.319-.094c1.79-.527 1.79-3.065 0-3.592l-.319-.094a.873.873 0 0 1-.52-1.255l.16-.292c.893-1.64-.902-3.433-2.541-2.54l-.292.159a.873.873 0 0 1-1.255-.52zm-2.633.283c.246-.835 1.428-.835 1.674 0l.094.319a1.873 1.873 0 0 0 2.693 1.115l.291-.16c.764-.415 1.6.42 1.184 1.185l-.159.292a1.873 1.873 0 0 0 1.116 2.692l.318.094c.835.246.835 1.428 0 1.674l-.319.094a1.873 1.873 0 0 0-1.115 2.693l.16.291c.415.764-.42 1.6-1.185 1.184l-.291-.159a1.873 1.873 0 0 0-2.693 1.116l-.094.318c-.246.835-1.428.835-1.674 0l-.094-.319a1.873 1.873 0 0 0-2.692-1.115l-.292.16c-.764.415-1.6-.42-1.184-1.185l.159-.291A1.873 1.873 0 0 0 1.945 8.93l-.319-.094c-.835-.246-.835-1.428 0-1.674l.319-.094A1.873 1.873 0 0 0 3.06 4.377l-.16-.292c-.415-.764.42-1.6 1.185-1.184l.292.159a1.873 1.873 0 0 0 2.692-1.115z"/>
                        </svg></a>
                </li>
            </ul>
        </div>
        <div class="card-body">
            <h5 class="card-title">Einstellungen</h5>
            <p class="card-text">
                Themes von <a href="https://bootstrap.build/themes" target="_blank">https://bootstrap.build/themes</a> auswählen.<br>
                <h4>Oberfläche</h4>
                <form method="post">
                    <select name="styling" onchange="submit()">
                        <option <?=($_COOKIE['backendstyle']=="Superhero"?"selected":"")?>>Superhero</option>
                        <option <?=($_COOKIE['backendstyle']=="Materia"?"selected":"")?>>Materia</option>
                        <option <?=($_COOKIE['backendstyle']=="Journal"?"selected":"")?>>Journal</option>
                        <option <?=($_COOKIE['backendstyle']=="Solar"?"selected":"")?>>Solar</option>
                        <option <?=($_COOKIE['backendstyle']=="Sandstone"?"selected":"")?>>Sandstone</option>
                        <option <?=($_COOKIE['backendstyle']=="Cerulean"?"selected":"")?>>Cerulean</option>
                        <option <?=($_COOKIE['backendstyle']=="Cyborg"?"selected":"")?>>Cyborg</option>
                        <option <?=($_COOKIE['backendstyle']=="Slate"?"selected":"")?>>Slate</option>
                        <option <?=($_COOKIE['backendstyle']=="United"?"selected":"")?>>United</option>
                        <option <?=($_COOKIE['backendstyle']=="Lumen"?"selected":"")?>>Lumen</option>
                        <option <?=($_COOKIE['backendstyle']=="Pulse"?"selected":"")?>>Pulse</option>
                        <option <?=($_COOKIE['backendstyle']=="Simplex"?"selected":"")?>>Simplex</option>
                        <option <?=($_COOKIE['backendstyle']=="Cosmo"?"selected":"")?>>Cosmo</option>
                        <option <?=($_COOKIE['backendstyle']=="Flatly"?"selected":"")?>>Flatly</option>
                        <option <?=($_COOKIE['backendstyle']=="Litera"?"selected":"")?>>Litera</option>
                        <option <?=($_COOKIE['backendstyle']=="Sketchy"?"selected":"")?>>Sketchy</option>
                        <option <?=($_COOKIE['backendstyle']=="Spacelab"?"selected":"")?>>Spacelab</option>
                        <option <?=($_COOKIE['backendstyle']=="Minty"?"selected":"")?>>Minty</option>
                    </select>
                </form>
            <br><br><br><br>
                <a href="clear.php" target="_blank">CLEAR</a>
            </p>
        </div>
    </div>

