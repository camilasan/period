#  <img src="img/app.svg" width="30"> Period

How can you deal with it your menstrual cycle data while making sure big tech and health insurances do not have access to it?

Track your period without worrying about data privacy.

## 💾 Installation

- Place this app in ```<path to nextcloud>/apps/```
- [Enable it](https://docs.nextcloud.com/server/latest/admin_manual/apps_management.html)

## ⚙️ Building the app

### Requirements

- [A Nextcloud server running (> stable28)](https://docs.nextcloud.com/server/latest/admin_manual/installation/index.html)
- [Node.js](https://nodejs.org/en/)
- [Composer](https://getcomposer.org/)
- [Webpack](https://webpack.js.org/)

### 📝 General build instructions

- Install PHP dependencies: `composer install --no-dev`
- Install JS dependencies: `npm ci`
- Build JavaScript for the frontend
    - Development build `npm run dev`
    - Watch for changes `npm run watch`
    - Production build `npm run build`

### 🐋 Docker: Simple app development container

- Fork the app
- Clone the repository: `git clone https://github.com/camilasan/period.git`
- Go into deck directory: `cd period`
- Build the app as described in the general build instructions
- Run Nextcloud development container and mount the apps source code into it:
```bash
docker run --rm \
    -p 8080:80 \
    -v ~/path/to/app:/var/www/html/apps-extra/app \
    ghcr.io/juliushaertl/nextcloud-dev-php80:latest
```
> 🤓 https://juliushaertl.github.io/nextcloud-docker-dev/basics/standalone/

### 👩‍💻 Full Nextcloud development environment

- You need to setup a [development environment](https://docs.nextcloud.com/server/latest/developer_manual//getting_started/devenv.html) of the current Nextcloud version. 
- Alternatively you can use the [nextcloud docker container](https://github.com/juliushaertl/nextcloud-docker-dev).
- After the finished installation, you can clone the deck project directly in the `/[nextcloud-docker-dev-dir]/workspace/server/apps/` folder.

> 🤓 https://juliushaertl.github.io/nextcloud-docker-dev/basics/stable-versions/

## Running tests
You can use the provided Makefile to run all tests by using:
```bash
make test
```
This will run the PHP unit and integration tests and if a package.json is present in the **js/** folder will execute **npm run test**

Of course, you can also install [PHPUnit](http://phpunit.de/getting-started.html) and use the configurations directly:

```bash
phpunit -c phpunit.xml
```
or:
```bash
    phpunit -c phpunit.integration.xml
```
for integration tests.
