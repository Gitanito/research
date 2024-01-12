<?php
include_once "header.php";

?>
<script>
    $(document).ready(function(){
        $("#myInput").on("keyup", function() {
            var value = $(this).val().toLowerCase();
            $(".card-body ul li").filter(function() {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
            });
        });
    });
</script>
<div class="card">
        <div class="card-header">
            <ul class="nav nav-tabs card-header-tabs">
                <li class="nav-item">
                    <a class="nav-link" href="left_nav_topics.php">Themen</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" href="#">Index</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="left_nav_settings.php"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-gear" viewBox="0 0 16 16">
                            <path d="M8 4.754a3.246 3.246 0 1 0 0 6.492 3.246 3.246 0 0 0 0-6.492M5.754 8a2.246 2.246 0 1 1 4.492 0 2.246 2.246 0 0 1-4.492 0"/>
                            <path d="M9.796 1.343c-.527-1.79-3.065-1.79-3.592 0l-.094.319a.873.873 0 0 1-1.255.52l-.292-.16c-1.64-.892-3.433.902-2.54 2.541l.159.292a.873.873 0 0 1-.52 1.255l-.319.094c-1.79.527-1.79 3.065 0 3.592l.319.094a.873.873 0 0 1 .52 1.255l-.16.292c-.892 1.64.901 3.434 2.541 2.54l.292-.159a.873.873 0 0 1 1.255.52l.094.319c.527 1.79 3.065 1.79 3.592 0l.094-.319a.873.873 0 0 1 1.255-.52l.292.16c1.64.893 3.434-.902 2.54-2.541l-.159-.292a.873.873 0 0 1 .52-1.255l.319-.094c1.79-.527 1.79-3.065 0-3.592l-.319-.094a.873.873 0 0 1-.52-1.255l.16-.292c.893-1.64-.902-3.433-2.541-2.54l-.292.159a.873.873 0 0 1-1.255-.52zm-2.633.283c.246-.835 1.428-.835 1.674 0l.094.319a1.873 1.873 0 0 0 2.693 1.115l.291-.16c.764-.415 1.6.42 1.184 1.185l-.159.292a1.873 1.873 0 0 0 1.116 2.692l.318.094c.835.246.835 1.428 0 1.674l-.319.094a1.873 1.873 0 0 0-1.115 2.693l.16.291c.415.764-.42 1.6-1.185 1.184l-.291-.159a1.873 1.873 0 0 0-2.693 1.116l-.094.318c-.246.835-1.428.835-1.674 0l-.094-.319a1.873 1.873 0 0 0-2.692-1.115l-.292.16c-.764.415-1.6-.42-1.184-1.185l.159-.291A1.873 1.873 0 0 0 1.945 8.93l-.319-.094c-.835-.246-.835-1.428 0-1.674l.319-.094A1.873 1.873 0 0 0 3.06 4.377l-.16-.292c-.415-.764.42-1.6 1.185-1.184l.292.159a1.873 1.873 0 0 0 2.692-1.115z"/>
                        </svg></a>
                </li>
            </ul>
        </div>
        <div class="card-body">
            <h5 class="card-title">Index</h5>
            <input id="myInput" type="text" placeholder="Search..">
            <p class="card-text">
                <ul>
                <?php
                    $entries = $db->query("SELECT * FROM wordindex LIMIT 20;");
                    while ($obj = $entries->fetchArray(SQLITE3_ASSOC)) {
                        // $key => $wi
                        if (substr($obj['name'],0, 1) != "_") {
                            echo "<li><a href='wiki/".findPage($obj['value'])."' target=main>".$obj['name']."</a>";
                            if (strstr($obj['value'], ".html")) {
                                echo " <a href='wordindex_edit.php?action=add&key=".$obj['name']."' target=main title='Ein Synonym hinzufügen'> + </a>";
                            } else {
                                echo " <a href='wordindex_edit.php?action=del&key=".$obj['name']."' target=main title='Synonym entfernen'> - </a>";
                            }
                            echo "</li>";
                        }
                    }
                ?>
            </ul>
            </p>
        </div>
    </div>

