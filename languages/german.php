<?php
/*
 * GamePanelX
 * 
 * German Language file
 * Written by Shark62
 * 
 * To translate to another language, copy this to a new PHP file named "yourlanguage.php", and translate all the english words on the right (to the right of the = sign).
 * Try and keep the structure of the file the same, and make sure you close all '';
 * 
*/
$lang = array();

########################################################################

// Common Words/Phrases
$lang['yes']                = 'Ja';
$lang['no']                 = 'Nein';
$lang['active']             = 'Aktiv';
$lang['inactive']           = 'Inaktiv';
$lang['setup']              = 'Setup';
$lang['settings']           = 'Einstellungen';
$lang['language']           = 'Sprache';
$lang['default_language']   = 'Standart Sprache';
$lang['email_address']      = 'Email Addresse';
$lang['company']            = 'Firma';
$lang['theme']              = 'Theme';
$lang['save']               = 'Speichern';
$lang['game']               = 'Spiel';
$lang['voice']              = 'Stimme';
$lang['desc']               = 'Beschreibung';
$lang['status']             = 'Status';
$lang['manage']             = 'Verwalten';
$lang['info']               = 'Info';
$lang['owner']              = 'Besitzer';
$lang['date_added']         = 'Datum hinzugefügt';
$lang['last_updated']       = 'Letztes Update';
$lang['default']            = 'Standart';
$lang['delete']             = 'Löschen';
$lang['create']             = 'Erstellen';
$lang['add']                = 'Hinzufügen';
$lang['edit']               = 'Editieren';
$lang['failed']             = 'Fehlgeschlagen';
$lang['name']               = 'Name';
$lang['first_name']         = 'Vorname';
$lang['last_name']          = 'Nachname';
$lang['type']               = 'Typ';
$lang['none']               = 'Keine';
$lang['optional']           = 'Optional';
$lang['complete']           = 'Komplett';
$lang['go_back']            = 'Zurück';
$lang['saved']              = 'Gespeichert';

// Tech Words
$lang['server']             = 'Server';
$lang['username']           = 'Benutzername';
$lang['password']           = 'Passwort';
$lang['newpassword']        = 'Neues Passwort';
$lang['newpassword_conf']   = '<b>Neues Passwort</b> (confirm)';
$lang['chpassword']         = 'Ändere Passwort';
$lang['cur_password']       = 'Aktuelles Passwort';
$lang['network']            = 'Netzwerk';
$lang['online']             = 'Online';
$lang['offline']            = 'Offline';
$lang['connect']            = 'Verbinden';
$lang['startup']            = 'Startup';
$lang['files']              = 'Dateien';
$lang['command']            = 'Kommando';
$lang['local_dir']          = 'Lokales Verzeichnis';
$lang['working_dir']        = 'Arbeitsverzeichnis';
$lang['pid_file']           = 'PID Datei';
$lang['ip']                 = 'IP Addresse';
$lang['ips']                = 'IP Addressen';
$lang['port']               = 'Port';
$lang['login']              = 'Einloggen';
$lang['logout']             = 'Ausloggen';
$lang['logged_out']         = 'Erfolgreich ausgeloggt';
$lang['install']            = 'Installieren';
$lang['installing']         = 'Installiert';
$lang['not_installed']      = 'Nicht installiert';

$lang['unknown']            = 'Unbekannt';
$lang['click_here']         = 'Klicke hier';
$lang['documentation']      = 'GamePanelX Dokumentation';
$lang['update_cmd']         = 'Aktualisiere CMD';
$lang['banned_start']       = 'Verbotene Startwerte';
$lang['banned_start_desc']  = '<b>Hinweis:</b> Zeichen, die von Clients nicht im Startup Editor benutzt werden dürfen.';
$lang['plugin']             = 'Plugin';
$lang['plugins']            = 'Plugins';
$lang['del_install']        = 'Bitte löschen Sie das "install"-Verzeichnis, bevor Sie fortfahren!';
$lang['version']            = 'Version';
$lang['system_update']      = 'Ein System-Update ist verfügbar!';
$lang['invalid_login']      = 'Login fehlerhaft! Bitte versuchen Sie es erneut.';
$lang['permissions']        = 'Berechtigungen';

// Error messages
$lang['err_query']          = 'Fehler bei Abfrage der Datenbank';
$lang['err_sql_update']     = 'Fehler beim aktualisieren der Datenbank';

// Left Panel
$lang['home']               = 'Home';
$lang['setup']              = 'Setup';
$lang['settings']           = 'Einstellungen';
$lang['game_setups']        = 'Spiele Setups';
$lang['cloud_games']        = 'Cloud Spiele';
$lang['server_templates']   = 'Server Templates';
$lang['template']           = 'Template';
$lang['templates']          = 'Templates';
$lang['servers']            = 'Server';
$lang['all_servers']        = 'Alle Server';
$lang['game_servers']       = 'Spiele Server';
$lang['voice_servers']      = 'Voice Server';
$lang['create_server']      = 'Erstelle Server';
$lang['accounts']           = 'Konten';
$lang['admins']             = 'Administratoren';
$lang['resellers']          = 'Resellers';
$lang['list_users']         = 'Benutzerliste';
$lang['add_user']           = 'Benutzer hinzufügen';
$lang['list_admins']        = 'Administratorenliste';
$lang['add_admin']          = 'Administrator hinzufügen';
$lang['list_resellers']     = 'Resellerliste';
$lang['add_reseller']       = 'Reseller hinzufügen';
$lang['welcome_msg']        = 'Willkommen bei GamePanelX';
$lang['int_name']           = 'Interner Name';
$lang['int_name_desc']      = 'Der interne Name darf nur Buchstaben, Zahlen und Unterstriche enthalten. Z.B. "red_1"';
$lang['query_engine']       = 'Query Engine';
$lang['create_network']     = 'Erstelle Netzwerk Server';

