# Change Log
All notable changes to this project will be documented in this file.

## [Unreleased][unreleased]

## [0.7.6] - 2020-01-23
### Fixed
- Updated app version for all Swedbank apps in order to support the new minimal required version to use the API.

## [0.7.5] - 2019-12-09
### Fixed
- Updated app version for all Swedbank apps in order to support the new minimal required version to use the API.

## [0.7.4] - 2019-10-24
### Fixed
- Updated app version for all Swedbank apps (expect youth apps) in order to support the new minimal required version to use the API.

## [0.7.3] - 2019-10-07
### Fixed
- Updated app version for all Swedbank apps in order to support the new minimal required version to use the API.

## [0.7.2] - 2019-05-26
### Fixed
- Updated app version for all Swedbank apps in order to support the new minimal required version to use the API.
- Stop endless looping when logout API call fails. Resulting in a Fatal error: Allowed memory message.

## [0.7.1] - 2016-10-22
### Changed
- Updated app version for all Swedbank apps.
- Updated UUID dependency.

## [0.7.0] - 2016-07-25
### Security
- Guzzle set to minimum version of 6.2.1 because of HTTP_PROXY vulnerability.

### Added
- Transfer money between accounts.
- Option to log HTTP requests to file for debugging purposes.

### Changed
- Translated the documentation to English.
- Improved documentation. Now with API response samples.
- Updated app version for all Swedbank apps.
- Refactor getchallenge() to getChallenge(). Method names are case-insensitive, should have no effect.
- Refactor setchallengeResponse() to setChallengeResponse(). Method names are case-insensitive, should have no effect.

### Deprecated
- Refactor of confirmTransfer() to confirmTransfers().

## [0.6.1] - 2016-02-07
### Added
- Documentation for quick balance.

### Fixed
- Cleanup() didn't clean up saved session.
- UserException namespace for Appdata.
- Documentation links

## [0.6.0] - 2016-01-31
### Added
- New authentication method: Mobile BankID.
- New authentication method: No login.

### Changed
- Upgraded to Guzzle 6, therefore PHP 5.5 or later is required.
- Updated app version for all Swedbank apps.
- Improved error handling.
- Improved documentation.

### Removed
- Automatic sign out - You have to manually use terminate() to sign out.

## [0.5.2] - 2015-04-21
### Changed
- Update of Guzzle dependencies.

### Fixed
- Getchallenge() didn't save the challenge within a session. Issue [#22](https://github.com/walle89/SwedbankJson/issues/22).

## [0.5.1] - 2015-03-09
### Changed
- Update of Guzzle.

## [0.5.0] - 2015-03-09
### Added
- Support for authentication method Security token with one time code (see [README.md]).

### Changed
- The SwedbankJson object have been redesigned to support security token login. Read more about it in [README.md].
- New way to set AppID. It's backward compatible with the old method.
- Updated app version for all Swedbank apps.
- Updated documentation with guide for Windows users.

## [0.4.0] - 2015-02-13
### Added
- Quick balance - Show, activate and deactivate Quick balance.
- Reminders - Get the number of rejected payments, payments signed, unsigned transfers and incoming e-invoices.
- Baseinfo - Account list grouped by type.

### Changed
- AccountDetails does not longer require AccountID. If no AccountID is provided, it will fallback to default account.
- Setting ProfileID is no longer required. If ProfileID is not provided, it will fallback to default profile. 
- Updated app version for all Swedbank apps.
- Updated documentation.
- Updated dependencies of UDID and Guzzle.

## [0.3.4] - 2014-06-18
### Added
- Support for Sparbanken Företag.

### Changed
- Updated app version for Swedbank and Sparbanken.
- Updated documentation.

### Deprecated
- Bank type "swedbank_företag" have changed to "swedbank_foretag".

## [0.3.3] - 2014-05-28
### Added
- MIT License file.

### Changed
- Improved documentation.
- Updated to Guzzle 4.1.

