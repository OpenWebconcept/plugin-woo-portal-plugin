# WOO Portal Plugin

This plugin adds a Gutenberg block that implement the WOO Portal into the WordPress block editor.

## Requirements

### OpenPub

In order to make the WOO Portal Plugin work, you will need to have a OpenPub installation with at least the following installed (and activaterd):

* [WordPress](https://wordpress.org/)
* [CMB2](https://wordpress.org/plugins/cmb2/)
* [OpenPub Base](https://github.com/OpenWebconcept/plugin-openpub-base)
* [OpenWoo](https://github.com/OpenWebconcept/plugin-openwoo)
* [OpenConvenanten](https://github.com/OpenWebconcept/plugin-openconvenanten)
* [OpenPub Portal](https://github.com/OpenWebconcept/plugin-openpub-portal)

On this OpenPub installation you will have to enable pretty permalinks (Settings > Permalinks > Select any of the options that is not plain). And under Settings > OpenPub Portal, you will need to fill in the portal url.

### WOO Portal

Now you have set up your OpenPub installation, you can set up the WOO Portal. There are three possible setups for the WOO Portal, this can be:

1. On the WordPress installation of an existing website.
2. On the same installation as the OpenPub. 
3. On a completely new WordPress installation. 

In all three scenarios the WOO Portal needs to have the following installed (and activated):

* [WordPress](https://wordpress.org/)
* [CMB2](https://wordpress.org/plugins/cmb2/)
* [Woo Portal Plugin](https://github.com/OpenWebconcept/plugin-woo-portal-plugin)

With this installed you can use the WOO Portal Search block on any page of your WordPress website. With this block there are several settings that need to be set in order for the block to work correctly.

If you chose for option 2 (same installation as the OpenPub) or option 3 (new WordPress installation), you will probably need to install a WordPress theme.. You can use the [WOO Portal Theme](https://github.com/OpenWebconcept/theme-woo-portal-theme), which is a simple theme for a WOO Portal. This theme provides several settings:

* Under Appearance > Customize > WOO Portal Theme, you can set the WOO Portal Colors.
* Under Theme Options there are several settings that need to be set.

## Installation

### Manual installation

1. Upload the `woo-portal-plugin` folder to the `/wp-content/plugins/` directory.
2. `cd /wp-contents/plugins/woo-portal-plugin`
3. `npm install && npm run build`
4. Activate the WOO Portal Plugin through the 'Plugins' menu in WordPress.

### Composer installation

1. `composer source git@github.com:OpenWebconcept/plugin-woo-portal-plugin.git`
2. `composer require acato/woo-portal-plugin`
3. `cd /wp-contents/plugins/woo-portal-plugin`
4. `npm install && npm run build`
5. Activate the WOO Portal Plugin through the 'Plugins' menu in WordPress.

## Development

### Coding Standards

Please remember, we use the WordPress PHP Coding Standards for this plugin! (https://make.wordpress.org/core/handbook/best-practices/coding-standards/php/) To check if your changes are compatible with these standards:

*  `cd /wp-content/plugins/woo-portal-plugin`
*  `composer install` (this step is only needed once after installing the plugin)
*  `./vendor/bin/phpcs --standard=phpcs.xml.dist .`
*  See the output if you have made any errors.
    *  Errors marked with `[x]` can be fixed automatically by phpcbf, to do so run: `./vendor/bin/phpcbf --standard=phpcs.xml.dist .`

N.B. the `composer install` command also install a git hook, preventing you from committing code that isn't compatible with the coding standards.

### Translations
```
wp i18n make-pot . languages/woo-portal-plugin.pot --exclude="node_modules/,vendor/" --domain="woo-portal-plugin"
```

```
cd languages && wp i18n make-json woo-portal-plugin-nl_NL.po --no-purge
```

### Helpful Hints

For development, make sure you whitelist your localhost.
```
add_filter( 'http_request_host_is_external', function( $is_external, $host, $url ) { return $is_external || 'your-development-site.test' === $host; }, 10, 3 );
```