// Templates
$lang['delete_tp']          = 'Lösche Template';
$lang['create_tp']          = 'Erstelle Template';
$lang['file_path']          = 'Datei Pfad';
$lang['browse']             = 'Blättern';

// Network
$lang['network_server']     = 'Netzwerk Server';
$lang['os']                 = 'Betriebssystem';
$lang['location']           = 'Standort';
$lang['datacenter']         = 'Datenzentrum';
$lang['local']              = 'Lokal';
$lang['local_server']       = 'Lokaler Server';
$lang['remote_server']      = 'Entfernter Server';
$lang['no_enc_key']         = 'Kein Schlüssel gefunden! Überprüfe "/configuration.php".';
$lang['login_user']         = 'Login Benutzer';
$lang['login_pass']         = 'Login Passwort';
$lang['login_port']         = 'Login Port';
$lang['login_homedir']      = 'Home Verzeichnis';
$lang['net_showing_ips']    = 'Zeige IP Addressen auf Netzwerk Server';
$lang['srv_using_net']      = 'Es existieren Spiele-Server die diesen Netzwerk-Server verwenden! Bitte zuerst Spiele-Server löschen und dann erneut versuchen.';
$lang['add_ip']             = 'IP Addresse hinzufügen';
$lang['new_ip']             = 'Neue IP Addresse';
$lang['ip_exists']          = 'Entschuldigung, diese IP Addresse existiert bereits!';
$lang['ip_port_used']       = 'Entschuldigung, diese IP/Port Kombination ist bereits in Benutzung!';
$lang['srv_using_ip']       = 'Es existieren bereits Spiele-Server die diese IP Addresse benutzen! Bitte zuerst Spiele-Server löschen und dann erneut versuchen.';
$lang['invalid_ip']         = 'Ungültige IP Addresse!  Bitte überprüfen und erneut probieren.';

// Servers
$lang['create_sv']          = 'Erstelle Server';
$lang['invalid_port']       = 'Ungültige Port-Angabe!  Bitte erneut versuchen.';
$lang['invalid_intname']    = '<ungültiger Interner Name angegeben!  Nur Buchstaben, Zahlen, - und _ wird als Eingabe akzeptiert. Bitte erneut versuchen.';
$lang['item']               = 'Gegenstand';
$lang['value']              = 'Wert';
$lang['user_editable']      = 'Veränderbar';
$lang['restart']            = 'Restart';
$lang['stop']               = 'Stop';
$lang['update']             = 'Update';
$lang['map']                = 'Map';
$lang['hostname']           = 'Hostname';
$lang['players']            = 'Spieler';
$lang['show_options']       = 'Zeige alle Optionen';
$lang['simple']             = 'Einfach';
$lang['advanced']           = 'Erweitert';

// Cloud
$lang['cloud_avail']        = 'Verfügbare Spiele über <i>GPX Cloud</i>';
$lang['cloud_topmsg']       = 'Da in Zukunft weitere Spiele in der GamePanelX Cloud hinzu kommen, werden diese dann hier verfügbar sein.';

// Games
$lang['games_add_desc']     = 'Verwende dieses Formular, um eine neue Spiel-Unterstützung hinzufügen.  Du kannst dann dann mit der Erstellung einer Vorlage für dieses Spiel beginnen.';
$lang['games_up_icon']      = '<b>Hinweis:</b> Lade ein 64x64 PNG Icon hoch nach';
$lang['note_steam_auto']    = '<b>Hinweis:</b> Für Steam-basierte Spiele, bitte den Dateipfad leer lassen um den Steam Auto-Installer benutzen zu können';

// File Manager
$lang['new_filename']       = 'Neuer Dateiname';
$lang['new_dirname']        = 'Neuer Verzeichnisname';

// User perms
$lang['access_ftp']         = 'Zugriff FTP';
$lang['update_usr_det']     = 'Aktualisiere Benutzer Details';
$lang['user_exists']        = 'Entschuldigung, aber dieser Benutzername existiert bereits!';

// Home Page hints
$lang['def_adm_step']       = 'Schritt';
$lang['def_adm_tip_docs']   = 'Bitte in der Dokumentation nachschauen.';
$lang['def_adm_tip_accts']  = 'Nicht gefunden!  Bitte erstelle ein Benutzerkonto um Server zu erstellen.';
$lang['def_adm_tip_net']    = 'Kein Netzwerk Server gefunden!  Erstelle jetzt einen Netzwerk Server.';
$lang['def_adm_tip_tpl']    = 'Keine vollständigen Templates gefunden!  Um Server zu erstellen, wähle bitte ein Spiel und';
$lang['def_adm_tip_srv1']   = 'Du bist jetzt in der Lage einen Server erstellen zu können!';
$lang['def_adm_tip_srv2']   = 'Arbeite alle obigen Schritte ab, um einen Spiel/Voice Server zu erstellen.';

// Other
$lang['api_key']            = 'API Key';

##############################################################################################################

// 3.0.8
$lang['install_mirrors']    = 'Install Mirrors';
$lang['game_panel']         = 'Game Control Panel';
$lang['show_console_out']   = 'Klicken um die Konsolenausgabe anzuzeigen';
$lang['config_file']        = 'Konfigurationsdatei';
$lang['modified']           = 'Modifiziert';
$lang['accessed']           = 'Zugegriffen';
$lang['size']               = 'Grösse';
$lang['maxplayers']         = 'Max Spieler';
$lang['hostname']           = 'Hostname';

?>
