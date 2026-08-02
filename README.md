# HoraDev

Plataforma para desenvolvedores terem controle de seus projetos.

> ⚠️ **Projeto em desenvolvimento.** Este é um projeto de estudo, construído aos poucos enquanto aprendo. Ainda estou testando ideias e decisões, então a estrutura pode mudar bastante até chegar num formato final.

## O que é o HoraDev

O HoraDev é uma plataforma pensada para devs organizarem seus próprios projetos: acompanhar o que está em andamento, registrar o progresso e ter uma visão central de tudo o que se está construindo — seja um projeto pessoal, freelance ou de estudo.

A ideia surgiu de uma necessidade bem prática: como dev, é fácil acabar com vários projetos espalhados, sem um lugar único pra acompanhar o andamento de cada um. O HoraDev busca resolver isso, dando um espaço simples onde o desenvolvedor cadastra seus projetos e acompanha a evolução deles.

O nome carrega também uma ideia futura: além de organizar os projetos, a plataforma pretende ajudar o dev a entender quanto tempo (quantas "horas dev") está de fato dedicando a cada um.

## De onde veio a base

Para não começar do zero, reaproveitei o sistema de autenticação do [Login-System-PHP](https://github.com/HenriqueC-r/Login-System-PHP), projeto que desenvolvi em conjunto com a [Mirian](https://github.com/mirianrl) como parte de uma avaliação técnica. Naquele projeto, eu cuidei do backend em PHP (autenticação segura, com PDO, password_hash, JWT e proteção CSRF) enquanto a Mirian desenvolveu o frontend em Vue.js/Quasar.

Aquele sistema de login serviu como ponto de partida sólido pro HoraDev: em vez de reconstruir autenticação, pude focar direto no que é o core da plataforma — o controle de projetos em si, começando pelo cadastro e listagem.

## Onde o projeto está agora

O HoraDev ainda está nos primeiros passos: a autenticação já está reaproveitada e funcionando, e o módulo de projetos está começando a ganhar forma, com as primeiras funcionalidades de criar e listar projetos. A ideia é ir evoluindo aos poucos — testando, ajustando e aprendendo no processo — até chegar numa plataforma completa de gestão de projetos para devs.

## Autor

Desenvolvido por [Henrique (HenriqueC-r)](https://github.com/HenriqueC-r), estudante de Engenharia de Software e desenvolvedor backend.
