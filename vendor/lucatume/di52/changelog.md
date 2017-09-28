# Change Log
All notable changes to this project will be documented in this file.
This project adheres to [Semantic Versioning](http://semver.org/).

## [Unreleased]

## [2.0.9] - 2017-09-26
### Fixed
- issue with `setVar` method where, in some instances, variable values could not be overridden

## [2.0.8] - 2017-07-18
### Fixed
- check for file existence in autoload script (thanks @truongwp)

## [2.0.7] - 2017-06-15
### Fixed
- issue where non registered classes object dependencies would be built just the first time (issue #2)

## [2.0.6] - 2017-05-09
### Fixed
- fix handling of unbound interface arguments

## [2.0.5] - 2017-02-22
### Changed
- change internal method visibility to improve compatibility with monkey patching libraries

## [2.0.4] - 2017-02-22
### Fixed
- allow unbound classes with `__construct` method requirements to be used in `instance` callbacks

## [2.0.3] - 2017-02-07
### Fixed
- support for use of `callback` to feed `instance` and viceversa

## [2.0.2] - 2017-02-02
### Fixed
- support for built objects in `instance` and `callback` methods

## [2.0.1] - 2017-01-23
### Fixed
- an issue where re-binding implementations could lead to built objects still using previous bindings

#### Changed
- removed some dead code left over from previous iterations

## [2.0.0] - 2017-01-21
### Added
- `instance` and `callback` methods

### Changed
- refactored the code completely
- the README file to update it to the new code

### Removed
- support for array based construction instructions (see `instance` methods)

## [1.4.5] - 2017-01-19
### Fixed
- an issue where singleton resolution would result in circular reference on some Windows versions (thanks @bordoni)

## [1.4.4] - 2017-01-09
### Added
- support for binding replacement

## [1.4.3] - 2016-10-18
### Changed
- snake_case method names are now set to camelCase

### Fixed
- an inheritance issue on PHP 5.2
- non PHP 5.2 compatible tests

### Added
- Travis CI support and build

## [1.4.2] - 2016-10-14
### Fixed
- nested dependency resolution issue with interfaces and default values

## [1.4.1b] - 2016-10-14
### Fixed
- pass the `afterBuildMethods` argument along...

## [1.4.1] - 2016-10-14
### Fixed
- updated `tad_di512_Container` `bind` and `singleton` methods signatures

## [1.4.0] - 2016-10-14
### Added
- more informative exception message when trying to resolve unbound slug or non existing class
- support for after build methods

### Fixed
- another nested dependency resolving issue

## [1.3.2] - 2016-07-28
### Fixed
- nested dependency resolving issue

## [1.3.1] - 2016-04-19
### Added
- more informative exception message when default primitive value is missing

## [1.3.0] - 2016-04-19
### Added
- support for the custom bindings
- support for same class singleton binding

### Changed
- performance optimization

## [1.2.6] - 2016-04-11
### Changed
- internal workings to improve performance (using [@TomBZombie benchmarks](https://github.com/TomBZombie/php-dependency-injection-benchmarks)

## [1.2.5] - 2016-03-06
### Added
- support for decorator pattern in PHP 5.2 compatible syntax
- code highlighting for code examples in doc (thanks @omarreiss)

## [1.2.4] - 2016-03-05
### Added
- tests for uncovered code

## [1.2.3] - 2016-03-04
### Fixed
- singleton resolution for same implementations

## [1.2.2] - 2016-02-13
- doc updates

## [1.2.1] - 2016-02-13
### Added
- `hasTag($tag)` method to the container
- `isBound($classOrInterface)` method to the container
- support for deferred service providers

## [1.2.1] - 2016-01-23
### Added
- tagging support
- service providers support 

## [1.2.0] - 2016-01-22
### Added
- the binding and automatic resolution API ([code inspiration](https://www.ltconsulting.co.uk/automatic-dependency-injection-with-phps-reflection-api/))

## [1.1.2] - 2016-01-19
### Fixed
- resolution for objects in arrays

## [1.1.1] - 2016-01-19
### Added
- support for the `%varName%` variable notation.

## [1.1.0] - 2016-01-18
### Added
- array resolution support for the Array Access API.
- the changelog.

[Unreleased]: https://github.com/lucatume/di52/compare/2.0.9...HEAD
[2.0.9]: https://github.com/lucatume/di52/compare/2.0.8...2.0.9
[2.0.8]: https://github.com/lucatume/di52/compare/2.0.7...2.0.8
[2.0.7]: https://github.com/lucatume/di52/compare/2.0.6...2.0.7
[2.0.6]: https://github.com/lucatume/di52/compare/2.0.5...2.0.6
[2.0.5]: https://github.com/lucatume/di52/compare/2.0.4...2.0.5
[2.0.4]: https://github.com/lucatume/di52/compare/2.0.3...2.0.4
[2.0.3]: https://github.com/lucatume/di52/compare/2.0.2...2.0.3
[2.0.2]: https://github.com/lucatume/di52/compare/2.0.1...2.0.2
[2.0.1]: https://github.com/lucatume/di52/compare/2.0.0...2.0.1
[2.0.0]: https://github.com/lucatume/di52/compare/1.4.5...2.0.0
[1.4.5]: https://github.com/lucatume/di52/compare/1.4.4...1.4.5
[1.4.4]: https://github.com/lucatume/di52/compare/1.4.3...1.4.4
[1.4.3]: https://github.com/lucatume/di52/compare/1.4.2...1.4.3
[1.4.2]: https://github.com/lucatume/di52/compare/1.4.1b...1.4.2
[1.4.1b]: https://github.com/lucatume/di52/compare/1.4.1...1.4.1b
[1.4.1]: https://github.com/lucatume/di52/compare/1.4.0...1.4.1
[1.4.0]: https://github.com/lucatume/di52/compare/1.3.1...1.4.0
[1.3.2]: https://github.com/lucatume/di52/compare/1.3.1...1.3.2
[1.3.1]: https://github.com/lucatume/di52/compare/1.3.0...1.3.1
[1.3.0]: https://github.com/lucatume/di52/compare/1.2.6...1.3.0
[1.2.6]: https://github.com/lucatume/di52/compare/1.2.5...1.2.6
[1.2.5]: https://github.com/lucatume/di52/compare/1.2.4...1.2.5
[1.2.4]: https://github.com/lucatume/di52/compare/1.2.3...1.2.4
[1.2.3]: https://github.com/lucatume/di52/compare/1.2.2...1.2.3
[1.2.2]: https://github.com/lucatume/di52/compare/1.2.1...1.2.2
[1.2.1]: https://github.com/lucatume/di52/compare/1.2.0...1.2.1
[1.2.0]: https://github.com/lucatume/di52/compare/1.1.2...1.2.0
[1.2.0]: https://github.com/lucatume/di52/compare/1.1.2...1.2.0
[1.1.2]: https://github.com/lucatume/di52/compare/1.0.3...1.1.2
[1.1.1]: https://github.com/lucatume/di52/compare/1.0.3...1.1.2
[1.1.0]: https://github.com/lucatume/di52/compare/1.0.3...1.1.0
[1.0.3]: https://github.com/lucatume/di52/compare/1.0.2...1.0.3
[1.0.2]: https://github.com/lucatume/di52/compare/1.0.1...1.0.2
