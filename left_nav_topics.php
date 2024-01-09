<?php
include_once "header.php";

?>
<div class="card">
        <div class="card-header">
            <ul class="nav nav-tabs card-header-tabs">
                <li class="nav-item">
                    <a class="nav-link active" href="#">Themen</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="left_nav_pages.php">Seiten</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link disabled" href="#" tabindex="-1" aria-disabled="true">Disabled</a>
                </li>
            </ul>
        </div>
        <div class="card-body">
            <h5 class="card-title">Themen</h5>
            <p class="card-text">
                <?php
                    foreach ($_wordindex as $key => $wi) {
                        if (substr($key,0, 1) != "_" && strstr($wi, '.html')) {
                            echo "<a href='wiki/" . $wi . "' target=main>" . $key . "</a><br>";
                        }
                    }
                ?>
            </p>
        </div>
    </div>

