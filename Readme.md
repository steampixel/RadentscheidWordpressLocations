# Verkehrswende Wordpress Locations 🚲🗺📌
Dieses Wordpress-Plugin soll die Verkehrswende und vor allem den Radaktivismus in Deutschland unterstützen und Radentscheide sowie Verkehrswenden voran bringen. Ziel ist es, interessierten Bürger*innen ein Bild der Rad- oder Gehweg-Infrastruktur vor Ort zu vermitteln. Über ein Melden-Formular können Aktivist&#42;innen ganz einfach problematische Stellen mit Koordinaten und Bild melden. Administrator&#42;innen können diese dann auf einer interaktiven Karte für alle Menschen sichtbar freischalten.

Benutzt dieses Plugin einfach als Grundlage, verändert es und passt es euren Bedürfnissen an.

In Aktion kannst du das Plugin hier sehen:
* https://www.radentscheid-wuerzburg.de/problemstellen/
* https://zweirat-stuttgart.de/projekte/schwachstellen-karte/
* https://www.fahrradfreundliches-falkensee.de/schwachstellen-karte/

## Features
* Jede*r kann Problemstellen melden
* Problemstellen werden auf einer Karte dargestellt
* Marker können frei angelegt werden (Problemstellen für Radverkehr, Problemstellen für Fußverkehr, Unterschriftenstellen, behobene Problemstellen, etc...)
* Alle eingereichten Orte müssen vorher im Backend freigeschaltet werden, bevor diese auf der Karte sichtbar sind
* Die Bilder liegen nicht im Medienmanager von Wordpress um diesen nicht zu "verstopfen"
* Bilder können im Backend einfach gedreht werden, da meldende Menschen oft nicht auf die Orientierung achten
* Im Backend können mehrere Bilder zu einer Stelle gepflegt werden
* Die Bilder können im Backend leicht getauscht werden, um beispielsweise Nummernschilder oder Gesichter schnell verdecken zu können
* Im Backend gibt es einen neuen Menupunkt "Locations" unter dem sich alles verwalten lässt
* Der Status der Karte wird im Hash der URL gespeichert. Somit können Kartenpositionen als Link verschickt werden
* Alle Locations verfügen nun über eigene Detailseiten und werden so durch Suchmaschinen indexiert
* Einfache Löschfunktion für einzelne Aktivistendaten
* Marker werden auf der Karte geklustert dargestellt
* Öffentliche JSON-Api, um der Öffentlichkeit Zugriff auf die Location-Daten zu gewähren
* RSA-Verschlüsselung der kritischen Aktivist&#42;innen Daten (Name, Telefon, Email)
* Druckversion für die einzelnen Locations, um Behörden und Beamt*innen das Leben leichter zu machen ;-)
* Automatisch E-Mail Benachrichtigung, wenn neue Meldungen eintreffen

## Installation
Das Plugin befindet sich (noch) nicht in der offiziellen Plugin-Datenbank von Wordpress. Bis dahin muss es manuell installiert werden. Es gibt zwei verschiedene Möglichkeiten für eine Installation.

