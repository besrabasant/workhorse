<?php

namespace Workhorse\InfraStructure;

use Illuminate\View\View;
use Workhorse\Contracts\AcfBlocks as AcfBlocksContract;
use Workhorse\Contracts\HasAcfFields;

abstract class AcfBlocks implements AcfBlocksContract {

	/**
	 * The default block alignment. Available settings are “left”, “center”, “right”, “wide” and “full”
	 *
	 * @var string $align
	 */
	protected $align = 'wide';

	/**
	 * An array of post types to restrict this block type to.
	 *
	 * @var string[] $post_types
	 */
	protected $post_types = [ 'post', 'page' ];

	abstract public function name(): string;

	abstract public function title(): string;

	abstract public function description(): string;

	abstract public function category(): string;

	abstract public function renderTemplate( array $data = [] ): View;

	public function options(): array {
		return [];
	}

	/**
	 * @param array $block The block settings and attributes.
	 * @param string $content The block inner HTML (empty).
	 * @param bool $is_preview True during AJAX preview.
	 * @param   (int|string) $post_id The post ID this block is saved to.
	 */
	public function dataProvider( $block, $content = '', $is_preview = false, $post_id = 0 ): array {
		return [];
	}

	/**
	 * @param array $block The block settings and attributes.
	 * @param string $content The block inner HTML (empty).
	 * @param bool $is_preview True during AJAX preview.
	 * @param   (int|string) $post_id The post ID this block is saved to.
	 *
	 * @throws \Throwable
	 */
	public function renderCallBack( $block, $content = '', $is_preview = false, $post_id = 0 ): void {
		$data = $this->dataProvider( $block, $content, $is_preview, $post_id );

		echo $this->renderTemplate( $data )->render();
	}

	public function registerBlockType(): void {
		acf_register_block_type( array_merge( [
			'name'            => $this->name(),
			'title'           => $this->title(),
			'description'     => $this->description(),
			'render_callback' => [ $this, 'renderCallback' ],
			'category'        => $this->category(),
			'align'           => $this->align,
			'post_types'      => $this->post_types
		], $this->options() ) );
	}

	public static function register() {
		if ( function_exists( 'acf_register_block_type' ) ) {
			$instance = new static();

			if ( $instance instanceof HasAcfFields ) {
				$instance->registerAcfFields();
			}

			$instance->registerBlockType();
		}
	}
}