<p align="center">
    <img src="https://raw.githubusercontent.com/railt/docs/master/resources/logo-big-white-bg.png" alt="Railt" />
</p>

<p align="center">
    <a href="https://travis-ci.org/railt/railt"><img src="https://travis-ci.org/railt/railt.svg?branch=master" alt="Travis CI" /></a>
    <a href="https://scrutinizer-ci.com/g/railt/railt/?branch=master"><img src="https://scrutinizer-ci.com/g/railt/railt/badges/quality-score.png?b=master" alt="Scrutinizer CI" /></a>
    <a href="https://scrutinizer-ci.com/g/railt/railt/?branch=master"><img src="https://scrutinizer-ci.com/g/railt/railt/badges/coverage.png?b=master" alt="Code coverage" /></a>
    <a href="https://packagist.org/packages/serafim/railt"><img src="https://poser.pugx.org/serafim/railt/version" alt="Latest Stable Version"></a>
    <a href="https://packagist.org/packages/serafim/railt"><img src="https://poser.pugx.org/serafim/railt/v/unstable" alt="Latest Unstable Version"></a>
    <a href="https://raw.githubusercontent.com/railt/railt/master/LICENSE"><img src="https://poser.pugx.org/serafim/railt/license" alt="License MIT"></a>
</p>

> Not ready for real world usage yet :bomb: :scream:   

## Introduction

This is a pure async PHP realization of the **GraphQL** protocol based on the 
[youshido/graphql](https://github.com/Youshido/GraphQL) and/or 
[webonyx/graphql-php](https://github.com/webonyx/graphql-php#fields)
core drivers of the official GraphQL Specification 
located on [Facebook GitHub](http://facebook.github.io/graphql/).

- [Documentation](https://railt.github.io/docs) 
    - [Russian](https://railt.github.io/docs/#/ru/)
    - [English](https://railt.github.io/docs)
    
> This documentation contains information about the old version and is 
NOT RELEVANT at all. Im complete it later.

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

- `composer require railt/railt`
