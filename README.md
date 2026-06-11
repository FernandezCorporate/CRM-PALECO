# NOTES

## Requirements
1. PHP
2. Composer
3. Laravel
4. MySQL workbench.

## VS Code Configuration
1. Open your global editor settings file by pressing **`Ctrl + ,`** (Windows) or **`Cmd + ,`** (Mac).
2. Click the **Open Settings (JSON)** icon in the top right corner of the tab (the small file icon with an arrow).
3. Paste the following line inside the main JSON object configuration block and save the file (This will prevent "red error lines" in Laravel codes primarily because VSCode falsely detects this, even with extensions):
   ```json
   "intelephense.diagnostics.undefinedMethods": false
   ```
4. Reload the editor window by pressing **`Ctrl + Shift + P`** (Windows) or **`Cmd + Shift + P`** (Mac), typing **`Reload Window`**, and pressing **Enter**.



## PHP Configuration  
1. Go to your php folder and **rename** *php.ini-development* into *php.ini*.
2. **Open command prompt** on PHP folder.
3. **Open** *php.ini* by enterring: `code php.ini`.
4. **Remove** `;` to uncomment these specific variables (Use ctrl + f):
- `extension=curl`
- `extension=fileinfo`
- `extension=gd`
- `extension=mbstring`
- `extension=openssl`
- `extension=pdo_mysql`
- `extension=zip`
- `extension=pdo_sqlite`
- `extension=sqlite3`
5. **Change** ext directory to: `extension_dir = "C:\[location of your php ext folder]"`.
6. **Change** `memory_limit` value to `256M`.
7. **Save changes**.

## PHP Syntax
| Action | Command |  
| --- | --- |
| Code block | `<?php [Code here] ?>` |
| Variables | `$variable_name` |
| Print display | `echo` |
| Concatenation | `echo 'String' . $variable_name` OR `echo "String $variable_name"` |
| Define function | `function functionName($parameter) { return $parameter; }` |
| Call function | `echo functionName($value)` |
| Condition | `if (condition) { process } else { process }` |
|

## PHP Run local server
`php -S localhost:8000`  

## Close local server
` CTRL + C `

## Composer
The dependency manager for PHP. *(Similar to how pip is for python and npm is for node.js)*

### Installation
1. Go to the [Composer website](getcomposer.org).
2. Click *Download*.
3. Click the link: *Composer-Setup.exe*.
4. Run downloaded executable file.
5. Click **Next** for all options (follow the default options).
6. Install.
7. Restart computer.
8. In command prompt, enter `composer` then check if a version number or command suggestions appear.

### Usage
| Action | Command |  
| --- | --- |
| Install package | `composer require vendor/packagename ` |
| Use package | <code>require \_\_DIR\_\_ . '/vendor/autoload.php';<br>use Vendor\Namespace\ClassName;</code> |
| Instantiate tool | `$variable = new ClassName();` |

## Laravel

### Installation / Setup  
*(for repo owner)*
1. Install the Laravel command to local device.  
`composer global require laravel/installer`

2. Create a project folder for Laravel.  
`laravel new appname`

3. Select starter kit; preferrably **[none]**.

4. Select testing framework; **[0] Pest** is recommended.

5. Select **no** when prompted to install Laravel boost.

- Notice the filename *artisan*, that will be often used in Laravel projects, similar to the functions of *manage.py* in Django.

6. Select database; **MySQL**.

7. Select **no** when asked to run migrations.

8. Select **yes** when asked to run npm commands. *(You may need to install Node.js)*

9. Configure database variables in *.env* file.

10. Migrate tables.  
`php artisan migrate`

11. Run server (backend and frontend).  
`composer run dev` 

12. (Optional) Install Tailwind.css offline.  
`npm install`

*(for collaborator)*

1. Pull repository and navigate inside the local folder, then into the **PALECO_OTRS_and_Mobile** app folder (You need to be inside the directory where `artisan` is. This is the manage.py of Laravel).

2. Open command prompt or VSCode terminal and run `composer install`. This will install Laravel dependencies by reading `composer.lock`.

3. Next, run `npm install`. This reads `package-lock.json` and installs the Tailwind files.

4. `.env` is gitignored but there is a copy of it pushed inside `.env.example`. Create a `.env` file and copy its content.

