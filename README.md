# Netgen Headless Bundle

## Installation

This chapter is separated into main installation and debug installation. Please Keep in mind **Symfony Flex** will by itself do most of the work for you when requiring with composer. 

Therefore it should be any enough to run following and accept recipes:

```
$ composer require rozbehsharahi/netgen-headless
$ composer require --dev overblog/graphiql-bundle
```

In case symfony flex is missing something, check the following steps:

### 1 Install bundles

```
// add to bundles.php
Rs\NetgenHeadless\NetgenHeadlessBundle::class => ['all' => true],
Overblog\GraphQLBundle\OverblogGraphQLBundle::class => ['all' => true],
```

### 2 Add routing

```
// add to config/routes.yaml
netgen-headless:
  resource: "@NetgenHeadlessBundle/Resources/config/routing.yaml"
  prefix:   /netgen-headless
```

### 3 Add GraphQL Route

```
overblog_graphql_endpoint:
    resource: "@OverblogGraphQLBundle/Resources/config/routing/graphql.yml"
    prefix: 'graphql'
```

## Installation of GraphIQL for debugging

### 1 Install package 

```
composer require --dev overblog/graphiql-bundle
```

### 2 Install bundle

```
// config/bundles.php
Overblog\GraphiQLBundle\OverblogGraphiQLBundle::class => ['dev' => true],
```

### 3 Add route

```
// config/routes/dev/graphiql.yaml
overblog_graphiql:
    resource: "@OverblogGraphiQLBundle/Resources/config/routing.xml"
    prefix: 'graphql'
```

## Run tests and application

This is a self-contained bundle containing also a test application in `Tests/Application`.

I developed this on my own docker-compose base repository: `https://github.com/RozbehSharahi/doka`. Feel free to use your this as well or your own setup.

```
// In root dir call:
git clone https://github.com/RozbehSharahi/doka.git

// Create an empty .doka.env as described on https://github.com/RozbehSharahi/doka and add:
DOKA_APP_APACHE_DOCUMENT_ROOT=/var/www/html/Tests/Application/public/

// Start docker env
doka/compose up -d

// Enter docker-container and run tests
doka/enter-app
vendor/bin/phpunit

// If you don't want to have db created all over again, you can create a file (git ignored) on root directory called `fast-tests`
touch fast-tests
```

### Access test application

```
# enter cli
doka/enter-app

# run once, whenever you call this you will have a clean and empty db again.
Tests/Application/setup-test-application

# Now go to
App: http://localhost:8080
Netgen Layouts: http://localhost:8080/nglayouts/admin (User: root, Password: root)
Graphql: http://localhost:8080/graphql/
Graphiql: http://localhost:8080/graphql/graphiql
```
