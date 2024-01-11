function makeGPT(pretext) {
    let ausgewaehlterText = pretext + " " + window.getSelection().toString();
    if (ausgewaehlterText) {
        navigator.clipboard.writeText(ausgewaehlterText)
            .then(() => {
                console.log('Auswahl erfolgreich in die Zwischenablage kopiert');
            })
            .catch(err => {
                console.error('Fehler beim Kopieren in die Zwischenablage: ', err);
            });
    }
}

$(document).ready(function(){
    $(document).on("mouseup", function(){
        makeGPT("Schreibe mir eine detaillierte Zusammenfassung für");
    })
})