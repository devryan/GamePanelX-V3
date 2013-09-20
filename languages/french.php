<?php
/*
* GamePanelX
*
* French Language file
* Written by @drien
*
* To translate to another language, copy this to a new PHP file named "yourlanguage.php", and translate all the english words on the right (to the right of the = sign).
* Try and keep the structure of the file the same, and make sure you close all '';
*
*/
header('Content-Type: text/html; charset=utf-8');
$lang = array();

########################################################################

// Common Words/Phrases
$lang['yes'] = 'Oui';
$lang['no'] = 'Non';
$lang['active'] = 'Actif';
$lang['inactive'] = 'Inactif';
$lang['setup'] = 'Installation';
$lang['settings'] = 'Paramètres';
$lang['language'] = 'Langue';
$lang['default_language'] = 'Langue par defaut';
$lang['email_address'] = 'Adresse Email';
$lang['company'] = 'Entreprise';
$lang['theme'] = 'Theme';
$lang['save'] = 'Sauver';
$lang['game'] = 'Jeu';
$lang['voice'] = 'Voix';
$lang['desc'] = 'Description';
$lang['status'] = 'Status';
$lang['manage'] = 'Gérer';
$lang['info'] = 'Info';
$lang['owner'] = 'Responsable';
$lang['date_added'] = 'Date ajout';
$lang['last_updated'] = 'Dernière mise à jour';
$lang['default'] = 'Defaut';
$lang['delete'] = 'Supprimer';
$lang['create'] = 'Créer';
$lang['add'] = 'Ajouter';
$lang['edit'] = 'Editer';
$lang['failed'] = 'Echec';
$lang['name'] = 'Nom';
$lang['first_name'] = 'Nom';
$lang['last_name'] = 'Prénom';
$lang['type'] = 'Type';
$lang['none'] = 'Aucun';
$lang['optional'] = 'Optionnel';
$lang['complete'] = 'Terminé';
$lang['go_back'] = 'Retour';
$lang['saved'] = 'Sauvé';

// Tech Words
$lang['server'] = 'Serveur';
$lang['username'] = 'Utilisateur';
$lang['password'] = 'Mot de passe';
$lang['newpassword'] = 'Nouveau mot de passe';
$lang['newpassword_conf'] = '<b>Nouveau mot de passe</b> (confirmez)';
$lang['chpassword'] = 'Changer mot de passe';
$lang['cur_password'] = 'Mot de passe actuel';
$lang['network'] = 'Réseau';
$lang['online'] = 'En ligne';
$lang['offline'] = 'Hors ligne';
$lang['connect'] = 'Connection';
$lang['startup'] = 'Démarrage';
$lang['files'] = 'Fichier';
$lang['command'] = 'Commande';
$lang['local_dir'] = 'Dossier local';
$lang['working_dir'] = 'Dossier de fonctionnement';
$lang['pid_file'] = 'Fichier PID';
$lang['ip'] = 'Adresse IP';
$lang['ips'] = 'Adresses IP';
$lang['port'] = 'Port';
$lang['login'] = 'Connexion';
$lang['logout'] = 'Déconnection';
$lang['logged_out'] = 'Déconnecté avec succés';
$lang['install'] = 'Installer';
$lang['installing'] = 'Installation en cours';
$lang['not_installed'] = 'Non Installé';

$lang['unknown'] = 'Inconnu';
$lang['click_here'] = 'Cliquez Ici';
$lang['documentation'] = 'Documentation de GamePanelX';
$lang['update_cmd'] = 'Commande de mise à jour';
$lang['banned_start'] = 'Valeur de démarrage bannies';
$lang['banned_start_desc'] = '<b>Note:</b>Mettez toutes les lettres que vous ne voulez pas que les clients tapent dans la partie «valeur» de leur éditeur de démarrage.';
$lang['plugin'] = 'Plugin';
$lang['plugins'] = 'Plugins';
$lang['del_install'] = 'Veuillez supprimer le dossier "install" avant de continuer !';
$lang['version'] = 'Version';
$lang['system_update'] = 'Une mise à jour du système est disponible !';
$lang['invalid_login'] = 'Identifiant incorrect ! Veuillez réessayer ultérieurement.';
$lang['permissions'] = 'Permissions';

// Error messages
$lang['err_query'] = 'Impossible de contacter la base de donnée';
$lang['err_sql_update'] = 'Impossible de mettre à jour la basede donnée';

// Left Panel
$lang['home'] = 'Accueil';
$lang['setup'] = 'Installation';
$lang['settings'] = 'Paramètres';
$lang['game_setups'] = 'Paramètrage des jeux';
$lang['cloud_games'] = 'Jeux dans le Cloud';
$lang['server_templates'] = 'Modèles de Serveur';
$lang['template'] = 'Modèle';
$lang['templates'] = 'Modèles';
$lang['servers'] = 'Serveurs';
$lang['all_servers'] = 'Tout les Serveurs';
$lang['game_servers'] = 'Serveurs de jeu';
$lang['voice_servers'] = 'Serveurs de communication';
$lang['create_server'] = 'Créer Serveur';
$lang['accounts'] = 'Comptes';
$lang['admins'] = 'Admins';
$lang['resellers'] = 'Revendeur';
$lang['list_users'] = 'Liste des Utilisateurs';
$lang['add_user'] = 'Ajouter un utilisateur';
$lang['list_admins'] = 'Liste des Admins';
$lang['add_admin'] = 'Ajouter un Admin';
$lang['list_resellers'] = 'Liste des Revendeurs';
$lang['add_reseller'] = 'Ajouter un Revendeur';
$lang['welcome_msg'] = 'Bienvenue dans GamePanelX';
$lang['int_name'] = 'Nom interne';
$lang['int_name_desc'] = 'Le nom interne doit seulement contenir des lettes, nombres et tirés (8), comme "red_1"';
$lang['query_engine'] = 'Moteur de requête';
$lang['create_network'] = 'Créer un serveur réseau';

