<?php

namespace Workhorse\InfraStructure;

use Illuminate\Support\Str;
use StoutLogic\AcfBuilder\FieldsBuilder;
use Workhorse\Contracts\CanSetLocationExplicitly;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


abstract class AcfFieldGroup {
	/** @var FieldsBuilder */
	protected $fieldsBuilder;

	/**
	 * Available options: 'default' | 'seamless'
	 *
	 * @var string
	 */
	protected $style = "seamless";

	protected $label_placement = "left";

	protected $text_domain;

	/** @var array $translatables */
	protected $translatables = [];

	/** @var string $translatable_prefix */
	protected $translatable_prefix = "acf-field";

	protected $translations_group;

	abstract public function textDomain(): string;

	abstract protected function title(): string;

	abstract protected function configure( FieldsBuilder $fieldsBuilder ): void;

	public static function slug(): string {
		throw new \RuntimeException( sprintf( "method \"slug\" not implemented in %s.", static::class ) );
	}

	public function translationsGroup() {
		return "Default";
	}

	public function __construct() {
		$this->setTextDomain( $this->textDomain(), $this->translationsGroup() );
	}

	public function createFieldsBuilderInstance() {
		$this->fieldsBuilder = new FieldsBuilder( static::slug(), [
			'title'           => $this->title(),
			'style'           => $this->style,
			'label_placement' => $this->label_placement,
			'hide_on_screen'  => $this->hideOnScreen(),
		] );
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

	protected function hideOnScreen() {
		return [];
	}

	public function registerTranslatable( string $value ) {
		$value = function_exists( 'pll__' ) ? \pll__( $value ) : $value;

		if ( function_exists( 'pll__' ) ) {
			$this->addTranslatable( Str::slug( $value ), $value );
		}

		return __( $value, $this->text_domain );
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
		}
	}

	public function hook(): void {
	}

	/**
	 * @return bool
	 */
	public function canRegister() {
		return true;
	}

	public function register() {
		\add_action( 'acf/init', function () {
			if ( $this->canRegister() ) {
				$this->createFieldsBuilderInstance();

				$this->configure( $this->fieldsBuilder );

				if ( $this instanceof CanSetLocationExplicitly) {
					$this->setLocation( $this->fieldsBuilder );
				}

				$this->registerTranslatables();

				\acf_add_local_field_group( $this->fieldsBuilder->build() );
			}
		} );

		$this->hook();
	}
}