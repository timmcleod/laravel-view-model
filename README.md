## Laravel View Model

###### Clean up those messy views using Laravel View Models.

This package can be used to help you encapsulate, validate, and manipulate the data that is required by each of the views in your Laravel 5.x applications.

### Purpose and Overview

As the views in our applications grow more complex, they tend to require more data that needs to be presented in varying ways. As our view dependencies increase and the view files become more complex, it can be increasingly difficult to keep track of the data is required by the view.

View Models help by:

* Validating the data that is injected into the view to verify that the data is exactly what the view expects.
* Reducing the chance that the "global" variables in your views will be inadvertently overwritten within your views.
* Providing a way to see what data is required by the view at-a-glance.
* Facilitating the bundling of data with the methods required to manipulate the data in the context of the view.

### Installation

Require this package with composer:

```
composer require timmcleod/laravel-view-model
```

### Basic Usage

...
