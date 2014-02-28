<?php
/*
 * GamePanelX
 * 
 * English Language file
 * Written by Milon Kremer (donlimito@hotmail.com)
 * 
 * To translate to another language, copy this to a new PHP file named "yourlanguage.php", and translate all the english words on the right (to the right of the = sign).
 * Try and keep the structure of the file the same, and make sure you close all '';
 * 
*/
$lang = array();

########################################################################

// Common Words/Phrases
$lang['yes']                = 'Ja';
$lang['no']                 = 'Nee';
$lang['active']             = 'Actief';
$lang['inactive']           = 'Inactief';
$lang['setup']              = 'Setup';
$lang['settings']           = 'Instellingen';
$lang['language']           = 'Taal';
$lang['default_language']   = 'Standaard Taal';
$lang['email_address']      = 'Email Adres';
$lang['company']            = 'Bedrijf';
$lang['theme']              = 'Thema';
$lang['save']               = 'Opslaan';
$lang['game']               = 'Game';
$lang['voice']              = 'Voice';
$lang['desc']               = 'Beschrijving';
$lang['status']             = 'Status';
$lang['manage']             = 'Beheer';
$lang['info']               = 'Info';
$lang['owner']              = 'Eigenaar';
$lang['date_added']         = 'Datum Toegevoegd';
$lang['last_updated']       = 'Laatst Geupdate';
$lang['default']            = 'Standaard';
$lang['delete']             = 'Verwijderen';
$lang['create']             = 'Creëren';
$lang['add']                = 'Toevoegen';
$lang['edit']               = 'Bewerken';
$lang['failed']             = 'Mislukt';
$lang['name']               = 'Naam';
$lang['first_name']         = 'Voornaam';
$lang['last_name']          = 'Achternaam';
$lang['type']               = 'Type';
$lang['none']               = 'Geen';
$lang['optional']           = 'Optioneel';
$lang['complete']           = 'Voltooi';
$lang['go_back']            = 'Ga terug';
$lang['saved']              = 'Opgeslagen';

// Tech Words
$lang['server']             = 'Server';
$lang['username']           = 'Gebruikersnaam';
$lang['password']           = 'Wachtwoord';
$lang['newpassword']        = 'Nieuw Wachtwoord';
$lang['newpassword_conf']   = '<b>Nieuw Wachtwoord</b> (bevestigen)';
$lang['chpassword']         = 'Verander Wachtwoord';
$lang['cur_password']       = 'Actueel Wachtwoord';
$lang['network']            = 'Netwerk';
$lang['online']             = 'Online';
$lang['offline']            = 'Offline';
$lang['connect']            = 'Verbinden';
$lang['startup']            = 'Opstarten';
$lang['files']              = 'Bestanden';
$lang['command']            = 'Commando';
$lang['local_dir']          = 'Lokale Folder';
$lang['working_dir']        = 'Werkende Folder';
$lang['pid_file']           = 'PID Bestand';
$lang['ip']                 = 'IP Adres';
$lang['ips']                = 'IP Adressen';
$lang['port']               = 'Port';
$lang['login']              = 'Inloggen';
$lang['logout']             = 'Uitloggen';
$lang['logged_out']         = 'Succesvol uitgelogd';
$lang['install']            = 'Installeer';
$lang['installing']         = 'Installeren';
$lang['not_installed']      = 'Niet Geïnstalleerd';

$lang['unknown']            = 'Onbekend';
$lang['click_here']         = 'Klik Hier';
$lang['documentation']      = 'GamePanelX Documentatie';
$lang['update_cmd']         = 'Update CMD';
$lang['banned_start']       = 'Gebande Opstart Waarden';
$lang['banned_start_desc']  = '<b>Note:</b> Plaats alle karakters die gebruikers niet mogen typen in het "value" gedeelte van hun opstart editor.';
$lang['plugin']             = 'Plugin';
$lang['plugins']            = 'Plugins';
$lang['del_install']        = 'Verwijder alstublieft de "install" folder voor het verder gaan!';
$lang['version']            = 'Versie';
$lang['system_update']      = 'Er is een systeem update beschikbaar!';
$lang['invalid_login']      = 'Incorrecte login! Probeer opnieuw.';
$lang['permissions']        = 'Rechten';

// Error messages
$lang['err_query']          = 'Query naar database mislukt';
$lang['err_sql_update']     = 'Database update mislukt';

// Left Panel
$lang['home']               = 'Home';
$lang['setup']              = 'Setup';
$lang['settings']           = 'Instellingen';
$lang['game_setups']        = 'Game Instellingen';
$lang['cloud_games']        = 'Cloud Games';
$lang['server_templates']   = 'Server Templates';
$lang['template']           = 'Template';
$lang['templates']          = 'Templates';
$lang['servers']            = 'Servers';
$lang['all_servers']        = 'Alle Servers';
$lang['game_servers']       = 'Game Servers';
$lang['voice_servers']      = 'Voice Servers';
$lang['create_server']      = 'Maak Server';
$lang['accounts']           = 'Accounts';
$lang['admins']             = 'Admins';
$lang['resellers']          = 'Resellers';
$lang['list_users']         = 'Gebruikerslijst';
$lang['add_user']           = 'Gebruiker Toevoegen';
$lang['list_admins']        = 'Admin Lijst';
$lang['add_admin']          = 'Admin Toevoegen';
$lang['list_resellers']     = 'Reseller Lijst';
$lang['add_reseller']       = 'Reseller toevoegen';
$lang['welcome_msg']        = 'Welkom in GamePanelX';
$lang['int_name']           = 'Interne Naam';
$lang['int_name_desc']      = 'Interne Naam mag alleen letters, nummers en underscores bevatten, zoals "red_1"';
$lang['query_engine']       = 'Query Engine';
$lang['create_network']     = 'Maak Netwerk Server';

