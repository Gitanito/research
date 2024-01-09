<?php
include_once "header.php";

?>
<div class="card">
        <div class="card-header">
            <ul class="nav nav-tabs card-header-tabs">
                <li class="nav-item">
                    <a class="nav-link" href="left_nav_topics.php">Themen</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" href="#">Seiten</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link disabled" href="#" tabindex="-1" aria-disabled="true">Disabled</a>
                </li>
            </ul>
        </div>
        <div class="card-body">
            <h5 class="card-title">Seiten</h5>
            <p class="card-text">
                <?php
                    foreach ($_wordindex as $key => $wi) {
                        if (substr($key,0, 1) != "_") {
                            echo "<a href='wiki/".findPage($wi)."' target=main>".$key."</a><br>";
                            if (isset($_wordindex['_additionals'][$key])) {
                                foreach ($_wordindex['_additionals'][$key] as $p) {
                                    echo "&nbsp;&nbsp;&nbsp;<a href='wiki/".$p.".html' target=main>".$p."</a><br>";
                                }
                            }
                        }
                    }
                ?>
            </p>
        </div>
    </div>

