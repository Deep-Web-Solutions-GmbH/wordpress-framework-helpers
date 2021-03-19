<?php

namespace DeepWebSolutions\Framework\Helpers\DataTypes;

\defined( 'ABSPATH' ) || exit;

/**
 * A collection of very useful array manipulation helpers to be used throughout the projects.
 *
 * @since   1.0.0
 * @version 1.0.2
 * @author  Antonius Hegyes <a.hegyes@deep-web-solutions.com>
 * @package DeepWebSolutions\WP-Framework\Helpers\DataTypes
 */
final class Arrays {
	/**
	 * Checks whether an array has any string keys or if they are all numerical.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   array   $array  The array to check.
	 *
	 * @return  bool    True if it has string keys, false otherwise.
	 */
	public static function has_string_keys( array $array ): bool {
		return \count( \array_filter( \array_keys( $array ), '\is_string' ) ) > 0;
	}

	/**
	 * Inserts new array entries after a given key within an existing array. If the key is not found,
	 * appends entries at the end of the array.
	 *
	 * @since   1.0.0
	 * @version 1.0.2
	 * @see     https://gist.github.com/wpscholar/0deadce1bbfa4adb4e4c
	 *
	 * @param   array           $array          Associative array to insert the new entries into.
	 * @param   string|int      $key            Key to insert the entries after.
	 * @param   array           $new_entries    The new entries to insert.
	 *
	 * @return  array
	 */
	public static function insert_after( array $array, $key, array $new_entries ): array {
		$index    = \array_search( $key, \array_keys( $array ), true );
		$position = ( false === $index ) ? \count( $array ) : ( $index + 1 );

		if ( empty( $array ) || self::has_string_keys( $array ) ) {
			$array = \array_slice( $array, 0, $position, true ) + $new_entries + \array_slice( $array, $position, null, true );
		} else {
			\array_splice( $array, $position, 0, $new_entries );
		}

		return $array;
	}

	/**
	 * Returns all the entries in a given array that match a given needle.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @SuppressWarnings(PHPMD.BooleanArgumentFlag)
	 *
	 * @param   array           $array      Array to search through.
	 * @param   mixed           $needle     The value to search for.
	 * @param   bool            $strict     Whether to perform type checks or not.
	 * @param   callable|null   $callback   Optional callback to run the value through before needle comparison.
	 *
	 * @return  array|null
	 */
	public static function search_values( array $array, $needle, bool $strict = true, ?callable $callback = null ): ?array {
		$comparison_array = \is_callable( $callback ) ? \array_map( $callback, $array ) : $array;

		foreach ( $comparison_array as $key => $value ) {
			if ( ( $strict && $needle === $value ) || ( ! $strict && $needle == $value ) ) { // phpcs:ignore
				continue;
			}

			unset( $array[ $key ] );
		}

		return empty( $array ) ? null : $array;
	}

	/**
	 * Returns all the keys in a given array that match a given needle.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @SuppressWarnings(PHPMD.BooleanArgumentFlag)
	 *
	 * @param   array           $array      Array whose keys to search through.
	 * @param   mixed           $needle     The value to search for.
	 * @param   bool            $strict     Whether to perform type checks or not.
	 * @param   callable|null   $callback   Optional callback to run the key through before needle comparison.
	 *
	 * @return  array|null
	 */
	public static function search_keys( array $array, $needle, bool $strict = false, callable $callback = null ): ?array {
		return self::search_values( \array_keys( $array ), $needle, $strict, $callback );
	}
}
