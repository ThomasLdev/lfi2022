<?php
/**
 * La configuration de base de votre installation WordPress.
 *
 * Ce fichier est utilisé par le script de création de wp-config.php pendant
 * le processus d’installation. Vous n’avez pas à utiliser le site web, vous
 * pouvez simplement renommer ce fichier en « wp-config.php » et remplir les
 * valeurs.
 *
 * Ce fichier contient les réglages de configuration suivants :
 *
 * Réglages MySQL
 * Préfixe de table
 * Clés secrètes
 * Langue utilisée
 * ABSPATH
 *
 * @link https://fr.wordpress.org/support/article/editing-wp-config-php/.
 *
 * @package WordPress
 */

// ** Réglages MySQL - Votre hébergeur doit vous fournir ces informations. ** //
/** Nom de la base de données de WordPress. */
define( 'DB_NAME', 'lfi2022' );

/** Utilisateur de la base de données MySQL. */
define( 'DB_USER', 'root' );

/** Mot de passe de la base de données MySQL. */
define( 'DB_PASSWORD', '' );

/** Adresse de l’hébergement MySQL. */
define( 'DB_HOST', 'localhost' );

/** Jeu de caractères à utiliser par la base de données lors de la création des tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/**
 * Type de collation de la base de données.
 * N’y touchez que si vous savez ce que vous faites.
 */
define( 'DB_COLLATE', '' );

/**#@+
 * Clés uniques d’authentification et salage.
 *
 * Remplacez les valeurs par défaut par des phrases uniques !
 * Vous pouvez générer des phrases aléatoires en utilisant
 * {@link https://api.wordpress.org/secret-key/1.1/salt/ le service de clés secrètes de WordPress.org}.
 * Vous pouvez modifier ces phrases à n’importe quel moment, afin d’invalider tous les cookies existants.
 * Cela forcera également tous les utilisateurs à se reconnecter.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         'T6$VUik:hn|xPpmWoO>$N@[a7,nDC@5>`Dy3Eg%Wx~Oi1o$<ZH.A;`=^/ae*hqby' );
define( 'SECURE_AUTH_KEY',  '6yGyIW:$S05IRmIb<]p; DPO+ZOy$y->O}]nW>+}A] U`ev[Fxwk}yl</k#poC*$' );
define( 'LOGGED_IN_KEY',    'GEE>&g=3I1H-(!ik6&39KHYAZw,/#q3DM3{z9jJCY5I<,ovcXd>(O(ghLGq6qraa' );
define( 'NONCE_KEY',        ':>;A0hWR :*18)*-PTWyIfcrRG5?5!<p=%`OhVpx?SgId~E@OF]Ffn-Az7$584.b' );
define( 'AUTH_SALT',        '77sSL/qoL,41VNyF^`?#cR^?BGk%iQTq[G$HovmAG)V@=aTexG8:w,8`1@[57ZGN' );
define( 'SECURE_AUTH_SALT', 'd}Ah8jW,PKlr!_B2`8h&{|qRymBB|P]z~mpWigu#H[:77Lm<lk)bZ_me -Eaa<;|' );
define( 'LOGGED_IN_SALT',   'L_`axUU&MA:;Ib,;s&BGsJvkod u}b8Lm8x>UiAT;F;H07>C$VGtB#uW4#Ea04/a' );
define( 'NONCE_SALT',       'P~p2>m>mBUlPv^Ru_l96vVXntFP#>s_S3HhQGY|Dyfb,4q.keaRz4>6?6q];Mqe`' );
/**#@-*/

/**
 * Préfixe de base de données pour les tables de WordPress.
 *
 * Vous pouvez installer plusieurs WordPress sur une seule base de données
 * si vous leur donnez chacune un préfixe unique.
 * N’utilisez que des chiffres, des lettres non-accentuées, et des caractères soulignés !
 */
$table_prefix = 'wp_';

/**
 * Pour les développeurs : le mode déboguage de WordPress.
 *
 * En passant la valeur suivante à "true", vous activez l’affichage des
 * notifications d’erreurs pendant vos essais.
 * Il est fortement recommandé que les développeurs d’extensions et
 * de thèmes se servent de WP_DEBUG dans leur environnement de
 * développement.
 *
 * Pour plus d’information sur les autres constantes qui peuvent être utilisées
 * pour le déboguage, rendez-vous sur le Codex.
 *
 * @link https://fr.wordpress.org/support/article/debugging-in-wordpress/
 */
define( 'WP_DEBUG', false );

/* C’est tout, ne touchez pas à ce qui suit ! Bonne publication. */

/** Chemin absolu vers le dossier de WordPress. */
if ( ! defined( 'ABSPATH' ) )
  define( 'ABSPATH', dirname( __FILE__ ) . '/' );

/** Réglage des variables de WordPress et de ses fichiers inclus. */
require_once( ABSPATH . 'wp-settings.php' );
