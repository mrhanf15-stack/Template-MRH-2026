function updateTime() {
	var date = new Date();
	var stunden = date.getHours();
	var minuten = date.getMinutes();
	var tag = date.getDate();
	var monatDesJahres = date.getMonth();
	var jahr = date.getFullYear();
	var tagInWoche = date.getDay();
	var wochentag = new Array("Sonntag", "Montag", "Dienstag", "Mittwoch", "Donnerstag", "Freitag", "Samstag");
	var monat = new Array("Januar", "Februar", "M&auml;rz", "April", "Mai", "Juni", "Juli", "August", "September", "Oktober", "November", "Dezember");
	var datum = wochentag[tagInWoche] + ", " + tag + ". " + monat[monatDesJahres] + " " + jahr + " " + stunden + ":" + minuten;
	document.getElementById('time').innerHTML = datum;
	setTimeout(updateTime, 60000);
}
window.addEventListener("load", updateTime);