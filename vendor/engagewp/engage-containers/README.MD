# Engage Containers
A collection of useful containers for Engage (or your) development.

## Install
`composer require engage-containers`

## Requires PHP 5.6+
* [Not tested in 5.6 or 7.0, but should work](https://github.com/EngageWP/engage-containers/issues/1)
## Containers

* `engagewp\EngageContainers\Container`
Basic PSR-11 compatible container decorating Pimple.
    - Is abstract.
    - Converts to arrays. `engagewp\EngageContainers\Interfaces\Arrayable`
    - Converts to JSON. `JsonSerializable`

* `engagewp\EngageContainers\ControlledContainer`
Extends the base container but only allows in specified attributes.
    - Is abstract
* `engagewp\EngageContainers\Service\Coantainer`
A basic service container, with provider bindings, lazy-loaded objects, and singletons.

### Usage
#### `engagewp\EngageContainers\Container`

#### `engagewp\EngageContainers\ControlledContainer`

#### `engagewp\EngageContainers\Service\Coantainer`

#### Binding As Factory
Add a binding that returns a _new_ object of the same class using the alias `std`

```php

$container = new \engagewp\EngageContainers\Service\Container();
$container->bind( 'std', function (){
     $obj = new \stdClass();
     $obj->foo = rand();
     return $obj;
});

//$obj1->foo !== $obj2->foo
$obj1 = $container->make('std');
$obj2 = $container->make('std');

```

#### Binding A Singleton
Add a binding that returns a _the same_ object of the same class using the alias `std`. You MUST instantiate class before binding.

```php

$container = new \engagewp\EngageContainers\Service\Container();
$obj = new \stdClass();
$obj->foo = rand();
$container->singleton( 'std', $obj );

//$obj1->foo === $obj2->foo
$obj1 = $container->make('std');
$obj2 = $container->make('std');
```

#### Binding A Lazy-Loaded Singleton
Add a binding that returns a _the same_ object of the same class using the alias `std`. Class is instantiated 1 times, but is not instantiated until used, if ever.


```php

$container = new \engagewp\EngageContainers\Service\Container();
$container->singleton( 'std', function (){
     $obj = new \stdClass();
     $obj->foo = rand();
     return $obj;
});

//$obj1->foo === $obj2->foo
$obj1 = $container->make('std');
$obj2 = $container->make('std');

## Stuff.
Copyright 2018 EngageWP LLC. License: GPL v2 or later.
