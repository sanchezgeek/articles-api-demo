# Articles API demo

## Install
- ``` docker-compose up -d ```
- ``` docker-compose exec php composer install ```
- ``` docker-compose exec php bin/console doctrine:migrations:migrate ```

## API

Create tags:

```sh
curl --location --request POST 'http://localhost:8889/api/v1/tags' \
--header 'Content-Type: application/json' \
--data-raw '{
    "name": "Tag name"
}'
```

Edit tag:

```sh
curl --location --request PUT 'http://localhost:8889/api/v1/tags/{tagId}' \
--header 'Content-Type: application/json' \
--data-raw '{
    "name": "New tag name"
}'
```

Create articles:

```sh
curl --location --request POST 'http://localhost:8889/api/v1/articles' \
--header 'Content-Type: application/json' \
--data-raw '{
    "title": "Article title",
    "tags": [
        1, 2, 3
    ]
}'
```

Edit article:

```sh
curl --location --request PUT 'http://localhost:8889/api/v1/articles/{articleId}' \
--header 'Content-Type: application/json' \
--data-raw '{
    "title": "New article title",
    "tags": [
        2, 3, 4
    ]
}'
```

Delete article:

```sh
curl --location --request DELETE 'http://localhost:8889/api/v1/articles/{articleId}' \
--header 'Content-Type: application/json'
```

List articles by tags:

```sh
curl --location --request GET 'http://localhost:8889/api/v1/articles' \
--header 'Content-Type: application/json' \
--data-raw '{
    "tags": [
        "tag1",
        "tag2",
        "tag3"
    ]
}'
```
View specified article:

```sh
curl --location --request GET 'http://localhost:8889/api/v1/articles/{articleId}' \
--header 'Content-Type: application/json'
```