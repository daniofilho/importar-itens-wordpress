<?php

	//Caminhos
	define('root_path', ABSPATH);
	define('root_url', get_bloginfo('url') . "/");

	ini_set('max_execution_time', 90);

	echo "$root_path <br/> $root_url";

	//variáveis
	$max_dirs 		= 62; 										// Quantidade total de pastas
	// Caso o script dê timeout, continua o loop da pasta que parou (setando o número aqui)	
	// !! Cuidado porque ele vai ter gerado os thumbs da ultima imagem já, então apague da pasta pra ele não inserir essas imagens como imagens novas !!
	$dir_inicial    = 1;


	$main_folder 	= root_path ."wp-content/uploads/produtos_cliente";	// Pasta principal com todas as outras dentro
	$pattern 		= "produto-";								// Nome padrão das subpastas sem o número

	$dir 			= "$main_folder/$pattern";					// Juntando tudo em uma variável só (ainda sem o número

	echo '<div class="container-fluid jumbotron">';
		echo "<h3>Console de importação:</h3>";
		echo "<pre>";
			/*
				## loop por pastas ##
			*/
			for($i = $dir_inicial; $i <= $max_dirs; $i++){

				$current_dir = $dir . $i; // Adicionando o número na pasta

				/*
					Verificando se o diretório existe ou se é um diretório
				*/
					if (is_dir($current_dir)){

						/*
							Função para criar o post
						*/

							iDebug("# Entrando no diretório <strong>'$current_dir'</strong>");
							iDebug("# # Adicionando novo produto...");

							iDebug("# # # Nome do produto: <u>$pattern$i</u>", "success");
							$postCriado =  adicionaPost( $pattern.$i );
							iDebug("# # # ID do produto: <u>$postCriado</u>", "success");


						/*
							## Loop nas imagens ##
								Verificando se existem arquivos com as extensões definifas no atual diretório e armazenando em um array
						*/

							iDebug("# # # # Verificando se existem imagens no diretório atual ( $current_dir )", "comment");

							$files = glob("$current_dir/*.{jpg,png,gif,JPG,JPEG}", GLOB_BRACE);

							/*
								Caso exista imagem
							*/
							if($files){

								/*
									Loop em cada imagem
								*/
								$debug_msg  = "<ul class='list-group'>";
								foreach($files as $file) {

									/*
										cadastra a imagem na mídia
									*/
										$info_imagem = cadastraImagemMidia( $file, $postCriado );

										$img_msg = "<span>id:   <u>$info_imagem[0]</u><br/>Nome: <u>$info_imagem[1]</u><br/>Path: <u>$info_imagem[2]</u><br/>Url:  <u>$info_imagem[3]</u></span>";

									$debug_msg .= "<li  class='list-group-item'>$img_msg</li>";

								}
								$debug_msg .= "</ul>";
								iDebug($debug_msg, 'info');

							} else {
								iDebug("# # Nenhuma imagen encontrada!", "error");
							}


					} else {
						iDebug("# O diretório '$current_dir' não existe!", "error");
					}

			}

		echo "</pre>";
	echo '</div>';


	function iDebug($msg, $tipo=""){

		switch($tipo){
			case 'success':
				$cor = "#466E39";
				break;
			case 'info':
				$cor = "#3073B8";
				break;
			case 'warning':
				$cor = "#8F6D3B";
			case 'error':
				$cor = "#8F6D3B";
				break;
			case 'comment':
				$cor = "#AAAAAA";
				break;
			default:
				$cor = "#3073B8";
				break;
		}

		echo "<span style='color:$cor !important;'>$msg</span><br/>";

	}


	function cadastraImagemMidia( $file, $id_produto ) {

		//prepara os parâmetros
		$file_path = $file;

		$file_name = explode("/", $file);
		$file_name = $file_name[ count($file_name) - 1 ];

		$file_url = str_replace( root_path, root_url, $file_path);

		$wp_filetype = wp_check_filetype($file, null);
		$attachment = array(
		    'guid'           => $file_url,
		    'post_mime_type' => $wp_filetype['type'],
		    'post_title'     => $file_name,
		    'post_status'    => 'inherit',
		    'post_date'      => date('Y-m-d H:i:s')
		);

		//insere na mídia e já cria seus thumbs
		$attachment_id = wp_insert_attachment( $attachment, $file_path, $id_produto );
		require_once( ABSPATH . 'wp-admin/includes/image.php' );

		$attachment_data = wp_generate_attachment_metadata( $attachment_id, $file_path ); // !!! pode ser o gargalo para o caso de muitas imagens !!!
		wp_update_attachment_metadata( $attachment_id, $attachment_data );

		//define a imagem atual como destacada
		// (sim, todas as imagens serão destacadas, mas só a ultima ficará como atual)
		set_post_thumbnail( $id_produto, $attachment_id );

		return array( $attachment_id, $file_name, $file_path, $file_url );
	}


	function adicionaPost($nome) {

		//Estou setando o tipo e a categoria do post na unha, mas pode ser feito de forma dinâmica

		$my_post = array(
		  'post_title'    => $nome,
		  'post_content'  => '',
		  'post_status'   => 'publish',
		  'post_author'   => 1,
		  'post_type'     => 'produtos'
		);
		$post_id = wp_insert_post( $my_post );

		wp_set_object_terms($post_id, 'diversos' , 'categoria_produtos' );

		return $post_id;
	}