5. Open MySQL workbench and choose any of your existing connections. Take not of its information (username and password), then, run this sql: `CREATE DATABASE paleco_otrs_with_mobile`

6. Go to `.env` and make sure these variables match your database in MySQL:
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=paleco_otrs_with_mobile
DB_USERNAME=root
DB_PASSWORD="1234"
```

7. Run `php artisan migrate.`

8. Run `php artisan tinker.`

9. Replace the "[]" with any value as needed, like Ralph for username and PSU for password,  and enter on the prompt as one line: `User::create(['username' => '[any name]', 'email' => '[any email]', 'password' => Hash::make('[any password]'), 'role' => 'admin']);` Role should be admin.

10. Enter `exit()`.

11. Test run by enterring `composer run dev`.


### Commands
| Keyword | Purpose |  
| --- | --- |
| `php artisan migrate` | Apply changes to a database. |
| `php artisan serve` | Run local backend server. |
| `php artisan make:controller Filename` | Create controller files with a provided format. |
| `php artisan make:migration Filename` | Creates migrations files where database structure can be edited. |
| `composer run dev` | Runs backend and frontend servers in one command; frontend server (Vite from step 8) is needed to render styling in real-time. |

### File System

**Views**: Contains the "blade templates" that will serve as the interface of the application.

- Directory: `projectfolder\appfolder\resources\views`
- This folder contains blade templates (*blade.php*), which is simply php and html in one file. 
- You no longer need to write `<?php >` just to include PHP codes in your HTML.
- Much like Django, you can immediately render output passed from the backend using `@` for logic and `{{ $variable }}` to render variables.

### Useful Keywords
| Keyword | Purpose |  
| --- | --- |
| `@auth` <br> `[HTML here]` <br> `endauth` | Write the html that will be displayed once user is logged in. |
| `@csrf` | Required after writing the form tag to validate user inputs. |
| `@foreach ($variables as $variable) @endforeach`  | Loop through a collection of data; primarily dictionaries. |
| `@method('PUT')` | Generates a hidden field that tells Laravel to treat the standard POST request as a PUT request, since browsers cannot send PUT natively. |
| `@method('PATCH')` | Generates a hidden field that tells Laravel to treat the standard POST request as a PATCH request, since browsers cannot send PATCH natively. |
| `@method('DELETE')` | Generates a hidden field that tells Laravel to treat the standard POST request as a DELETE request, since browsers cannot send DELETE natively. |

**Web.php**: Serves as the primary control hub for page URLs of your application.

- Directory: `projectfolder\appfolder\routes\web.php`
- Unlike Django, the HTTP method that will be performed for each view/template is not automatically detected by the URL. You must manually define it (get, post, put, etc.). 
- Follows the specific syntax for simple renderring (w/o controllers):  
```
Route::HTTP method('endpoint', function (){
    return view('View name, ['context' => $variable]')
})
```
- If the renderring involves **controllers**, follow this:
```
use App\HTTP\Controller\ControllerName      #Import controller 

Route::HTTP method('endpoint', [ControllerName::class, 'methodName'])       #Render the value returned by Controller
```
- The HTTP method refers to the method performed by the user on the page. The endpoint in `web.php`, in the case that the method alters/creates a data, must match the endpoint defined for `form action="[endpoint]"`. 

**Controllers**: These are actually the "Views", in the context of Django, for a Laravel project. This is where business logic will primarily live.
- Directory: `projectfolder\appfolder\app\HTTP\controllers`
- You can create an empty **controller file** with a predefined syntax using the command:
`php artisan make:controller Filename`
- **View mapping:** To render views on a controller, call their name minus the 'blade.php' extension.
- **Route Model Binding Reminder:** Keep the route parameter name inside the curly braces like **`'/edit/{post}/`** exactly the same as the variable name **`$post`** in your controller method signature, or Laravel will fail to auto-map the database record.


