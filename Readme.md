# Radentscheid Locations 🚲🗺📌
Dieses Wordpress-Plugin soll den Radaktivismus in Deutschland unterstützen und Radentscheide voran bringen. Ziel ist es, interessierten Bürgern ein Bild der Radinfrastruktur vor Ort zu vermitteln. Über ein Melden-Formular können Aktivist&#42;innen ganz einfach problematische Stellen mit Koordinaten und Bild melden. Administrator&#42;innen können diese dann auf einer interaktiven Karte für alle Menschen sichtbar freischalten. Zudem ist es möglich Unterschriftenstellen auf einer separaten Karte darzustellen.

Benutzt dieses PLugin einfach als Grundlage, verändert es und passt es euren Bedürfnissen an.

In Aktion kannst du das Plugin hier sehen: https://www.radentscheid-wuerzburg.de/problemstellen/

## Features
* Jeder kann Problemstellen melden
* Problemstellen werden auf einer Karte dargestellt
* Jeder kann Unterschriftenstellen registrieren
* Unterschriftenstellen auf einer Karte darstellen
* Alle eingereichten Orte müssen vorher im Backend freigeschaltet werden, bevor diese auf der Karte sichtbar sind
* Die Bilder liegen nicht im Medienmanager von Wordpress um diesen nicht zu "verstopfen".
* Bilder können im Backend einfach gedreht werden, da meldende Menschen oft nicht auf die Orientierung achten.
* Die Bilder können im Backend leicht getauscht werden.
* Im Backend gibt es einen neuen Menupunkt "Locations"

## Installation
* Lade dieses Plugin herunter und kopiere es nach ```wp-content/plugins/sp-locations```
* Aktiviere das Plugin im Backend deiner Wordpress-Installation
* Binde die Shortcodes für Formulare und Karten auf deinen Seiten ein

## Shortcodes

### Melden-Formular
Mit diesem Shortcode bindest du das Melden-Formular ein:
```
[steampixel-marker-form selected-type="problem" require-address="false" show-opening-hours="false" require-image="true" name-label="Name der Problemstelle" name-placeholder="Bsp: Radweg in schlechtem Zustand" file-placeholder="Lade ein Bild der Problemstelle hoch. Bitte nimm uns etwas Arbeit ab und schwärze ggf. Kennzeichen und Gesichter." submit-value="Problemstelle melden" require-personal-data="false"]
```

Parameter:
* selected-type: "problem" oder "sign" Definiert, ob eine Problemstelle oder eine Unterschriftenstelle gemeldet werden soll
* require-address: "true" oder "false" Ist die Angabe einer Adresse erforderlich?
* show-opening-hours: "true" oder "false" Öffnungszeiten-Feld anzeigen?
* require-image: "true" oder "false" Ist das Bild ein Pflichtfeld?
* name-label: Das Label des Namensfeldes
* name-placeholder: Der Placeholder des Namensfeldes
* file-placeholder: Der Placeholder für das Bild
* submit-value: Der Wert des Absenden-Buttons
* require-personal-data: "true" oder "false" Ist die Angabe von persönlichen Informationen Pflicht?

### Interaktive Karte einbinden
Mit diesem Shortcode erstellst du eine interaktive Karte:
```
[steampixel-marker-map height="100vh" type="problem" button-label="Problemstelle melden" button-link="https://verkehrswende-wuerzburg.de/radentscheid/mitmachen/problemstellen-melden/"]
<h2>Problemstellen in Würzburg</h2>
Auf dieser interaktiven Karte kannst du dich über die Situation der Radinfrastruktur in Würzburg informieren. Wir haben zahlreiche Problemstellen dokumentiert. Wenn du selbst problematische Orte melden möchtest, kannst du das <a href="https://verkehrswende-wuerzburg.de/radentscheid/mitmachen/problemstellen-melden/">auf dieser Seite</a> tun.
[/steampixel-marker-map]
```
Der Inhalt dieses SHortcodes wird, wenn vorhanden in einem Begrüßungspopup angezeigt.

Parameter:
* type: "problem" oder "sign"
* button-label: Die Aufschrift des Buttons, der über der Karte schwebt
* button-link: Die Link-URL des Buttons, der über der Karte schwebt

## Informationen für Entwickler&#42;innen
Dieses Plugin entstand in einer Nacht- und Nebelaktion und wurde zwischen Tür und Angel weiterentwickelt. Dementsprechend sehen auch einige Stellen im Code aus. Bitte schreckt nicht davor zurück Dinge zu korrigieren, Verbesserungen einzubauen und Pull-Requests zu senden. Es gibt bisher noch keine wirkliche Dokumentation. Daher hier erstmal ein paar grobe Zeilen:

Um die Entwicklung des Plugins einer breiten Masse zu öffnen wurden die Standards sehr weit runtergeschraubt. Es gibt keine Abhängigkeiten bis auf Leaflet. Alles andere ist selbst gebaut. Kein jQuery, kein ES6, kein Bootstrap, keine fancy Frameworks.

Je nach dem, wie es erforderlich ist, kann ich die Doku hier noch etwas "aufhübschen".

## Grenzen des Plugins
Momentan werden immer alle relevanten Punkte für eine Karte direkt geladen. Das macht natürlich nur dann Sinn, solange die Meldungen in einem gewissen Rahmen bleiben. Diese Karte unterstützt zur Zeit nicht das dynamische Laden von Koordinaten, je nach gezeigtem Kartenausschnitt. Für wirklich große Städte mit tausenden von Koordinaten ist das Plugin in der jetzigen Form daher noch ungeeignet und müsste etwas umgebaut werden.

## License
This project is licensed under the MIT License. See LICENSE for further information.
