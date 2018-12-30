<?php
echo <<<EOT

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="de" xml:lang="de">
<head>
  <meta charset="utf-8">
  <meta http-equiv="x-ua-compatible" content="ie=edge">
  <title>Weihnachtswunschliste</title>
  <meta name="description" content="Weihnachtswunschliste kann bis zu drei Wünsche und eine Adresse entgegennehmen.">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<meta name="author" content="Quirin Johannes Koch" />
  <link rel="stylesheet" href="css/normalize.css">
  <link rel="stylesheet" href="css/main.css">
</head>
<body>
<h1>Weihnachtswunschliste</h1>
<form action="index.php" method="post">
<p>
EOT;
// PHP Code beginnt hier

if (!empty($_POST)) {
  // Variablen, um Fehler zu prüfen
  $fehlerWegenSonderzeichen = false;
  $fehlerImZweitenFormular = false;

  // 2. Formular-Stufe, die prüft ob die Wünsche erfolgreich eingeben wurden, fragt nach oder erlaubt die Eingabe der Adresse.
  if (empty($_POST["name"])) {
    // handle post data
    schreibeErstesUndZweitesFormular();
    schreibeInputController();

  } else {

    // 3. Formular-Stufe, auf der die Wünsche erfolgreich und eine Adresse angegeben wurden. Adresse wird kontrolliert
    if (!passForErrorsInPLZ()) {
      schreibeZweitesFormularUndKorrektur();
      schreibeInputController();

    } else {
      // 4. Formular-Stufe, auf der die Wünsche und die Adresse erfolgreich angegeben wurde.n
      schreibeEndErgebnisse();

    }
  }
} else {
  // 1. Formular-Stufe, auf der die Wünsche noch nicht eingeben wurden.
  schreibeTextfeld("1. Wunsch", "ersterWunsch");
  schreibeTextfeld("2. Wunsch", "zweiterWunsch");
  schreibeTextfeld("3. Wunsch", "dritterWunsch");
  schreibeInputController();
}
echo <<<EOT


  <script src="js/vendor/modernizr-3.6.0.min.js"></script>
  <script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
  <script>window.jQuery || document.write('<script src="js/vendor/jquery-3.3.1.min.js"><\/script>')</script>
  <script src="js/plugins.js"></script>
  <script src="js/main.js"></script>

</body>

</html>

EOT;
/**
 * Erstellt Textfelder
 *
 * Erstellt Textfelder für ein Formular.
 *
 * @param string Text  der Text der dem Benutzer angezeigt werden soll
 * @param string Name  der Name des Textfeldes
 */


function schreibeErstesUndZweitesFormular()
{
  schreibeAusgefuelltesTextfeld("1. Wunsch", "ersterWunsch");
  schreibeAusgefuelltesTextfeld("2. Wunsch", "zweiterWunsch");
  schreibeAusgefuelltesTextfeld("3. Wunsch", "dritterWunsch");
  global $fehlerWegenSonderzeichen;
  if (!$fehlerWegenSonderzeichen) {
    schreibeTextfeld("Vor- und Nachname", "name");
    schreibeTextfeld("PLZ und Ort", "plzUndOrt");
    schreibeTextfeld("Telefon", "telefon");
  }
}

function schreibeZweitesFormularUndKorrektur()
{
  schreibeAusgefuelltesTextfeld("1. Wunsch", "ersterWunsch");
  schreibeAusgefuelltesTextfeld("2. Wunsch", "zweiterWunsch");
  schreibeAusgefuelltesTextfeld("3. Wunsch", "dritterWunsch");
  global $fehlerWegenSonderzeichen;
  if (!$fehlerWegenSonderzeichen) {
    schreibeAusgefuelltesTextfeld("Vor- und Nachname", "name");
    schreibePLZFehlerTextfeld("PLZ und Ort", "plzUndOrt");
    schreibeAusgefuelltesTextfeld("Telefon", "telefon");
  }
}

;

function schreibeEndErgebnisse()
{
  schreibeFinalesTextfeld("1. Wunsch", "ersterWunsch");
  schreibeFinalesTextfeld("2. Wunsch", "zweiterWunsch");
  schreibeFinalesTextfeld("3. Wunsch", "dritterWunsch");
  schreibeFinalesTextfeld("Vor- und Nachname", "name");
  schreibeFinalesTextfeld("PLZ und Ort", "plzUndOrt");
  schreibeFinalesTextfeld("Telefon", "telefon");
}

;


function schreibeTextfeld($Text, $Name)
{
  echo "$Text: <input type=\"text\" name=\"$Name\" /><br />\r\n";
}

function schreibeInputController()
{
  echo <<<EOT
<br />
<input type="reset" value="Abbrechen" />
<input type="submit" value="Formular abschicken" />
</p>
</form>
EOT;
}




function schreibePLZFehlerTextfeld($Text, $Name)
{
  $Value = $_POST[$Name];
  if (passForSonderzeichen($Name)) {

    global $fehlerWegenSonderzeichen;
    $fehlerWegenSonderzeichen = true;
    echo "<div class=\"red\">" . $Text . ": " . "<input type=\"text\" name=\"$Name\" value=\"" . htmlspecialchars($Value) . "\" /><br />\r\n<p>Das ist keine korrekte PLZ!</p></div><br />\r\n";
  } else {
    echo "<div class=\"red\">" . $Text . ": " . "<input  type=\"text\" name=\"$Name\" value=\"" . htmlspecialchars($Value) . "\" /><br />\r\n<p>Das ist keine korrekte PLZ!</p></div><br />\r\n";
  }
}



function schreibeAusgefuelltesTextfeld($Text, $Name)
{
  $Value = $_POST[$Name];
  if (passForSonderzeichen($Name)) {
    global $fehlerWegenSonderzeichen;
    $fehlerWegenSonderzeichen = true;
    echo "<div class=\"red\">" . $Text . ": " . "<input type=\"text\" name=\"$Name\" value=\"" . htmlspecialchars($Value) . "\" /><br />\r\n<p>Sonderzeichen sind nicht erlaubt!</p></div><br />\r\n";
  } else {
    echo $Text . ": " . $_POST[$Name] . "<input type=\"hidden\" type=\"text\" name=\"$Name\" value=\"" . htmlspecialchars($Value) . "\" /><br />\r\n";
  }

}

// ausgabe der aller korrekten Angaben
function schreibeFinalesTextfeld($Text, $Name)
{

  echo $Text . ": " . $_POST[$Name] . "<br />\r\n";


}

// Prüffunktionen

function passForSonderzeichen($Name)
{
  $inhalt = $_POST[$Name];
  if (preg_match("/[^a-zA-Z0-9]/", $inhalt)) {
    return true;
  } else {
    return false;
  }
}

function passForErrorsInPLZ()
{
  $index = "plzUndOrt";
  $inhalt = $_POST[$index];
  $plzZumPruefen = substr($inhalt, 0, 5);
  $leerZeichenZumPruefen = substr($inhalt, 5, 1);
  $stadtZumPruefen = substr($inhalt, 6);
  if (preg_match("/[0-9]/", $plzZumPruefen)) {
    if (($leerZeichenZumPruefen === " ")) {
      if (preg_match("/[a-zA-Z]/", $stadtZumPruefen)) {
        return true;
      }
    }
  } else {
    return false;
  }
  return false;
}


?>
