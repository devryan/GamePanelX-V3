<?php
/*
 * GamePanelX
 * 
 * Hungarian Language file
 * Written by Kovács Levente
 * Email: kovacs_levi@yahoo.com
 * 
 * To translate to another language, copy this to a new PHP file named "yourlanguage.php", and translate all the english words on the right (to the right of the = sign).
 * Try and keep the structure of the file the same, and make sure you close all '';
 * 
*/
$lang = array();

########################################################################

// Common Words/Phrases
$lang['yes']                = 'Igen';
$lang['no']                 = 'Nem';
$lang['active']             = 'Aktív';
$lang['inactive']           = 'Inaktív';
$lang['setup']              = 'Beállítás';
$lang['settings']           = 'Beállítások';
$lang['language']           = 'Nyelv';
$lang['default_language']   = 'Alapértelmezett Nyelv';
$lang['email_address']      = 'E-mail Cím';
$lang['company']            = 'Vállalat';
$lang['theme']              = 'Téma';
$lang['save']               = 'Mentés';
$lang['game']               = 'Játék';
$lang['voice']              = 'Hang';
$lang['desc']               = 'Leírás';
$lang['status']             = 'Státusz';
$lang['manage']             = 'Kezelés';
$lang['info']               = 'Infó';
$lang['owner']              = 'Tulajdonos';
$lang['date_added']         = 'Hozzáadás Dátuma';
$lang['last_updated']       = 'Utoljára Frissítve';
$lang['default']            = 'Alapértelmezett';
$lang['delete']             = 'Töröl';
$lang['create']             = 'Készít';
$lang['add']                = 'Hozzáadás';
$lang['edit']               = 'Szerkeszt';
$lang['failed']             = 'Sikertelen';
$lang['name']               = 'Név';
$lang['first_name']         = 'Keresztnév';
$lang['last_name']          = 'Vezetéknév';
$lang['type']               = 'Típus';
$lang['none']               = 'Nincs';
$lang['optional']           = 'Opcionális';
$lang['complete']           = 'Befejez';
$lang['go_back']            = 'Vissza';
$lang['saved']              = 'Mentve';

// Tech Words
$lang['server']             = 'Szerver';
$lang['username']           = 'Felhasználónév';
$lang['password']           = 'Jelszó';
$lang['newpassword']        = 'Új Jelszó';
$lang['newpassword_conf']   = '<b>Új Jelszó</b> (megerõsítés)';
$lang['chpassword']         = 'Jelszó Módosítása';
$lang['cur_password']       = 'Jelenlegi Jelszó';
$lang['network']            = 'Hálózat';
$lang['online']             = 'Online';
$lang['offline']            = 'Offline';
$lang['connect']            = 'Csatlakozás';
$lang['startup']            = 'Indító';
$lang['files']              = 'Fájlok';
$lang['command']            = 'Parancs';
$lang['local_dir']          = 'Helyi Könyvtár';
$lang['working_dir']        = 'Mûködtetõi Könyvtár';
$lang['pid_file']           = 'PID Fájl';
$lang['ip']                 = 'IP Cím';
$lang['ips']                = 'IP Címek';
$lang['port']               = 'Port';
$lang['login']              = 'Belépés';
$lang['logout']             = 'Kilépés';
$lang['logged_out']         = 'Sikeresen Kiléptél';
$lang['install']            = 'Telepít';
$lang['installing']         = 'Telepítés';
$lang['not_installed']      = 'Nincs Telepítve';

$lang['unknown']            = 'Ismeretlen';
$lang['click_here']         = 'Kattints Ide';
$lang['documentation']      = 'GamePanelX Dokumentáció';
$lang['update_cmd']         = 'CMD Frissítés';
$lang['banned_start']       = 'Tiltott Indítói értékek';
$lang['banned_start_desc']  = '<b>Megjegyzés:</b> Írd ide azokat a szavakat, amelyeket nem szeretnéd, hogy az ügyfelek a szerver indítói értékekben használjanak';
$lang['plugin']             = 'Bõvítmény';
$lang['plugins']            = 'Bõvítmények';
$lang['del_install']        = 'A folytatáshoz töröld ki az "install" könyvtárat!';
$lang['version']            = 'Verzió';
$lang['system_update']      = 'Rendszerfrissítés elérhetõ!';
$lang['invalid_login']      = 'Sikertelen belépés!  Kérlek Próbáld újra.';
$lang['permissions']        = 'Engedélyek';

// Error messages
$lang['err_query']          = 'Sikertelen adatbázis lekérdezés';
$lang['err_sql_update']     = 'Sikertelen adatbázis frissítés';

// Left Panel
$lang['home']               = 'Fõoldal';
$lang['setup']              = 'Beállít';
$lang['settings']           = 'Beállítások';
$lang['game_setups']        = 'Játék Beállítások';
$lang['cloud_games']        = 'Felhõ Játékok';
$lang['server_templates']   = 'Szerver Sablonok';
$lang['template']           = 'Sablon';
$lang['templates']          = 'Sablonok';
$lang['servers']            = 'Szerverek';
$lang['all_servers']        = 'Összes Szerver';
$lang['game_servers']       = 'Játékszerverek';
$lang['voice_servers']      = 'Hangszerverek';
$lang['create_server']      = 'Szerverkészítés';
$lang['accounts']           = 'Felhasználók';
$lang['admins']             = 'Adminok';
$lang['resellers']          = 'Viszonteladók';
$lang['list_users']         = 'Ügyfelek Listája';
$lang['add_user']           = 'Ügyfél Hozzáadás';
$lang['list_admins']        = 'Adminok Listája';
$lang['add_admin']          = 'Admin Hozzáadás';
$lang['list_resellers']     = 'Viszonteladók Listája';
$lang['add_reseller']       = 'Viszonteladó Hozzáadás';
$lang['welcome_msg']        = 'Üdvözöl a GamePanelX';
$lang['int_name']           = 'Belsõ Név';
$lang['int_name_desc']      = 'A Belsõ Név csak betûket, számokat és aláhúzásokat tartalmazhat, például: "red_1"';
$lang['query_engine']       = 'Query Motor';
$lang['create_network']     = 'Hálózati Szerver Létrehozás';