// Templates
$lang['delete_tp']          = 'Verwijder deze template';
$lang['create_tp']          = 'Maak Template';
$lang['file_path']          = 'Bestandslocatie';
$lang['browse']             = 'Blader';

// Network
$lang['network_server']     = 'Netwerk Server';
$lang['os']                 = 'Besturings Systeem';
$lang['location']           = 'Locatie';
$lang['datacenter']         = 'Datacenter';
$lang['local']              = 'Lokaal';
$lang['local_server']       = 'Lokale Server';
$lang['remote_server']      = 'Remote Server';
$lang['no_enc_key']         = 'Geen encryptiesleutel gevonden! Controleer "/configuration.php".';
$lang['login_user']         = 'Login Gebruiker';
$lang['login_pass']         = 'Login Wachtwoord';
$lang['login_port']         = 'Login Poort';
$lang['login_homedir']      = 'Home Folder';
$lang['net_showing_ips']    = 'Laten zien van IP Adressen op Netwerk Server';
$lang['srv_using_net']      = 'Er zijn game servers die gebruik maken van deze Netwerk Server!  Verwijder de servers en probeer het opnieuw.';
$lang['add_ip']             = 'IP Adres Toevoegen';
$lang['new_ip']             = 'Nieuw IP Adres';
$lang['ip_exists']          = 'Sorry, dat IP Adres bestaat al!';
$lang['ip_port_used']       = 'Sorry, die IP/Poort combinatie is al in gebruik!';
$lang['srv_using_ip']       = 'Er zijn game servers die gebruik maken van dit IP Adres!  Verwijder de servers en probeer het opnieuw.';
$lang['invalid_ip']         = 'Incorrect IP Adres!  Controleer en probeer opnieuw.';

// Servers
$lang['create_sv']          = 'Maak Server';
$lang['invalid_port']       = 'Verkeerde poort opgegeven!  Probeer opnieuw.';
$lang['invalid_intname']    = 'Verkeerde Interne Naam opgegeven!  Alleen letters, nummers, - en _ worden geaccepteerd.  Probeer opnieuw.';
$lang['item']               = 'Item';
$lang['value']              = 'Waarde';
$lang['user_editable']      = 'Bewerkbare Gebruiker';
$lang['restart']            = 'Herstarten';
$lang['stop']               = 'Stoppen';
$lang['update']             = 'Update';
$lang['map']                = 'Map';
$lang['hostname']           = 'Hostnaam';
$lang['players']            = 'Spelers';
$lang['show_options']       = 'Opties';
$lang['simple']             = 'Simpel';
$lang['advanced']           = 'Geavanceerd';

// Cloud
$lang['cloud_avail']        = 'Games Beschikbaar via <i>gpx cloud</i>';
$lang['cloud_topmsg']       = 'Als er meer games worden toegevoegd aan de GamePanelX Cloud, worden ze hier beschikbaar gesteld.';

// Games
$lang['games_add_desc']     = 'Gebruik dit formulier om nieuwe spel ondersteuning toe te voegen.  U kan vervolgens doorgaan met het maken van een template.';
$lang['games_up_icon']      = '<b>Note:</b> Upload uw 64x64 PNG icon naar';
$lang['note_steam_auto']    = '<b>Note:</b> Laat de bestandslocatie leeg om de Steam auto-installer te gebruiken';

// File Manager
$lang['new_filename']       = 'Nieuwe Bestandsnaam';
$lang['new_dirname']        = 'Nieuwe Foldernaam';

// User perms
$lang['access_ftp']         = 'Access FTP';
$lang['update_usr_det']     = 'Update User Details';
$lang['user_exists']        = 'Sorry, die gebruiksnaam bestaat al!';

// Home Page hints
$lang['def_adm_step']       = 'Stap';
$lang['def_adm_tip_docs']   = 'Bekijk alstublieft de gehelde documentatie!';
$lang['def_adm_tip_accts']  = 'Geen gevonden!  Je moet een gebruikersaccount aanmaken om een server te kunnen maken.';
$lang['def_adm_tip_net']    = 'Geen Netwerk Servers gevonden!  Maak er eerst een';
$lang['def_adm_tip_tpl']    = 'Geen complete templates gevonden!  Om servers te maken moet je een game selecteren';
$lang['def_adm_tip_srv1']   = 'U bent klaar met het toevoegen van de server!';
$lang['def_adm_tip_srv2']   = 'Voltooi de bovenstaande stappen om een nieuwe game of voice server aan te maken.';

// Other
$lang['api_key']            = 'API Sleutel';

##############################################################################################################

// 3.0.8
$lang['install_mirrors']    = 'Installeer Mirrors';
$lang['game_panel']         = 'Game Control Panel';
$lang['show_console_out']   = 'Klik om console te zien';
$lang['config_file']        = 'Configuratie Bestand';
$lang['modified']           = 'Bewerkt';
$lang['accessed']           = 'Accessed';
$lang['size']               = 'Grootte';
$lang['maxplayers']         = 'Max Spelers';
$lang['hostname']           = 'Servernaam';

?>
