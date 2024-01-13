<?php
include_once "header.php";

if (isset($_POST['action']) && isset($_POST['oldindex']) && trim($_POST['oldindex']) != "") {
    if ($_POST['action'] === "add" && isset($_POST['newindex']) && trim($_POST['newindex']) != "") {
        $stmt = $db->query("SELECT value FROM wordindex WHERE name=:name");
        $stmt->bindValue(':name', $_POST['newindex'], SQLITE3_TEXT);
        $exists = ($stmt->execute())->fetchArray()[0];
        if (!isset($exists[0])) {
            $stmt  = $db->prepare ("INSERT INTO wordindex (name,value) values (:name,:value);");
            $stmt->bindValue(':name', $_POST['newindex'], SQLITE3_TEXT);
            $stmt->bindValue(':value', $_POST['oldindex'], SQLITE3_TEXT);
            $stmt->execute();

        }
    }
    if ($_POST['action'] === "del") {
        $stmt  = $db->prepare ("DELETE FROM wordindex WHERE name=:name;");
        $stmt->bindValue(':name', $_POST['oldindex'], SQLITE3_TEXT);
        $stmt->execute();
    }
    unset($_GET);
    unset($_POST);
}
?>




<?php if (isset($_GET['action'])) { ?>
    <?php if ($_GET['action'] === "add") { ?>


        <div class="card text-white bg-success mb-3" style="max-width: 18rem;">
            <div class="card-header"><?=$_GET['key']?></div>
            <div class="card-body">
                <form method="post">
                   <div class="form-group">
                        <label for="ib">Alternative Bezeichnung</label>
                       <input type="hidden" name="oldindex" value="<?=$_GET['key']?>">
                       <input type="hidden" name="action" value="add">
                       <input type="text" class="form-control" id="ib" name="newindex">
                    </div>
                    <button type="submit" class="btn btn-primary">Anlegen</button>
                </form>
            </div>
        </div>

    <?php } else if ($_GET['action'] === "del") { ?>

        <div class="card text-white bg-danger mb-3" style="max-width: 18rem;">
            <div class="card-header"><?=$_GET['key']?></div>
            <div class="card-body">
                <h5 class="card-title">Wirklich löschen?</h5>
                <p class="card-text">Wollen Sie die alternative Bezeichnung <b>"<?=$_GET['key']?>"</b> wirklich löschen?</p>
                <form method="post">
                    <div class="form-group">
                        <input type="hidden" name="oldindex" value="<?=$_GET['key']?>">
                        <input type="hidden" name="action" value="del">
                    </div>
                    <button type="submit" class="btn btn-primary">Ja, wirklich Löschen!</button>
                </form>
            </div>
        </div>
    <?php } ?>


<?php } else { ?>

    <div class="card bg-light mb-3" style="max-width: 18rem;">
        <div class="card-header">Erledigt</div>
        <div class="card-body">
            <h5 class="card-title">Die Änderung wurde durchgeführt.</h5>
            <p class="card-text">Sie sehen die Änderung wenn Sie die Navigation neu laden.</p>
        </div>
    </div>

<?php } ?>

<?php
include_once "footer.php";

?>
