# ApiMockery - A Simple API mock server

[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/larsroettig/ApiMockery/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/larsroettig/ApiMockery/?branch=master)
[![Build Status](https://scrutinizer-ci.com/g/larsroettig/ApiMockery/badges/build.png?b=master)](https://scrutinizer-ci.com/g/larsroettig/ApiMockery/build-status/master)
[![Code Coverage](https://scrutinizer-ci.com/g/larsroettig/ApiMockery/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/larsroettig/ApiMockery/?branch=master)


ApiMockery is a simple API mocking solution written in PHP based on the Slim Framework.

## Installation
```bash
 composer install
```

## Configuration
Currently this library support swagger config files from type v2

### Register File Response Handler

```json
{
  "paths": {
    "/pets": {
      "get": {
        "x-apis-json": {
          "response_handler_type": "files",
          "response_handler": "responses/pets/pet{id}"
        }
      }
    }
  }
}
``` 

 
### Register Service Class Response Handler
```json
{
  "paths": {
    "/pets": {
      "get": {
        "x-apis-json": {
           "response_handler_type": "object",
           "response_handler": "\\Pets\\SaveService"
        }
      }
    }
  }
}
``` 
### [Example Config File](https://github.com/larsroettig/ApiMockeryExample/blob/master/web/swagger.json)

## Example Implementation
[Github Repo](https://github.com/larsroettig/ApiMockeryExample)

## We’re hiring!

Wanna work for [one of Germany’s leading Magento partners][11]? With agile methods, small teams and big clients? We’re currently looking for experienced **PHP & Magento developers in Munich**. Sounds interesting? Just drop me a line via l.roettig@techdivision.com

[11]: https://www.techdivision.com/karriere/offene-stellen/magento-developer-m-w.html
