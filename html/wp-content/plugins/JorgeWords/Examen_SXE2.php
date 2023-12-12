<?php
/**
 * @package Examen_SXE2
 * @version 1.0.0
 */
/*
Plugin Name: Examen_SXE2
Plugin URI: http://wordpress.org/plugins/Examen_SXE2/
Description: Examen de SXE.
Author: Jorge Gonzalez
Version: 1.0.1
Author URI: http://JorgeGonzalez/
*/

/*
//Cambiar palabras malsonantes por otras más suaves en el contenido
function renym_wordpress_typo_fix( $text ) {
    global $palabrasBaneadas, $SolucionPalabrasBaneadas;
    return str_replace($palabrasBaneadas, $SolucionPalabrasBaneadas, $text);
}
 */

$palabrasBaneadas= array("Todos los insultos existentes");

$SolucionPalabrasBaneadas= array("******");

// Elimina totalmente los insultos por asteriscos
function renym_wordpress_typo_fix( $content ) {
    $words = selectData();
    foreach ($words as $result){
        $palabrasBaneadas[] = $result-> Toxico;
        $SolucionPalabrasBaneadas[] = $result-> SinToxicidad;
    }
    return str_replace($palabrasBaneadas, $SolucionPalabrasBaneadas, $content);
}

add_filter( 'the_content', 'renym_wordpress_typo_fix' );


/*
 * Crear una tabla en la base de datos
 */
function crear_tabla() {
    global $wpdb;

    $nombre_tabla = $wpdb->prefix . "Zona 0% Toxica";
    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE IF NOT EXISTS $nombre_tabla (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        palabrotas varchar(255) NOT NULL,
        eufemismo varchar(255) NOT NULL,
        PRIMARY KEY  (id)
    ) $charset_collate;";

    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    dbDelta( $sql );
}

add_action( "plugins_loaded", "crear_tabla" );

/*
 * Insertar datos
 */
function insertar_Datos(){
    global $wpdb, $palabrasBaneadas, $SolucionPalabrasBaneadas;
    $table_name = $wpdb->prefix . "words";
    $flag = $wpdb->get_results("SELECT * FROM $table_name");
    if (count($flag)==0){
        for ($i = 0; $i < count($palabrasBaneadas); $i++){
            $wpdb->insert(
                $table_name,
                array(
                    'Toxico' => $palabrasBaneadas[$i],
                    'Sin Toxicidad' => $SolucionPalabrasBaneadas[$i]
                )
            );
        }
    }
}

add_action("plugins_loaded", "insertData");

/*
 * Seleccionar datos de la tabla
 */
function selectData(){
    global $wpdb;
    $table_name = $wpdb->prefix . "words";
    $resultados = $wpdb->get_results("SELECT * FROM $table_name");
    return $resultados;
}