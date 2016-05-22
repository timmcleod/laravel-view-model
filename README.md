## View Models for Laravel

###### Clean up messy views using View Models

This package can be used to help you encapsulate, validate, and manipulate the data that is required by each of the views in your Laravel 5.x applications.

### Purpose and Overview

As the views in our applications grow more complex, they tend to require more data that needs to be presented in varying ways. As our view dependencies increase and the view files become more complex, it can be increasingly difficult to keep track of the data that is required by the view.

View Models help by:

* Validating the data that is injected into the view to verify that the data is exactly what the view expects.
* Facilitating the bundling of data with the methods required to manipulate the data in the context of the view.
* Reducing the chance that the "global" variables in your views will be inadvertently overwritten within your views.
* Providing a way to see what data is required by the view at-a-glance.

### Installation

Require this package with composer:

```
composer require timmcleod/laravel-view-model
```

Then, register the following service provider: `TimMcLeod\ViewModel\ViewModelServiceProvider`

### Getting Started

After you have registered the `ViewModelServiceProvider`, a new Artisan command will be available to generate your view models. To create a new view model, use the `make:view-model` Artisan command:

```
php artisan make:view-model EditProfileViewModel
```

This command will place a new `EditProfileViewModel` class within your `app/ViewModels` directory. If the directory doesn't exist, it will be created for you.

```php
<?php

namespace App\ViewModels;

use TimMcLeod\ViewModel\BaseViewModel;

class EditProfileViewModel extends BaseViewModel
{
    /** @var array */
    protected $rules = [];
}
```
 
If you prefer to subgroup your view models, you can generate them this way:

```
php artisan make:view-model User/EditProfileViewModel
```

This command will place a new `EditProfileViewModel` class within your `app/ViewModels/User` directory.

### Basic Usage

To use view models, just create a new instance of a view model and pass the instance into the view. 

```php
$vm = new EditProfileViewModel([
    'timezones' => Timezone::all(),
    'states'    => State::all(),
    'cities'    => City::all(),
    'user'      => Auth::user()
]);

return view('user.profile')->with('vm', $vm);
```

When the new view model is instantiated, the data is validated using the rules defined in your view model class. You can use any of the [available validation rules](https://laravel.com/docs/5.2/validation#available-validation-rules) defined in Laravel documentation.

Three additional rules are also available: `instance_of`, `collection_of`, and `paginator_of`.

```php
protected $rules = [
    'timezones' => 'required|collection_of:App\\Timezone',
    'states'    => 'required|collection_of:App\\State',
    'cities'    => 'present|collection_of:App\\City',
    'user'      => 'required|instance_of:App\\User',
];
```

If any of the data coming from the controller fails validation, an exception is thrown.

Within your views, you can access the data in your view model like this: `$vm->timezones`.

You can also add your own methods to your view model to keep your view-specific logic bundled nicely with the data to keep your views clean.

```php
// Inside of EditProfileViewModel class:

/**
 * Returns true if the user's timezone is the same as the given timezone.
 *
 * @param Timezone $timezone
 * @return bool
 */
public function isSelectedTimezone(Timezone $timezone)
{
    return $this->user->timezone === $timezone->code;
}
```

```html
<!-- Inside of user.profile view -->

<select id="timezone" name="timezone">
    @foreach($vm->timezones as $timezone)
        <option value="{{$timezone->code}}" {{$vm->isSelectedTimezone($timezone) ? 'selected' : ''}}>{{$timezone->label}}</option>
    @endforeach
</select>
```

Or maybe you want to create a list of checkboxes for cities in 2 separate columns in this particular view. You could do something like this:

```php
// Inside of EditProfileViewModel class:

/**
 * Returns the first half of cities from the cities array.
 *
 * @return array
 */
public function citiesCol1()
{
    $len = count($this->cities);
    return $this->cities->slice(0, round($len / 2));
}

/**
 * Returns the second half of cities from the cities array.
 *
 * @return array
 */
public function citiesCol2()
{
    $len = count($this->cities);
    return $this->cities->slice(round($len / 2));
}
```

```html
<!-- Inside of user.profile view -->

<!-- Inside markup for column 1 -->
@foreach($vm->citiesCol1() as $city)
    <label><input name="cities[]" type="checkbox" value="{{$city->id}}">{{"$city->name, $city->state_code"}}</label>
@endforeach
    
<!-- Inside markup for column 2 -->
@foreach($vm->citiesCol2() as $city)
    <label><input name="cities[]" type="checkbox" value="{{$city->id}}"> {{"$city->name, $city->state_code"}}</label>
@endforeach
```

As you can see, View Models allow us to bundle the view data with the methods required to manipulate the data in the context of the view.

## License

View Models for Laravel is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT).

[Laravel](http://laravel.com) is a trademark of Taylor Otwell.