### Fixed
- Removing cookie file after each run. 

## [0.3.2] - 2014-05-15
### Fixed
- Issue [#8](https://github.com/walle89/SwedbankJson/issues/8). Linux uses a case sensitive file system, unlike OS X.

## [0.3.1] - 2014-05-15
### Changed
- Changed namespace.
- Composer adjustments.

## [0.3.0] - 2014-05-15
### Changed
- Starting using of Guzzle 4 library, therefore updated system requirement of PHP 5.4 or later.
- Cleaner and better structure.
- Updated Readme.md documentation.

### Fixed
- Issue [#6](https://github.com/walle89/SwedbankJson/issues/6).

## [0.2.0] - 2014-05-14
### Changed
- Converted to Composer.

## 0.1.0 - 2014-05-14
### Removed
- Removing the temp folder and adjusting the tests folder.

## 0.0.6 - 2014-05-04
### Added
- Adjustable row limit for bank statements.
- Automatic profile selection by bank type.

### Changed
- Updated menu API requests.
- Support for Swedbank's "Easy login" API.
- Outputs error message for mandatory password change.

### Fixed
- Issue [#4](https://github.com/walle89/SwedbankJson/issues/4)

## 0.0.5 - 2014-04-21
### Added
- Support for new Bank types; Sparbanken, Swedbank Företeg, Swedbank Ung and Sparbanken Ung.

### Changed
- Improved documentation.

## 0.0.4 - 2014-02-09
### Changed
- Improved error handling.

## 0.0.3 - 2014-02-01
### Added
- Support for investment savings accounts.

### Changed
- Better output of error messages.

## 0.0.2 - 2013-03-24
### Changed
- Compatibility with API version 3.2.0.

## 0.0.1 - 2013-03-15
### Added
- First release.

[unreleased]: https://github.com/walle89/SwedbankJson/compare/v0.7.6...HEAD
[0.7.6]: https://github.com/walle89/SwedbankJson/compare/v0.7.5...v0.7.6
[0.7.5]: https://github.com/walle89/SwedbankJson/compare/v0.7.4...v0.7.5
[0.7.4]: https://github.com/walle89/SwedbankJson/compare/v0.7.3...v0.7.4
[0.7.3]: https://github.com/walle89/SwedbankJson/compare/v0.7.2...v0.7.3
[0.7.2]: https://github.com/walle89/SwedbankJson/compare/v0.7.1...v0.7.2
[0.7.1]: https://github.com/walle89/SwedbankJson/compare/v0.7.0...v0.7.1
[0.7.0]: https://github.com/walle89/SwedbankJson/compare/v0.6.1...v0.7.0
[0.6.1]: https://github.com/walle89/SwedbankJson/compare/v0.6.0...v0.6.1
[0.6.0]: https://github.com/walle89/SwedbankJson/compare/v0.5.2...v0.6.0
[0.5.2]: https://github.com/walle89/SwedbankJson/compare/v0.5.1...v0.5.2
[0.5.1]: https://github.com/walle89/SwedbankJson/compare/v0.5.0...v0.5.1
[0.5.0]: https://github.com/walle89/SwedbankJson/compare/v0.4.0...v0.5.0
[0.4.0]: https://github.com/walle89/SwedbankJson/compare/v0.3.4...v0.4.0
[0.3.4]: https://github.com/walle89/SwedbankJson/compare/v0.3.3...v0.3.4
[0.3.3]: https://github.com/walle89/SwedbankJson/compare/v0.3.2...v0.3.3
[0.3.2]: https://github.com/walle89/SwedbankJson/compare/v0.3.1...v0.3.2
[0.3.1]: https://github.com/walle89/SwedbankJson/compare/v0.3.0...v0.3.1
[0.3.0]: https://github.com/walle89/SwedbankJson/compare/v0.2.0...v0.3.0
[0.2.0]: https://github.com/walle89/SwedbankJson/compare/v0.1.0...v0.2.0
[README.md]: README.md