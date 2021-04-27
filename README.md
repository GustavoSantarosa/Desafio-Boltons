# Desafio Boltons
 
[Desafio Boltons](https://www.notion.so/Desafio-Boltons-f40fd110d22a4fa3a6f8eb38defb62d1)

**Sobre o desafio.**

Esse desafio é proposto para avaliarmos o conhecimento da linguagem proposta e a assinatura de código do participante.O desafio propoe um caso de uso, simulando os desafios do dia-a-dia da Arquivei.Atualmente na arquivei temos um **API** para a disponibilização de documentos do tipo nota fiscal eletrônica (NFe), onde o cliente faz uma chamada utilizando **REST** e devolvemos de 0 até 50 arquivos XML com um encode em **base64**. O desafio proposto é:

1. Integrar com a API da arquivei utilizando, os seguintes dados:

- Endpoint: [https://sandbox-api.arquivei.com.br](https://sandbox-api.arquivei.com.br/)
- Credenciais:
    - As credenciais devem ser inseridas do cabeçalho da requisição (header):
        - x-api-id: f96ae22f7c5d74fa4d78e764563d52811570588e
        - x-api-key: cc79ee9464257c9e1901703e04ac9f86b0f387c2
- Encoding:
    - No cabeçalho também deve ser enviado o parâmetro content-type, com o valor "application/json".

Exemplo em cURL da request:

```bash
curl -X GET \
  https://sandbox-api.arquivei.com.br/v1/nfe/received \
  -H 'Content-Type: application/json' \
  -H 'x-api-id: f96ae22f7c5d74fa4d78e764563d52811570588e' \
  -H 'x-api-key: cc79ee9464257c9e1901703e04ac9f86b0f387c2'
```

Caso ainda tenha dúvidas você pode consultar a documentação em: [https://docs.arquivei.com.br/?urls.primaryName=Arquivei%20API](https://docs.arquivei.com.br/?urls.primaryName=Arquivei%20API)

2. Para cada nota retornada via API inserir a **chave de acesso** mais o **valor** total da nota**,** em um banco de dados relacional de sua escolha, até o fim dos registros.3. Criar um endpoint (REST) no projeto onde o usuário informe uma **chave de acesso** e o sistema retorne o **valor** do documento.

# O que mais?

- Documente como seu projeto se comporta e o passo-a-passo para a utilização do mesmo.
- Crie um repositório github ou bitbucket e envie seus arquivos.
- Utilize docker.

# Extra

- Principios de código limpo (S.O.L.I.D)
- Desacoplamento
- Arquitetura em camadas (ou Hexagonal)
- Testes unitários.

E bom desafio.

## Envio do Desafio

O link público para o repositório GIT e as informações relevantes devem ser enviadas para o email [**desafio.engenharia@arquivei.com.br**](mailto:desafio.engenharia@arquivei.com.br) com o assunto **Desafio Back End**