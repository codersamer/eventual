

# Laravel Eventual
Laravel Eventual Package is responsible to add functionality of WordPress-Link Actions and Filters with ease of use and implementation

## Getting Started

- First you need to install this package into your laravel project
`composer require codersamer/eventual`
- Oh that's it, it's auto discoverable so you don't need any further steps to start use this package

## Concept

usually you will be able to register and fire actions and filters using facade :  `Codersamer\Eventual\Facades\Eventual` , so you will have access to static methods like `onFilter`, `onAction`, `doFilter`, `doAction`.


## Working with Actions

Actions is like Events, something that happening and some other application parts needs to be notified by this event and act accordingly.

### Register Action Handler / Listener
You can Register an Action Handler or Listener using static method `onAction`
```php
<?php
using Codersamer\Eventual\Facades\Eventual;
....
Eventual::onAction($name, function($argument1, $argument2){
	//Act to the Action
});
```

### Register Filter Handler / Listener
You can Register an Filter Handler or Listener using static method `onFilter`
```php
<?php
using Codersamer\Eventual\Facades\Eventual;
....
Eventual::onFilter($name, function($name){
	//Add Updates to the Argument if needed, then return it
	return $name;
});
```
For Comprehensive and Detail Guide Please [Follow this Link](https://codersam.website/tutorials/laravel-actions-and-filters-like-wordpress)