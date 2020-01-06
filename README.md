# Desafio Back-End AlpesOne (seminovosbh)

## Método de uso

1 - Clone o repositório com o comando ... na pasta desejada

2 - Abra o terminal e entre pasta utilizando ‘cd [diretório da pasta do projeto]’ e após rode o comando ‘composer install’.

3 - Rode o comando ‘php artisan serve’ para iniciar o servidor.

4 - Para efetuar requisições aponte para o endereço ‘localhost:8000/api/’ utilizando o postman ou insomnia para testar.


#### [POST] api/find
Rota utilizada para buscar os anúncios de acordo com os parâmetros fornecidos.

##### Parametros
- vehicule: "carro" | "moto" | "caminhao" (*)
- brand: string (*)
- model: string (*)
- minValue: int
- maxValue: int
- conservationState: "new" | "used"
- cities: string  (Separadas por vigula)
- startYear: int
- endYear: int
- page: int\
(*) Campos obrigatórios

![Find](https://user-images.githubusercontent.com/8294278/71798111-be7f1700-3048-11ea-8356-870e27980203.png)

#### [GET] api/details/{id}
Rota utilizada para buscar os detalhes de um automovel específico.

![detail](https://user-images.githubusercontent.com/8294278/71798121-d0f95080-3048-11ea-8cf5-73c2b41427f7.png)
