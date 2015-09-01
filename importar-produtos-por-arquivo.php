<?php
	$arquivo = ABSPATH . 'tabela_produtos.txt';

	$f = fopen( $arquivo, "r");

	$prodAtual = "";

	// Lê cada uma das linhas do arquivo
	while(!feof($f)) {

	    $data = explode(",", fgets($f) );

		//Novo produto
		//if( $data[0] != "" ){
		if( $data[0] != "" ){

			echo "<pre>i:$i / diferente de prod atual</pre>";

			//Caso já tenha algum produto cadastrado anteriormente, então o salvo antes de começar
			if( $prodAtual != "" ) {
				salva_produto($prodAtual, $cat, $obs, $arrayFinal);
				echo "<pre>Salvando $prodAtual</pre>";
			}

			$prodAtual = $data[0];
			$cat       = $data[1];
			$obs       = $data[2];

			$arrayFinal = array();

			$arrayTemp = array(
								"tipo"       => "simples",
								"referencia" => $data[3],
								"variacao"   => $data[4]
							);
			array_push($arrayFinal, $arrayTemp);

			$i++;

		} else {

			echo "<pre>i:$i / cai no else</pre>";

			//Pertence ao mesmo produto, logo continua com o cadastro no mesmo produto
			$arrayTemp = array(
								"tipo"       => "simples",
								"referencia" => $data[3],
								"variacao"   => $data[4]
							);
			array_push($arrayFinal, $arrayTemp);

		}

	}

	fclose($f);

	function salva_produto($prodAtual, $cat, $obs, $arrayFinal) {

		echo "Produto:$prodAtual / Categoria: $cat / Obs: $obs<br/>";

			echo  ajusta_char( json_encode($arrayFinal) );

		echo "<br/><br/><br/><br/>";

		/*
		$arrayFinal = ajusta_char( json_encode($arrayFinal) );

		$my_post = array(
		  'post_title'    => $prodAtual,
		  'post_content'  => '',
		  'post_status'   => 'publish',
		  'post_author'   => 1,
		  'post_type'     => 'produtos'
		);
		$post_id = wp_insert_post( $my_post );

		wp_set_object_terms($post_id, $cat , 'categoria_produtos' );

		$cores = array(
			'ameixa-negra',
			'branco',
			'cinza-cristal',
			'geneve',
			'laricina',
			'ovo',
			'preto'
		);
		wp_set_object_terms($post_id,  $cores , 'cor_produtos' );


		update_post_meta($post_id, "meta_json_danwidgets", $arrayFinal);*/

	}

	function ajusta_char($string){

		$string = preg_replace_callback('/\\\\u([0-9a-fA-F]{4})/', function ($match) {
		    return mb_convert_encoding(pack('H*', $match[1]), 'UTF-8', 'UCS-2BE');
		}, $string);

		return $string;
	}

	function wp_exist_post_by_title($title_str) {

		global $wpdb;
		return $wpdb->get_row("SELECT * FROM wp3ha45_posts WHERE post_title = '" . $title_str . "'", 'ARRAY_A');

	}
