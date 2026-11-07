/*
 * GP House — reserve a suite from its room page.
 * "Reserve this Suite" opens the reserve panel → rate plan → guest details (sign-in required) → PayPal
 * → My Booking. Talks to the GP House Booking plugin (eZee availability / booking + PayPal capture).
 * The browser never decides a price: every amount comes back from the server.
 */
( function () {
	'use strict';

	const cfg = window.gpBooking;
	const root = document.querySelector( '[data-reserve]' );
	if ( ! cfg || ! root ) return;

	const $ = ( s, c = root ) => c.querySelector( s );
	const $$ = ( s, c = root ) => Array.from( c.querySelectorAll( s ) );
	const card = document.querySelector( '[data-booking]' );
	const form = $( '[data-stay-form]' );
	const offersEl = $( '[data-book-offers]' );
	const statusEl = $( '[data-book-status]' );
	const checkout = $( '[data-book-checkout]' );
	const summary = $( '[data-book-summary]' );
	const gate = $( '[data-gate]' );
	const details = $( '[data-details-form]' );
	const payment = $( '[data-payment]' );
	const t = cfg.i18n;
	const state = { offers: [], offer: null, stay: null, booking: null, timer: null, sdk: null, run: 0 };

	const esc = ( s ) => String( s ?? '' ).replace( /[&<>"']/g, ( c ) => ( { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[ c ] ) );
	const iso = ( d ) => d.toISOString().slice( 0, 10 );
	const addDays = ( s, n ) => {
		const d = new Date( s + 'T00:00:00Z' );
		d.setUTCDate( d.getUTCDate() + n );
		return iso( d );
	};
	const fmtDate = ( s ) => new Date( s + 'T00:00:00' ).toLocaleDateString( 'en-GB', { weekday: 'short', day: 'numeric', month: 'short', year: 'numeric' } );
	const nights = () => parseInt( ( card && card.querySelector( '[data-nights]' ) || {} ).textContent, 10 ) || 2;

	const api = async ( path, opts = {} ) => {
		const res = await fetch( cfg.rest + path, {
			credentials: 'same-origin',
			headers: { 'Content-Type': 'application/json', 'X-WP-Nonce': cfg.nonce },
			...opts,
		} );
		const json = await res.json().catch( () => ( {} ) );
		return { status: res.status, ...json };
	};
	const setStatus = ( msg, kind ) => {
		statusEl.textContent = msg || '';
		statusEl.className = 'gp-book__status' + ( kind ? ' is-' + kind : '' );
		statusEl.hidden = ! msg;
	};
	const stayParams = () => {
		const d = Object.fromEntries( new FormData( form ).entries() );
		return { property: root.dataset.property, room: root.dataset.room, checkin: d.checkin, checkout: form.elements.checkout.value, adults: d.adults, children: d.children, promo: ( d.promo || '' ).trim() };
	};
	const syncCheckout = () => {
		const ci = form.elements.checkin.value;
		form.elements.checkout.value = ci ? addDays( ci, nights() ) : '';
	};

	/* Open / close ---------------------------------------------------------- */
	let opened = false;
	const open = () => {
		root.hidden = false;
		syncCheckout();
		root.scrollIntoView( { behavior: 'smooth', block: 'start' } );
		if ( ! opened ) {
			opened = true;
			search();
		}
	};
	document.addEventListener( 'click', ( e ) => {
		const a = e.target.closest( '[data-reserve-open]' );
		if ( a ) {
			e.preventDefault();
			open();
		}
	} );

	/* Search ------------------------------------------------------------------ */
	const search = async () => {
		resetCheckout();
		syncCheckout();
		if ( ! form.checkValidity() ) return form.reportValidity();
		const run = ++state.run;
		offersEl.innerHTML = '';
		setStatus( t.searching, 'loading' );
		const res = await api( 'availability?' + new URLSearchParams( stayParams() ).toString() );
		if ( run !== state.run ) return;
		if ( ! res.ok ) {
			setStatus( res.message || t.error, 'error' );
			return;
		}
		if ( res.flow === 'link' ) {
			setStatus( '' );
			offersEl.innerHTML = `<div class="gp-book__panel"><h2>${ esc( res.label ) }</h2><p>${ esc( t.linkText ) }</p><a class="gp-btn gp-btn--primary gp-btn--tab" target="_blank" rel="noopener" href="${ esc( res.url ) }">${ esc( t.linkButton ) }</a></div>`;
			return;
		}
		state.stay = res.stay;
		state.offers = res.offers;
		setStatus( '' );
		renderOffers();
		if ( state.offers.length === 1 ) selectOffer( state.offers[ 0 ].key, true );
	};

	const renderOffers = () => {
		if ( ! state.offers.length ) {
			offersEl.innerHTML = `<div class="gp-book__panel gp-book__panel--empty"><p>${ esc( t.none ) }</p></div>`;
			return;
		}
		offersEl.innerHTML = state.offers.map( ( o ) => `
			<label class="gp-rate" data-key="${ esc( o.key ) }">
				<input type="radio" name="rate" value="${ esc( o.key ) }">
				<span class="gp-rate__body">
					<strong>${ esc( o.rate && o.rate !== o.room ? o.rate.replace( o.room + ' — ', '' ) : o.room ) }${ o.promotion ? ` <em>${ esc( o.promotion ) }</em>` : '' }</strong>
					<span class="gp-rate__meta">${ o.inclusion ? esc( o.inclusion ) + ' · ' : '' }<span class="${ o.nonrefundable ? 'is-strict' : 'is-ok' }">${ esc( o.cancel_text ) }</span>${ o.available <= 2 ? ' · ' + esc( t.left.replace( '%d', o.available ) ) : '' }</span>
				</span>
				<span class="gp-rate__price"><strong>${ esc( o.total_label ) }</strong><span>${ esc( o.night_label ) } / ${ esc( t.night ) }</span></span>
			</label>` ).join( '' );
	};
	offersEl.addEventListener( 'change', ( e ) => {
		if ( e.target.name === 'rate' ) selectOffer( e.target.value );
	} );

	/* Select a rate plan ------------------------------------------------------ */
	const selectOffer = ( key, auto ) => {
		const offer = state.offers.find( ( o ) => o.key === key );
		if ( ! offer ) return;
		if ( state.booking ) resetHold();
		state.offer = offer;
		$$( '.gp-rate', offersEl ).forEach( ( el ) => {
			const on = el.dataset.key === key;
			el.classList.toggle( 'is-selected', on );
			el.querySelector( 'input' ).checked = on;
		} );
		checkout.hidden = false;
		summary.hidden = false;
		renderSummary();
		payment.hidden = true;
		if ( ! cfg.loggedIn ) {
			// Sign in first, then come straight back to the reserve panel.
			const back = window.location.href.split( '#' )[ 0 ] + '#reserve';
			$$( '[data-gate-link]', gate ).forEach( ( a ) => {
				a.dataset.redirect = back;
				a.href = a.href.split( '?' )[ 0 ] + '?redirect_to=' + encodeURIComponent( back );
			} );
			gate.hidden = false;
			details.hidden = true;
		} else {
			gate.hidden = true;
			details.hidden = false;
			fillDetails();
		}
		if ( ! auto ) checkout.scrollIntoView( { behavior: 'smooth', block: 'nearest' } );
	};

	const fillDetails = () => {
		const u = cfg.user || {};
		const f = details.elements;
		if ( ! f.first.value ) f.first.value = u.first || '';
		if ( ! f.last.value ) f.last.value = u.last || '';
		f.email.value = u.email || '';
		if ( ! f.phone.value ) f.phone.value = u.phone || '';
	};

	const renderSummary = () => {
		const o = state.offer;
		const s = state.stay;
		$( '[data-summary-body]' ).innerHTML = `
			<p class="gp-sum__room">${ esc( o.room ) }${ o.rate && o.rate !== o.room ? ' — ' + esc( o.rate.replace( o.room + ' — ', '' ) ) : '' }</p>
			<dl>
				<div><dt>Check-in</dt><dd>${ esc( fmtDate( s.checkin ) ) }</dd></div>
				<div><dt>Check-out</dt><dd>${ esc( fmtDate( s.checkout ) ) }</dd></div>
				<div><dt>Guests</dt><dd>${ esc( s.adults ) } adults${ Number( s.children ) ? ', ' + esc( s.children ) + ' children' : '' }</dd></div>
				<div><dt>${ esc( o.night_label ) } × ${ esc( o.nights ) } ${ esc( o.nights === 1 ? t.night : t.nights ) }</dt><dd>${ esc( o.total_label ) }</dd></div>
			</dl>
			<p class="gp-sum__total"><span>Total to pay today</span><strong>${ esc( o.total_label ) }</strong></p>
			<p class="gp-sum__note">${ esc( o.cancel_text ) }. Prices include all taxes and fees unless stated.</p>`;
	};

	/* Guest details → hold + PayPal order ------------------------------------------ */
	details.addEventListener( 'submit', async ( e ) => {
		e.preventDefault();
		const alertEl = $( '[data-form-alert]', details );
		alertEl.hidden = true;
		const btn = $( '[data-pay-button]', details );
		if ( ! details.checkValidity() ) return details.reportValidity();
		const d = Object.fromEntries( new FormData( details ).entries() );
		btn.disabled = true;
		btn.textContent = t.processing;
		const res = await api( 'checkout', {
			method: 'POST',
			body: JSON.stringify( { ...state.stay, offer: state.offer.key, first: d.first, last: d.last, phone: d.phone, country: d.country, requests: d.requests || '', terms: d.terms ? 1 : 0 } ),
		} );
		btn.disabled = false;
		btn.textContent = t.pay;
		if ( ! res.ok ) {
			alertEl.textContent = res.message || t.error;
			alertEl.hidden = false;
			if ( res.status === 409 ) search();
			return;
		}
		state.booking = res;
		details.hidden = true;
		payment.hidden = false;
		startTimer( res.expires_in );
		payment.scrollIntoView( { behavior: 'smooth', block: 'nearest' } );
		mountPayPal( res );
	} );

	const startTimer = ( seconds ) => {
		clearInterval( state.timer );
		const el = $( '[data-hold-timer]', payment );
		let left = Math.max( 0, seconds );
		const tick = () => {
			el.textContent = String( Math.floor( left / 60 ) ).padStart( 2, '0' ) + ':' + String( left % 60 ).padStart( 2, '0' );
			if ( left <= 0 ) {
				clearInterval( state.timer );
				search().then( () => setStatus( t.expired, 'error' ) );
				return;
			}
			left--;
		};
		tick();
		state.timer = setInterval( tick, 1000 );
	};

	const loadSdk = ( clientId ) => new Promise( ( resolve, reject ) => {
		if ( window.paypal && state.sdk === clientId ) return resolve( window.paypal );
		document.querySelectorAll( 'script[data-gp-paypal]' ).forEach( ( s ) => s.remove() );
		delete window.paypal;
		const s = document.createElement( 'script' );
		s.src = 'https://www.paypal.com/sdk/js?client-id=' + encodeURIComponent( clientId ) + '&currency=THB&intent=capture&components=buttons';
		s.dataset.gpPaypal = '1';
		s.onload = () => {
			state.sdk = clientId;
			resolve( window.paypal );
		};
		s.onerror = () => reject( new Error( 'sdk' ) );
		document.head.appendChild( s );
	} );

	const mountPayPal = async ( order ) => {
		const box = $( '#gp-paypal-buttons', payment );
		const alertEl = $( '[data-pay-alert]', payment );
		box.innerHTML = '';
		alertEl.hidden = true;
		try {
			const paypal = await loadSdk( order.client_id );
			paypal.Buttons( {
				style: { layout: 'vertical', shape: 'rect', label: 'pay', height: 48 },
				createOrder: () => order.order_id,
				onApprove: async ( data ) => {
					box.classList.add( 'is-busy' );
					const res = await api( 'capture', { method: 'POST', body: JSON.stringify( { booking_id: order.booking_id, order_id: data.orderID } ) } );
					if ( res.ok && res.redirect ) {
						clearInterval( state.timer );
						state.booking = null;
						window.location.href = res.redirect;
						return;
					}
					box.classList.remove( 'is-busy' );
					alertEl.textContent = res.message || t.error;
					alertEl.hidden = false;
				},
				onCancel: () => {
					alertEl.textContent = t.cancelled;
					alertEl.hidden = false;
				},
				onError: () => {
					alertEl.textContent = t.error;
					alertEl.hidden = false;
				},
			} ).render( box );
		} catch ( e ) {
			alertEl.textContent = t.sdk;
			alertEl.hidden = false;
		}
	};

	/* Reset / change ---------------------------------------------------------------- */
	const resetHold = () => {
		clearInterval( state.timer );
		if ( state.booking ) {
			api( 'abandon', { method: 'POST', body: JSON.stringify( { booking_id: state.booking.booking_id } ) } );
			state.booking = null;
		}
		payment.hidden = true;
	};
	const resetCheckout = () => {
		resetHold();
		state.offer = null;
		checkout.hidden = true;
		summary.hidden = true;
		details.hidden = true;
		gate.hidden = true;
		$$( '.gp-rate', offersEl ).forEach( ( el ) => el.classList.remove( 'is-selected' ) );
	};
	$( '[data-change-room]', payment ).addEventListener( 'click', () => {
		resetCheckout();
		renderOffers();
		offersEl.scrollIntoView( { behavior: 'smooth', block: 'nearest' } );
	} );
	window.addEventListener( 'pagehide', () => {
		if ( state.booking ) {
			try {
				fetch( cfg.rest + 'abandon', { method: 'POST', keepalive: true, credentials: 'same-origin', headers: { 'Content-Type': 'application/json', 'X-WP-Nonce': cfg.nonce }, body: JSON.stringify( { booking_id: state.booking.booking_id } ) } );
			} catch ( e ) {
				// Best effort: the hold also expires on its own.
			}
		}
	} );

	/* Re-run the search when dates, guests or the nights counter change ------------------ */
	let timer;
	const later = () => {
		clearTimeout( timer );
		timer = setTimeout( () => opened && search(), 350 );
	};
	form.addEventListener( 'change', later );
	form.addEventListener( 'submit', ( e ) => e.preventDefault() );
	if ( card ) card.querySelectorAll( '[data-nights-step]' ).forEach( ( b ) => b.addEventListener( 'click', () => {
		syncCheckout();
		later();
	} ) );
	if ( window.location.hash === '#reserve' ) open();
}() );
