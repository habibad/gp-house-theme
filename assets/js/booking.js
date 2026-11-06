/*
 * GP House — booking page (/book/).
 * Search → choose a room → guest details → PayPal → confirmation.
 * Talks to the GP House Booking plugin: GET availability, POST checkout / capture / abandon.
 * The browser never decides a price: every amount comes back from the server.
 */
( function () {
	'use strict';

	const cfg = window.gpBooking;
	const root = document.querySelector( '[data-book]' );
	if ( ! cfg || ! root ) return;

	const $ = ( s, c = document ) => c.querySelector( s );
	const $$ = ( s, c = document ) => Array.from( c.querySelectorAll( s ) );
	const form = $( '[data-book-search]' );
	const offersEl = $( '[data-book-offers]', root );
	const statusEl = $( '[data-book-status]', root );
	const checkout = $( '[data-book-checkout]', root );
	const summary = $( '[data-book-summary]', root );
	const gate = $( '[data-gate]', checkout );
	const details = $( '[data-details-form]', checkout );
	const payment = $( '[data-payment]', checkout );

	const state = { stay: null, offers: [], offer: null, booking: null, timer: null, sdk: null };
	const t = cfg.i18n;

	const esc = ( s ) => String( s ?? '' ).replace( /[&<>"']/g, ( c ) => ( { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[ c ] ) );
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
	const fmtDate = ( iso ) => new Date( iso + 'T00:00:00' ).toLocaleDateString( 'en-GB', { weekday: 'short', day: 'numeric', month: 'short', year: 'numeric' } );
	const query = () => {
		const d = Object.fromEntries( new FormData( form ).entries() );
		return { property: d.property, checkin: d.checkin, checkout: d.checkout, adults: d.adults, children: d.children, promo: ( d.promo || '' ).trim() };
	};

	/* Dates: keep check-out after check-in --------------------------------- */
	const inEl = form.elements.checkin;
	const outEl = form.elements.checkout;
	inEl.addEventListener( 'change', () => {
		const min = new Date( inEl.value + 'T00:00:00' );
		min.setDate( min.getDate() + 1 );
		const iso = min.toISOString().slice( 0, 10 );
		outEl.min = iso;
		if ( ! outEl.value || outEl.value < iso ) {
			const d = new Date( inEl.value + 'T00:00:00' );
			d.setDate( d.getDate() + 2 );
			outEl.value = d.toISOString().slice( 0, 10 );
		}
	} );

	/* Search ---------------------------------------------------------------- */
	const search = async ( keepOffer ) => {
		resetCheckout();
		const q = query();
		const params = new URLSearchParams( q );
		window.history.replaceState( null, '', '?' + params.toString() + ( root.dataset.room && root.dataset.room !== '0' ? '&room=' + root.dataset.room : '' ) );
		offersEl.innerHTML = '';
		setStatus( t.searching, 'loading' );
		const res = await api( 'availability?' + params.toString() );
		if ( ! res.ok ) {
			setStatus( res.message || t.error, 'error' );
			return;
		}
		if ( res.flow === 'link' ) {
			setStatus( '' );
			offersEl.innerHTML = `<div class="gp-book__panel"><h2>${ esc( res.label ) }</h2><p>Complete your booking on our secure booking page.</p><a class="gp-btn gp-btn--primary gp-btn--tab" target="_blank" rel="noopener" href="${ esc( res.url ) }">Continue to booking page</a></div>`;
			return;
		}
		state.stay = res.stay;
		state.offers = res.offers;
		setStatus( '' );
		renderOffers( res.label );
		const want = keepOffer || root.dataset.offer;
		if ( want && state.offers.some( ( o ) => o.key === want ) ) {
			root.dataset.offer = '';
			selectOffer( want, true );
		}
	};

	const renderOffers = ( label ) => {
		if ( ! state.offers.length ) {
			offersEl.innerHTML = `<div class="gp-book__panel gp-book__panel--empty"><p>${ esc( t.none ) }</p></div>`;
			return;
		}
		const nightsLabel = state.stay.nights + ' ' + ( state.stay.nights === 1 ? t.night : t.nights );
		offersEl.innerHTML = `<h2 class="gp-book__h2">${ esc( label ) } · ${ esc( fmtDate( state.stay.checkin ) ) } – ${ esc( fmtDate( state.stay.checkout ) ) } · ${ esc( nightsLabel ) }</h2>` +
			state.offers.map( ( o ) => `
			<article class="gp-offer" data-key="${ esc( o.key ) }">
				<div class="gp-offer__media">${ o.image ? `<img src="${ esc( o.image ) }" alt="" loading="lazy">` : '<span class="gp-offer__ph"></span>' }</div>
				<div class="gp-offer__body">
					<h3>${ esc( o.room ) }</h3>
					<p class="gp-offer__rate">${ esc( o.rate && o.rate !== o.room ? o.rate : '' ) }${ o.promotion ? ` <span class="gp-offer__tag">${ esc( o.promotion ) }</span>` : '' }</p>
					${ o.description ? `<p class="gp-offer__desc">${ esc( o.description ) }</p>` : '' }
					<ul class="gp-offer__chips">${ o.inclusion ? `<li class="is-incl">${ esc( o.inclusion ) }</li>` : '' }${ o.amenities.slice( 0, 4 ).map( ( a ) => `<li>${ esc( a ) }</li>` ).join( '' ) }</ul>
					<p class="gp-offer__cancel ${ o.nonrefundable ? 'is-strict' : '' }">${ esc( o.cancel_text ) }</p>
				</div>
				<div class="gp-offer__price">
					${ o.available <= 2 ? `<span class="gp-offer__left">${ esc( t.left.replace( '%d', o.available ) ) }</span>` : '' }
					<strong>${ esc( o.night_label ) }</strong><span>/ ${ esc( t.night ) }</span>
					<p class="gp-offer__total">${ esc( o.total_label ) } total</p>
					<button class="gp-btn gp-btn--primary gp-offer__select" type="button" data-select="${ esc( o.key ) }">${ esc( t.select ) }</button>
				</div>
			</article>` ).join( '' );
	};

	offersEl.addEventListener( 'click', ( e ) => {
		const b = e.target.closest( '[data-select]' );
		if ( b ) selectOffer( b.dataset.select );
	} );

	/* Select a room ------------------------------------------------------------ */
	const selectOffer = ( key, auto ) => {
		const offer = state.offers.find( ( o ) => o.key === key );
		if ( ! offer ) return;
		state.offer = offer;
		$$( '.gp-offer', offersEl ).forEach( ( el ) => el.classList.toggle( 'is-selected', el.dataset.key === key ) );
		$$( '[data-select]', offersEl ).forEach( ( b ) => ( b.textContent = b.dataset.select === key ? t.selected : t.select ) );
		checkout.hidden = false;
		renderSummary();
		payment.hidden = true;
		if ( ! cfg.loggedIn ) {
			// Sign in first, then come straight back to this room.
			const url = new URL( window.location.href );
			url.search = new URLSearchParams( { ...query(), offer: key } ).toString();
			$$( '[data-gate-link]', gate ).forEach( ( a ) => {
				a.dataset.redirect = url.toString();
				a.href = a.href.split( '?' )[ 0 ] + '?redirect_to=' + encodeURIComponent( url.toString() );
			} );
			gate.hidden = false;
			details.hidden = true;
		} else {
			gate.hidden = true;
			details.hidden = false;
			fillDetails();
		}
		if ( ! auto || cfg.loggedIn ) checkout.scrollIntoView( { behavior: 'smooth', block: 'start' } );
	};

	const fillDetails = () => {
		const u = cfg.user || {};
		if ( ! details.elements.first.value ) details.elements.first.value = u.first || '';
		if ( ! details.elements.last.value ) details.elements.last.value = u.last || '';
		details.elements.email.value = u.email || '';
		if ( ! details.elements.phone.value ) details.elements.phone.value = u.phone || '';
	};

	const renderSummary = () => {
		const o = state.offer;
		const s = state.stay;
		summary.hidden = false;
		$( '[data-summary-body]', summary ).innerHTML = `
			<p class="gp-sum__hotel">${ esc( ( cfg.properties.find( ( p ) => p.slug === s.property ) || {} ).label ) }</p>
			<p class="gp-sum__room">${ esc( o.room ) }${ o.rate && o.rate !== o.room ? ' — ' + esc( o.rate ) : '' }</p>
			<dl>
				<div><dt>Check-in</dt><dd>${ esc( fmtDate( s.checkin ) ) }</dd></div>
				<div><dt>Check-out</dt><dd>${ esc( fmtDate( s.checkout ) ) }</dd></div>
				<div><dt>Guests</dt><dd>${ esc( s.adults ) } adults${ Number( s.children ) ? ', ' + esc( s.children ) + ' children' : '' }</dd></div>
				<div><dt>${ esc( o.night_label ) } × ${ esc( o.nights ) } ${ esc( o.nights === 1 ? t.night : t.nights ) }</dt><dd>${ esc( o.total_label ) }</dd></div>
			</dl>
			<p class="gp-sum__total"><span>Total to pay today</span><strong>${ esc( o.total_label ) }</strong></p>
			<p class="gp-sum__note">${ esc( o.cancel_text ) }. Prices include all taxes and fees unless stated.</p>`;
	};

	/* Guest details → hold + PayPal order ---------------------------------------- */
	details.addEventListener( 'submit', async ( e ) => {
		e.preventDefault();
		const alertEl = $( '[data-form-alert]', details );
		alertEl.hidden = true;
		const btn = $( '[data-pay-button]', details );
		const d = Object.fromEntries( new FormData( details ).entries() );
		if ( ! details.checkValidity() ) {
			details.reportValidity();
			return;
		}
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
			if ( res.status === 409 ) search( state.offer.key );
			return;
		}
		state.booking = res;
		details.hidden = true;
		payment.hidden = false;
		startTimer( res.expires_in );
		payment.scrollIntoView( { behavior: 'smooth', block: 'start' } );
		mountPayPal( res );
	} );

	const startTimer = ( seconds ) => {
		clearInterval( state.timer );
		const el = $( '[data-hold-timer]', payment );
		let left = Math.max( 0, seconds );
		const tick = () => {
			const m = String( Math.floor( left / 60 ) ).padStart( 2, '0' );
			const s = String( left % 60 ).padStart( 2, '0' );
			el.textContent = m + ':' + s;
			if ( left <= 0 ) {
				clearInterval( state.timer );
				setStatus( t.expired, 'error' );
				resetCheckout();
				search();
				return;
			}
			left--;
		};
		tick();
		state.timer = setInterval( tick, 1000 );
	};

	const loadSdk = ( clientId ) => new Promise( ( resolve, reject ) => {
		if ( window.paypal && state.sdk === clientId ) return resolve( window.paypal );
		$$( 'script[data-gp-paypal]' ).forEach( ( s ) => s.remove() );
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

	/* Reset / change room --------------------------------------------------------- */
	const resetCheckout = () => {
		clearInterval( state.timer );
		if ( state.booking && payment && ! payment.hidden ) {
			const id = state.booking.booking_id;
			api( 'abandon', { method: 'POST', body: JSON.stringify( { booking_id: id } ) } );
		}
		state.booking = null;
		state.offer = null;
		checkout.hidden = true;
		summary.hidden = true;
		payment.hidden = true;
		details.hidden = true;
		gate.hidden = true;
		$$( '.gp-offer', offersEl ).forEach( ( el ) => el.classList.remove( 'is-selected' ) );
	};
	$( '[data-change-room]', payment ).addEventListener( 'click', () => {
		resetCheckout();
		offersEl.scrollIntoView( { behavior: 'smooth', block: 'start' } );
		$$( '[data-select]', offersEl ).forEach( ( b ) => ( b.textContent = t.select ) );
	} );
	window.addEventListener( 'pagehide', () => {
		if ( state.booking && ! payment.hidden ) {
			const body = JSON.stringify( { booking_id: state.booking.booking_id } );
			try {
				fetch( cfg.rest + 'abandon', { method: 'POST', keepalive: true, credentials: 'same-origin', headers: { 'Content-Type': 'application/json', 'X-WP-Nonce': cfg.nonce }, body } );
			} catch ( e ) {
				// Best effort: the hold also expires on its own.
			}
		}
	} );

	form.addEventListener( 'submit', ( e ) => {
		e.preventDefault();
		if ( ! form.checkValidity() ) return form.reportValidity();
		search();
	} );

	search();
}() );
