app/AppKernel.php
```php
new BorysZielonka\ApiStoreProductBundle\BorysZielonkaApiStoreProductBundle()
```


app/config/routing.yml
```yml
    borys_zielonka_api_store_product:
        resource: "@BorysZielonkaApiStoreProductBundle/Controller/"
        type:     annotation
        prefix:   /
```


app/config/config.yml
```yml
fos_rest:
    body_listener: true
    format_listener:
        rules:
            - { path: '^/', priorities: ['json'], fallback_format: json, prefer_extension: false }
    param_fetcher_listener: true
    view:
        view_response_listener: 'force'
        formats:
            json: true

nelmio_cors:
    defaults:
        allow_credentials: false
        allow_origin: ['*']
        allow_headers: ['*']
        allow_methods: ['GET', 'POST', 'PUT', 'DELETE', 'OPTIONS']
        max_age: 0
        hosts: []
        origin_regex: false
        forced_allow_origin_value: ~
```

composer.json
```json
    "repositories" : [
        {
        "type" : "vcs",
        "url" : "https://gitlab.com/boryszielonka/api-store-product-bundle.git"
        }
    ],
```

```sh
composer require friendsofsymfony/rest-bundle
composer require jms/serializer-bundle
composer require nelmio/cors-bundle
composer require boryszielonka/api-store-product-bundle dev-develop
composer require --dev doctrine/doctrine-fixtures-bundle
```

```
bin/console doctrine:database:create
bin/console doctrine:schema:create
```

INSERT records to database
```sh
bin/console doctrine:fixtures:load
```
or

```sql
bin/console doctrine:query:sql "INSERT INTO product (name, amount) VALUES ('Produkt 1',  4)"
bin/console doctrine:query:sql "INSERT INTO product (name, amount) VALUES ('Produkt 2',  12)"
bin/console doctrine:query:sql "INSERT INTO product (name, amount) VALUES ('Produkt 5',  0)"
bin/console doctrine:query:sql "INSERT INTO product (name, amount) VALUES ('Produkt 7',  6)"
bin/console doctrine:query:sql "INSERT INTO product (name, amount) VALUES ('Produkt 8',  2)"
```

