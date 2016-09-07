<?php
/*
 *
 * @package WPBilbao\Single\Videos
 * @author  Ibon Azkoitia
 * @license GPL-2.0+
 * @link    https://www.wpbilbao.es
 *
 */

	/** Init WPBilbao Single Videos **/
	add_action( 'genesis_meta', 'wpbilbao_single_videos' );

	function wpbilbao_single_videos() {

		/*
     * Esta función removerá la Información del Post, sólo queremos mostrar el título
     */
		remove_action( 'genesis_entry_header', 'genesis_post_info', 5 );

		/*
     * Con esta acción personalizada añadimos la sección del vídeo al Contenido del Post
     *
     * Estamos diciendo que el contenido de la Función "wpbilbao_single_videos_video" se añada al "genesis_entry_content"
     */
		add_action( 'genesis_entry_content', 'wpbilbao_single_videos_video' );


		/*
		 * Añadimos una acción al Hook the Genesis 'genesis_before_sidebar_widget_area', esto es, justo antes del Sidebar
		 *
		 * Le pasamos un segundo parámetro, esta es la función que contendrá nuestro código
		 *
		 * Este código será para mostrar la información del usuario que ha subido y grabado el vídeo
		 */
		add_action( 'genesis_before_sidebar_widget_area', 'wpbilbao_single_videos_meta' );

	}

	// Declaramos la función antes citada
	function wpbilbao_single_videos_video() {
		/*
		 * Identificamos el Slug del idioma actual del visitante, en nuestra página web será "es" o "eu"
		 * Asignamos este valor a la variable $polylangSlug
		 */
		$polylangSlug = pll_current_language('locale');


		// Añadimos la sección para alojar el vídeo
		echo '<div class="embed-container">';
			/*
			 * Añadimos el campo de "Advanced Custom Fields" con el contenido del vídeo
			 * En el panel de control añadimos la URL del vídeo
			 */
			the_field( 'videos_url_video' );
		echo '</div><!-- .embed-container -->';


		// Añadimos la sección para alojar los botones
		echo '<div class="videos-botones">';

			/*
			 * Comprobamos si el campo "Enlace al Resumen de la Meetup" tiene contenido,
			 * En caso de tenerlo, mostramos el botón
			 */
			if ( get_field( 'videos_enlace_resumen' ) ) :

				echo '<a href="' . get_field( 'videos_enlace_resumen' ) . '" class="btn btn-primario" title="' . __( 'View Summary', 'wpbilbao-videos-cpt' ) . '">' . __( 'View Summary', 'wpbilbao-videos-cpt' ) . '</a>';

			endif;


			/*
			 * Añadimos el botón para regresar al listado de vídeos
			 *
			 * Tenemos que diferenciar los botones según el idioma del visitante
			 *
			 * El idioma es_ES es el idioma principal, por lo que no necesita el Slug "es" en la URL
			 */
			if ( $polylangSlug == 'es_ES' ) :

				echo '<a href="' . site_url() . '/videos/" class="btn btn-primario" title="' . __( 'View more videos', 'wpbilbao-videos-cpt' ) . '">' . __( 'View more videos', 'wpbilbao-videos-cpt' ) . '</a>';

			else :

				echo '<a href=' . site_url() . '/' . $polylangSlug . '/videos/" class="btn btn-primario" title="' . __( 'View more videos', 'wpbilbao-videos-cpt' ) . '">' . __( 'View more videos', 'wpbilbao-videos-cpt' ) . '</a>';

			endif;

		echo '</div><!-- .videos-botones -->';
	}


	function wpbilbao_single_videos_meta() {
		$polylangSlug = pll_current_language('locale');

		/*
		 * Los campos "Grabado Por" y "Subido Por" son un objeto
		 *
     * Asignamos los valores a diferentes variables para poder utilizarlas después
     *
		 */
		$miembro_grabado = get_field('videos_grabado_por');
			$miembro_grabado_nombre = $miembro_grabado[0]['display_name'];
			$miembro_grabado_gravatar = $miembro_grabado[0]['user_avatar'];

		$miembro_subido = get_field('videos_subido_por');
			$miembro_subido_nombre = $miembro_subido[0]['display_name'];
			$miembro_subido_gravatar = $miembro_subido[0]['user_avatar'];

		/*
		 * Comprobamos si el Miembro que ha Grabado el vídeo es el mismo que el que lo ha Subido
		 * Lo que haremos es juntar en uno si es la misma persona
		 *
		 * Si no es el mismo, entonces mostramos el contenido de la Línea 120
		 *   Contará con dos Widgets para cada uno de los miembros
		 *
		 * Si es el mismo, mostramos el contenido de la Línea 160
		 *   contará con un sólo Widget
		 *
		 */
		if ( $miembro_grabado_nombre != $miembro_subido_nombre ) :

			// Añadimos el Widget de la persona que lo ha Grabado
			echo '<section id="video-meta" class="widget">';

				echo '<h4 class="widget-title">' . __( 'Recorded by', 'wpbilbao-videos-cpt' ) . '</h4>';

				echo '<div class="miembro-meta">';

					echo '<div class="miembro-meta-gravatar">';
						echo $miembro_grabado_gravatar;
					echo '</div><!-- .miembro_grabado_gravatar -->';

					echo '<p>' . $miembro_grabado_nombre . '</p>';

				echo '</div><!-- .miembro-meta -->';

			echo '</section><!-- #video-meta -->';


			// Añadimos el Widget de la persona que lo ha Subido
			echo '<section id="video-meta" class="widget">';

				echo '<h4 class="widget-title">' . __( 'Uploaded by', 'wpbilbao-videos-cpt' ) . '</h4>';

				echo '<div class="miembro-meta">';

					echo '<div class="miembro-meta-gravatar">';
						echo $miembro_subido_gravatar;
					echo '</div><!-- .miembro_grabado_gravatar -->';

					echo '<p>' . $miembro_subido_nombre . '</p>';

				echo '</div><!-- .miembro-meta -->';

			echo '</section><!-- #video-meta -->';

		else :


			// Añadimos el Widget de la persona que lo ha Grabado y Subido
			echo '<section id="video-meta" class="widget">';

				echo '<h4 class="widget-title">' . __( 'Recorded and Uploaded by', 'wpbilbao-videos-cpt' ) . '</h4>';

				echo '<div class="miembro-meta">';

					echo '<div class="miembro-meta-gravatar">';
						echo $miembro_grabado_gravatar;
					echo '</div><!-- .miembro_grabado_gravatar -->';

					echo '<p>' . $miembro_grabado_nombre . '</p>';

				echo '</div><!-- .miembro-meta -->';

			echo '</section><!-- #video-meta -->';

		endif;
	}

genesis();