<img src="https://pa1.narvii.com/6326/c8646512f329d218c7df8860b03fa28cb7677896_hq.gif" width="100%">

<h3>Bem vindos ao Beco Diagonal </h3>
<p>Onde Bruxos do mundo todo podem comprar tudo que é necessário para bruxarias.<p>
<p>No momento, o beco diagonal está em reforma em algumas lojas, mas você ainda pode usar nosso banco Gringotts Bank<p>
    
<hr>
    
<h3> Gringotts Bank </h3>
<img src="https://img.wattpad.com/3ca02b02d3cfa8d2f472a8eab5bc598b64c82193/68747470733a2f2f73332e616d617a6f6e6177732e636f6d2f776174747061642d6d656469612d736572766963652f53746f7279496d6167652f5033736b385f5859594249512d413d3d2d3539343134303439342e313533623439636538376364373935613732303730323735363836342e676966?s=fit&w=720&h=720" width="100%">

<p>Dobby sabe que para acessar Gringotts Bank é necessário ter php e algumas outras dependências guardadas em seu bolso, mas não se preocupe,Dobby tem um amigo e Bruxo chamado Docker, ele cuidou disso para a gente, fazendo uma magia chamada containers.</p>
<p>Então, antes de mais nada, Dobby acha que você precisa fazer algumas coisinhas para liberar seu acesso ao Gringotts</p>
<p>Primeiro</p>
<ul>
    <li>Antes de iniciar, troque o nome do nosso arquivo .env.example para .env com a magia de sua preferência</li>
    <li>Agora abra seu terminal onde se possa executar magias</li>
    <li>Vamos usar a magia do docker, execute para iniciar os containers <code> docker-compose up -d </code> </li>
    <li>Prontinho, agora execute a magia de acessar o container php, <code>docker-compose exec php-fpm bash</code> </li>
    <li><code>composer install</code> essa magia irá baixar as dependências que o docker guardou para a gente sobre o Gringotts</li>
    <li>Agora, Dobby e você já podemos entrar no banco, mas precisamos de executar uma magia que irá liberar nosso acesso aos dados do banco, "irá gerar as tabelas"<code>php artisan migrate</code>. Você irá receber um alerta sobre termos de uso dizendo a seguinte menssagem, <code> Application In Production! </code>, basta aceitar com a magia do <code> yes </code> </li>
    </li>Prontinho, agora Dobby e você já consegue usar as funcionalidades do gringotts</li>
</ul>
<p>Como usar o Gringotts Banco</p>
<ul>
    <li>Primeiro efetue a magia de criar um usuário</li>
    <li>depois deposite dinheiro em sua conta com a magia</li>
    <li>Por fim, você consegue efetuar transferências</li>
</ul>
<p>Dobby precisa se despedir agora,Dobby irá deixar uma documentacao com você sobre a api do gringotts</p>
<code>https://app.swaggerhub.com/apis-docs/RuanRosa/gringotts/1.0.0#/</code>

<h4>Proposta de melhoria</h4>
<p>Eu Hermione acho que poderia retirar os erros customizados, tipar as repostas das funcoes como :void e retornar exceptions</p>
