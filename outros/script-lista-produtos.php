<?php

/*
   Pega os produtos cadastrados e cria um arquivo txt com os campos e caminho das imagens

   Obs: Foi criado pois eu precisava alterar todas as fotos de todos os produtos de uma vez. Como nenhuma imagem tinha o mesmo nome e novas imagens seriam adicionadas
   criei este script pra gerar um arquivo novo de importação, assim eu excluo todos os produtos atuais e subo tudo novamente.
*/

$fileProdutos = fopen("listagem-produtos.txt", "w") or die("Unable to open file!");
$txt = "";

$args = array(
   'posts_per_page'   => -1,
   'offset'           => 0,
   'orderby'          => 'post_title',
   'order'            => 'DESC',
   'post_type'        => 'produtos',
   'post_status'      => 'publish',
   'suppress_filters' => true );

$posts_array = get_posts( $args );

echo count($posts_array)." produtos";

?>
<table>
<tr>
   <td>Nome</td>
   <td>Descrição</td>
   <td>Categoria</td>
   <td>Cores</td>
   <td>Variações (json)</td>
   <td>Caminho Imagens<td>
</tr>
<?php



foreach ( $posts_array as $post ) : setup_postdata( $post ); ?>
   <tr style="border-bottom:1px solid #CFCFCF;">
      <td>
         <?php
            $titulo = get_the_title();
            echo $titulo;
            $txt .= $titulo.";";
         ?>
      </td>
      <td>
         <?php
            $content = get_the_content();
            echo $content;
            $txt .= $content.";";
         ?>
      </td>
      <td>
         <?php
               $product_terms = wp_get_object_terms($post->ID, 'categoria_produtos');

               $cat= "";
               if(!empty($product_terms)){
                 if(!is_wp_error( $product_terms )){
                  $cat .= '{';
                   foreach($product_terms as $term){
                     $cat .= '"'.$term->slug.'",';
                   }
                   $cat .= '}';
                 }
                }

                echo $cat;
                $txt .= $cat.";";
         ?>
      </td>
      <td>
         <?php
               $cor_terms = wp_get_object_terms($post->ID, 'cor_produtos');

               $cor = "";
               if(!empty($cor_terms)){
                 if(!is_wp_error( $cor_terms )){
                  $cor .= '{';
                   foreach($cor_terms as $term){
                     $cor .= '"'.$term->slug.'",';
                   }
                   $cor .= '}';
                 }
                }

                echo $cor;
                $txt .= $cor.";";
         ?>
      </td>
      <td>
         <?php
            $meta = get_post_meta($post->ID, 'meta_json_danwidgets', true);

            echo $meta;
            $txt .= $meta.";";
         ?>
      </td>
      <td>
         <?php

            //Cria a estrutura do diretório
            $dir_cat = ABSPATH . "wp-content/uploads/produtos/" . $product_terms[0]->slug;
            if (!file_exists( $dir_cat ))
                  mkdir($dir_cat, 0775, true);


            $dir_prod = $dir_cat . "/" . $post->post_name;
            if (!file_exists( $dir_prod ))
                  mkdir($dir_prod, 0775, true);

            echo $dir_prod;
            $txt .= $dir_prod."\n";
         ?>
      </td>
   </tr>

<?php
endforeach;
wp_reset_postdata();

//fwrite($fileProdutos, $txt);
fclose($fileProdutos);
?>
</table>
