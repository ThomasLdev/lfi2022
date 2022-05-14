<?php
/**
 * The header.
 * This is the template that displays all of the <head> section and everything up until content wrapper.
 */
?>
	<!doctype html>
<html <?php language_attributes(); ?>>
	<head>
		<meta charset="<?php bloginfo( 'charset' ); ?>"/>
		<meta name="viewport" content="width=device-width, initial-scale=1"/>
		<title>David Guiraud 2022 - <?php echo get_the_title() ?></title>
		<?php wp_head(); ?>
	</head>

<body <?php
switch (is_front_page()) {
	case true:
		body_class('front-page');
		break;
	case false:
		body_class('normal-page');
		break;
}
?>>

<?php wp_body_open(); ?>
