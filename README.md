Wix Framework Bundle
========================

The Wix framework bundle provides an easy, powerful way to develop power Wix application for the [Wix.com HTML5 editor](http://www.wix.com) using the Symfony2 framework.
 

[![Build Status](https://secure.travis-ci.org/ranm8/WixFrameworkBundle.png?branch=master)](http://travis-ci.org/ranm8/WixFrameworkBundle)

Installing the Wix Framework Bundle
------------------------------------

Installing the Wix framework bundle is easy and can be achieved using composer.

### Set WixFrameworkBundle as dependancy 

For including WixFrameworkBundle as dependancy for your Symfony2 application just add the following row to your composer.json (within Symfony2 application root) file:

    "require": {
		"wix/framework-bundle": "dev-master"
	}

Then, run composer:

    composer.phar update wix/framework-bundle

Add the following lines to app/AppKernel.php

    $bundles = array(
        ...
        new Wix\FrameworkBundle\WixFrameworkBundle(),
        ...
    );

Add you Wix app keys (You can get them by [Creating Wix app via Wix Dev Center](http://dev.wix.com) to config.yml :

    wix_framework:
      keys:
        application_key: 12e09531-deff-498d-c7e4-782fcfc2c88a
        application_secret: c20e90e6-0a53-49b2-9705-20b1dfbea22e

What's inside?
---------------

### The Wix Controller

### Security Annotations

### Wix Instance Decoding

### Built-in MongoDB Wix Document Management

### Settings and View support

### Wix Twig Filters

### Wix Debug Toolbar

### Fully Unit-tested