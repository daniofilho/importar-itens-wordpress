   <input type="button" id="envia" value="enviar" />

   <script>

      jQuery(document).ready(function($) {

         $('#envia').click(function(){
            var arrayOfLines = $('#itens').val().split('\n');
            $.each(arrayOfLines, function(index, item) {
                 do_ajax(item);
            });
				console.log('finalizei');
         });

         function do_ajax(item){
             $.ajax({
                type: "POST",
                url: "ENDERECO_DA_API",
                data: { pwd: 'senha', itens: item },
                success: function(data){
                  console.log(data);
                }
            });
         }

      });

   </script>
	<?php

	$arquivo = ABSPATH . 'itens_para_site.csv';
	$f = fopen( $arquivo, "r");
	$prodAtual = "";

	echo "<textarea id='itens' style='width:100%;height:400px;'>";

	// Lê cada uma das linhas do arquivo
	while(!feof($f)) {
		 $data = explode(";", fgets($f) );


		 /*

			Especificações deste cliente

		 	0 = codigo
			1 = descricao / titulo
			2 = especificacoes
			3 = peso bruto
			4 = peso liquido
			5 = unidade
			6 = observação
		 */


		 //Cria o json das categorias para cadastrar pela api

		 	/*

				Neste cliente eu criei um textarea com um json para enviar para api em cada linha. Logo meu script pegava linha por linha e enviava para a api um por vez, o que me permitiu importar mais de 2000 itens sem timeout no servidor
				já que era executado um por vez. Esse processo demorou aproximadamente 30 minutos.

				A ideia aqui foi primeiro cadastrar as categorias pai (2 digitos no código), mas sub (4) e as sub sub (6). No final cadastrar os produtos (10), já que as categorias já estavam certas.
			*/

		 if( strlen($data[0]) == 10  ) {

			 //Fiz esse tratamento porque a string estava imprimindo \n dentro do JSON e isso estava quebrando o esquema de linha por linha da textarea
			 $obs = utf8_encode($data[6]);
			 $obs = preg_replace('/[\x00-\x1F\x7F]/', '', $obs);

			 echo '{"codigo": "'.$data[0].'", "descricao": "'.$data[1].'", "especificacao": "'.utf8_encode($data[2]).'", "pesoliq": "'.$data[4].'", "pesobru": "'.$data[3].'", "unidade": "'.$data[5].'", "obs": "'.$obs.'", "tipo": "g", "ativo": "s"}' . "\n";


		 }


	}
	fclose($f);

	echo "</textarea>";

	?>
