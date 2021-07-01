/**
 * External dependencies
 */
import 'whatwg-fetch';

/**
 * Internal dependencies
 */
import { rest } from '@moderntribe/common/utils/globals';

/**
 * Send a request into a wp-json endpoint
 *
 * @param {object} params An object with the following properties:
 * - path: Path for the endpoint
 * - headers: Array of extra headers for the request
 * - initParams: Params send into the fetch along with headers and credentials
 * - namespace: Endpoint namespace default to `wp/v2`
 * @returns {Promise<Response>} return a fetch promise
 */
export const wpREST = async ( params ) => {
	const { url = '', nonce = {}, namespaces = {} } = rest();

	/**
	 * @todo refactor this method as for more details look into:
	 * - https://github.com/the-events-calendar/events-gutenberg/pull/346#discussion_r222217138
	 */
	const options = {
		path: '',
		headers: {},
		initParams: {},
		namespace: namespaces.core || 'wp/v2',
		...params,
	};

	const endpoint = `${ url }${ options.namespace }/${ options.path }`;

	const headers = {
		'X-WP-Nonce': nonce.wp_rest || '',
		...options.headers,
	};

	/* eslint-disable no-useless-catch */
	try {
		const response = await fetch( endpoint, {
			...options.initParams,
			credentials: 'include',
			headers,
		} );

		let data = {};

		if ( response.ok ) {
			data = await response.json();
		}

		return {
			response,
			data,
		};
	} catch ( e ) {
		throw e;
	}
	/* eslint-enable no-useless-catch */
};
