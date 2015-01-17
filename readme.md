# Widgets manager

Widgets manager for [Nette Framework](http://nette.org/)

## Introduction

This extensions add ability to display small webpage parts called widgets (like in WordPress) in your application based on Nette framework

* Collects all site widgets which could be created by you or downloaded
* Render each widget in way you want

## Installation

The best way to install ipub/widgets-manager is using  [Composer](http://getcomposer.org/):

```json
{
	"require": {
		"ipub/widgets-manager": "dev-master"
	}
}
```

or

```sh
$ composer require ipub/widgets-manager:@dev
```

After that you have to register extension in config.neon.

```neon
extensions:
	widgetsManager: IPub\WidgetsManager\DI\WidgetsManagerExtension
```
