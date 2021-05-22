<?php

namespace DeepWebSolutions\Framework\Helpers\Security;

\defined( 'ABSPATH' ) || exit;

/**
 * A collection of very useful validation helpers to be used throughout the projects.
 *
 * @since   1.0.0
 * @version 1.3.1
 * @author  Antonius Hegyes <a.hegyes@deep-web-solutions.com>
 * @package DeepWebSolutions\WP-Framework\Helpers\Security
 */
final class Validation {
	/**
	 * Validates a string-like value.
	 *
	 * @since   1.3.1
	 * @version 1.3.1
	 *
	 * @param   mixed   $string     Value to validate.
	 * @param   string  $default    The default value to return if all fails.
	 *
	 * @return  string
	 */
	public static function validate_string( $string, string $default = '' ): string {
		return \is_array( $string ) || ( \is_object( $string ) && ! \method_exists( $string, '__toString' ) ) ? $default : \strval( $string );
	}

	/**
	 * Validates a string-like variable from an input stream.
	 *
	 * @since   1.3.1
	 * @version 1.3.1
	 *
	 * @param   int     $input_type     One of INPUT_GET, INPUT_POST, INPUT_COOKIE, INPUT_SERVER, or INPUT_ENV.
	 * @param   string  $variable_name  Name of a variable to get.
	 * @param   string  $default        The default value to return if all fails.
	 *
	 * @return  string
	 */
	public static function validate_string_input( int $input_type, string $variable_name, string $default = '' ): string {
		$value = \filter_input( $input_type, $variable_name, FILTER_UNSAFE_RAW );
		return self::validate_string( $value, $default );
	}

	/**
	 * Validates a bool-like value.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   mixed   $boolean    Value to validate.
	 * @param   bool    $default    The default value to return if all fails.
	 *
	 * @return  bool
	 */
	public static function validate_boolean( $boolean, bool $default ): bool {
		return \filter_var( $boolean, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE ) ?? $default;
	}

	/**
	 * Validates a bool-like variable from an input stream.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   int     $input_type     One of INPUT_GET, INPUT_POST, INPUT_COOKIE, INPUT_SERVER, or INPUT_ENV.
	 * @param   string  $variable_name  Name of a variable to get.
	 * @param   bool    $default        The default value to return if all fails.
	 *
	 * @return  bool
	 */
	public static function validate_boolean_input( int $input_type, string $variable_name, bool $default ): bool {
		$value = \filter_input( $input_type, $variable_name, FILTER_UNSAFE_RAW );
		return self::validate_boolean( $value, $default );
	}

	/**
	 * Validates an int-like value.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   mixed   $integer    Value to sanitize.
	 * @param   int     $default    The default value to return if all fails.
	 *
	 * @return  int
	 */
	public static function validate_integer( $integer, int $default ): int {
		return \filter_var(
			$integer,
			FILTER_VALIDATE_INT,
			array(
				'options' => array( 'default' => $default ),
				'flags'   => FILTER_FLAG_ALLOW_OCTAL | FILTER_FLAG_ALLOW_HEX,
			)
		);
	}

	/**
	 * Validates an int-like variable from an input stream.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   int     $input_type     One of INPUT_GET, INPUT_POST, INPUT_COOKIE, INPUT_SERVER, or INPUT_ENV.
	 * @param   string  $variable_name  Name of a variable to get.
	 * @param   int     $default    The default value to return if all fails.
	 *
	 * @return  int
	 */
	public static function validate_integer_input( int $input_type, string $variable_name, int $default ): int {
		$value = \filter_input( $input_type, $variable_name, FILTER_UNSAFE_RAW );
		return self::validate_integer( $value, $default );
	}

	/**
	 * Validates a float-like value.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   mixed   $float      Value to sanitize.
	 * @param   float   $default    The default value to return if all fails.
	 *
	 * @return  float
	 */
	public static function validate_float( $float, float $default ): float {
		$result = \filter_var( $float, FILTER_VALIDATE_FLOAT, FILTER_FLAG_ALLOW_THOUSAND );
		if ( false === $result ) {
			$result = \filter_var(
				$float,
				FILTER_VALIDATE_FLOAT,
				array(
					'options' => array( 'decimal' => ',' ),
					'flags'   => FILTER_FLAG_ALLOW_THOUSAND,
				)
			);
		}

		return $result ?: $default; // phpcs:ignore
	}

	/**
	 * Validates a float-like variable from an input stream.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   int     $input_type     One of INPUT_GET, INPUT_POST, INPUT_COOKIE, INPUT_SERVER, or INPUT_ENV.
	 * @param   string  $variable_name  Name of a variable to get.
	 * @param   float   $default        The default value to return if all fails.
	 *
	 * @return  float
	 */
	public static function validate_float_input( int $input_type, string $variable_name, float $default ): float {
		$value = \filter_input( $input_type, $variable_name, FILTER_UNSAFE_RAW );
		return self::validate_float( $value, $default );
	}

	/**
	 * Validates a callback.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   mixed       $callback   Value to sanitize.
	 * @param   callable    $default    The default value to return if all fails.
	 *
	 * @return  callable
	 */
	public static function validate_callback( $callback, callable $default ): callable {
		if ( \is_string( $callback ) ) {
			$callback = \trim( $callback );
			return \is_callable( $callback ) ? $callback : $default;
		} elseif ( \is_callable( $callback ) ) {
			return $callback;
		}

		return $default;
	}

	/**
	 * Validates a values against a whitelist.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   mixed   $entry      The entry to validate.
	 * @param   array   $allowed    The list of allowed entries.
	 * @param   mixed   $default    The default value to return if all fails.
	 *
	 * @return  mixed
	 */
	public static function validate_allowed_value( $entry, array $allowed, $default ) {
		$is_allowed = \in_array( $entry, $allowed, true );
		if ( false === $is_allowed && \is_string( $entry ) ) {
			$entry      = \trim( $entry );
			$is_allowed = \in_array( $entry, $allowed, true );
		}

		return $is_allowed ? $entry : $default;
	}
}
