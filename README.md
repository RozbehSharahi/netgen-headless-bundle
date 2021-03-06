# Netgen Headless Bundle

![Main Build](https://travis-ci.com/RozbehSharahi/netgen-headless-bundle.svg?branch=main)

## Installation

Symfony flex should do the configuration automatically. So step 2 and 3 are normally not needed.

### 1 Install

```
$ composer require rozbehsharahi/netgen-headless-bundle

// This package has no release yet, so you will need to install like following in fact:
$ composer require rozbehsharahi/netgen-headless-bundle:dev-main
```

In case symfony flex is missing something, check the following steps:

### 2 Activate bundles

```
// add to bundles.php
Overblog\GraphQLBundle\OverblogGraphQLBundle::class => ['all' => true],
Rs\NetgenHeadlessBundle\NetgenHeadlessBundle::class => ['all' => true],
```

### 3 Add GraphQL Route

```
overblog_graphql_endpoint:
    resource: "@OverblogGraphQLBundle/Resources/config/routing/graphql.yml"
    prefix: '' // add here a prefix for graphql in case you don't want graphql to register on `/`
```

### Try it out

```
// Please replace http://localhost with your host in case it is not localhost
curl -X POST -H "Content-Type: application/json" -d '{ "query": "{ sayHello }"  }' http://localhost/graphql/netgen
```

**Notice** that this bundle serves a separated schema. Therefore, please do not forget to append the schema `netgen` to your graphql endpoint:

`http://localhost/graphql/netgen`

## Installation of GraphIQL for debugging and documentation

GraphIQL is a nice way to discover what this bundle offers. Symfony Flex should configure your
 application automatically. Therefore, step 2 & 3 are normally not needed.

### 1 Install package

```
composer require --dev overblog/graphiql-bundle
```

### 2 Activate bundle

```
// config/bundles.php
Overblog\GraphiQLBundle\OverblogGraphiQLBundle::class => ['dev' => true],
```

### 3 Add route

```
// config/routes/dev/graphiql.yaml
overblog_graphiql:
    resource: "@OverblogGraphiQLBundle/Resources/config/routing.xml"
```

### 4 Try it out

Now you can browse to `http://localhost/graphiql/netgen`.

**Notice:** Please do not forget to add the schema `/netgen` to your graphiql route.

## Usage

This package provides a graphql endpoint which depending on the configuration made can be reached
via `http://your-domain.com/graphql/netgen` for instance.

### Example Query

```
{
    layout(search: { request: { uri: "/hello-world" } }) {
        id # receive id
        json # receive json of whole layout
        zones {
            identifier # receive identifier
            json # receive json of zone
        }
    }
}
```

## Run tests and application

This is a self-contained bundle containing also a test application in `Tests/Application`.

I developed this on my own docker-compose base repository: `https://github.com/RozbehSharahi/doka`. Feel free to use
your this as well or your own setup.

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
Graphql: http://localhost:8080/graphql/netgen
Graphiql: http://localhost:8080/graphiql/netgen
```
