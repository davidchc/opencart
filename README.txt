=== Módulo de integração PagSeguro + Opencart ===
Contributors: 
    ldmotta(visie.com.br - Implementação do múdulo da visie),

Module version: 1.0.2
Tags: pagseguro, opencart
Tested up to: Opencart v1.5.0.5
Requires at least: 1.0.5.2
Stable tag: 1.5.1.3

Módulo de integração do Opencart com o Pagseguro

== Description ==

Permite que o OpenCart utilize o geteway de pagamento PagSeguro, de forma fácil e intuitiva, contém todas as
ferramentas necessárias a esta integração.


Algumas notas sobre as seções acima:

*   "Contributors" Lista de contribuidores para construção do módulo separados por vírgula
*   "Tags" É uma lista separada por vírgulas de tags que se aplicam ao plugin
*   "Requires at least" É a menor versão do plugin que irá trabalhar em
*   "Tested up to" É a versão mais alta do e-commerce utilizado com sucesso para testar o plugin *. Note-se que ele pode trabalhar em
versões superiores ... Este é apenas um mais alto que foi verificado.

== Installation ==

Passos para instalação

1. Descompacte o módulo na raiz da sua instalação do OpenCart
2. Instale o módulo na sesão Extensions -> Payment na área administrativa do OpenCart
3. Ative o módulo da página de edição do módulo em Extensions -> Payment -> Edit
4. Defina a url de retorno no site do pagseguro (https://pagseguro.uol.com.br/preferences/automaticReturn.jhtml) como:
   "http://seu_dominio.com.br/notification.php"

== Perguntas Frequentes ==

= Eu posso instalar o meu módulo sem ter conhecimentos de php ou qualquer linguagem de programação? =

Pode, você só precisa ter conhecimentos em transferência de dados via FTP ou SFTP, ter os dados de acesso
ao servidor onde está hospedado a sua aplicação, e ter um gerenciador de arquivos FTP como o FileZilla
(http://filezilla-project.org/). Entretanto, recomendamos enfaticamente que procure um técnico da área.

= O módulo não funcionou na minha loja, o que fazer? =

Se já verificou a versão da sua loja virtual e ela e a versão testada com o módulo, e ainda assim não funciona,
entre em contato com o desenvolvedor atravéz do endereço http://motanet.com.br.

== Changelog ==

= 1.0.1 =
* Correção da imagem no título da tabela de configuração do meio de pagamento
* Suporte a desconto

= 1.0.0 =
* Implementação da integração do opencart com o geteway pagseguro

= 1.0.2 =
* Alterando a key weight_class para weight_class_id conforme a nova especificaçao do opencart
Para maiores informações acesse http://visie.com.br/pagseguro
