<?php

namespace Workhorse\InfraStructure;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Workhorse\Contracts\PostType;
use function register_extended_post_type;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 *
 */
abstract class CPT implements PostType {

	protected $text_domain;

	protected $options = [];

	protected $labels = [];

	protected $translations_group;

	protected $translatables = [];

	protected $translatable_prefix = "cpt";

	/** @var bool */
	protected $hide_featured_image = false;

	abstract public function labelSingular(): string;

	abstract public function labelPlural(): string;

	abstract public function titlePlaceholder(): string;

	abstract public function textDomain(): string;

	public static function slug(): string {
		throw new \RuntimeException( sprintf("method \"slug\" not implemented in %s.", static::class) );
	}

	public function translationsGroup() {
		return "Default";
	}

	public function __construct() {
		$this->setTextDomain( $this->textDomain(), $this->translationsGroup() );
	}

	/**
	 * @param string $text_domain
	 * @param string $translations_group
	 *
	 * @return $this
	 */
	private function setTextDomain( string $text_domain, string $translations_group = "Default" ) {
		$this->text_domain        = $text_domain;
		$this->translations_group = $translations_group;

		return $this;
	}

	/**
	 * @return string|null
	 */
	public function icon() {
		return null;
	}

	/**
	 * @return array
	 */
	public function labels() {
		return [];
	}

	/**
	 * @return array
	 */
	public function options() {
		return [];
	}

	public function register(): void {
		add_action( 'init', function () {
			$this->setTitlePlaceholder( $this->titlePlaceholder() );

			$options = $this->buildOptions();

			$labels = $this->buildLabels();

			if ( $this->hide_featured_image ) {
				$this->hideFeaturedImage();
			}

			$options['labels'] = Arr::except( $labels, [ 'singular', 'plural', 'slug' ] );

			$labels = Arr::only( $labels, [ 'singular', 'plural', 'slug' ] );

			$this->registerTranslatables();

			register_extended_post_type( static::slug(), $options, $labels );
		} );
	}

	public function setOption( $key, $value ) {
		$this->options[ $key ] = $value;

		return $this;
	}

	public function setLabel( string $key, string $value, bool $translate = true ) {
		$value = $translate && function_exists( 'pll__' ) ? \pll__( $value ) : $value;

		$this->labels[ $key ] = __( $value, $this->text_domain );

		if ( $translate && function_exists( 'pll__' ) ) {
			$this->addTranslatable( Str::slug( $value ), $value );
		}

		return $this;
	}

	public function setTitlePlaceholder( $value ) {
		$value = function_exists( 'pll__' ) ? \pll__( $value ) : $value;

		$this->setOption( 'enter_title_here', __( $value, $this->text_domain ) );

		$this->addTranslatable( Str::slug( $value ), $value );

		return $this;
	}

	protected function addTranslatable( $key, $value ) {
		$key = sprintf( '%s_%s_%s', $this->translatable_prefix, static::slug(), $key );

		$this->translatables[ $key ] = $value;

		return $this;
	}

	public function registerTranslatables() {
		if ( function_exists( 'pll_register_string' ) ) {
			foreach ( $this->translatables as $key => $value ) {
				\pll_register_string( $key, $value, $this->translations_group );
			}

			\add_filter( 'pll_get_post_types', function ( $post_types, $is_settings ) {
				if ( ! $is_settings ) {
					unset( $post_types[ static::slug() ] );
				} else {
					$post_types[ static::slug() ] = static::slug();
				}

				return $post_types;
			}, 10, 2 );
		}
	}

	public function buildOptions() {
		$this->configureOptions();

		return $this->options;
	}

	/**
	 * @return array
	 */
	protected function buildLabels() {
		$this->configureLabels();

		return $this->labels;
	}

	protected function configureOptions() {
		foreach ( $this->options() as $optionsKey => $optionValue ) {
			$this->setOption( $optionsKey, $optionValue );
		}

		$this->options['menu_icon'] = $this->icon();
	}

	protected function configureLabels() {
		$this->setLabel( 'singular', $this->labelSingular() );

		$this->setLabel( 'plural', $this->labelPlural() );

		foreach ( $this->labels() as $labelKey => $label ) {
			$this->setLabel( $labelKey, $label );
		}

		$this->labels['slug'] = static::slug();
	}

	/**
	 * @return $this
	 */
	private function hideFeaturedImage() {
		Utils::hideFeaturedImage( static::slug() );

		return $this;
	}

	public static function getPosts( $args = [] ) {
		$args['post_type'] = static::slug();

		return get_posts( $args );
	}
}