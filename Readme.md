# Verkehrswende Wordpress Locations 🚲🗺📌
Dieses Wordpress-Plugin soll die Verkehrswende und vor allem den Radaktivismus in Deutschland unterstützen und Radentscheide sowie Verkehrswenden voran bringen. Ziel ist es, interessierten Bürger:innen ein Bild der Rad- oder Gehweg-Infrastruktur vor Ort zu vermitteln. Über ein Melden-Formular können Aktivist:innen ganz einfach problematische Stellen mit Koordinaten und Bild melden. Administrator:innen können diese dann auf einer interaktiven Karte für alle Menschen sichtbar freischalten.

Du kannst das Plugin hier in Aktion sehen:
* https://www.verkehrswende-wuerzburg.de/problemstellen/
* https://zweirat-stuttgart.de/projekte/schwachstellen-karte/
* https://www.fahrradfreundliches-falkensee.de/schwachstellen-karte/
* https://www.radentscheid-schwerin.de/?page_id=90
* https://radentscheid-essen.de/ideenmelder/

![Preview](https://raw.githubusercontent.com/steampixel/RadentscheidWordpressLocations/master/media/preview.png)

## Features
* Jede:r kann Problemstellen melden
* Problemstellen werden auf einer interaktiven Karte dargestellt
* Alle eingereichten Orte müssen vorher im Backend freigeschaltet werden, bevor diese auf der Karte sichtbar sind
* Verschiedene Marker-Arten können frei angelegt werden (Problemstellen für Radverkehr, Problemstellen für Fußverkehr, behobene Problemstellen, etc...)
* Die Bilder liegen nicht im Medienmanager von Wordpress um diesen nicht zu "verstopfen"
* Bilder können im Backend einfach gedreht werden, da meldende Menschen manchmal nicht auf die Orientierung achten können
* Im Backend können mehrere Bilder zu einer Stelle gepflegt werden
* Die Bilder können im Backend leicht getauscht werden, um beispielsweise Nummernschilder oder Gesichter schnell verdecken zu können
* Alle Locations verfügen über eigene Detailseiten und werden so durch Suchmaschinen indexiert
* Marker werden auf der Karte geklustert dargestellt
* Öffentliche JSON-API, um der Öffentlichkeit Zugriff auf die Location-Daten zu gewähren
* Einfache Löschfunktion für einzelne Aktivist:innen-Daten
* RSA-Ende-zu-Ende-Verschlüsselung der kritischen Aktivist:innen-Daten (Name, Telefon, Email)
* Druckversion für die einzelnen Locations, um Behörden, Planer:innen und Beamt:innen das Leben leichter zu machen ;-) (Ja ich weiß, wir haben 2020^^)
* Automatisch E-Mail Benachrichtigungen, wenn neue Meldungen eintreffen
* Auf der Karte können die verschiedenen Marker-Typen gefiltert werden
* Importieren von Daten anderer Instanzen dieser Software
* Pflegen und darstellen zusätlicher GeoJSON Objekte auf der Karte (Z.B. zum markieren bestimmter Straßen oder Plätze)
* Öffentliches Kommentieren und Diskutieren von Locations über die Wordpress-Kommentarfunktion

## Installation
Das Plugin befindet sich (noch) nicht in der offiziellen Plugin-Datenbank von Wordpress. Bis dahin muss es manuell installiert werden. Es gibt zwei verschiedene Möglichkeiten für eine Installation.

