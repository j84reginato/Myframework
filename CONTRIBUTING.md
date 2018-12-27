# CONTRIBUINDO

## RECURSOS

Se você deseja contribuir com este projeto, não se esqueça de ler os seguintes recursos:

 - [Padrões de codificação](https://github.com/j84reginato/MyFramework)
 - [Guia do Colaborador](https://github.com/j84reginato/MyFramework)

## Relatando possíveis problemas de segurança

Se você encontrou uma potencial vulnerabilidade de segurança, por favor **NÃO** relate isso ao público. Envie-o para (mailto: j84reginato@gmail.com).
Trabalharemos com você para verificar a vulnerabilidade e corrigi-la o mais rápido possível.

Ao relatar problemas, forneça as seguintes informações:

- Componente(s) afetados;
- Uma descrição que indica como reproduzir o problema;
- Um resumo da vulnerabilidade e impacto de segurança.

Solicitamos que você entre em contato através do endereço de e-mail acima e forneça aos contribuidores do projeto a chance de resolver a vulnerabilidade e emitir uma nova versão antes de qualquer exposição pública.

## Fluxo de trabalho recomendado para contribuições

Seu primeiro passo é estabelecer um repositório público a partir do qual possamos *"puxar"* seu trabalho para o repositório principal.
Recomendamos usar [GitHub](https://github.com), porque é onde o componente já está hospedado.

1. Configure uma [conta do GitHub](https://github.com/), se ainda não tiver
2. Faça *"fork"* do [repositório](https://github.com/j84reginato/MyFramework)
3. Clone o repositório localmente.

```bash
$ git clone https://github.com/j84reginato/MyFramework.git
$ cd MyFramework
```

4. Adicione um novo repositório remoto no Git ao seu *"fork"*;

*Substitua seu nome de usuário do GitHub no comando abaixo.*

```bash
$ git remote add {username} git@github.com:{username}/MyFramework.git
$ git fetch {username}
```

### Manter-se atualizado

Periodicamente, você deve atualizar seu fork ou repositório pessoal para corresponder ao repositório canônico.
Supondo que você tenha configurado seu repositório local pelas instruções acima, você pode fazer o seguinte:


```bash
$ git checkout master
$ git fetch origin
$ git rebase origin/master
# OPCIONALMENTE, para manter seu controle remoto atualizado
$ git push {username} master:master
```

Se você estiver rastreando outros *"branches"* - por exemplo, o *"branch develop"*, onde o desenvolvimento de novos recursos ocorre - você vai querer fazer as mesmas operações para aquele *"branch"*; simplesmente substitua *"develop"* por *"master"*.

### Trabalhando em um *"patch"*

Recomendamos que você faça cada novo recurso ou correção de bugs em um novo *"branch"*.
Isso simplifica a tarefa de revisão de código, bem como a tarefa de mesclar suas alterações para o repositório.

Um fluxo de trabalho típico consistirá do seguinte:

1. Crie um novo *"branch"* local baseado no seu *"master"* ou *"develop branch"*;
2. Realize um *"Switch"* para o seu novo *"branch"* local. (Este passo pode ser combinado com o passo anterior com o uso de `git checkout -b`.)
3. Faça algum trabalho, realize o *"commit"*, repita conforme necessário.
4. Envie o *"branch"* local para o seu repositório remoto.
5. Envie um *"pull request"*.

A mecânica desse processo é na verdade bem trivial.
Abaixo, temos um exemplo de um *"branch"* para corrigir um problema.

```bash
$ git checkout -b hotfix/9977
Switched para um novo branch 'hotfix/9977'
```

... faça algum trabalho ...


```bash
$ git commit
```

... escreva sua mensagem de log ...


```bash
$ git push {username} hotfix/9977:hotfix/9977
Counting objects: 38, done.
Delta compression using up to 2 threads.
Compression objects: 100% (18/18), done.
Writing objects: 100% (20/20), 8.19KiB, done.
Total 20 (delta 12), reused 0 (delta 0)
To ssh://git@github.com/{username}/MyFramework.git
   b5583aa..4f51698  HEAD -> master
```

Para enviar um *"pull request"*, você tem duas opções:

Se estiver usando o GitHub, você pode fazer o pedido de *"pull"* de lá.
Navegue até o seu repositório, selecione o *"branch"* que você acabou de criar e, em seguida, selecione Botão *"Pull Request"* no canto superior direito.
Selecione o usuário/organização "j84reginato" como o destinatário.

Se estiver usando seu próprio repositório - ou mesmo usando o GitHub - você pode usar o `git format-patch` para criar um patchset; na verdade, isso é **recomendado** por segurança.
Se você usa `format-patch`, por favor envie os patches como anexos para: j84reginato@gmail.com

#### Qual *"branch"* emitir um *"pull request"*?

Em qual *"branch"* você deve enviar o *"pull request"*?

- Para correções na versão estável, emita o *"pull request"* no *"branch master"*.
- Para novos recursos ou correções que introduzam novos elementos à API (como novos métodos ou propriedades), emita o *"pull request"* no *"branch develop"*

### Limpeza do *"branch"*

Depois de saber que suas alterações foram aceitas para o repositório master, sugerimos fazer a limpeza desses *"branches"*.

- Limpeza do *"branch"* local

```bash
$ git branch -d <branchname>
```

- Remoção remota do *"branch"*

```bash
$ git push {username} :<branchname>
```

## Código de Conduta

Consulte nosso [CONDUCT.md](CONDUCT.md) para entender o comportamento esperado ao interagir com outras pessoas no projeto.