// Templates
$lang['delete_tp']          = 'Sablon Törlése';
$lang['create_tp']          = 'Sablon Létrehozás';
$lang['file_path']          = 'Szerver Fájlok';
$lang['browse']             = 'Böngészés';

// Network
$lang['network_server']     = 'Hálózati Szerverek';
$lang['os']                 = 'Operációs Rendszer';
$lang['location']           = 'Elhelyezés';
$lang['datacenter']         = 'Adatközpont';
$lang['local']              = 'Helyi';
$lang['local_server']       = 'Helyi Szerver';
$lang['remote_server']      = 'Távoli Szerver';
$lang['no_enc_key']         = 'Nem található titkosított kulcs!  Ellenõrizd a "/configuration.php" fájlt.';
$lang['login_user']         = 'Felhasználónév';
$lang['login_pass']         = 'Jelszó';
$lang['login_port']         = 'Port';
$lang['login_homedir']      = 'Home Könyvtár';
$lang['net_showing_ips']    = 'IP Címek mutatása';
$lang['srv_using_net']      = 'Léteznek szerverek ezen a hálózati szerveren!  Kérlek elõbb töröld õket.';
$lang['add_ip']             = 'IP Cím Hozzáadás';
$lang['new_ip']             = 'Új IP Cím';
$lang['ip_exists']          = 'Sajnálom, Ez az IP Cím már létezik!';
$lang['ip_port_used']       = 'Sajnálom, Ez az IP/Port már használatban van!';
$lang['srv_using_ip']       = 'Léteznek szerverek ezzel az IP Címmel!  Kérlek elõbb töröld õket.';
$lang['invalid_ip']         = 'Helytelen IP Cím!  Kérlek Próbáld újra.';

// Servers
$lang['create_sv']          = 'Szerver Készítés';
$lang['invalid_port']       = 'Helytelen port!  Kérlek próbáld újra.';
$lang['invalid_intname']    = 'Helytelen Belsõ név!  Csak betûket, számokat, vonalat és alúlvonalat használhatsz.  Kérlek Próbáld újra.';
$lang['item']               = 'Item';
$lang['value']              = 'Érték';
$lang['user_editable']      = 'Ügyfél által módosítható';
$lang['restart']            = 'Újraindítás';
$lang['stop']               = 'Leállítás';
$lang['update']             = 'Frissítés';
$lang['map']                = 'Pálya';
$lang['hostname']           = 'Szerver Név';
$lang['players']            = 'Játékosok';
$lang['show_options']       = 'Összes opciók mutatása';
$lang['simple']             = 'Kezdõ';
$lang['advanced']           = 'Haladó';

// Cloud
$lang['cloud_avail']        = 'Játékok elérhetõek a <i>gpx cloud</i> által';
$lang['cloud_topmsg']       = 'Ha megjelenik egy új játék a GamePanelX Cloud-on, akkor itt lesznek elérhetõek.';

// Games
$lang['games_add_desc']     = 'Itt létrehozhatsz új telepíthetõ játékot.';
$lang['games_up_icon']      = '<b>Megjegyzés:</b> Tölts fel egy 64x64 PNG ikont';
$lang['note_steam_auto']    = '<b>Megjegyzés:</b> Steam-es szerverekhez, hagyd üresen a szerver fájlok útvonalát, hogy a Steam Automatikus telepítõt használd.';

// File Manager
$lang['new_filename']       = 'Új Fájlnév';
$lang['new_dirname']        = 'Új Könyvtárnév';

// User perms
$lang['access_ftp']         = 'FTP Hozzáférés';
$lang['update_usr_det']     = 'Felhasználói adatok frissítése';
$lang['user_exists']        = 'Sajnálom, a felhasználónév már létezik!';

// Home Page hints
$lang['def_adm_step']       = 'Lépés';
$lang['def_adm_tip_docs']   = 'Kérlek Olvasd el az egész Dokumentációt';
$lang['def_adm_tip_accts']  = 'Nincs Ügyfél!  Elõbb készíts egy ügyfél felhasználót, hogy aztán szervert készíthess.';
$lang['def_adm_tip_net']    = 'Nincs Hálózati Szerver!  Elõbb készíts egyet.';
$lang['def_adm_tip_tpl']    = 'Nincs kész szerver sablon!  Szerver létrehozáshoz, elõbb készíts egy szerver sablont';
$lang['def_adm_tip_srv1']   = 'Készen állsz egy szerver létrehozáshoz!';
$lang['def_adm_tip_srv2']   = 'Most már készíthetsz játék/hang szervert.';

// Other
$lang['api_key']            = 'API Kulcs';

##############################################################################################################

// 3.0.8
$lang['install_mirrors']    = 'Telepítési Útvonalak';
$lang['game_panel']         = 'Webadmin';
$lang['show_console_out']   = 'Kattints ide, hogy lásd a console-t';
$lang['config_file']        = 'Konfig Fájl';
$lang['modified']           = 'Módosítva';
$lang['accessed']           = 'Elérhetõ';
$lang['size']               = 'Méret';
$lang['maxplayers']         = 'Férõhely';
$lang['hostname']           = 'Hosztnév';

?>
