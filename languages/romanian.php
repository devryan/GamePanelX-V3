<?php
/*
 * GamePanelX
 * 
 * Romania Language file
 * Written by Heinous
 * Email: Hardkboy@hotmail.com

 * To translate to another language, copy this to a new PHP file named "yourlanguage.php", and translate all the english words on the right (to the right of the = sign).
 * Try and keep the structure of the file the same, and make sure you close all '';
 * 
*/
header('Content-Type: text/html; charset=UTF-8');

$lang = array();

########################################################################

// Common Words/Phrases
$lang['yes']                = 'Da';
$lang['no']                 = 'Nu';
$lang['active']             = 'Activ';
$lang['inactive']           = 'Inactiv';
$lang['setup']              = 'Configurare';
$lang['settings']           = 'Setări';
$lang['language']           = 'Limbi';
$lang['default_language']   = 'Limba maternă';
$lang['email_address']      = 'Adresa de e-mail';
$lang['company']            = 'Companie';
$lang['theme']              = 'Temă';
$lang['save']               = 'Salveaza';
$lang['game']               = 'Joc';
$lang['voice']              = 'Voce';
$lang['desc']               = 'Descriere';
$lang['status']             = 'Stare';
$lang['manage']             = 'Administrare';
$lang['info']               = 'Info';
$lang['owner']              = 'Proprietar';
$lang['date_added']         = 'Data adaugarii';
$lang['last_updated']       = 'Ultima actualizare';
$lang['default']            = 'Lipsă';
$lang['delete']             = 'Şterge';
$lang['create']             = 'Creaza';
$lang['add']                = 'Adauga';
$lang['edit']               = 'Edita';
$lang['failed']             = 'Eșuat';
$lang['name']               = 'Nume';
$lang['first_name']         = 'Prenume';
$lang['last_name']          = 'Nume';
$lang['type']               = 'Tip';
$lang['none']               = 'Nici unul';
$lang['optional']           = 'Optional';
$lang['complete']           = 'Complet';
$lang['go_back']            = 'Inapoi';
$lang['saved']              = 'Salvat';

// Tech Words
$lang['server']             = 'Server';
$lang['username']           = 'Utilizator';
$lang['password']           = 'Parola';
$lang['newpassword']        = 'Parola Noua';
$lang['newpassword_conf']   = '<b>Parola Noua</b> (confirmare)';
$lang['chpassword']         = 'Schimba Parola';
$lang['cur_password']       = 'Parola Actuala';
$lang['network']            = 'Retea';
$lang['online']             = 'Conectat';
$lang['offline']            = 'Deconectat';
$lang['connect']            = 'Conectare';
$lang['startup']            = 'Startup';
$lang['files']              = 'Fișiere';
$lang['command']            = 'Comandă';
$lang['local_dir']          = 'Directorul local';
$lang['working_dir']        = 'Directorul de lucru';
$lang['pid_file']           = 'Fișier PID';
$lang['ip']                 = 'Adresa IP';
$lang['ips']                = 'Adrese IP';
$lang['port']               = 'Port';
$lang['login']              = 'Autentificare';
$lang['logout']             = 'Ieșire';
$lang['logged_out']         = 'Ieșire cu succes';
$lang['install']            = 'Instalare';
$lang['installing']         = 'Se instaleaza';
$lang['not_installed']      = 'Nu este instalat';

$lang['unknown']            = 'Necunoscut';
$lang['click_here']         = 'Click Aici';
$lang['documentation']      = 'Documentare GamePanelX';
$lang['update_cmd']         = 'Actualizare CMD';
$lang['banned_start']       = 'Interzise valorilor de pornire';
$lang['banned_start_desc']  = '<b>Nota:</b> pune toate caracterele care nu doriţi clienţii sţ tastaţi în "valoare" o parte din editorul de pornire.';
$lang['plugin']             = 'Plugin';
$lang['plugins']            = 'plugin-uri';
$lang['del_install']        = 'Vă rugăm să ștergeţi directorul "Install" înainte de a continua!';
$lang['version']            = 'Versiune';
$lang['system_update']      = 'O actualizare pentru sistem este disponibila!';
$lang['invalid_login']      = 'Autentificare invalida! Va rugam sa incercati din nou.';
$lang['permissions']        = 'Permisiuni';

// Error messages
$lang['err_query']          = 'Eroare la interogarea bazei de date';
$lang['err_sql_update']     = 'Actualizarea bazei de date a eşuat';

// Left Panel
$lang['home']               = 'Acasă';
$lang['setup']              = 'Configurare';
$lang['settings']           = 'Setări';
$lang['game_setups']        = 'Setări Joc';
$lang['cloud_games']        = 'Jocuri Cloud';
$lang['server_templates']   = 'Temă Server';
$lang['template']           = 'Temă';
$lang['templates']          = 'Teme';
$lang['servers']            = 'Servere';
$lang['all_servers']        = 'Toate Serverele';
$lang['game_servers']       = 'Servere Jocuri';
$lang['voice_servers']      = 'Servere Voce';
$lang['create_server']      = 'Creaţi Server';
$lang['accounts']           = 'Conturi';
$lang['admins']             = 'Admine';
$lang['resellers']          = 'Distribuitori';
$lang['list_users']         = 'Lista de utilizatori';
$lang['add_user']           = 'Adăugaţi utilizator';
$lang['list_admins']        = 'Lista de admine';
$lang['add_admin']          = 'Adăugaţi admin';
$lang['list_resellers']     = 'Lista de Distribuitori';
$lang['add_reseller']       = 'Adăugaţi Distribuitori';
$lang['welcome_msg']        = 'Bine ati venit la GamePanelX';
$lang['int_name']           = 'Numele intern';
$lang['int_name_desc']      = 'Numele intern trebuie să conţină doar litere, numere si subliniaza, cum ar fi "red_1"';
$lang['query_engine']       = 'Query Engine';
$lang['create_network']     = 'Creează reţea server';

