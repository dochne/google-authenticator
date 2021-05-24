# Change Log
All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](http://keepachangelog.com/) 
and this project adheres to [Semantic Versioning](http://semver.org/).

## [2.2.0] - 2021-05-24
### Added
- Support for different types of writer for Endroid
- Added LICENCE file

## [2.1.0] - 2019-03-12
### Added
- Support for custom timeslice windows
- Better unit testing support for timeslices
- Support for PSR-16
### Bugfix
- Fix for potential timing attack

## [2.0.7] - 2019-02-07
### Bugfix
- Support for Composer 2.0
- Updated endroid/qrcode to be the new format of endroid/qr-code

## [2.0.6] - 2019-01-31
### Bugfix
- Support NOP version of paragonie/random_compat

## [2.0.5] - 2019-01-08
### Bugfix
- Fixed timeslices not being used properly

## [2.0.4] - 2018-08-20
### Dependency compatibility
- Added support for Endroid ~3

## [2.0.3] - 2018-07-09
### Bugfix
- Actually fixed malformed composer.json

## [2.0.2] - 2018-07-09
### Bugfix
- Fixed malformed composer.json

## [2.0.1] - 2018-07-08
### Bugfix
- Updated tests to PHPUnit 6
- Fixed cache to use correct PSR-6 compliant keys
- Updated example to be clear about cache usage
- Added cache/filesystem-adapter as a dev dependency 

## [2.0.0] - 2018-05-24
### Bugfix
- Moved to use Endroid as default QR provider
- Fixed Google QR provider (for the time being at least)
- Removed cache/filesystem-adapter as a compulsory dependency 

## [1.1.1] - 2017-10-04
### Bugfix
- Fixed verification that secret cannot contain a colon

## [1.1.0] - 2017-06-20
### Added
- Endroid QR Generation Support

## [1.0.1] - 2016-09-19
### Added
- Bugfixes regarding incorrect use of urlencode instead of rawurlencode
- Added random-compat library to support php7 functions when running php5

## [1.0.0]
### Initial Release
