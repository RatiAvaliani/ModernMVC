# ModernMVC
### This MVC is basic, and If you want to add, modify any properties feel free to do so.
#### Folder structure
  ##### if you use this MVC right It will do most of the work for you. 
  ###### Creating new Controller:
 - [x] When creating a new controller you need to use specific ending. like: example.controller.php.
 - [x] Use the Controller namespace inside of the file. Like: ``` namespace Controller ```.
 - [x] When creating a class you need to extend controller abstract class. Like: ``` class example extends controller ```.
 - [x] Inside of the ``` __construct ``` method you need to call parent constructor. Like: ``` parent::__construct(); ```.
 - [x] Inside of the controller create a new method called index, this index method is the default method of the route
  ###### Creating new Model:
 - [x]  When creating a new controller you need to use specific ending. Like: exampleModel.php. 
 - [x]  Use trait namespace. Like: ``` namespace Model; ```
 - [x]  Inside of new model extend model abstract class.
 - [x]  MODEL IS REQUIRED
 - [x]  To use model in controller call ``` $this->modal->... ``` and your model method.
 ###### Creating new Trait:
 - [x]  When creating a new controller you need to use specific ending. Like: example.trait.php.
 - [x]  Use trait namespace. Like: ``` namespace Traits; ```
 ###### Creating new Views:
 - [x]  When creating new view for controller if you what to use auto loade function you need to create a folder using the same name as a controller
 - [x]  Default file (view) name is index.php, and the rest of the view names need corespond to controller method names. Like ``` public function example.... example.php ```
 ###### Creating new Assets:
 - [x]  Inside of the folder ``` Public/Assets/ ``` you can add ```js``` and ```css```
 - [x]  To use auto loade function you will need to create folder inside of ```js``` or ```css```, the same name needs to be used as controller and the names of ```js``` and ```css``` needs to correspond to controller method names.
 ###### Creating new default errors:
 - [x]  Inside of ``` Public/Errors/ ``` you can add more error html files.
 ###### Creating new default elements like (header and footer):
 - [x]  Inside of ``` Public/deafult/ ``` you can add or modify ``` header.php ``` or ``` footer.php ```.
 ###### Config
 - [x]  Inide of config you need to change ``` define('DOMAIN', 'http://localhost/ModernMVC/Public/'); ``` and ``` define('DB_CONFIG',  ...) ``` constants

#### Folder structure
```
  - Config
     - config.php
  - Controller
      - controller.php
      - admin.controller.php
      - home.controller.php
      - langs.controller.php
  - Core
      - request.php
      - route.php
      - routes.php
  - Libs
      - Traits
          - loadAssets.traits.php
          - loadController.traits.php
          - log.traits.php
          - render.traits.ph
          - session.traits.php
      - database.php
      - virtualVariables.php
   - Logs
      - logs.php
   - Models
      - adminModel.php
      - homeModel.php
      - langsModel.php
      - model.php
   - Public
      - Assets
        - js
          - Admin
          - Langs
          - Modules
          - app.js
      - admin
        - index.php
        - login.php
      - default
        - bootstrapModal.html
        - footer.php
        - header.php
      - Errors
        - 404.html
        - Art.html
      - langs
        - add.php
        - index.php
      - .htaccess
      - index.php
   - install.sql
 ```
 
    
   
