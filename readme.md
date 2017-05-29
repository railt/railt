<p align="center">
    <img src="https://habrastorage.org/web/ced/5e8/bcb/ced5e8bcbc324b15964a7b3eb805aaf5.png"" alt="Railgun" />
</p>

<p align="center">
    <a href="https://travis-ci.org/SerafimArts/Railgun">
        <img src="https://travis-ci.org/SerafimArts/Railgun.svg?branch=master" alt="Travis CI" />
    </a>
    <a href="https://scrutinizer-ci.com/g/SerafimArts/Railgun/?branch=master">
        <img src="https://scrutinizer-ci.com/g/SerafimArts/Railgun/badges/quality-score.png?b=master" alt="Scrutinizer CI" />
    </a>
    <a href="https://scrutinizer-ci.com/g/SerafimArts/Railgun/?branch=master">
        <img src="https://scrutinizer-ci.com/g/SerafimArts/Railgun/badges/coverage.png?b=master" alt="Code coverage" />
    </a>
    <a href="https://packagist.org/packages/serafim/railgun">
        <img src="https://poser.pugx.org/serafim/railgun/version" alt="Latest Stable Version">
    </a>
    <a href="https://packagist.org/packages/serafim/railgun">
        <img src="https://poser.pugx.org/serafim/railgun/v/unstable" alt="Latest Unstable Version">
    </a>
    <a href="https://raw.githubusercontent.com/SerafimArts/Railgun/master/LICENSE">
        <img src="https://poser.pugx.org/serafim/railgun/license" alt="License MIT">
    </a>
</p>

> Not ready for real world usage yet :bomb: :scream:   

## Introduction

[Documentation](https://serafimarts.github.io/Railgun) 
([Russian](https://serafimarts.github.io/Railgun/#/ru/), [English](https://serafimarts.github.io/Railgun))

-------------

This is a pure async PHP realization of the **GraphQL** protocol based on the 
[youshido/graphql](https://github.com/Youshido/GraphQL) and/or 
[webonyx/graphql-php](https://github.com/webonyx/graphql-php#fields)
core drivers of the official GraphQL Specification 
located on [Facebook GitHub](http://facebook.github.io/graphql/).

**GraphQL** is a modern replacement of the almost obsolete **REST** approach to present API. 
It's been almost 16 years since the **REST** idea was found in 2000 by Roy Fielding. 
With all credit to everything we accomplished using REST it's time to change for 
something better. **GraphQL** advanced in many ways and has fundamental 
improvements over the old good **REST**:

- Self-checks embedded on the ground level of your backend architecture
- Reusable API for different client versions and devices, i.e. no more need in maintaining "/v1", "/v2" or "/v10002545.07E20"
- A complete new level of distinguishing of the backend and frontend logic
- Easily generated documentation and incredibly intuitive way to explore created API
- Once your architecture is complete – most client-based changes does not require backend modifications
- It could be hard to believe but give it a try and you'll be rewarded with much better architecture and so much easier to support code.

## Requirements

- PHP 7.1+
- Composer
- GraphQL base driver (one of):
    - [webonyx/graphql-php (0.9+)](https://github.com/webonyx/graphql-php#fields)
    - TODO: [youshido/graphql (1.4+)](https://github.com/Youshido/GraphQL)
- Frameworks (one of):
    - [laravel/framework (5.1+)](https://github.com/laravel/framework)
    - [symfony/symfony (2.8+)](https://github.com/symfony/symfony)
    - or nothing (native php)
    - TODO: [reactphp/http](https://github.com/reactphp/http)
    - TODO (?): [zendframework/zendframework](https://github.com/zendframework/zendframework)
    - TODO (?): [appserver-io/appserver](https://github.com/appserver-io/appserver)
    - TODO (?): [php-pm/php-pm](https://github.com/php-pm/php-pm)

## Installation

- `composer require serafim/railgun`