#### Useful keywords
| Keyword | Purpose |  
| --- | --- |
| `(Request $request)` | Used as a common parameter for data altering methods like POST and PUT. |
| `$request->validate([`<br>`    'field1' => [condition],`<br>`    'field2' => [condition]`<br>`]);` | Validates incoming browser form data on the backend before running execution logic. |
| `Model::create($variable)` | Insert variable values into the database |
| `Model::update($variable)` | Update variable values into the database |
| `Model::delete($variable)` | Delete variable values into the database |
| `bcrpyt($variable['password'])` | Use bcrypt to hash passwords |
| `use Illumination\Validation\Rule;` <br> `... Rule::unique('table', 'field')`| Validate unique inputs |
| `Auth::login($user)` | Authenticates the given user instance and stores their login state securely into the active web session. |
| `Auth::logout($user)` | Terminates the current user's authenticated session, clearing their active login state from the system. |
| `return redirect('/')` | Instructs the browser to instantly navigate away to the home page route pathway. |
| `Auth::attempt('[DBfield]' => $incomingField['InputName'])` | Returns a boolean checking if the attempt login fields matches those from the database fields, and if the credentials are valid, then the user ID is saved into the session cookies. |
| `$request->session()->regenerate()` | Regenerates a new session ID once a user is logged in. |
| `strip_tags()` | Removes HTML and PHP tags in an input to prevent malicious injection. |
| `Auth::id()` | Access the id of the authorized user. | 
| `Model::all()` | Query to select all records of a model/table. |
|`Model::where('field', value)->get();`| Query a filtered selection of records. |
|`auth()->user()->RelationshipModel()->latest()->get();`| Alternative for querying related objects to a logged user.|
|`auth()->check()`| Checks if there is user currently logged for that session. |  

<br>

**Database**: Database connection is configured via the `.env` file of the app folder.
- Directory: `projectfolder\appfolder\.env`
- Set the **database variables** of the *env file* to:
```
DB_CONNECTION=mysql     # If MySQL
DB_HOST=127.0.0.1       # Commonly localhost
DB_PORT=3306        # Constant; database server port
DB_DATABASE=[database name] 
DB_USERNAME=root        # Commonly root
DB_PASSWORD=[database password]      
```
- Run: `php artisan migrate`

- To change the structure of a database (add new tables, edit attributes, etc.), you can use the migration files of Laravel instead of manually changing it on MySQL workbench.
1. Run `php artisan make:migration Filename`. This will create a new migration file with ready-made classes and methods. Also, Laravel can guess the name of the table if you name it `create_posts`, where `posts` will be set as the name.
2. Inside `public function up() {}`, this is where you will set the code for the changes you want to add to your database. For `create` methods, the syntax for adding a fied is `$table->datatype('field_name')`. 
3. After creating the database table, create its corresponding **Model** using `php artisan make:model ModelName`. 
4. **ModelName** has strict definitions for naming, where you must name the model as the **singular** form. *(Ex. A table 'posts' on the database MUST be named 'Post' for its model.)*

**Models**: Maps database tables as objects, which can then be accessed by PHP codes.

#### Model keywords
| Keyword | Purpose |  
| --- | --- |
| `use Illuminate\Database\Eloquent\Attributes\Fillable;` <br> `#[Fillable(['field1', 'field2', 'field3'])]` | Defines the field that can only be filled by a user. |
| `public function FuncName() {`<br>&nbsp;&nbsp;&nbsp;&nbsp;`return $this->hasMany(ChildModel::class, 'foreign_key_field');`<br>`}` | Defines a One-to-Many relationship on the parent model to fetch all related records using a custom foreign key. |
| `public function FuncName() {`<br>&nbsp;&nbsp;&nbsp;&nbsp;`return $this->belongsTo(ParentModel::class, 'foreign_key_field');`<br>`}` | Defines the parent model linked to a specific child record. |

<hr>

## Frontend

In the context of the capstone project, there are 5 major components that will be used:

- **Laravel blade:** The templating engine built-in with Laravel projects. Distinguishable through their file extension of *.blade.php*, and allows for the usage of `@` and `{}` to render PHP logic and variables into HTML code instead of using the traditonal `<?php ?>`.

