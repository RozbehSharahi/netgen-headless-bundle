# Netgen Headless Bundle

## Installation

## 1 Install package

`composer require rozbehsharahi/netgen-headless`

## 2 Install bundles

```
// add to bundles.php
Rs\NetgenHeadless\RsNetgenHeadlessBundle::class => ['all' => true],
Overblog\GraphQLBundle\OverblogGraphQLBundle::class => ['all' => true],
```

## 3 Add routing

```
// add to config/routes.yaml
netgen-headless:
  resource: "@RsNetgenHeadlessBundle/Resources/config/routing.yaml"
  prefix:   /ngh
```

## 4 Add GraphQL Yaml

```
// add to config/packages/graphql.yaml
overblog_graphql:
    definitions:
        schema:
            query: Query
        mappings:
            types:
                -
                    type: yaml
                    dir: "%kernel.project_dir%/config/graphql/types"
                    suffix: null
```

## 5 Prepare graphql types directory

```
# Create file config/graphql/types/.gitignore
```

