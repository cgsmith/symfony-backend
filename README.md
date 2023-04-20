# GitHub Repo Backend

Run the following steps to install

1. If not already done, install [Docker Compose](https://docs.docker.com/compose/install/) (v2.10+)
1. Run `docker compose build --pull --no-cache` to build fresh images
1. Run `docker compose up` (the logs will be displayed in the current shell)
1. Open https://localhost in your favorite web browser and accept the auto-generated TLS certificate
1. Run docker compose down --remove-orphans to stop the Docker containers.

## API

API is documented at https://localhost/api/doc and uses NelmioApiDocBundle for generation. You can also goto 
https://localhost/api/repos directly through Postman and also pass `?fullName` as a parameter.

## CLI

Before running the CLI you should generate a GitHub Access Token to allow repo creation and deletion.

* `php bin/console app:create-repo reponame` will create a repo under your user with the specified `reponame` argument
* `php bin/console app:delete-repo owner/reponame` will delete a repo if it exists in the database and on Github. `owner/reponame` must be specified

