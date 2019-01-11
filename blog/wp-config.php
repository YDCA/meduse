<?php
/**
 * La configuration de base de votre installation WordPress.
 *
 * Ce fichier contient les réglages de configuration suivants : réglages MySQL,
 * préfixe de table, clés secrètes, langue utilisée, et ABSPATH.
 * Vous pouvez en savoir plus à leur sujet en allant sur
 * {@link http://codex.wordpress.org/fr:Modifier_wp-config.php Modifier
 * wp-config.php}. C’est votre hébergeur qui doit vous donner vos
 * codes MySQL.
 *
 * Ce fichier est utilisé par le script de création de wp-config.php pendant
 * le processus d’installation. Vous n’avez pas à utiliser le site web, vous
 * pouvez simplement renommer ce fichier en "wp-config.php" et remplir les
 * valeurs.
 *
 * @package WordPress
 */

// ** Réglages MySQL - Votre hébergeur doit vous fournir ces informations. ** //
/** Nom de la base de données de WordPress. */
define('DB_NAME', 'medusewp_');

/** Utilisateur de la base de données MySQL. */
define('DB_USER', 'medusewp');

/** Mot de passe de la base de données MySQL. */
define('DB_PASSWORD', 'nQn*4r66');

/** Adresse de l’hébergement MySQL. */
define('DB_HOST', 'localhost');

/** Jeu de caractères à utiliser par la base de données lors de la création des tables. */
define('DB_CHARSET', 'utf8');

/** Type de collation de la base de données.
  * N’y touchez que si vous savez ce que vous faites.
  */
define('DB_COLLATE', '');

/**#@+
 * Clés uniques d’authentification et salage.
 *
 * Remplacez les valeurs par défaut par des phrases uniques !
 * Vous pouvez générer des phrases aléatoires en utilisant
 * {@link https://api.wordpress.org/secret-key/1.1/salt/ le service de clefs secrètes de WordPress.org}.
 * Vous pouvez modifier ces phrases à n’importe quel moment, afin d’invalider tous les cookies existants.
 * Cela forcera également tous les utilisateurs à se reconnecter.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         'O+pfcbV,kpgz7b7=w]eN3V$A[;lt8e*Xpy>X#BHm`$P1c;>GTQ$oZz,0qKi{Rpr,');
define('SECURE_AUTH_KEY',  '[q4SD7y9#*vYJlY8cm#Qbh6>]7%ql<$fi_,MA6)Ojq($7`NC]9z9]Vd7n^`VY7WR');
define('LOGGED_IN_KEY',    'f/exNj>wo~1~$T*@S f:<hqEkb9K=590C@KDsBNqMDn7S0q^UO4[70]zY-^..]:n');
define('NONCE_KEY',        '8+py&cK`0PXJ3jzUY@y@L{-{eBQ;u%9Hp&<`A9GLjH2hJ^&6r}CQ]}.D48$G?4x!');
define('AUTH_SALT',        'zqdWvRut4Q1F1yN2H7V5|Jv%xDzfv$fa{kCg!=ow(it$x~1ZRIgqulTUkJ513#7<');
define('SECURE_AUTH_SALT', 'd],9tZZ&y[3<-VY5&v?EGpdyMatcKg8FX+Ba$1?cjO&qP/f4I3LbO$[*$Dweuyeo');
define('LOGGED_IN_SALT',   'sY.1i8!+yuvpmo7`MXDEO$[wHa;{Lnj<-BD+d^`E^]XB7ZB5x>cN D]UNFty]d@b');
define('NONCE_SALT',       '(3NALD}CY:!-e>#Po<)sgf2{J_G?h#sBu8*!VM@:n0Kab!:MI5RF+jY:Ai`2$hwR');
/**#@-*/

/**
 * Préfixe de base de données pour les tables de WordPress.
 *
 * Vous pouvez installer plusieurs WordPress sur une seule base de données
 * si vous leur donnez chacune un préfixe unique.
 * N’utilisez que des chiffres, des lettres non-accentuées, et des caractères soulignés !
 */
$table_prefix  = 'wp_';

/**
 * Pour les développeurs : le mode déboguage de WordPress.
 *
 * En passant la valeur suivante à "true", vous activez l’affichage des
 * notifications d’erreurs pendant vos essais.
 * Il est fortemment recommandé que les développeurs d’extensions et
 * de thèmes se servent de WP_DEBUG dans leur environnement de
 * développement.
 *
 * Pour plus d’information sur les autres constantes qui peuvent être utilisées
 * pour le déboguage, rendez-vous sur le Codex.
 *
 * @link https://codex.wordpress.org/Debugging_in_WordPress
 */
define('WP_DEBUG', false);

/* C’est tout, ne touchez pas à ce qui suit ! */

/** Chemin absolu vers le dossier de WordPress. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Réglage des variables de WordPress et de ses fichiers inclus. */
require_once(ABSPATH . 'wp-settings.php');