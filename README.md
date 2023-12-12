# Examen_SXE2

Descripción del Examen

    Filtrado de Palabras Malsonantes:
        Se implementó una función renym_wordpress_typo_fix que filtra palabras malsonantes en el contenido y las reemplaza por asteriscos ("******").

    php

function renym_wordpress_typo_fix( $content ) {
    $words = selectData();
    foreach ($words as $result){
        $palabrasBaneadas[] = $result->Toxico;
        $SolucionPalabrasBaneadas[] = $result->SinToxicidad;
    }
    return str_replace($palabrasBaneadas, $SolucionPalabrasBaneadas, $content);
}
add_filter( 'the_content', 'renym_wordpress_typo_fix' );

Creación de Tabla en la Base de Datos:

    Se implementó una función crear_tabla que crea una tabla llamada "Zona 0% Toxica" que censura las palabras

php

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

Inserción de Datos en la Base de Datos:

    Se implementó una función insertar_Datos que inserta datos en la tabla de la base de datos al activar el plugin.

php

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
add_action("plugins_loaded", "insertar_Datos");

Selección de Datos de la Tabla en la Base de Datos:

    Se implementó una función selectData que selecciona datos de la tabla en la base de datos.

php

function selectData(){
    global $wpdb;
    $table_name = $wpdb->prefix . "words";
    $resultados = $wpdb->get_results("SELECT * FROM $table_name");
    return $resultados;
}
