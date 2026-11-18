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

	// Rates are charged in THB; other display currencies get an approximation (window.gpCurrency, inc/currency.php).
	const approx = ( thb ) => {
		const c = window.gpCurrency;
		if ( ! c || 'THB' === c.code || ! c.thb ) return '';
		return '≈ ' + c.symbol + Math.round( ( thb / c.thb ) * c.rate ).toLocaleString( 'en-US' );
	};
	const esc = ( s ) => String( s ?? '' ).replace( /[&<>"']/g, ( c ) => ( { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[ c ] ) );
	const iso = ( d ) => d.toISOString().slice( 0, 10 );
	const addDays = ( s, n ) => {
		const d = new Date( s + 'T00:00:00Z' );
		d.setUTCDate( d.getUTCDate() + n );
		return iso( d );
	};
	const fmtDate = ( s ) => new Date( s + 'T00:00:00' ).toLocaleDateString( 'en-GB', { weekday: 'short', day: 'numeric', month: 'short', year: 'numeric' } );
	const fmtNight = ( s ) => new Date( s + 'T00:00:00' ).toLocaleDateString( 'en-GB', { weekday: 'short', day: 'numeric', month: 'short' } );
	const nights = () => parseInt( ( card && card.querySelector( '[data-nights]' ) || {} ).textContent, 10 ) || 3;
	// Sets the stay length on the booking card (main.js re-renders the counter and estimate).
	const setNights = ( n ) => {
		n = Math.min( 30, Math.max( 1, n ) );
		if ( ! card ) return;
		card.querySelectorAll( '[data-nights]' ).forEach( ( el ) => ( el.textContent = n ) );
		card.dispatchEvent( new CustomEvent( 'gp:nights', { detail: n } ) );
	};
	const daysBetween = ( a, b ) => Math.round( ( Date.parse( b ) - Date.parse( a ) ) / 864e5 );

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
	// Promo code carried over from an offer (?promo= on this page or remembered from the Stays page).
	( () => {
		let code = '';
		try {
			code = new URLSearchParams( window.location.search ).get( 'promo' ) || sessionStorage.getItem( 'gpPromo' ) || '';
		} catch ( e ) {}
		if ( code && form.elements.promo && ! form.elements.promo.value ) form.elements.promo.value = code.replace( /[^A-Za-z0-9_-]/g, '' ).slice( 0, 30 );
		// Dates / guests chosen in the home search.
		let stay = {};
		try {
			stay = JSON.parse( sessionStorage.getItem( 'gpStay' ) || '{}' ) || {};
		} catch ( e ) {}
		const ci = form.elements.checkin;
		if ( stay.checkin && ci && stay.checkin >= ci.min ) ci.value = stay.checkin;
		if ( stay.nights >= 1 && stay.nights <= 30 ) setNights( Number( stay.nights ) );
		const ad = form.elements.adults;
		if ( stay.adults && ad && [ ...ad.options ].some( ( o ) => Number( o.value ) === stay.adults ) ) ad.value = String( stay.adults );
	} )();

	const syncCheckout = () => {
		const ci = form.elements.checkin.value;
		const co = form.elements.checkout;
		co.value = ci ? addDays( ci, nights() ) : '';
		co.min = ci ? addDays( ci, 1 ) : '';
		co.max = ci ? addDays( ci, 30 ) : '';
	};
	// A picked check-out date sets the number of nights.
	const checkoutPicked = () => {
		const ci = form.elements.checkin.value;
		const co = form.elements.checkout.value;
		if ( ! ci || ! co ) return;
		const n = daysBetween( ci, co );
		if ( n >= 1 ) setNights( n );
	};

	/* Booking card: live eZee rate for the stay in the form ------------------- */
	// eZee prices are THB; the card shows the visitor's currency (THB exact, others converted).
	const cardMoney = ( thb ) => {
		const c = window.gpCurrency;
		if ( ! c || 'THB' === c.code || ! c.thb ) return '฿' + thb.toLocaleString( 'en-US', { minimumFractionDigits: Math.abs( thb - Math.round( thb ) ) >= 0.005 ? 2 : 0, maximumFractionDigits: 2 } );
		return c.symbol + Math.round( ( thb / c.thb ) * c.rate ).toLocaleString( 'en-US' );
	};
	const cardShortDate = ( s ) => new Date( s + 'T00:00:00' ).toLocaleDateString( 'en-GB', { day: 'numeric', month: 'short', year: 'numeric' } );
	const updateCard = ( offer, stay ) => {
		if ( ! card || ! stay ) return;
		const dates = card.querySelector( '[data-card-dates]' );
		const range = `${ cardShortDate( stay.checkin ) } – ${ cardShortDate( stay.checkout ) }`;
		if ( dates ) {
			dates.hidden = false;
			dates.textContent = offer ? range : `${ range }: ${ t.cardNone }`;
		}
		const set = ( sel, txt ) => card.querySelectorAll( sel ).forEach( ( el ) => ( el.textContent = txt ) );
		if ( ! offer ) {
			set( '[data-subtotal]', '—' );
			set( '[data-total]', '—' );
			const was = card.querySelector( '[data-card-was]' );
			if ( was ) was.hidden = true;
			return;
		}
		const night = offer.total / Math.max( 1, offer.nights );
		// The nights counter (main.js) estimates with this until the next live answer arrives.
		if ( window.gpCurrency && window.gpCurrency.thb ) card.dataset.price = String( night / window.gpCurrency.thb );
		set( '[data-card-night]', cardMoney( night ) + ' / ' );
		set( '[data-card-unit]', cardMoney( night ) );
		set( '[data-subtotal]', cardMoney( offer.total ) );
		set( '[data-total]', cardMoney( offer.total ) );
		const was = card.querySelector( '[data-card-was]' );
		if ( was ) {
			was.hidden = ! offer.before_total;
			was.textContent = offer.before_total ? cardMoney( offer.before_total ) : '';
		}
	};
	const cheapest = ( offers ) => offers.reduce( ( a, o ) => ( ! a || o.total < a.total ? o : a ), null );

	// Price check without opening the reserve panel (page load, nights counter).
	let quoteRun = 0;
	const quote = async () => {
		syncCheckout();
		if ( ! form.elements.checkin.value ) return;
		const run = ++quoteRun;
		if ( card ) card.classList.add( 'is-loading' );
		const res = await api( 'availability?' + new URLSearchParams( stayParams() ).toString() );
		if ( run !== quoteRun ) return;
		if ( card ) card.classList.remove( 'is-loading' );
		if ( res.ok && 'api' === res.flow ) updateCard( cheapest( res.offers || [] ), res.stay );
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
		state.others = res.others || [];
		state.promo = res.promo || null;
		// Remember the stay so another suite's page starts with the same dates / guests.
		try {
			sessionStorage.setItem( 'gpStay', JSON.stringify( { checkin: res.stay.checkin, nights: Number( res.stay.nights ) || daysBetween( res.stay.checkin, res.stay.checkout ), adults: Number( res.stay.adults ) } ) );
		} catch ( e ) {}
		setStatus( '' );
		renderOffers();
		++quoteRun; // a pending card-only price check is now out of date
		if ( card ) card.classList.remove( 'is-loading' );
		updateCard( cheapest( state.offers ), state.stay );
		if ( state.offers.length === 1 ) selectOffer( state.offers[ 0 ].key, true );
	};

	// Promo codes are checked by eZee: say whether this one applies.
	const promoNote = () => {
		const p = state.promo;
		if ( ! p || ! p.code ) return '';
		// The automatic member code only shows when eZee applied it.
		if ( p.member ) return p.applied ? `<p class="gp-book__promo is-ok">${ esc( t.memberRate ) }</p>` : '';
		return p.applied
			? `<p class="gp-book__promo is-ok">${ esc( t.promoOk.replace( '%s', p.code ) ) }</p>`
			: `<p class="gp-book__promo is-bad">${ esc( t.promoBad.replace( '%s', p.code ) ) }</p>`;
	};

	const renderOffers = () => {
		if ( ! state.offers.length ) {
			const others = ( state.others || [] ).map( ( o ) => `<li><a href="${ esc( o.url ) }">${ esc( o.title ) }</a> <span>${ esc( t.from ) } ${ esc( o.night_label ) }${ approx( o.total / Math.max( 1, nights() ) ) ? ' (' + esc( approx( o.total / Math.max( 1, nights() ) ) ) + ')' : '' } / ${ esc( t.night ) }</span></li>` ).join( '' );
			offersEl.innerHTML = promoNote() + `<div class="gp-book__panel gp-book__panel--empty"><p>${ esc( others ? t.roomFull : t.none ) }</p>${ others ? `<ul class="gp-book__others">${ others }</ul>` : '' }</div>`;
			return;
		}
		offersEl.innerHTML = promoNote() + state.offers.map( ( o ) => `
			<label class="gp-rate" data-key="${ esc( o.key ) }">
				<input type="radio" name="rate" value="${ esc( o.key ) }">
				<span class="gp-rate__body">
					<strong>${ esc( o.rate && o.rate !== o.room ? o.rate.replace( o.room + ' — ', '' ) : o.room ) }${ o.promotion ? ` <em>${ esc( o.promotion ) }</em>` : '' }</strong>
					<span class="gp-rate__meta">${ o.inclusion ? esc( o.inclusion ) + ' · ' : '' }<span class="${ o.nonrefundable ? 'is-strict' : 'is-ok' }">${ esc( o.cancel_text ) }</span>${ o.available <= 2 ? ' · ' + esc( t.left.replace( '%d', o.available ) ) : '' }</span>
				</span>
				<span class="gp-rate__price">${ o.before_total ? `<span class="gp-rate__was"><s>${ esc( o.before_label ) }</s> <b>${ esc( t.save.replace( '%d', o.save_pct ) ) }</b></span>` : '' }<strong>${ esc( o.total_label ) }</strong><span>${ o.varies ? esc( t.avg ) + ' ' : '' }${ esc( o.night_label ) } / ${ esc( t.night ) }${ approx( o.total ) ? ' · ' + esc( approx( o.total ) ) : '' }</span></span>
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
		updateCard( offer, state.stay );
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
				${ o.before_total ? `<div><dt>${ esc( t.regular ) }</dt><dd><s>${ esc( o.before_label ) }</s></dd></div><div class="gp-sum__save"><dt>${ esc( o.discount_name ) } (−${ esc( o.save_pct ) }%)</dt><dd>−${ esc( o.save_label ) }</dd></div>` : '' }
				${ ( o.days || [] ).length ? `<div class="gp-sum__nights"><dt>${ esc( t.nightly ) }</dt><dd><ul>${ o.days.map( ( d ) => `<li><span>${ esc( fmtNight( d.date ) ) }</span><span>${ esc( d.label ) }</span></li>` ).join( '' ) }</ul></dd></div>` : `<div><dt>${ esc( o.night_label ) } × ${ esc( o.nights ) } ${ esc( o.nights === 1 ? t.night : t.nights ) }</dt><dd>${ esc( o.total_label ) }</dd></div>` }
				${ o.tax_label ? `<div><dt>${ esc( o.tax_name ? t.taxIncl.replace( '%s', o.tax_name ) : t.taxes ) }</dt><dd>${ esc( o.tax_label ) }</dd></div>` : '' }
			</dl>
			<p class="gp-sum__total"><span>Total to pay today</span><strong>${ esc( o.total_label ) }</strong></p>${ approx( o.total ) ? `<p class="gp-sum__approx">${ esc( approx( o.total ) ) } — ${ esc( t.charged ) }</p>` : '' }
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
		const pts = $( '[data-pay-points]', payment );
		if ( pts ) {
			pts.hidden = ! res.points;
			pts.textContent = res.points ? t.points.replace( '%s', new Intl.NumberFormat( 'en-US' ).format( res.points ) ) : '';
		}
		const testBox = $( '[data-test-box]', payment );
		if ( testBox ) testBox.hidden = ! res.test_checkout;
		if ( res.order_id ) mountPayPal( res );
	} );

	// Test mode (PayPal sandbox): finish the booking without paying; the hotel hold is released.
	const testBtn = $( '[data-test-complete]', payment );
	if ( testBtn ) {
		testBtn.addEventListener( 'click', async () => {
			if ( ! state.booking ) return;
			const alertEl = $( '[data-pay-alert]', payment );
			alertEl.hidden = true;
			testBtn.disabled = true;
			const res = await api( 'test-complete', { method: 'POST', body: JSON.stringify( { booking_id: state.booking.booking_id } ) } );
			if ( res.ok && res.redirect ) {
				clearInterval( state.timer );
				state.booking = null;
				window.location.href = res.redirect;
				return;
			}
			testBtn.disabled = false;
			alertEl.textContent = res.message || t.error;
			alertEl.hidden = false;
		} );
	}

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
		timer = setTimeout( () => ( opened ? search() : quote() ), 350 );
	};
	form.addEventListener( 'change', ( e ) => {
		if ( e.target === form.elements.checkout ) checkoutPicked();
		syncCheckout();
		later();
	} );
	form.addEventListener( 'submit', ( e ) => e.preventDefault() );
	if ( card ) card.addEventListener( 'click', ( e ) => {
		if ( ! e.target.closest( '[data-nights-step]' ) ) return;
		syncCheckout();
		later();
	} );
	syncCheckout();
	if ( window.location.hash === '#reserve' ) open();
	else quote();
}() );
