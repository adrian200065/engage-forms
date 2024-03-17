[![Build Status](https://travis-ci.org/engagewp/engage-forms-query.svg?branch=master)](https://travis-ci.org/engagewp/engage-forms-query)

This library provides for developer-friendly ways to query for or delete Engage Forms entry data.

## Why?
* [To provide the types of queries we need for reporting and deleting data in order to add GDPR compliance to Engage Forms](https://github.com/EngageWP/Engage-Forms/issues/2108)
* To provide the types of queries we need for improving Engage Forms features such as entry viewer, entry export, entry editing and Connected Forms.

## Install
`composer require engagewp/engage-forms-query`

## Requires
* WordPress - tested with 4.8, latest and trunk
* PHP 5.6+ - tested with PHP 7.1 and 7.2
* Engage Forms 1.6.0+ - tested with Engage Forms 1.6.1 beta 1

## Status
* Works
* Does not yet select/delete by date range
* **Prepared SQL needs to be sanitized better.**
## Usage


### Basic Queries
```php
/**
 * Examples of simple queries
 *
 * Using the class: \engagewp\EngageFormsQuery\Features\FeatureContainer
 * Via the static accessor function: engagewp\EngageFormsQueries\EngageFormsQueries()
 */

/** First make the function usable without a full namespace */
use function engagewp\EngageFormsQueries\EngageFormsQueries;

/** Do Some Queries */
//Select all data by user ID
$entries = EngageFormsQueries()->selectByUserId(42);

//Select all entries that have a field whose slug is "email" and the value of that field's value is "delete@please.eu"
$entries = EngageFormsQueries()->selectByFieldValue( 'email', 'delete@please.eu' );

//Select all entries that do not have field whose slug is "size" and the value of that field's value is "big"
$entries = EngageFormsQueries()->selectByFieldValue( 'size', 'big', false );

//Delete all data by Entry ID
EngageFormsQueries()->deleteByEntryIds([1,1,2,3,5,8,42]);

//Delete all data by User ID
EngageFormsQueries()->deleteByUserId(42);
```

### Paginated Queries
The selectByFieldValue feature method defaults to limiting queries to 25. You can set the page and limit with the 4th & 5th arguments.
```php
/**
 * Examples of simple queries
 *
 * Using the class: \engagewp\EngageFormsQuery\Features\FeatureContainer
 * Via the static accessor function: engagewp\EngageFormsQueries\EngageFormsQueries()
 */

/** First make the function usable without a full namespace */
use function engagewp\EngageFormsQueries\EngageFormsQueries;

/** Do Some Queries */
//Select all entries that have a field whose slug is "email" and the value of that field's value is "delete@please.eu"
//The first 25 entries
$entries = EngageFormsQueries()->selectByFieldValue( 'email', 'delete@please.eu' );
//The second 25 entries
$entries = EngageFormsQueries()->selectByFieldValue( 'email', 'delete@please.eu', true, 2 );
//Get 5th page, with 50 results per page
$entries = EngageFormsQueries()->selectByFieldValue( 'email', 'delete@please.eu', true, 5, 50 );
```

## Constructing Other Queries
The feature container provides helper methods that allow for simple queries like those listed above. It also exposes the underlying query generators. 

You can access any of the generators using the `getQueries()` method. For example:

```php
 $featureContainer = \engagewp\EngageFormsQueries\EngageFormsQueries();
    $fieldValue = 'X@x.com';
    $formId = 'EF5afb00e97d698';
    $count = Engage_Forms_Entry_Bulk::count($formId );

    $entrySelector = $featureContainer
        ->getQueries()
        ->entrySelect();
```

#### `is()` Helper Method
This is a more complete example showing a selection of entry values where the field with the slug `primary_email` is `roy@hiroy.club` and the field with the slug of `first_name` is `Mike`. It is also using the `is()` method to add WHERE statements, as well as the `addPagination()` method to query for the second page of results with 50 results per page.

```php
    $featureContainer = \engagewp\EngageFormsQueries\EngageFormsQueries();
    $entrySelector = $featureContainer
        ->getQueries()
        ->entrySelect()
        ->is( 'primary_email', 'roy@hiroy.club' )
        ->is( 'first_name', 'Mike' )
        ->addPagination(2,50 );
```

#### `in()` Helper Method
This example shows selection of all entry values where the entry ID is in an array of entry IDs.

```php
    $featureContainer = \engagewp\EngageFormsQueries\EngageFormsQueries();
    $entrySelector = $featureContainer
        ->getQueries()
        ->entrySelect()
        ->in( 'entry_id', [ 42, 3 ] );
```

### Query Generators
All query generators extend the `\engagewp\EngageFormsQuery\QueryBuilder` class and impairment `\engagewp\EngageFormsQuery\CreatesSqlQueries`.

Query generators are responsible for creating SQL queries. They do not perform sequel queries.
#### Select Query Generators
Select query generators extend `\engagewp\EngageFormsQuery\Select\SelectQueryBuilder` and impliment `\engagewp\EngageFormsQuery\Select\DoesSelectQuery` and `\engagewp\EngageFormsQuery\Select\DoesSelectQueryByEntryId`. 

#### Useful Methods of `SelectQueryBuilder`s

* `in()`


### Using Query Generators To Perform SQL Queries

#### SELECT
The `getQueries()` method of the `FeatureContainer` returns a `engagewp\EngageFormsQuery\Features\Queries` instance. This provides us with a `select` method when passed a `SelectQueryBuilder` returns an array of `stdClass` object of results.


```php
        $featureContainer = \engagewp\EngageFormsQueries\EngageFormsQueries();
        $entryValueSelect = $featureContainer
            ->getQueries()
            ->entryValuesSelect()
            ->is( 'size', 'large' );

       $featureContainer->getQueries()->select( $entryValueSelect );
```

You can also access the generated SQL as a string.

```php

  $featureContainer = \engagewp\EngageFormsQueries\EngageFormsQueries();
        $sql = $featureContainer
            ->getQueries()
            ->entryValuesSelect()
            ->is( 'size', 'large' )
            ->getPreparedSql();
```

#### DELETE
The `Queries` class also has a `delete` method we can pass a `DeleteQueryBuilder` to perform a DELETE query against the database.


## Development
### Install
Requires git and Composer

* `git clone git@github.com:engagewp/engage-forms-query.git`
* `cd engage-forms-query`
* `composer install`

### Local Development Environment
A  local development environment is included, and provided. It is used for integration tests. Requires Composer, Docker and Docker Compose.

* Install Local Environment And WordPress "Unit" Test Suite
- `composer wp-install`

You should know have WordPress at http://localhost:8888/

* (re)Start Server: Once server is installed, you can start it again
- `composer wp-start`

### Testing

#### Install
Follow the steps above to create local development environment, then you can use the commands listed in the next section.

#### Use
Run these commands from the plugin's root directory.

* Run All Tests and Code Sniffs and Fixes
    - `composer tests`
* Run Unit Tests
    - `composer unit-tests`
* Run WordPress Integration Tests
    - `composer wp-tests`
* Fix All Code Formatting
    - `composer formatting`
    
    
## WordPress and Engage Forms Dependency
For now, this library is dependent on Engage Forms and WordPress (for `\WPDB`.) This will change, possibly with breaking changes, when [engage-interop](https://github.com/EngageWP/engage-interop) is integrated with this tool.

## Stuff.
Copyright 2018 EngageWP LLC. License: GPL v2 or later.
