<?php

namespace Workhorse\InfraStructure;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Workhorse\Contracts\PostType;
use Workhorse\Contracts\Taxonomy as iTaxonomy;
use function register_extended_taxonomy;

abstract class Taxonomy implements iTaxonomy {

	/**
	 * @var string
	 */
	protected $text_domain;

	/**
	 * @var array
	 */
	protected $options = [];

	/**
	 * @var array
	 */
	protected $labels = [];

	/** @var string */
	protected $translations_group;

	/** @var array */
	protected $translatables = [];

	protected $translatable_prefix = "tax";

	/** @var bool */
	protected $hierarchical = true;

	abstract public function labelSingular(): string;

	abstract public function labelPlural(): string;

	abstract public function textDomain(): string;

	public static function slug(): string {
		throw new \RuntimeException( sprintf("method \"slug\" not implemented in %s.", static::class) );
	}

	public static function postType(): string {
		throw new \RuntimeException( "method \"postType\" should be implemented and return a PostType." );
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
			$postType = static::postType();

			if ( class_exists( $postType ) && in_array( PostType::class, class_implements( $postType ) ) ) {
				$postType = $postType::slug();
			}

			$options = $this->buildOptions();

			$labels = $this->buildLabels();

			$options['labels'] = Arr::except( $labels, [ 'singular', 'plural', 'slug' ] );

			$labels = Arr::only( $labels, [ 'singular', 'plural', 'slug' ] );

			$this->registerTranslatables();

			register_extended_taxonomy( static::slug(), $postType, $options, $labels );

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

	protected function addTranslatable( $key, $value ) {
		$key = sprintf( '%s_%s_%s', $this->translatable_prefix, static::slug(), $key );

		$this->translatables[ $key ] = $value;

		return $this;
	}

	public function registerTranslatables() {
		if ( function_exists( 'pll_register_string' ) ) {
			foreach ( $this->translatables as $key => $value ) {
				pll_register_string( $key, $value, $this->translations_group );
			}
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

		$this->setOption( 'hierarchical', $this->hierarchical );
	}

	protected function configureLabels() {
		$this->setLabel( 'singular', $this->labelSingular() );

		$this->setLabel( 'plural', $this->labelPlural() );

		foreach ( $this->labels() as $labelKey => $label ) {
			$this->setLabel( $labelKey, $label );
		}

		$this->labels['slug'] = static::slug();
	}

	public static function getTerms( $options = [] ) {
		$options['taxonomy'] = static::slug();

		return get_terms( $options );
	}

	public static function getTerm( $term ) {
		return get_term( $term, static::slug() );
	}
}