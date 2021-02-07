# Netgen Headless Bundle

## Installation

This chapter is separated into main installation and debug installation. Please Keep in mind **Symfony Flex** will by itself do most of the work for you when requiring with composer. 

Therefore it should be any enough to run following and accept recipes:

```
$ composer require rozbehsharahi/netgen-headless
$ composer require --dev overblog/graphiql-bundle
```

Nevertheless in the following chapters you see how you could install manually. 

| A main difference on manual installation will be that you will have the route prefix:<br /><br />Example: /gql/graphiql |
| --- |

### 1 Install package

`composer require rozbehsharahi/netgen-headless`

### 2 Install bundles

```
// add to bundles.php
Rs\NetgenHeadless\NetgenHeadlessBundle::class => ['all' => true],
Overblog\GraphQLBundle\OverblogGraphQLBundle::class => ['all' => true],
```

### 3 Add routing

```
// add to config/routes.yaml
netgen-headless:
  resource: "@NetgenHeadlessBundle/Resources/config/routing.yaml"
  prefix:   /netgen-headless
```

### 4 Add GraphQL Route

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
