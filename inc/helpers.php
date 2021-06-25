<?php
/**
 * Function helpers 
 * 
 * @package Emertech Transform Plugin
 */

 
/**
 * get_template_part() equivalent function for plugin
 */
if ( ! function_exists( 'get_transform_template_part' ) ) {
    /**
     * Get plugin template part
     *
     * @param string $path Path of template part file (**WITHOUT** file extension)
     * @since 1.0
     */
	function get_transform_template_part(string $path) {
        if($path == '') return; 
        try {

            $file_name = EMERTECH_TRANSFORM_DIR . $path . '.php';

            if(! file_exists($file_name)) 
                throw new Exception("No such file or directory in ($file_name)");
            
            include $file_name;
            
        } catch (Exception $e) {
            echo "<script>";
            echo "console.error(\"Plugin template part ($path) was not found.\");";
            echo "</script>";
        }
    }
}

/**
 * Get term top most parent
 */
if ( ! function_exists( 'get_term_top_parent' ) ) {
    /**
     * Get term top most parent
     *
     * @param WP_Term $path Term of which the parent will be get
     * @param string $taxonomy Taxonomy of term
     * @since 1.0
     */
	function get_term_top_parent(WP_Term $term, string $taxonomy = '') {
        $parent_id = $term->parent;
    
        // If parent ID is 0, then the term given is already 
        // the top most parent
        if($parent_id == 0) 
            return false;

        // Keep getting the parent of the parent until the top most 
        // parent is found
        while($parent_id != 0) {
            $parent = get_term( $parent_id, $taxonomy );
            $parent_id = $parent->parent;
        }
        
        // Return found parent
        return $parent;
    }
}