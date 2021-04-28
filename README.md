# wp-plugin-nfn-css-cleanup
A WordPress plugin that handles HTML cleanup.

# Installation
To install this plugin within a WordPress project, add the repository to
the `composer.json` file:

```json
"repositories": [
  ...
  {
    "type": "vcs",
    "url": "git@github.com:G00times/wp-plugin-nfn-css-cleanup.git"
  },
  ...
]
```

Then run

`docker-compose run --rm cmd composer install`

# Updating

Run
```shell
docker-compose run --rm cmd composer update G00dtimes/wp-plugin-nfn-css-cleanup
```
