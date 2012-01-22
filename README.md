WMenu - A menu for PHP websites
===============================
Version 2.0
-------------------------------

Disclaimer: this project is quite old and it may have problems on
modern configurations. For example it wasn't tested at all on PHP5.

1 - Purpose
-----------
A new menu system is usually developed for each site. But it's clearly
smarter to develop only one, flexible enough to accommodate many
configurations. Using menus is commonplace, providing an independant
fully-featured library for this task is the goal of this project.

2 - Key features
--------------------
- Written in XHTML1/CSS
- a descriptive configuration file
- ability to detect its location (but it can be overridden)
- accessible

3 - Installation
----------------
Wmenu can be installed server-wide or for a particular site.

### Install on a single site
- To install, copy the files `utils.inc.php` and `wmenu.class.inc.php`, and also
 `empty.inc.php` if needed, to your project's include directory.
 This directory must be in PHP's variable `include_path`, which can be modified
 in an `.htaccess` as follows :

```
php_value include_path "include directory:/usr/share/pear:."
```

- Copy both `stylemenu.css` and `styleie.css` in the main directory where
 your other style files are located, and import the first one in your main
 style file, whose name must be `style.css` :

```css
@import url(stylemenu.css);
```

- Finally, you can copy the file `empty.php` to your site's root directory, if
 you have to use it.

### Several sites on the same server
Instead of installing the menu once per site, you can do a server-wide install
in a common directory, with the `include_path` variable set as follows :

    php_value include_path "specific include directory:general include directory:/usr/share/pear:."

Moreover, CSS files could be linked to a common CSS from a common directory;
this makes updates easier.

4 - Configuration
-----------------
WMenu is configured using a single file: `menuconf.inc.php`. This file must be
in one of `include_path`'s include directories.

This file defines a PHP class, for example `SiteConfiguration`. This class
contains four variables :
- `$base_url` defines the site URL's base directory

- `$site_title` defines the site's name, which will be displayed in the header

- `$texts` is an associative array, and defines some displayed strings. As of now,
  there are only two keys :
  + `main` : title of the link to the main content
  + `menu` : title of the link to the menu
  
- `$menu` is a tree, done using arrays, and defines the menu itself.
  + Each key is an item's title, and the depth defines the current item's
    depth in the menu. Each title belongs to a 2-element array: the first one is
    a "page", the second one is another "node".
  + An element "page" is either a string defining an HTML link, or an array
    containing several elements "page". If it's an array, then the generated
    link will point to its first element, but each of these "pages" will highlight
    this item.
  + An element "node" is like another tree.
  + A HTML link can be relative to the current page, or absolute.
    It can be prefixed by 1 or several special characters (see below).

- Special characters :
  + `!` : open a new window
  + `+` : emphasize
  + `@` : a click on this item will do nothing

- If the link ("page") is an empty string, then :
  + if it's a submenu, it will go to the empty.php page, so as to open the
    submenu.
  + if it's a leaf item, it will do nothing (exactly like the special
    characters @).

You can look at the example file (in French) if I wasn't clear enough.

5 - Using WMenu
---------------

### a - Basic use

Each PHP script should begin like that :

```php
<?php
require_once ("wmenu.class.inc.php");
require_once ("menuconf.inc.php");

$menu = new WMenu (new SiteConfiguration);
$menu->calculate();
?>
```

The library will internally generate the HTML menu.
Then, the method `header()` returns the page's header :

```php
<?php
print $menu->header();
?>
```

Similarly, the methode `footer()` returns the page's footer :

<?php
print $menu->footer();
?>

### b - Changing the way it works

WMenu's behaviour can be changed in two ways.

- Highlighting a specific menu item. Doing this is as simple as calling the
  method setMenuType before calculate :

```php
<?php
$menu = new WMenu (new SiteConfiguration);
$menu->setMenuType(MENU_TYPE_TITLE, 'Title');
$menu->calculate();
?>
```

    The constant MENU_TYPE_TITLE defines a calcul by title, whereas the default
    constant MENU_TYPE_URL calculates using the page's URL.

- Displaying the menu in the page's header, instead of its footer. It is
  defined in `menuconfig.inc.php`, using the variable `$menu_place`.
  By default, the menu will appear in the footer, which is friendlier for
  text-based browsers, since the content will be displayed first.
  
    The possible constants are :
  + `'footer'` (by default) to show in the footer ;
  + `'header'` to show in the header ;
  + `'none'` to not show it at all.

6 - Customizing
---------------
Since the menu is using valid XHTML, the whole look is defined in the CSS
file `stylemenu.css` (and also in `styleie.css`, for the styles specific to
Internet Explorer). Therefore, it's easy to match your site's look and feel,
simply by modifying this file.

If you want to change the class behaviour, it's possible to directly modify
the methods `header()` and `footer()`. It's also possible to extend the class
WMenu, since we're using object-oriented PHP :-)

Moreover, the object passed to WMenu's constructor isn't necessarily static.
It can be dynamically built, which is useful for large sites with several
sections, each with its own particular menu.

