## Mist

**Custom PHP Framework**
- 0 dependencies
- 99% original code

**Root folder structure:**
- **/app** - place for frontend framework
- **/docker** - folder for docker related files, e.g. Dockerfiles
- **/public** - folder containing index.php entrypoint into app and other static assets
- **/src** - folder containing framework

**Framework structure:**
- **/Core** - folder containing core classes for application
	- Container.php - class used to resolve depencies for classes and methods
	- Core.php - core framework class extends container
	- Database.php - class used for interacting with database (extends PDO)
	- gateway.php - gateway file determins with list of routes to use for a request and call all neccessary middlewares
	- Request.php - request class, constructed from request
	- Router.php - router class
- **/Controllers** - folder containing controllers
   - Controller.php - abstract class containig core functionality for all controllers
- **/Middlewares** - contains all middlewares
	- Middleware.php - abstract class that should be extended by all middlewares
- **/Migrations** - contains all migrations scripts
	- Migration.php - class for running/managing migrations
- **/Models** - folder for storing models
	- Model.php - abstract class containig core functionality for all models
- **/Repositories** - contains all repositories
	- Repository.php - abstract class containig core functionality for all repositories
- **/Services** - folder containig all services
	- Service.php - abstract class containig core functionality for all services
- **/Helpers** - contains all helper functions/classes
	- globals.php - contains functions that shoulde be globally available
	- array.php - contains helper function for working with arrays
- **/routes** - folder containig route list
	- api.php - list of api routes
	- view.php - list of view/web routes
- **/config** - folder containig config files
	- container.php - config file for container
	- database.php - config file for database connection
	- middlewares.php - config file for middlewares

**Todos:**
 - JWT
 - Observers?
