# Laravel Stub Maker

## Overview

Laravel Stub Maker is a simple, powerful package for programmatically generating PHP class and interface stubs with an
intuitive API.

## Installation

```bash
composer require ayup-creative/laravel-stub-maker
```

## Basic Usage

### Creating a Stub

```php
use Ayup\LaravelStubMaker\Concerns\MakesStubs;

$stub = $this->stub('MyClass')
            ->extends('\\App\\Examples\\ExampleClass');
```

Using ```$stub``` as a string from the above example would output the FQN of the class being created.

```php
echo $stub;

// "App\MyClass"
```

Calling the ```output()``` method would return PHP that can then be written to a file.

```php
echo $stub->output();

// <?php
// 
// namespace App;
// 
// use \App\Examples\ExampleClass;
// 
// class MyClass extends ExampleClass
// {}
```

## Key Features

- **Automatic Namespace Extraction:** The ```stub()``` method intelligently sets the namespace
- **String Casting:** Convert stub to its Fully Qualified Name
- **Output Generation:** Easily generate complete PHP file contents

## Advanced Configuration

```php
$stub = $this->stub('UserRepositoryInterface')
    ->interface()
    ->extends('\\App\\Contracts\\RepositoryInterface')
    ->output();
```

## Key Methods

```stub()```: Create a new stub

```interface()```: Define an interface

```extends()```: Set parent class or interface

```implements()```: Add implemented interfaces

```outputPath()```: Set the file output location

```constructor()```: Define class constructor

```writeOut()```: Generate the PHP file

## Adding A Constructor

When generating class objects, you can easily define a constructor and it arguments using the ```Constructor``` and
```Argument``` objects respectively.

The example below could be used to add a constructor, which defines a protected property type hinted to an Eloquent 
Model with a private scope.

```php
use Ayup\LaravelStubMaker\Constructor;
use Ayup\LaravelStubMaker\Argument;

$stub->constructor(
        Constructor::make([
            Argument::make('model')->protected()->hint(\Illuminate\Database\Eloquent\Model::class)
        ])
    );
```

You can also define simple arguments by passing a simple string value as part of the ```$arguments``` argument.

## License

This project is open-sourced software licensed under the MIT license.

## Contributing

Contributions are welcome! Feel free to submit a Pull Request.

## Contact

For support or inquiries, please open an issue on the GitHub repository.
