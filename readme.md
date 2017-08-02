<p align="center">
    <img src="https://raw.githubusercontent.com/SerafimArts/Railgun/master/docs/resources/logo-big.png" alt="Railgun" />
</p>

<p align="center">
    <a href="https://travis-ci.org/SerafimArts/Railgun"><img src="https://travis-ci.org/SerafimArts/Railgun.svg?branch=master" alt="Travis CI" /></a>
    <a href="https://scrutinizer-ci.com/g/SerafimArts/Railgun/?branch=master"><img src="https://scrutinizer-ci.com/g/SerafimArts/Railgun/badges/quality-score.png?b=master" alt="Scrutinizer CI" /></a>
    <a href="https://scrutinizer-ci.com/g/SerafimArts/Railgun/?branch=master"><img src="https://scrutinizer-ci.com/g/SerafimArts/Railgun/badges/coverage.png?b=master" alt="Code coverage" /></a>
    <a href="https://packagist.org/packages/serafim/railgun"><img src="https://poser.pugx.org/serafim/railgun/version" alt="Latest Stable Version"></a>
    <a href="https://packagist.org/packages/serafim/railgun"><img src="https://poser.pugx.org/serafim/railgun/v/unstable" alt="Latest Unstable Version"></a>
    <a href="https://raw.githubusercontent.com/SerafimArts/Railgun/master/LICENSE"><img src="https://poser.pugx.org/serafim/railgun/license" alt="License MIT"></a>
</p>

> Not ready for real world usage yet :bomb: :scream:   

## Introduction

### Documentation

- [Documentation](https://serafimarts.github.io/Railgun) 
    - [Russian](https://serafimarts.github.io/Railgun/#/ru/)
    - [English](https://serafimarts.github.io/Railgun)
    
> This documentation contains information about the old version and is 
NOT RELEVANT at all. Im complete it later.

### About

This is a pure async PHP realization of the **GraphQL** protocol based on the 
[youshido/graphql](https://github.com/Youshido/GraphQL) and/or 
[webonyx/graphql-php](https://github.com/webonyx/graphql-php#fields)
core drivers of the official GraphQL Specification 
located on [Facebook GitHub](http://facebook.github.io/graphql/).

## How it works?

Below is a diagram for the life cycle of the instance.
You do not need to fully understand everything happening right now,
But this diagram will be useful in the future. 
The diagram is not complete and only the stage that is implemented 
at the moment is presented.

![https://serafimarts.github.io/Railgun/resources/lifecycle.png](https://serafimarts.github.io/Railgun/resources/lifecycle.png)

## Requirements

- PHP 7.1 or greater
    - ext-mbstring
- Composer
- GraphQL base driver:
    - or [webonyx/graphql-php (0.9+)](https://github.com/webonyx/graphql-php#fields)
    - or [youshido/graphql (1.4+)](https://github.com/Youshido/GraphQL)
- Frameworks:
    - Framework agnostic (Native PHP)
    - or [laravel/framework (5.1+)](https://github.com/laravel/framework)
    - or [symfony/symfony (2.8+)](https://github.com/symfony/symfony)

## Installation

- `composer require serafim/railgun`

## Usage

Not yet.

...you can see the `./tests` if you're curious =)