// Templates
$lang['delete_tp']          = 'Ştergeţi acesta tema';
$lang['create_tp']          = 'Creare tema';
$lang['file_path']          = 'Cale fișier';
$lang['browse']             = 'Căutaţi';

// Network
$lang['network_server']     = 'Reţea Server';
$lang['os']                 = 'Sistemul de operare';
$lang['location']           = 'Locatie';
$lang['datacenter']         = 'Centru de date';
$lang['local']              = 'Local';
$lang['local_server']       = 'Server Local';
$lang['remote_server']      = 'Server Remote';
$lang['no_enc_key']         = 'Nici o cheie de criptare găsita! verifica "/configuration.php".';
$lang['login_user']         = 'Utilizator de Autentificare';
$lang['login_pass']         = 'Parola de Autentificare';
$lang['login_port']         = 'Port';
$lang['login_homedir']      = 'Director Principal';
$lang['net_showing_ips']    = 'Rezultate Adrese IP pe server de reţea';
$lang['srv_using_net']      = 'Exist? servere de joc care folosesc acest server de reţea! ștergeti serverele și încercaţi din nou.';
$lang['add_ip']             = 'Adăugaţi adresă IP';
$lang['new_ip']             = 'Adresă de IP nouă';
$lang['ip_exists']          = 'Ne pare rău, adresa de IP există deja!';
$lang['ip_port_used']       = 'Ne pare rău, această combinaţie IP/Port este deja în uz!';
$lang['srv_using_ip']       = 'Există servere de joc care utilizează această adresă de IP! ştergeti serverele şi încercaţi din nou.';
$lang['invalid_ip']         = 'Adresa de IP invalida! Va rugam sa verificati si incercati din nou.';

// Servers
$lang['create_sv']          = 'Creaţi Server';
$lang['invalid_port']       = 'Portul este incorect! Vă rugăm să încercaţi din nou.';
$lang['invalid_intname']    = 'Numele intern specificat este invalid! Numai litere, numere, - si _ sunt acceptate. Vă rugăm să încercaţi din nou.';
$lang['item']               = 'Articol';
$lang['value']              = 'Valoare';
$lang['user_editable']      = 'Utilizator editabil';
$lang['restart']            = 'Resetare';
$lang['stop']               = 'Oprire';
$lang['update']             = 'Actualizare';
$lang['map']                = 'Mapa';
$lang['hostname']           = 'Hostname';
$lang['players']            = 'Jucători';
$lang['show_options']       = 'Arata toate opţiunile';
$lang['simple']             = 'Simplu';
$lang['advanced']           = 'Avansat';

// Cloud
$lang['cloud_avail']        = 'Jocuri disponibile prin <i>gpx cloud</i>';
$lang['cloud_topmsg']       = 'Toate jocurile adăugate în GamePanelX Cloud, vor fi disponibile aici.';

// Games
$lang['games_add_desc']     = 'Folosiţi acest formular pentru a adăuga suport nou joc.  Puteţi continua apoi cu crearea unui model pentru acest joc.';
$lang['games_up_icon']      = '<b>Note:</b> Încărcaţi pictograma 64x64 PNG la';
$lang['note_steam_auto']    = '<b>Note:</b> Pentru jocurile Steam Bazate pe steam, lăsaţi File Path gol pentru a utiliza instalare automatica Steam.';

// File Manager
$lang['new_filename']       = 'Nume de fişier nou';
$lang['new_dirname']        = 'Nume de director nou';

// User perms
$lang['access_ftp']         = 'acces FTP';
$lang['update_usr_det']     = 'Actualizaţi detalii utilizator';
$lang['user_exists']        = 'Ne pare rău, numele de utilizator există deja!';

// Home Page hints
$lang['def_adm_step']       = 'Pas';
$lang['def_adm_tip_docs']   = 'Vă rugăm să consultaţi documentaţia completă';
$lang['def_adm_tip_accts']  = 'Nici unul gasit! ar trebui să creezi un cont de utilizator pentru a crea servere.';
$lang['def_adm_tip_net']    = 'Nu au fost gasite servere de retea! ar trebui să creezi unul acum';
$lang['def_adm_tip_tpl']    = 'Nu au fost gasite template-uri completate! Pentru a crea servere, ar trebui să alegeţi un joc şi';
$lang['def_adm_tip_srv1']   = 'Sunteţi gata pentru a crea un server de!';
$lang['def_adm_tip_srv2']   = 'Efectuaţi paşii de mai sus pentru a crea un server de joc/voce.';

// Other
$lang['api_key']            = 'API Key';

##############################################################################################################

// 3.0.8
$lang['install_mirrors']    = 'Instalaţi Oglinzi';
$lang['game_panel']         = 'Panoul de control joc';
$lang['show_console_out']   = 'Click pentru a arăta consola de ieşire';
$lang['config_file']        = 'Fişier de configurare';
$lang['modified']           = 'Modificate';
$lang['accessed']           = 'Accesate';
$lang['size']               = 'Dimensiune';
$lang['maxplayers']         = 'Max Jucători';
$lang['hostname']           = 'Hostname';

?>
