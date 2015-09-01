#Scripts para importação de itens no Wordpress

Scripts criados  de acordo com necessidades específicas de clientes.
Nada foi criado pensando em englobar todos os casos possíveis (pelo menos não por enquanto), apenas armazeno o que foi feito para referência futura.
Caso alguém se interesse, modifique e use da melhor forma que desejar.

###importar-produtos-por-pastas.php

Criado para importar produtos que estão organizados dentro de um determinado padrão em uma pasta.

###importar-produtos-via-arquivo.php

Criado para ler um arquivo txt, pegar os campos no arquivo (nome, descrição, campos personalizados, taxonomias, etc) e os cadastra junto com o novo item.
Além disso ele pega um campo com um caminho de onde estão as imagens daquele item e registra aquelas fotos naquele post (as fotos precisam já estar no ftp!).
Neste caso, para evitar timeout, estou apenas registrando as imagens sem criar as miniaturas delas. As miniaturas podem ser criadas posteriormente com o uso do plugin Regenerate Thumbnail.

---
**Créditos de contribuição**: Filipe Perina
