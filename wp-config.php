<?php

// Configuration common to all environments
include_once __DIR__ . '/wp-config.common.php';

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
define('DB_NAME', 'coteouespress');

/** Utilisateur de la base de données MySQL. */
define('DB_USER', 'root');

/** Mot de passe de la base de données MySQL. */
define('DB_PASSWORD', '');

/** Adresse de l’hébergement MySQL. */
define('DB_HOST', 'localhost');

/** Jeu de caractères à utiliser par la base de données lors de la création des tables. */
define('DB_CHARSET', 'utf8mb4');

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
define('AUTH_KEY',         '%=!M9Q5^gyIQjLgJPvS2fc&2]SdrnI$N#~,=@M:LdtS%S#u:wnDIG2&T$3LhHlA@');
define('SECURE_AUTH_KEY',  '06_C)WEj[[<zo8lQtfxcPf2o]t2xI?qE@j)aNuz5/wY~fx`]en7A_ h9G|}>GJe#');
define('LOGGED_IN_KEY',    'TCi|(=R<VC=/aoae&=blgR=}]@Qy>D>mNAtJl*u2E3[wCJ0b`v+#|~bfVvey}J2|');
define('NONCE_KEY',        '=MF6E%W2hF^%7}}F)w;T|RcaF#MP1^{  1qM&Y|I<@90{DC)>F;jypAJ`]sK<9Y4');
define('AUTH_SALT',        '>ly=$TmyuL|fr;]RnQGz4I34jUj34tG*=r@E,VpA0E62]5HI ]I/wG6QA7=hUI+P');
define('SECURE_AUTH_SALT', 'x_%[IGLW7gnT?DxG99~Kmn6KeJTQ:CM_dn(Z^RSH2XE9I%?Boj$U?(|FS6Pc43`q');
define('LOGGED_IN_SALT',   'K1xIi-YF0mN}SGI.)z+Dm>X?ywBxWG81o1I9i*F?n8O/Nj54D~~qfZwhb!>e6+NM');
define('NONCE_SALT',       '_;IPTi&bELG.G%*]f0P5=f R>Y1ijDjt-^x_p%#X*t||!lX%`ev88Q4@ZRhi&R_O');
/**#@-*/

/**
 * Préfixe de base de données pour les tables de WordPress.
 *
 * Vous pouvez installer plusieurs WordPress sur une seule base de données
 * si vous leur donnez chacune un préfixe unique.
 * N’utilisez que des chiffres, des lettres non-accentuées, et des caractères soulignés !
 */
$table_prefix  = 'coa_';

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

/* C’est tout, ne touchez pas à ce qui suit !
define('MULTISITE', true);
define('SUBDOMAIN_INSTALL', false);
define('DOMAIN_CURRENT_SITE', 'coteouest.local');
define('PATH_CURRENT_SITE', '/');
define('SITE_ID_CURRENT_SITE', 1);
define('BLOG_ID_CURRENT_SITE', 1);
Chemin absolu vers le dossier de WordPress. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Réglage des variables de WordPress et de ses fichiers inclus. */
require_once(ABSPATH . 'wp-settings.php');