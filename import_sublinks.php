<?php

error_reporting(E_ALL);
ini_set('display_errors', 'On');
ini_set('memory_limit', '512M');
ini_set('max_execution_time', '300');
include_once "header.php";
include_once "functions_add.php";
include_once "simple_html_dom.php";


?>
    <script>

        let mylist = [];
        let running = false;
        $( function() {

            function doit() {
                running = true;
                $('html,body').css('cursor','wait');

                let v = mylist.shift();
                console.log(v);
                let info = v.split("&");
                let title = info[0].split("=");
                $("#progress_doing").html("In Arbeit: <b>"+decodeURIComponent(title[1].replace(/\+/g, '%20'))+"</b>");

                $.get( "import_single_link.php?" + v, function( data ) {
                    console.log('done');
                    $('html,body').css('cursor','default');
                    let info = v.split("&");
                    let title = info[0].split("=");
                    $("#progress_info").html("<b>"+decodeURIComponent(title[1].replace(/\+/g, '%20'))+"</b> wurde importiert.");
                    $("#progress_count").html("<b>"+mylist.length+"</b> weitere in der Warteschlange.");
                    $("#progress_alert").show();
                    //window.parent.frames.left.location.reload();
                    if (mylist.length < 1) {
                        $("#progress_doing").html("In Arbeit: <b>-</b>");
                    }
                    running = false;
                });

            }

            $(".import").on( "click", function() {
               mylist.push($(this).data("q"));
               $(this).prop('disabled', true);
               $("#progress_count").html("<b>"+mylist.length+"</b> weitere in der Warteschlange.");
            });

            window.setInterval(function () {
                if (mylist.length > 0 && !running) {
                    doit();
                }
            }, 1000);
            $("#progress_alert").hide();

            $('#progress_alert').on('click', function(){
                $("#progress_alert").hide();
            })
        });
    </script>
    <div class="card">

        <div class="card-body">
            <h5 class="card-title">Neuen Link importieren</h5>
            <p class="card-text">

            <form method="post">
                <div class="form-group">
                    <label for="tit">URL</label>
                    <input type="text" class="form-control" id="tit" aria-describedby="titleHelp" name="myurl" value="<?=($_POST['myurl']??"")?>">
                </div>
                <button type="submit" class="btn btn-primary">Scan</button>
            </form>

            </p>
        </div>
    </div>

<table>
    <tr><th>Importieren</th><th>Titel</th><th>Link</th></tr>
<?php


if (isset($_POST['myurl']) && trim($_POST['myurl']) != "") {
    $baseurl = parse_url($_POST['myurl']);
    $regexp = "(.*?)<a .*?href=\"(.*?)\".*?>(.*?)<\/a>(.*)";
    if(preg_match_all("/$regexp/m", file_get_contents($_POST['myurl']), $matches, PREG_SET_ORDER)) {
        $displayed = [];


        $imported = [];
        $imported_ = $db->query("SELECT * FROM content WHERE type='link';");
        while ($a = $imported_->fetchArray(SQLITE3_ASSOC)) {
            $imported[] = $a['mylink'];
        }

        foreach($matches as $key => $match) {
            // $match[2] = link address
            // $match[3] = link text

            $link = $match[2];
            if (strstr( $link,"#")) continue;

            switch (substr($match[2],0,1)) {
                case "/":
                    $link = $baseurl["scheme"].'://'.$baseurl["host"].$match[2];
                    break;
                case "h":
                    $link = $match[2];
                    break;
                default:
                    $link = $baseurl["scheme"].'://'.$baseurl["host"].$baseurl["path"].'/'.$match[2];
            }
            $inlink = $link;
            if (in_array($inlink, $displayed)) continue;

            $intitle = $match[3];
            $intype = "link";

            //$imported = $_content->findBy([["type", "=", "link"],["mylink", "=", $inlink]]);
            echo "<tr><td><button class='import' id='l".$key."' data-q='mytitle=".urlencode($intitle)."&mylink=".urlencode($inlink)."' ".(in_array($inlink, $imported)?"disabled":"").">Import</button></td>";
            echo "<td>".$intitle."</td>";
            echo "<td><a target=_blank href='".$inlink."'>".$inlink."</a></td>";

            echo "</tr>";
            $displayed[] = $inlink;
        }
    }
}
?>
</table>

    <div id="progress_alert" class="alert alert-success" role="alert" style="position:fixed;top:10px;right:10px;">
        <h4 class="alert-heading">Fertig</h4>
        <p id="progress_info"></p>
        <hr>
        <p class="mb-0" id="progress_count"></p>
        <hr>
        <p class="mb-0" id="progress_doing"></p>
    </div>
<?php
include_once "footer.php";