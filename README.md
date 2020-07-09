# ModernMVC
### This MVC is basic, and If you want to add, modify any properties feel free to do so.
#### Folder structure
  ##### if you use this MVC right It will do most of the work for you. 
  ###### Creating new controller: 
  When creating a new controller you need to use specific ending. like: example.controller.php.
  Use the Controller namespace inside of the file. Like: ``` namespace Controller ```.
  When creating a class you need to extend controller abstract class. Like: ``` class example extends controller ```.
  Inside of the ``` __construct ``` method you need to call parent constructor. Like: ``` parent::__construct(); ```.
  #######
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
 
    
   