### Installation über ein ZIP-Archiv
* Lade [hier](https://github.com/steampixel/RadentscheidWordpressLocations/tree/master/dist) das Plugin-Archiv in deiner wunsch-Version herunter und lade es über das Wordpress-Backend hoch. Achtung! Nutze nicht das Zip-Archiv, welches hier über den Button "Clone or download" bereitgestellt wird.
* Aktiviere das Plugin im Backend deiner Wordpress-Installation
* Binde die Shortcodes für Formulare und Karten auf deinen Seiten ein

### Installation über FTP / SSH
* Lade dieses Plugin herunter und kopiere es nach ```wp-content/plugins/sp-locations```
* Aktiviere das Plugin im Backend deiner Wordpress-Installation
* Binde die Shortcodes für Formulare und Karten auf deinen Seiten ein

## Update
Nutze zum Update entweder ZIP-Archive, wie bei der Installation oder überschreibe einfach alle Plugin-Dateien via FTP oder SSH. Erstelle vor jedem Update unbedingt ein Backup deiner Website!

### Update auf Version 1.5.0
Bei diesem Update werden automatisch Daten mirgriert. Alle Thumbnails werden beim ersten Aufrufen der Seite neu generiert. Dies kann dazu führen, dass die Seite sehr lange lädt. In Abhängigkeit von der Anzahl der Thumbnails und Locations kann es auch zu einer Fehlermeldung kommen, da die maximale Script-Laufzeit erreicht wurde. Lade in diesem Fall die Seite neu, damit die Migration fortgesetzt werden kann. Wenn es sehr viele Daten zum migrieren gibt, musst du die Seite evtl. öffter neu laden und kannst unter Umständen öfter einen Timeout-Fehler sehen. Sobald die Migration abgeschlossen ist, wird die Website wieder normal funktionieren. Die Migration löscht die alten Daten vorsichthalber nicht. Du kannst diese daher wenn du möchtest selbst löschen. Lösche dazu alle Bilder, die sich direkt in wp-content/uploads/sp-locations befinden. Lösche auch den Ordner thumbs in diesem Verzeichnis. Die Anderen numerischen Verzeichnisse sind die neue migrierten Daten. Diese sollen bitte bleiben.

## Shortcodes

### Melden-Formular
Mit diesem Shortcode bindest du das Melden-Formular ein:
```
[steampixel-marker-form selected-type="problem" require-address="false" show-opening-hours="false" require-image="true" name-label="Name der Problemstelle" name-placeholder="Bsp: Radweg in schlechtem Zustand" file-placeholder="Lade ein Bild der Problemstelle hoch. Bitte nimm uns etwas Arbeit ab und schwärze ggf. Kennzeichen und Gesichter." submit-value="Problemstelle melden" require-personal-data="false"]
```

Parameter:
* selected-type: Der Schlüssel des Markers, der gemeldet werden soll. Also zum Beispiel "problem", "sign", "solved" oder eigene Marker-Schlüssel.
* require-address: "true" oder "false" Ist die Angabe einer Adresse erforderlich?
* show-description: "true" oder "false" Ist das Beschreibungsfeld sichtbar?
* show-opening-hours: "true" oder "false" Öffnungszeiten-Feld anzeigen?
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

Bitte denke daran, dass derzeit immer nur ein Shortcode pro Seite eingebunden werden kann. Es ist noch nicht möglich den selben Shortcode auf einer Seite mehrfach zu benutzen.

### Interaktive Karte einbinden
Mit diesem Shortcode erstellst du eine interaktive Karte:
```
[steampixel-marker-map height="100vh" type="problem" button-label="Problemstelle melden" button-link="https://verkehrswende-wuerzburg.de/radentscheid/mitmachen/problemstellen-melden/"]
<h2>Problemstellen in Würzburg</h2>
Auf dieser interaktiven Karte kannst du dich über die Situation der Radinfrastruktur in Würzburg informieren. Wir haben zahlreiche Problemstellen dokumentiert. Wenn du selbst problematische Orte melden möchtest, kannst du das <a href="https://verkehrswende-wuerzburg.de/radentscheid/mitmachen/problemstellen-melden/">auf dieser Seite</a> tun.
[/steampixel-marker-map]
```
Der Inhalt dieses Shortcodes wird, wenn vorhanden in einem Begrüßungspopup angezeigt.

Parameter:
* height: Höhe der Karte in px, %, vh, etc. zum Beispiel "400px"
* lat: Start-Latitude zum Beispiel "49.78"
* lng: Start-Longitude zum Beispiel "9.94"
* zoom: Start-Zoom zum Beispiel "13"
* type: Die Schlüssel der Marker, die auf der Karte dargestellt werden sollen. Beispiel: "problem", "sign" oder multiple Marker mit Komma getrennt: "sign, problem"
* button-label: Die Aufschrift des Buttons, der über der Karte schwebt
* button-link: Die Link-URL des Buttons, der über der Karte schwebt

Bitte denke daran, dass derzeit immer nur ein Shortcode pro Seite eingebunden werden kann. Es ist noch nicht möglich den selben Shortcode auf einer Seite mehrfach zu benutzen.

### Anzahl der aktiven Meldungen ausgeben
Mit diesem Shortcode kannst du die Anzahl der aktiven Meldungen ausgeben. So kannst du die Zahl direkt in einem Text verwenden.
```
[steampixel-marker-count type="problem"]
```
Parameter:
* type: Die Schlüssel der Marker, die gezählt werden sollen. Beispiel: "problem", "sign" oder multiple Marker mit Komma getrennt: "sign, problem"

## E-Mail Benachrichtigungen aktivieren
Das Plugin kann dir automatisch bei neuen Meldungen eine Email senden. Diese Funktion kannst du folgendermaßen aktivieren:
* Öffne das Backend und klicke auf "Settings" -> "Location Options"
* Dort kannst du eine Mailadresse hinterlegen

Bitte beachte, dass das erfolgreiche Versenden von Mails von vielen Faktoren abhängig ist. Das Plugin nutzt die interne Mail-Funktion von Wordpress. Daher kannst du zum Beispiel auch SMTP-Plugins nutzen, um die Mails über einen SMTP-Server zu versenden.

## Öffentlich Api
Alle Locations, die im Backend freigeschaltet werden, sind über eine einfache öffentliche API im JSON-Format einsehbar. Das Plugin sorgt somit dafür, dass öffentliche Daten auch öffentlich zugänglich bleiben und durch andere Menschen frei nutzbar sind. Du erreichst die Daten unter https://www.deine-domain.de/api/locations.
Hier kannst du ein Beipsiel sehen: https://www.radentscheid-wuerzburg.de/api/locations

## Aktivist&#42;innen Daten verschlüsseln
Dieses Plugin bietet die Möglichkeit, die Kontaktdaten der Aktivist&#42;innen (Name, Telefonnummer, Email) verschlüsselt zu speichern. Dadurch sind diese Daten im Falle eines Angriffs oder Einbruchs sicher. Dieses Feature muss vorher jedoch explizit von dir aktiviert werden. Achtung! Solltest du den privaten Schlüssel verlieren, können die verschlüsselten Daten nicht wiederhergestellt werden! Sie sind dann für immer verloren! Bewahre deine Schlüssel daher an einem sicheren Ort auf und erstelle dir unbedingt ein Backup der Schlüssel. Zum Beispiel auf einem geheimen USB-Stick.

Die Verschlüsselung funktioniert mittels OpenSSL und RSA. Dazu musst du ein Schlüsselpaar bestehend aus einem privaten und öffentlichen Schlüssel generieren. Wie das genau funktioniert ist weiter unten beschrieben. Der öffentliche Schlüssel wird im Backend von Wordpress hinterlegt. Dieser dient zum verschlüsseln der Daten und kann nicht zum entschlüsseln genutzt werden. Die Kontaktinformationen werden dann noch im Browser der Aktivist&#42;innen verschlüsselt und so sicher zum Server übertragen und dort gespeichert. Im Backend hast du dann die Möglichkeit einzelne Daten mit Hilfe deines privaten Schlüssels wieder zu entschlüsseln und so eine Kontaktaufnahme zu starten. Der private Schlüssel ist die einzige Möglichkeit die Daten zu entschlüsseln. Bewahre ihn gut auf und gib ihn NIE an andere Personen weiter.

Die Generierung von Schlüsselpaaren und der Umgang damit kann etwas verwirrend sein, wenn du noch nie damit gearbeitet hast. Bitte darum andere Personen, die sich damit auskennen, dir im Zweifel zu helfen.

### Erstelle ein neues Schlüsselpaar
Öffne einen Terminal und nutze folgendes Kommando, um dir einen privaten Schlüssel zu erstellen:
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
Bei Locations, deren Aktivist&#42;innen Daten verschlüsselt hinterlegt wurden, erscheint nun eine Textbox. Damit du die dort hinterlegten Daten lesen kannst, musst du den Inhalt aus deiner privaten (geheimen) Schlüsseldatei (rsa_1024_priv.pem) in das Feld kopieren. Klicke dann auf "decrypt", um die Daten anzuzeigen.

## Informationen für Entwickler&#42;innen
Dieses Plugin entstand in einer Nacht- und Nebelaktion und wurde zwischen Tür und Angel weiterentwickelt. Dementsprechend sehen auch einige Stellen im Code aus. Bitte schreckt nicht davor zurück Dinge zu korrigieren, Verbesserungen einzubauen und Pull-Requests zu senden. Es gibt bisher noch keine wirkliche Dokumentation. Daher hier erstmal ein paar grobe Zeilen:

Um die Entwicklung des Plugins einer breiten Masse zu öffnen wurden die Standards sehr weit runtergeschraubt. Es gibt keine Abhängigkeiten bis auf Leaflet. Alles andere ist selbst gebaut. Kein jQuery, kein ES6, kein Bootstrap, keine fancy Frameworks.

Je nach dem, wie es erforderlich ist, kann ich die Doku hier noch etwas "aufhübschen". Fragen bitte einfach in die Issues.

## Grenzen des Plugins
Momentan werden immer alle relevanten Punkte für eine Karte direkt geladen. Das macht natürlich nur dann Sinn, solange die Meldungen in einem gewissen Rahmen bleiben. Diese Karte unterstützt zur Zeit nicht das dynamische Laden von Koordinaten, je nach gezeigtem Kartenausschnitt. Für wirklich große Städte mit tausenden von Koordinaten ist das Plugin in der jetzigen Form daher noch ungeeignet und müsste etwas umgebaut werden.

## Credits
Map marker icons: https://github.com/mrmichalzik

## License
This project is licensed under the AGPLv3 License. See LICENSE for further information.
