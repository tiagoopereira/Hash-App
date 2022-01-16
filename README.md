# Hash-App
### Execução
####  Makefile:
    - make run
    - porta utilizada: 8000
#### Rodando separadamente
    - composer install (Para instalar as dependências e gerar o arquivo de autoload).
    
    > Utilizando Docker:
        - docker-compose up -d
        - docker exec -it hash_app php bin/console doctrine:migrations:migrate --no-interaction
        - porta utilizada: 8000
          
    > Utilizando somente PHP:
        - Necessário PHP:8.*
        - php bin/console doctrine:migrations:migrate --no-interaction
        - php -S 0.0.0.0:8000 -t public/

#### Rotas
  - /api/hashes/generate/{string} [GET] => gerar hashes baseadas na string de entrada e retorná-las.
    > Retorno:
      ```json
          {
            "hash": "string",
            "key": "string",
            "attempts": "int"
          }
      ```

  - /api/hashes/ [GET] => listar todas as hashes criadas.
    > Filtros:
    ```
      - limit = quantidade de itens por página.
      - offset = página.
      - filter:
        - filter[key] = campo que deseja filtrar.
        - filter[exp] = tipo de filtro que deseja fazer (baseado nos tipos do QueryBuilder do Doctrine).
        - filter[value] = valor desejado para o filtro.
     ```
      > Retorno:
      ```json
          {
            "success": "bool",
            "limit": "int",
            "page": "int",
            "data": [
                {
                    "batch": "datetime",
                    "block": "int",
                    "string": "string",
                    "key": "string"
                },
            ]
          }
      ```
     
#### Command
  ```
    (docker exec -it hash_app) php bin/console avato:test {string} --requests={number}
  ```
  - *string*: string de entrada para base do hash.
  - *number*: número de requests que deseja executar (opcional/default: 1)
  - <strong>Obs:</strong> caso o número de requests seja maior que 1, nas requisições seguintes serão utilizadas  como string de entrada sempre o valor da hash  gerado na requisição anterior.
