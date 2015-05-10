Build Status
---
[![Build Status](https://travis-ci.org/magento-hackathon/AdvancedAcl.svg?branch=master)](https://travis-ci.org/magento-hackathon/AdvancedAcl)

# Extended Access Control List for Magento

This extension allows merchants to restrict admin users to specific store views,
so it makes your Magento multitenant.

![image](https://raw.github.com/magento-hackathon/AdvancedAcl/master/docs/EditRole.png)

### What we can do

We currently support restrictions of Magento Core:

* system configuration,
* customers
* catalog, and
* sales

### What we can't do

There are lots of third party extensions for Magento and we can't support every
single of them, so we focused on Magento Core, allowing you (or your developer)
to extend it according to your needs.

## Installation

We recommend to use [Composer](http://getcomposer.org) to install this into your
Magento store, but you may also use
[modman](https://github.com/colinmollenhour/modman) or just copy the extension
into your Magento root.

Most of our code uses event/observer pattern to gain maximum compatibility, but
there are few rewrites, too (namely store switcher blocks). So make sure, your
other extensions do not override the same blocks.

Clean cache after installation.

## Usage

Open System → Permissions → Roles and select a role without full system access.
There you will find a new tab "Advanced ACL" in there, where you will be able to
restrict the role to some specific store views.

*If none is selected, the role access won't get limited.*

Regarding system configuration, users will be able to enter website level, if
they have access to all stores of the website. Furthermore, users will be able
to enter default level, if they have access to all stores of your Magento
installation.

Licence
-------
[OSL - Open Software Licence 3.0](http://opensource.org/licenses/osl-3.0.php)

Copyright
---------
(c) 2015
