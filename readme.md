# Widgets

[![Build Status](https://img.shields.io/travis/iPublikuj/widgets.svg?style=flat-square)](https://travis-ci.org/iPublikuj/widgets)
[![Scrutinizer Code Coverage](https://img.shields.io/scrutinizer/coverage/g/iPublikuj/widgets.svg?style=flat-square)](https://scrutinizer-ci.com/g/iPublikuj/widgets/?branch=master)
[![Scrutinizer Code Quality](https://img.shields.io/scrutinizer/g/iPublikuj/widgets.svg?style=flat-square)](https://scrutinizer-ci.com/g/iPublikuj/widgets/?branch=master)
[![Latest Stable Version](https://img.shields.io/packagist/v/ipub/widgets.svg?style=flat-square)](https://packagist.org/packages/ipub/widgets)
[![Composer Downloads](https://img.shields.io/packagist/dt/ipub/widgets.svg?style=flat-square)](https://packagist.org/packages/ipub/widgets)

Dynamic page content components alias **widgets** manager for [Nette Framework](http://nette.org/)

## Introduction

This extensions add ability to display small webpage parts called widgets (like in WordPress) in your application based on Nette framework

* Collects all site widgets which could be created by you or downloaded
* Render each widget in way you want

## Installation

The best way to install ipub/widgets is using [Composer](http://getcomposer.org/):

```sh
$ composer require ipub/widgets
```

After that you have to register extension in config.neon.

```neon
extensions:
	widgets: IPub\Widgets\DI\WidgetsExtension
```

## Documentation

Learn how to use widgets manager for custom widgets in [documentation](https://github.com/iPublikuj/widgets/blob/master/docs/en/index.md).

***
Homepage [http://www.ipublikuj.eu](http://www.ipublikuj.eu) and repository [http://github.com/iPublikuj/widgets](http://github.com/iPublikuj/widgets).
