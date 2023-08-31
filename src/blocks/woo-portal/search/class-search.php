<?php
/**
 * Register the Search Block.
 *
 * Registers the Search Block for usage in the Gutenberg editor.
 *
 * @since      1.0.0
 *
 * @package    WOO_Portal
 * @subpackage WOO_Portal/Src/Blocks/Woo_Portal/Search
 */

namespace WOO_Portal\Src\Blocks\Woo_Portal;

use WOO_Portal\Includes\Base_Block;

/**
 * The Search class.
 *
 * This is used to register and render the block.
 *
 * @since      1.0.0
 * @package    WOO_Portal
 * @subpackage WOO_Portal/Src/Blocks/Woo_Portal/Search
 * @author     Paul van Impelen <paul@acato.nl>, Richard Korthuis <richard@acato.nl>
 */
class Search extends Base_Block {
	/**
	 * Render the blocks HTML.
	 *
	 * @param array  $attributes An array of block attributes.
	 * @param string $content    The content for the block.
	 *
	 * @return string The HTML for the block.
	 */
	public function render_block( $attributes, $content ) {
		ob_start();
		include __DIR__ . '/template.php';
		$output = ob_get_clean();

		return $output;
	}
}

new Search();