// Templates
$lang['delete_tp'] = 'Supprimer ce modèle';
$lang['create_tp'] = 'Créer un modèle';
$lang['file_path'] = 'Dossier des fichiers';
$lang['browse'] = 'Parcourir';

// Network
$lang['network_server'] = 'Serveur réseau';
$lang['os'] = 'OS';
$lang['location'] = 'Localisation';
$lang['datacenter'] = 'Datacentre';
$lang['local'] = 'Local';
$lang['local_server'] = 'Serveur Local';
$lang['remote_server'] = 'Serveur Distant';
$lang['no_enc_key'] = 'Pas de clef de cryptage trouvée ! Vérifiez "/configuration.php".';
$lang['login_user'] = 'Identifiant';
$lang['login_pass'] = 'Mot de passe';
$lang['login_port'] = 'Port pour identification';
$lang['login_homedir'] = 'Dossier racine';
$lang['net_showing_ips'] = 'Montrer Adresses IP sur serveur réseau';
$lang['srv_using_net'] = 'Il y a des serveurs de jeu utilisant ce serveur réseau ! Supprimer le serveur en premier et réessayez.';
$lang['add_ip'] = 'Ajouter Adresse IP';
$lang['new_ip'] = 'Nouvelle Adresse IP';
$lang['ip_exists'] = 'Désolé, cette adresse IP existe déja !';
$lang['ip_port_used'] = 'Désolé, cette combinaison IP/Port est déja utilisée !';
$lang['srv_using_ip'] = 'Il y a des serveurs de jeu utilisant cette adresse IP ! Supprimez les serveurs en premier et réessayez.';
$lang['invalid_ip'] = 'Adresse IP invalide! Veuillez vérifier et réessayer.';

// Servers
$lang['create_sv'] = 'Créer Serveur';
$lang['invalid_port'] = 'Port invalid spécifié ! Veuillez réessayer.';
$lang['invalid_intname'] = 'Le nom interne spécifié est invalide ! Seul les lettres, nombres - et _ sont acceptés. Veuillez réessayer.';
$lang['item'] = 'Objet';
$lang['value'] = 'Valeur';
$lang['user_editable'] = 'Modifiable par un utilisateur';
$lang['restart'] = 'Redémarrer';
$lang['stop'] = 'Arrêter';
$lang['update'] = 'Mettre à jour';
$lang['map'] = 'Map';
$lang['hostname'] = 'Nom de hôte';
$lang['players'] = 'Joueurs';
$lang['show_options'] = 'Voir toutes les options';
$lang['simple'] = 'Simple';
$lang['advanced'] = 'Avancé';

// Cloud
$lang['cloud_avail'] = 'Jeux disponibles via le <i>gpx cloud</i>';
$lang['cloud_topmsg'] = 'Quand des autres jeux seront ajoutés sur le GamePanelX Cloud, ils seront disponibles ici.';

// Games
$lang['games_add_desc'] = 'Utilisez ce formulaire pour ajouter le support de nouveau jeux. Vous pouvez ensuite procéder à la création du modèle pour ce jeu.';
$lang['games_up_icon'] = '<b>Note:</b> Uploadez votre icon au format PNG 64x64 vers';
$lang['note_steam_auto'] = '<b>Note:</b> Pour les jeux basés sur sur Steam, laissez le champ du dossier vide pour utiliser l auto-installateur de Steam';

// File Manager
$lang['new_filename'] = 'Nouveau nom de fichier';
$lang['new_dirname'] = 'Nouveau nom de dossier';

// User perms
$lang['access_ftp'] = 'Accés FTP';
$lang['update_usr_det'] = 'Mettre à jour les details de l utilisateur';
$lang['user_exists'] = 'Désolé, ce nom d utilisateur existe déja !';

// Home Page hints
$lang['def_adm_step'] = 'Etape';
$lang['def_adm_tip_docs'] = 'Veuillez regarder la documentation complète';
$lang['def_adm_tip_accts'] = 'None found! You should create a user account to create servers.';
$lang['def_adm_tip_net'] = 'Aucun serveur réseau trouvé ! Vous devez en créer un maintenant';
$lang['def_adm_tip_tpl'] = 'Aucun modèle complet trouvé ! Pour créer des serveur, vous devez choisir un jeu et';
$lang['def_adm_tip_srv1'] = 'Vous êtes prêt à créer un serveur !';
$lang['def_adm_tip_srv2'] = 'Complétez les étapes avant de créer un serveur de jeu / communication.';

// Other
$lang['api_key'] = 'Clef API';

##############################################################################################################

// 3.0.8
$lang['install_mirrors'] = 'Mirroirs d installation';
$lang['game_panel'] = 'Panneau de contrôle des jeux';
$lang['show_console_out'] = 'Cliquez ici pour voir la console';
$lang['config_file'] = 'Fichier de Config';
$lang['modified'] = 'Modifié';
$lang['accessed'] = 'Accedé';
$lang['size'] = 'Taille';
$lang['maxplayers'] = 'Nombre de joueurs max';
$lang['hostname'] = 'Nom d hôte';

?>