### Installation über ein ZIP-Archiv
* Lade [hier](https://github.com/steampixel/RadentscheidWordpressLocations/tree/master/dist) das Plugin-Archiv aus dem "dist"-Verzeichnis in deiner wunsch-Version herunter und lade es über das Wordpress-Backend hoch. Achtung! Nutze nicht das Zip-Archiv, welches hier über den Button "Clone or download" bereitgestellt wird.
* Aktiviere das Plugin im Backend deiner Wordpress-Installation
* Binde die Shortcodes für Formulare und Karten auf deinen Seiten ein
* Regeneriere die Permalinks, indem du unter Einstellungen -> Permalinks auf den Speichern-Button klickst. Erfolgt dies nicht können Detailseiten einzelner Locations evtl. nicht aufgerufen werden und es erscheint ein 404-Fehler.

### Installation über FTP / SSH
* Lade [hier](https://github.com/steampixel/RadentscheidWordpressLocations/tree/master/dist) dieses Plugin herunter und kopiere es nach ```wp-content/plugins/sp-locations```
* Aktiviere das Plugin im Backend deiner Wordpress-Installation
* Binde die Shortcodes für Formulare und Karten auf deinen Seiten ein
* Regeneriere die Permalinks, indem du unter Einstellungen -> Permalinks auf den Speichern-Button klickst. Erfolgt dies nicht können Detailseiten einzelner Locations evtl. nicht aufgerufen werden und es erscheint ein 404-Fehler.

## Shortcodes

### Melden-Formular
Mit diesem Shortcode bindest du das Melden-Formular ein:
```
[steampixel-marker-form selected-type="problem_side_walk" available-types="problem_side_walk,problem_bike" require-address="false" require-image="true" name-label="Name der Problemstelle" name-placeholder="Bsp: Radweg in schlechtem Zustand" file-placeholder="Lade ein Bild der Problemstelle hoch. Bitte nimm uns etwas Arbeit ab und schwärze ggf. Kennzeichen und Gesichter." submit-value="Problemstelle melden" require-personal-data="false" lat="49.78" lng="9.94"]
```

Parameter:
* selected-type: Der Schlüssel des Markers, der gemeldet werden soll. Also zum Beispiel "problem_side_walk", "problem_bike" oder andere und eigene Marker-Schlüssel.
* available-types: Mit Komma getrennt die Arten der Marker, die gemeldet werden können. Also zum Beispiel "problem_side_walk,problem_bike" oder andere und eigene Marker-Schlüssel.
* require-address: "true" oder "false" Ist die Angabe einer Adresse erforderlich?
* show-description: "true" oder "false" Ist das Beschreibungsfeld sichtbar?
* require-image: "true" oder "false" Ist das Bild ein Pflichtfeld?
* name-label: Das Label des Namensfeldes
* name-placeholder: Der Placeholder des Namensfeldes
* file-label: Das Label für das Bild
* file-placeholder: Der Placeholder für das Bild
* submit-value: Der Wert des Absenden-Buttons
* require-personal-data: "true" oder "false" Ist die Angabe von persönlichen Informationen Pflicht?
* lat: Start-Latitude des Kartenfeldes zum Beispiel "49.78"
* lng: Start-Longitude des Kartenfeldes zum Beispiel "9.94"
* zoom: Start-Zoom des Kartenfeldes zum Beispiel "13"

### Interaktive Karte einbinden
Mit diesem Shortcode erstellst du eine interaktive Karte:
```
[steampixel-marker-map height="500px" type="problem_side_walk,problem_bike,solved_side_walk,solved_bike" button-label="Problemstelle melden" button-link="https://www.verkehrswende-wuerzburg.de/problemstellen-melden/" lat="49.78" lng="9.94"]
<h2>Problemstellen in Würzburg</h2>
Auf dieser interaktiven Karte kannst du dich über die Situation der Geh- und Radweginfrastruktur in Würzburg informieren. Wir haben zahlreiche Problemstellen dokumentiert. Wenn du selbst problematische Orte melden möchtest, kannst du das <a href="https://www.verkehrswende-wuerzburg.de/problemstellen-melden/">auf dieser Seite</a> tun.
[/steampixel-marker-map]
```
Parameter:
* height: Höhe der Karte in px, %, vh, etc. zum Beispiel "400px"
* lat: Start-Latitude zum Beispiel "49.78"
* lng: Start-Longitude zum Beispiel "9.94"
* zoom: Start-Zoom zum Beispiel "13"
* type: Die Schlüssel der Marker, die auf der Karte dargestellt werden sollen. Beispiel: "problem_side_walk", "problem_bike" oder multiple Marker mit Komma getrennt: "problem_bike, problem_side_walk"
* geojson: Die Schlüssel der zusätzlichen GeoJSON-Daten, die auf der Karte verfügbar sein sollen mit Komma voneinander getrennt
* button-label: Die Aufschrift des Buttons, der über der Karte schwebt
* button-link: Die Link-URL des Buttons, der über der Karte schwebt

Der Inhalt dieses Shortcodes wird, wenn vorhanden in einem Begrüßungspopup angezeigt.

### Anzahl der aktiven Meldungen ausgeben
Mit diesem Shortcode kannst du die Anzahl der aktiven Meldungen ausgeben. So kannst du die Zahl direkt in einem Text verwenden.
```
[steampixel-marker-count type="problem_side_walk"]
```
Parameter:
* type: Die Schlüssel der Marker, die gezählt werden sollen. Beispiel: "problem_side_walk", "solved_bike" oder multiple Marker mit Komma getrennt: "solved_side_walk, solved_bike"

## Neue Marker-Typen anlegen oder ändern
Du kannst neue Marker-Typen anlegen. Sie dir dazu einen bereits vorhandenen Marker an. Jeder Marker benötigt die dort hinterlegten Custom-Felder, um korrekt zu funktionieren. Folgende Felder werden benötigt:
* key: Der interne Schlüssel des Markers. Zum Beispiel "problem_bike". Bitte zusammen schreiben sowie auf Sonderzeichen verzichten.
* order: Die Reihenfolge als ganze Zahl, die dieser Marker im Filter einnimmt.
* icon: Der relative Pfad zu einem Icon, welches auf der Karte angezeigt wird.
* filter_icon: Der relative Pfad zu einem Icon, welches im Filter angezeigt wird.

## Eigene GeoJSONs in die Karte einbetten
Du kannst unter dem Punkt GeoJSONs neue Einträge anlegen und damit Strukturen auf den Karten anzeigen. Mit diesem Plugin kannst du nicht selbst GeoJSONs erstellen. Dazu kannst du zum Beispiel Services wie https://umap.openstreetmap.fr/de/ verwenden und deine Strukturen dort als GeoJSON exportieren und in das Plugin einfügen. Damit die Strukturen richtig angezeigt werden, musst du folgende Custom-Felder in den Einträgen der GeoJSONs anlegen:
* key: Den Schlüssel der Struktur zur Verwendung im Karten-Shortcode zum Beispiel "my_new_structure" (Bitte zusammenschreiben und auf Sonderzeichen verzichten)
* color: Die Farbe der Struktur als Hexadezimalwert zum Beispiel "#ffcccc"
* geojson: Hier wird das komplette GeoJSON als Text eingefügt
* opacity: Transparenz der Struktur zum Beispiel "0.5"
* weight: Die Stärke der Struktur, zum Beispiel "10"
* order: Die Reihenfolge als ganze Zahl, die dieser Marker im Filter einnimmt.
* description: Ein kleiner Text, der beim Klick auf die Struktur auf der Karte angezeigt wird.
* url: Link zu einer Seite mit mehr Informationen zu der Struktur.

Die veröffentlichten GeoJSONs werden dann auf der Karte und in den Kartenfiltern angezeigt und sind auch in der API verfügbar.

## E-Mail Benachrichtigungen aktivieren
Das Plugin kann automatisch bei neuen Meldungen Emails an ein oder mehrere Empfänger:innen senden. Diese Funktion kannst du folgendermaßen aktivieren:
* Öffne das Backend und klicke auf "Settings" -> "Location Options"
* Dort kannst du eine oder mehrere Mailadressen hinterlegen. Schreibe jede Adresse in eine eigene Zeile.

Bitte beachte, dass das erfolgreiche Versenden von Mails von vielen Faktoren abhängig ist. Das Plugin nutzt die interne Mail-Funktion von Wordpress. Daher kannst du zum Beispiel auch SMTP-Plugins nutzen, um die Mails über einen SMTP-Server zu versenden.

## Kommentare aktivieren
Für die Locations ist standardmäßig die Kommentarfunktion aktiviert. Um diese zu deaktivieren kannst du die Kommentare in Wordpress entweder global deaktivieren oder innerhalb einer einzelnen Location über die entsprechende Funktion abschalten.

## Öffentlich API

### Location API
Alle Locations, die im Backend freigeschaltet werden, sind über eine einfache öffentliche API im JSON-Format einsehbar. Das Plugin sorgt somit dafür, dass öffentliche Daten auch öffentlich zugänglich bleiben und durch andere Menschen frei nutzbar sind. Du erreichst die Daten unter https://www.deine-domain.de/api/locations.
Hier kannst du ein Beipsiel sehen: https://www.verkehrswende-wuerzburg.de/api/locations

Die Daten liegen im JSON-Format vor:

```
{
	"2343": {
		"id": 2343,
		"title": "Hier fehlen Abstellm\u00f6glichkeiten in der N\u00e4he",
		"date": "2020-08-11 12:42:14",
		"url": "https:\/\/www.verkehrswende-wuerzburg.de\/location\/hier-fehlen-abstellmoeglichkeiten-in-der-naehe\/",
		"type": "problem_bike",
		"lng": "9.93469297885895",
		"lat": "49.782945610074556",
		"place": "W\u00fcrzburg",
		"postcode": "97072",
		"description": "Hier w\u00e4ren Abstellm\u00f6glichkeiten f\u00fcr Fahrr\u00e4der in der N\u00e4he angebracht.",
		"street": "Weingartenstra\u00dfe",
		"suburb": "Sanderau",
		"images": [
      {
  			"src": "https:\/\/www.verkehrswende-wuerzburg.de\/wp-content\/uploads\/sp-locations\/2343\/5f327606a6910.jpg",
  			"description": "",
  			"name": "5f327606a6910.jpg",
  			"thumbnails": {
  				"300": "https:\/\/www.verkehrswende-wuerzburg.de\/wp-content\/uploads\/sp-locations\/2343\/300\/5f327606a6910.jpg",
  				"600": "https:\/\/www.verkehrswende-wuerzburg.de\/wp-content\/uploads\/sp-locations\/2343\/600\/5f327606a6910.jpg"
  			}
  		},
      ...
    ],
		"marker": {
			"key": "problem_bike",
			"title": "Radweg-Problemstelle",
			"icon": "\/wp-content\/plugins\/sp-locations\/assets\/img\/marker_bike_orange.svg"
		}
	},
  ...
}
```

Es handelt sich bei den Daten um ein JSON-Objekt. Der Key einer jeden Location ist deren eindeutige ID. Alle anderen Daten sollen (hoffentlich) selbsterklärend sein.

### GeoJSON API
Auch die veröffentlichten GeoJSONs können über eine Schnittstelle abgerufen werden: https://www.deine-domain.de/api/geojsons.
Die Daten sind im JSON-Format verfügbar:

```
{
  "1862": {
    "id":	1862,
    "title":	"Erweiterung Fußgängerzone",
    "date":	"2020-07-26 20:18:34",
    "key":	"erweiterung_fussgaengerzone",
    "color":	"#00ff00",
    "opacity":	"0.5",
    "weight":	"10",
    "description":	"Vorgeschlagene Erweiterung der Fußgängerzone",
    "geojson"	{…}
  },
  ...
}
```

Es handelt sich bei den Daten um ein JSON-Objekt. Der Key einer jeden Location ist deren eindeutige ID. Alle anderen Daten sollen (hoffentlich) selbsterklärend sein.

## Aktivist:innen Daten verschlüsseln
Dieses Plugin bietet die Möglichkeit, die Kontaktdaten der Aktivist:innen (Name, Telefonnummer, Email) verschlüsselt zu speichern. Dadurch sind diese Daten im Falle eines Angriffs oder Einbruchs sicher. Die Daten werden Ende-Zu-Ende verschlüsselt. Das bedeutet, dass diese Daten noch im Browser der Aktivisti verschlüsselt werden und erst im Browser eines Admins wieder entschlüsselt werden können. Auf dem Server selbst gibt es keine Zugriffsmöglichkeit.

Dieses Feature muss vorher jedoch explizit von dir aktiviert werden. Achtung! Solltest du den privaten Schlüssel verlieren, können die verschlüsselten Daten nicht wiederhergestellt werden! Sie sind dann für immer verloren! Bewahre deine Schlüssel daher an einem sicheren Ort auf und erstelle dir unbedingt ein Backup der Schlüssel. Zum Beispiel auf einem geheimen USB-Stick.

Die Verschlüsselung funktioniert mittels OpenSSL und RSA. Dazu musst du ein Schlüsselpaar bestehend aus einem privaten und öffentlichen Schlüssel generieren. Wie das genau funktioniert, ist weiter unten beschrieben. Der öffentliche Schlüssel wird im Backend von Wordpress hinterlegt. Dieser dient zum verschlüsseln der Daten und kann nicht zum entschlüsseln genutzt werden. Die Kontaktinformationen werden dann noch im Browser der Aktivist:innen verschlüsselt und so sicher zum Server übertragen und dort gespeichert. Im Backend hast du dann die Möglichkeit einzelne Daten mit Hilfe deines privaten Schlüssels wieder zu entschlüsseln und so eine Kontaktaufnahme zu starten. Der private Schlüssel ist die einzige Möglichkeit die Daten zu entschlüsseln. Bewahre ihn gut auf und gib ihn NIE an andere Personen weiter.

Die Generierung von Schlüsselpaaren und der Umgang damit kann etwas verwirrend sein, wenn du noch nie damit gearbeitet hast. Bitte darum andere Personen, die sich damit auskennen, dir im Zweifel zu helfen.

### Erstelle ein neues Schlüsselpaar
Öffne einen Terminal (Linux) und nutze folgendes Kommando, um dir einen privaten Schlüssel zu erstellen:
```
openssl genrsa -out rsa_1024_priv.pem 1024
```
Nutze dann das folgende Kommando, um aus dem privaten Schlüssel einen öffentlichen Schlüssel zu erstellen:
```
openssl rsa -pubout -in rsa_1024_priv.pem -out rsa_1024_pub.pem
```

### Aktiviere die Verschlüsselung in Wordpress
* Öffne das Backend und klicke auf "Settings" -> "Location Options"
* Kopiere den Inhalt aus der Datei "rsa_1024_pub.pem" in das Textfeld "RSA public key"
* Aktiviere die Verschlüsselung, indem du den Haken bei "Enable RSA encryption" setzt
* Speichere die Einstellungen

Die Daten werden von nun an verschlüsselt gespeichert. Bitte sei dir darüber im klaren, dass momentan keine Daten nachträglich verschlüsselt werden können. Die alten Daten sind weiterhin ganz normal im Backend an den jeweiligen Locations im Klartext lesbar.

### Daten entschlüsseln
Bei Locations, deren Aktivist:innen Daten verschlüsselt hinterlegt wurden, erscheint nun eine Textbox. Damit du die dort hinterlegten Daten lesen kannst, musst du den Inhalt aus deiner privaten (geheimen) Schlüsseldatei (rsa_1024_priv.pem) in das Feld kopieren. Klicke dann auf "decrypt", um die Daten anzuzeigen.

## Grenzen des Plugins
Momentan werden immer alle relevanten Punkte für eine Karte direkt geladen. Das macht natürlich nur dann Sinn, solange die Meldungen in einem gewissen Rahmen bleiben. Diese Karte unterstützt zur Zeit nicht das dynamische Laden von Koordinaten, je nach gezeigtem Kartenausschnitt. Für wirklich große Städte mit tausenden von Koordinaten ist das Plugin in der jetzigen Form daher noch ungeeignet und müsste etwas umgebaut werden.

## Update
Nutze zum Update entweder ZIP-Archive, wie bei der Installation oder überschreibe einfach alle Plugin-Dateien via FTP oder SSH. Erstelle vor jedem Update unbedingt ein Backup deiner Website!

### Update auf Version 1.5.0
Bei diesem Update werden automatisch Daten mirgriert. Alle Thumbnails werden beim ersten Aufrufen der Seite neu generiert. Dies kann dazu führen, dass die Seite sehr lange lädt. In Abhängigkeit von der Anzahl der Thumbnails und Locations kann es auch zu einer Fehlermeldung kommen, da die maximale Script-Laufzeit erreicht wurde. Lade in diesem Fall die Seite neu, damit die Migration fortgesetzt werden kann. Wenn es sehr viele Daten zum migrieren gibt, musst du die Seite evtl. öffter neu laden und kannst unter Umständen öfter einen Timeout-Fehler sehen. Sobald die Migration abgeschlossen ist, wird die Website wieder normal funktionieren. Die Migration löscht die alten Daten vorsichthalber nicht. Du kannst diese daher wenn du möchtest selbst löschen. Lösche dazu alle Bilder, die sich direkt in wp-content/uploads/sp-locations befinden. Lösche auch den Ordner thumbs in diesem Verzeichnis. Die Anderen numerischen Verzeichnisse sind die neue migrierten Daten. Diese sollen bitte bleiben.

## Credits
Map marker icons: https://github.com/mrmichalzik, https://github.com/derdennis

## License
This project is licensed under the AGPLv3 License. See LICENSE for further information.
