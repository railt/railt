<p align="center">
    <a href="https://railt.org"><img src="https://avatars.githubusercontent.com/u/31258828?s=140" width="140" alt="Railt" /></a>
</p>
<p align="center">
    <a href="https://github.com/railt/railt/actions?workflow=Testing"><img src="https://github.com/railt/railt/workflows/tests/badge.svg" alt="Testing" /></a>
    <a href="https://packagist.org/packages/railt/railt"><img src="https://img.shields.io/badge/PHP-^8.1-6f4ca5.svg" alt="PHP ^8.1"></a>
    <a href="https://railt.org"><img src="https://img.shields.io/badge/official-site-6f4ca5.svg" alt="railt.org"></a>
    <a href="https://discord.gg/ND7SpD4"><img src="https://img.shields.io/badge/discord-chat-6f4ca5.svg" alt="Discord"></a>
    <a href="https://packagist.org/packages/railt/railt"><img src="https://poser.pugx.org/railt/railt/version" alt="Latest Stable Version"></a>
    <a href="https://packagist.org/packages/railt/railt"><img src="https://poser.pugx.org/railt/railt/downloads" alt="Total Downloads"></a>
    <a href="https://raw.githubusercontent.com/railt/railt/master/LICENSE.md"><img src="https://poser.pugx.org/railt/railt/license" alt="License MIT"></a>
</p>

## Introduction

Project idea is clean and high-quality code.

Unlike most (all at the moment) implementations, like [webonyx](https://github.com/webonyx/graphql-php),
[youshido](https://github.com/youshido-php/GraphQL) or [digitalonline](https://github.com/digiaonline/graphql-php)
the Railt contains a completely own implementation of the GraphQL SDL parser
which is based on [EBNF-like grammar](https://github.com/railt/railt/tree/master/libs/sdl/resources/grammar). 
This opportunity allows not only to have the 
[original implementation of the language](https://facebook.github.io/graphql/draft/) and to
keep it always up to date, but also to implement [a new backward compatible
functionality](https://github.com/railt/railt/projects/1) that is not available
to other implementations.

Goal of Railt:
- Do not repeat the mistakes made in the JS-based implementations.
- Implement a modern and convenient environment for PHP developers.
- Implement easy integration into any ready-made solutions based on PSR.
- Provide familiar functionality (including dependency injection, routing, etc.).

## Installation

- `composer require railt/railt`

## Quick start

The documentation is in the process of writing, therefore, 
in order to understand how it works, a quick start.

// TODO

## Learning Railt

// TODO

## Contributing

Thank you for considering contributing to the Railt Framework! 
The contribution guide can be found in the [documentation](https://railt.org/docs/contributions).

## Security Vulnerabilities

If you discover a security vulnerability within Railt, please send an e-mail to maintainer 
at nesk@xakep.ru. All security vulnerabilities will be promptly addressed.

## License

The Railt Framework is open-sourced software licensed under 
the [MIT license](https://opensource.org/licenses/MIT).

[![FOSSA Status](https://app.fossa.io/api/projects/git%2Bgithub.com%2Frailt%2Frailt.svg?type=large)](https://app.fossa.io/projects/git%2Bgithub.com%2Frailt%2Frailt?ref=badge_large)

## Help & Community [![Discord](https://img.shields.io/badge/discord-chat-6f4ca5.svg)](https://discord.gg/ND7SpD4)

Join our [Discord community](https://discord.gg/ND7SpD4) if you run into issues or have questions. We love talking to you!

<p align="center"><a href="https://discord.gg/ND7SpD4"><img src="https://habrastorage.org/webt/mh/s4/hg/mhs4hg2eb0roaix7igak0syhcew.png" /></a></p>

## Supported By

<p align="center">
    <a href="https://www.jetbrains.com/" target="_blank"><img src="https://phplrt.org/img/thanks/jetbrains.svg" alt="JetBrains" /></a>
</p>