| Syntax / Directive | Purpose | Example / Usage |
| :--- | :--- | :--- |
| `{{ $variable }}` | Echo data (escaped) | `<h1>{{ $title }}</h1>` |
| `{!! $html !!}` | Echo raw data (unescaped) | `<div>{!! $trustedHtml !!}</div>` |
| `@if() / @else / @endif` | Conditional statements | `@if($isAdmin) <p>Admin</p> @endif` |
| `@foreach() / @endforeach`| Loop through arrays/collections | `@foreach($users as $user) <li>{{ $user->name }}</li> @endforeach` |
| `@auth / @endauth` | Check if user is logged in | `@auth <a href="/profile">Profile</a> @endauth` |
| `@csrf` | Insert hidden CSRF token for forms | `<form method="POST"> @csrf ... </form>` |
| `@error('field')` | Render validation error messages | `@error('email') <span class="text-red-500">{{ $message }}</span> @enderror` |
| `<x-component-name />` | Render a reusable Blade component | `<x-button type="submit">Save</x-button>

- **Laravel livewire:** A PHP alternative to JavaScript in terms of providing dynamic design to HTML code. Common use-cases will be animation, changing state of buttons and other dynamic frontend features.


| Attribute / Command | Purpose | Example / Usage |
| :--- | :--- | :--- |
| `php artisan make:livewire Name` | Create a new component & class | Run this in your terminal |
| `<livewire:component-name />` | Render a Livewire component in Blade| `<livewire:search-users />` |
| `wire:model="property"` | Bind an HTML input to a PHP variable | `<input type="text" wire:model="search">` |
| `wire:click="method"` | Trigger a PHP function on click | `<button wire:click="save">Save</button>` |
| `wire:submit="method"` | Handle form submission via PHP | `<form wire:submit="register">` |
| `wire:loading` | Show/hide elements during AJAX load | `<div wire:loading>Processing...</div>` |
| `wire:confirm="Message"` | Prompt user before running a method | `<button wire:click="delete" wire:confirm="Are you sure?">` |
| `$this->dispatch('event')` | Emit an event to other components | Used inside the Livewire PHP class |


- **Alpine.js:** A lightweight JavaScript framework built directly into Livewire. It handles client-side UI interactions—like toggling dropdowns, opening modals, or animating mobile menus—instantly in the browser without making a slow roundtrip request to your server. *(Built-in with Livewire; will not use its coding syntax and was only explained because this is how Livewire runs under the hood)*

- **Tailwind CSS:** A utility-first CSS framework that replaces traditional stylesheets. Instead of writing custom CSS rules, you style your HTML directly inside your Blade files by combining pre-made utility classes (like flex, pt-4, or text-center) for rapid layout design. Similar to Bootstrap however, Tailwind allows for more flexibility as buttons and common elements are not pre-defined in this framework.


| Category | Purpose | Common Class Examples |
| :--- | :--- | :--- |
| **Layout & Flex** | Structure alignment | `flex`, `grid`, `items-center`, `justify-between`, `hidden` |
| **Spacing** | Margins and padding | `p-4` (padding), `mx-auto` (center), `mt-2` (margin-top), `space-y-4` |
| **Sizing** | Width and height | `w-full`, `h-screen`, `max-w-md`, `size-12` |
| **Typography** | Text styling | `text-lg`, `font-bold`, `text-center`, `text-gray-700`, `tracking-wide` |
| **Backgrounds** | Element fills | `bg-blue-600`, `bg-white`, `bg-opacity-50`, `bg-gradient-to-r` |
| **Borders** | Outlines and corners | `border`, `border-gray-300`, `rounded-lg`, `rounded-full` |
| **States** | Interactive modifiers | `hover:bg-blue-700`, `focus:ring-2`, `disabled:opacity-50` |
| **Responsive** | Mobile-first breakpoints | `sm:flex-row`, `md:text-xl`, `lg:grid-cols-3` |


- **Vite:** The modern frontend build tool and asset bundler that replaces Webpack (Laravel Mix). It instantly compiles your Tailwind CSS and JavaScript assets, and uses Hot Module Replacement (HMR) to refresh your browser layout the millisecond you save a file.


| Command / Code | Purpose | Environment / File |
| :--- | :--- | :--- |
| `npm run dev` | Starts local build server with HMR | Run in Terminal (during development) |
| `npm run build` | Compiles & minifies assets for production| Run in Terminal (before deployment) |
| `@vite(['resources/css/app.css', 'resources/js/app.js'])` | Injects compiled assets into HTML | Add to HTML `<head>` in your root Blade layout |
| `vite.config.js` | Configuration file for asset paths | Located in your project root directory |
| `refresh: true` | Forces browser auto-reload on Blade changes| Configured inside `vite.config.js` |
