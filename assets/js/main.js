/**
 * GP House — front-end interactions. No dependencies.
 */
( function () {
	'use strict';

	const $ = ( sel, ctx = document ) => ctx.querySelector( sel );
	const $$ = ( sel, ctx = document ) => Array.from( ctx.querySelectorAll( sel ) );

	/* Header: "Others" dropdown + mobile menu ----------------------------- */
	function initHeader() {
		const more = $( '.gp-nav__more' );
		if ( more ) {
			const toggle = $( '.gp-nav__more-toggle', more );
			const setOpen = ( open ) => {
				more.classList.toggle( 'is-open', open );
				toggle.setAttribute( 'aria-expanded', String( open ) );
			};
			toggle.addEventListener( 'click', () => setOpen( ! more.classList.contains( 'is-open' ) ) );
			document.addEventListener( 'click', ( e ) => {
				if ( ! more.contains( e.target ) ) setOpen( false );
			} );
			document.addEventListener( 'keydown', ( e ) => {
				if ( e.key === 'Escape' ) setOpen( false );
			} );
		}

		const burger = $( '.gp-header__toggle' );
		const menu = $( '#gp-mobile-menu' );
		if ( burger && menu ) {
			const setMenu = ( open ) => {
				burger.setAttribute( 'aria-expanded', String( open ) );
				menu.hidden = ! open;
				document.documentElement.classList.toggle( 'gp-menu-open', open && window.matchMedia( '(max-width: 600px)' ).matches );
			};
			burger.addEventListener( 'click', () => setMenu( burger.getAttribute( 'aria-expanded' ) !== 'true' ) );
			$$( '[data-menu-close]', menu ).forEach( ( b ) => b.addEventListener( 'click', () => setMenu( false ) ) );
			document.addEventListener( 'keydown', ( e ) => e.key === 'Escape' && ! menu.hidden && setMenu( false ) );
		}
	}

	/* Hero search: clicking anywhere in a field opens its picker ---------- */
	function initSearch() {
		$$( '.gp-search__field' ).forEach( ( field ) => {
			const control = $( 'select, input[type="date"]', field );
			if ( ! control ) return;
			field.addEventListener( 'click', ( e ) => {
				if ( window.matchMedia( '(max-width: 600px)' ).matches ) return; // phone: custom pop-ups (initHeroSearch)
				if ( e.target === control && control.tagName === 'SELECT' ) return;
				if ( typeof control.showPicker === 'function' ) {
					try {
						control.showPicker();
					} catch ( err ) {
						control.focus();
					}
				} else {
					control.focus();
				}
			} );
		} );
	}

	/* Explore destinations: map/grid views, pins, cards, zoom ------------- */
	function initExplore() {
		$$( '[data-view-root]' ).forEach( initViewRoot );
		$$( '.gp-map' ).forEach( initMap );
	}

	/* Grid / map view toggle inside any [data-view-root] */
	function initViewRoot( section ) {

		$$( '[data-view-btn]', section ).forEach( ( btn ) => {
			btn.addEventListener( 'click', () => {
				const view = btn.dataset.viewBtn;
				section.dataset.view = view;
				$$( '[data-view-btn]', section ).forEach( ( b ) => {
					const on = b === btn;
					b.classList.toggle( 'is-active', on );
					b.setAttribute( 'aria-selected', String( on ) );
				} );
				$$( '[data-panel]', section ).forEach( ( panel ) => {
					panel.hidden = panel.dataset.panel !== view;
					panel.classList.toggle( 'gp-anim-in', ! panel.hidden );
				} );
			} );
		} );

	}

	/* Stay map component: pins, cards, region tabs, zoom */
	function initMap( map ) {

		const pins = $$( '.gp-pin', map );
		const cards = $$( '[data-stay-card]', map );
		const minis = $$( '.gp-mini-card', map );
		const regions = $$( '[data-region]', map );

		const select = ( index ) => {
			index = String( index );
			cards.forEach( ( c ) => {
				const show = c.dataset.stayCard === index;
				if ( show && c.hidden ) {
					c.classList.remove( 'gp-anim-in' );
					void c.offsetWidth; // restart the animation
					c.classList.add( 'gp-anim-in' );
				}
				c.hidden = ! show;
			} );
			minis.forEach( ( m ) => m.classList.toggle( 'is-active', m.dataset.stay === index ) );
			pins.forEach( ( p ) => p.classList.toggle( 'is-active', p.dataset.stay === index ) );
		};

		[ ...pins, ...minis ].forEach( ( el ) => el.addEventListener( 'click', () => select( el.dataset.stay ) ) );

		regions.forEach( ( tab ) => {
			tab.addEventListener( 'click', () => {
				regions.forEach( ( t ) => t.classList.toggle( 'is-active', t === tab ) );
				const name = tab.dataset.region;
				const match = cards.find( ( c ) => ( $( '.gp-chip', c ) || {} ).textContent.trim() === name );
				if ( match ) select( match.dataset.stayCard );
			} );
		} );

		const image = $( '.gp-map__image', map );
		let zoom = 1;
		$$( '[data-zoom]', map ).forEach( ( btn ) => {
			btn.addEventListener( 'click', () => {
				zoom = Math.min( 2, Math.max( 1, zoom + ( btn.dataset.zoom === 'in' ? 0.25 : -0.25 ) ) );
				image.style.transform = `scale(${ zoom })`;
			} );
		} );
	}

	/* Featured stays carousel -------------------------------------------- */
	function initCarousels() {
		$$( '[data-carousel]' ).forEach( ( root ) => {
			const track = $( '[data-carousel-track]', root );
			const prev = $( '[data-carousel-prev]', root );
			const next = $( '[data-carousel-next]', root );
			const bar = $( '[data-carousel-progress]', root );
			if ( ! track ) return;
			const items = Array.from( track.children );
			let index = 0;

			const step = () => {
				const gap = parseFloat( getComputedStyle( track ).columnGap ) || 0;
				return items[ 0 ].getBoundingClientRect().width + gap;
			};
			const visible = () => Math.max( 1, Math.floor( ( track.parentElement.clientWidth + 24 ) / step() ) );
			const max = () => Math.max( 0, items.length - visible() );
			const render = () => {
				index = Math.min( index, max() );
				track.style.transform = `translateX(${ -index * step() }px)`;
				if ( prev ) prev.disabled = index === 0;
				if ( next ) next.disabled = index >= max();
				if ( bar ) {
					const room = bar.parentElement.clientWidth - bar.offsetWidth;
					bar.style.left = `${ max() ? ( index / max() ) * room : 0 }px`;
				}
			};

			prev && prev.addEventListener( 'click', () => {
				index = Math.max( 0, index - 1 );
				render();
			} );
			next && next.addEventListener( 'click', () => {
				index = Math.min( max(), index + 1 );
				render();
			} );

			// Touch swipe.
			let startX = null;
			track.addEventListener( 'touchstart', ( e ) => ( startX = e.touches[ 0 ].clientX ), { passive: true } );
			track.addEventListener( 'touchend', ( e ) => {
				if ( startX === null ) return;
				const dx = e.changedTouches[ 0 ].clientX - startX;
				if ( Math.abs( dx ) > 40 ) {
					index = Math.min( max(), Math.max( 0, index + ( dx < 0 ? 1 : -1 ) ) );
					render();
				}
				startX = null;
			} );

			window.addEventListener( 'resize', render );
			render();
		} );
	}

	/* Saved stays (heart) — stored locally until member accounts exist ---- */
	function initFavourites() {
		const KEY = 'gpHouseSaved';
		let saved;
		try {
			saved = JSON.parse( localStorage.getItem( KEY ) || 'null' );
		} catch ( e ) {
			saved = null;
		}
		$$( '[data-fav]' ).forEach( ( btn ) => {
			const id = btn.dataset.fav;
			if ( Array.isArray( saved ) ) {
				const on = saved.includes( id );
				btn.classList.toggle( 'is-active', on );
				btn.setAttribute( 'aria-pressed', String( on ) );
			}
			btn.addEventListener( 'click', () => {
				const list = $$( '[data-fav].is-active' ).map( ( b ) => b.dataset.fav );
				const on = ! btn.classList.contains( 'is-active' );
				btn.classList.toggle( 'is-active', on );
				btn.setAttribute( 'aria-pressed', String( on ) );
				const next = on ? [ ...new Set( [ ...list, id ] ) ] : list.filter( ( x ) => x !== id );
				try {
					localStorage.setItem( KEY, JSON.stringify( next ) );
				} catch ( e ) {}
			} );
		} );
	}

	/* Why book direct: highlight the hovered / focused benefit ----------- */
	function initBenefits() {
		const items = $$( '.gp-benefit' );
		items.forEach( ( item ) => {
			const activate = () => items.forEach( ( i ) => i.classList.toggle( 'is-active', i === item ) );
			item.addEventListener( 'mouseenter', activate );
			item.addEventListener( 'focus', activate );
		} );
	}

	/* Journal: destination filter + stacking cards ----------------------- */
	function initJournal() {
		const root = $( '[data-journal]' );
		if ( ! root ) return;
		const stories = $$( '.gp-story', root );

		$$( '[data-filter]', root ).forEach( ( tab ) => {
			tab.addEventListener( 'click', () => {
				$$( '[data-filter]', root ).forEach( ( t ) => t.classList.toggle( 'is-active', t === tab ) );
				const f = tab.dataset.filter;
				let shown = 0;
				stories.forEach( ( s ) => {
					const on = ! f || s.dataset.location === f;
					s.hidden = ! on;
					if ( on ) s.style.setProperty( '--i', shown++ );
				} );
				update();
			} );
		} );

		// Figma end state: back cards are 902px and 960px wide in a 1128px column.
		const targets = [ 1, 960 / 1128, 902 / 1128 ];
		const reduced = window.matchMedia( '(prefers-reduced-motion: reduce)' );
		let ticking = false;

		function update() {
			ticking = false;
			const visible = stories.filter( ( s ) => ! s.hidden );
			if ( reduced.matches || window.innerWidth < 768 ) {
				visible.forEach( ( s ) => ( s.style.transform = '' ) );
				return;
			}
			const scaleFor = ( k ) => ( k < targets.length ? targets[ k ] : targets[ targets.length - 1 ] - ( k - targets.length + 1 ) * 0.05 );
			// How far each card is from its sticky point: 0 = a full card away, 1 = stuck.
			const arrival = visible.map( ( s ) => {
				const range = s.offsetHeight + 48;
				const gap = s.getBoundingClientRect().top - ( parseFloat( getComputedStyle( s ).top ) || 0 );
				return Math.min( 1, Math.max( 0, 1 - gap / range ) );
			} );
			visible.forEach( ( story, i ) => {
				const covered = arrival.slice( i + 1 ).reduce( ( sum, a ) => sum + a, 0 );
				const lo = Math.floor( covered );
				const scale = scaleFor( lo ) + ( scaleFor( Math.ceil( covered ) ) - scaleFor( lo ) ) * ( covered - lo );
				story.style.transform = scale < 1 ? `scale(${ scale })` : '';
			} );
		}

		window.addEventListener( 'scroll', () => {
			if ( ! ticking ) {
				ticking = true;
				requestAnimationFrame( update );
			}
		}, { passive: true } );
		window.addEventListener( 'resize', update );
		update();
	}

	/* Footer currency switch (visual until pricing API exists) ----------- */
	function initCurrency() {
		const buttons = $$( '[data-currency]' );
		const label = $( '.gp-locale__currency' );
		let current = 'USD';
		try {
			current = localStorage.getItem( 'gpHouseCurrency' ) || 'USD';
		} catch ( e ) {}
		const set = ( code ) => {
			buttons.forEach( ( b ) => b.classList.toggle( 'is-active', b.dataset.currency === code ) );
			if ( label ) label.textContent = code;
			try {
				localStorage.setItem( 'gpHouseCurrency', code );
			} catch ( e ) {}
		};
		buttons.forEach( ( b ) => b.addEventListener( 'click', () => set( b.dataset.currency ) ) );
		set( current );
	}


	/* Filter dropdowns (gp_dropdown) ------------------------------------- */
	function initDropdowns() {
		const all = $$( '[data-dropdown]' );
		const close = ( dd ) => {
			dd.classList.remove( 'is-open' );
			$( '.gp-select__toggle', dd ).setAttribute( 'aria-expanded', 'false' );
			$( '.gp-select__menu', dd ).hidden = true;
		};
		all.forEach( ( dd ) => {
			const toggle = $( '.gp-select__toggle', dd );
			const menu = $( '.gp-select__menu', dd );
			const input = $( 'input[type=hidden]', dd );
			const text = $( '.gp-select__text', dd );
			const showValue = dd.classList.contains( 'gp-select--sort' );
			toggle.addEventListener( 'click', () => {
				const open = ! dd.classList.contains( 'is-open' );
				all.forEach( close );
				if ( open ) {
					dd.classList.add( 'is-open' );
					toggle.setAttribute( 'aria-expanded', 'true' );
					menu.hidden = false;
					( $( '[aria-selected=true]', menu ) || menu.firstElementChild ).focus();
				}
			} );
			const choose = ( li ) => {
				$$( 'li', menu ).forEach( ( o ) => o.setAttribute( 'aria-selected', String( o === li ) ) );
				input.value = li.dataset.value;
				text.textContent = li.dataset.value || showValue ? li.textContent : toggle.dataset.label;
				dd.classList.toggle( 'is-set', !! li.dataset.value && ! showValue );
				close( dd );
				toggle.focus();
				input.dispatchEvent( new Event( 'change', { bubbles: true } ) );
			};
			menu.addEventListener( 'click', ( e ) => {
				const li = e.target.closest( 'li' );
				if ( li ) choose( li );
			} );
			menu.addEventListener( 'keydown', ( e ) => {
				const items = $$( 'li', menu );
				const i = items.indexOf( document.activeElement );
				if ( e.key === 'ArrowDown' || e.key === 'ArrowUp' ) {
					e.preventDefault();
					items[ ( i + ( e.key === 'ArrowDown' ? 1 : -1 ) + items.length ) % items.length ].focus();
				} else if ( e.key === 'Enter' || e.key === ' ' ) {
					e.preventDefault();
					if ( items[ i ] ) choose( items[ i ] );
				} else if ( e.key === 'Escape' ) {
					close( dd );
					toggle.focus();
				}
			} );
		} );
		document.addEventListener( 'click', ( e ) => {
			all.forEach( ( dd ) => {
				if ( ! dd.contains( e.target ) ) close( dd );
			} );
		} );
	}

	/* Stays: filter rooms in place and keep the URL in sync --------------- */
	function initRoomFilters() {
		const form = $( '[data-filters]' );
		const list = $( '[data-rooms]' );
		if ( ! form || ! list ) return;
		const tagsInput = $( 'input[name=tags]', form );
		const empty = $( '[data-rooms-empty]', list );
		const rooms = $$( '[data-room]', list );

		const apply = () => {
			const v = Object.fromEntries( new FormData( form ) );
			const tags = ( v.tags || '' ).split( ',' ).filter( Boolean );
			let shown = 0;
			rooms.forEach( ( r ) => {
				const d = r.dataset;
				const on = ( ! v.destination || d.location === v.destination )
					&& ( ! v.room || d.type === v.room )
					&& ( ! v.bed || d.beds.split( ' ' ).includes( v.bed ) )
					&& tags.every( ( t ) => d.tags.split( ' ' ).includes( t ) );
				r.hidden = ! on;
				if ( on ) shown++;
			} );
			const key = { 'price-asc': ( a, b ) => a.dataset.price - b.dataset.price, 'price-desc': ( a, b ) => b.dataset.price - a.dataset.price, 'size-desc': ( a, b ) => b.dataset.area - a.dataset.area }[ v.sort ];
			const ordered = key ? rooms.slice().sort( key ) : rooms;
			ordered.forEach( ( r ) => list.insertBefore( r, empty ) );
			empty.hidden = shown > 0;

			const params = new URLSearchParams( window.location.search );
			[ 'destination', 'room', 'bed', 'tags', 'sort' ].forEach( ( k ) => ( v[ k ] ? params.set( k, v[ k ] ) : params.delete( k ) ) );
			const qs = params.toString();
			history.replaceState( null, '', window.location.pathname + ( qs ? '?' + qs : '' ) );
		};

		$$( '[data-tag]', form ).forEach( ( chip ) => {
			chip.addEventListener( 'click', () => {
				const on = ! chip.classList.contains( 'is-active' );
				chip.classList.toggle( 'is-active', on );
				chip.setAttribute( 'aria-pressed', String( on ) );
				tagsInput.value = $$( '[data-tag].is-active', form ).map( ( c ) => c.dataset.tag ).join( ',' );
				apply();
			} );
		} );
		form.addEventListener( 'change', apply );
		form.addEventListener( 'submit', ( e ) => {
			e.preventDefault();
			apply();
		} );
	}

	/* Room galleries: thumbnail strip swaps the main photo ---------------- */
	function initGalleries() {
		$$( '[data-gallery]' ).forEach( ( g ) => {
			const main = $( '[data-gallery-main]', g );
			const thumbs = $$( '.gp-room__thumb', g );
			thumbs.forEach( ( t ) => {
				t.addEventListener( 'click', () => {
					thumbs.forEach( ( x ) => {
						x.classList.toggle( 'is-active', x === t );
						x.toggleAttribute( 'aria-current', x === t );
					} );
					main.src = t.dataset.full;
				} );
			} );
		} );
	}

	/* Room page: gallery strip + arrows ---------------------------------- */
	function initRoomGallery() {
		const root = $( '[data-room-gallery]' );
		if ( ! root ) return;
		const main = $( '[data-room-gallery-main]', root );
		const strip = $( '[data-room-gallery-strip]', root );
		const thumbs = $$( '.gp-room-gallery__thumb', strip );
		let index = Math.max( 0, thumbs.findIndex( ( t ) => t.classList.contains( 'is-active' ) ) );
		let offset = 0;

		const show = ( i ) => {
			index = ( i + thumbs.length ) % thumbs.length;
			thumbs.forEach( ( t, k ) => {
				t.classList.toggle( 'is-active', k === index );
				t.toggleAttribute( 'aria-current', k === index );
			} );
			// The mobile-only crop applies to the first photo only.
			main.parentElement.querySelectorAll( 'source' ).forEach( ( s ) => s.remove() );
			main.src = thumbs[ index ].dataset.full;
			// Keep the active thumbnail inside the visible area (clear of the arrows).
			const view = root.getBoundingClientRect();
			const r = thumbs[ index ].getBoundingClientRect();
			const pad = Math.min( 220, view.width * 0.2 );
			if ( r.left < view.left + pad ) offset += view.left + pad - r.left;
			else if ( r.right > view.right - pad ) offset -= r.right - ( view.right - pad );
			strip.style.transform = offset ? `translateX(${ offset }px)` : '';
		};

		thumbs.forEach( ( t, k ) => t.addEventListener( 'click', () => show( k ) ) );
		$( '[data-room-gallery-prev]', root ).addEventListener( 'click', () => show( index - 1 ) );
		$( '[data-room-gallery-next]', root ).addEventListener( 'click', () => show( index + 1 ) );
	}

	/* Room page: nights counter updates the estimate --------------------- */
	function initBookingCard() {
		const card = $( '[data-booking]' );
		if ( ! card ) return;
		const price = parseFloat( card.dataset.price ) || 0;
		const fmt = ( n ) => '$' + Math.round( n ).toLocaleString( 'en-US' );
		let nights = parseInt( $( '[data-nights]', card ).textContent, 10 ) || 1;
		const render = () => {
			$$( '[data-nights]', card ).forEach( ( el ) => ( el.textContent = nights ) );
			$( '[data-subtotal]', card ).textContent = fmt( price * nights );
			$( '[data-total]', card ).textContent = fmt( price * nights );
			$( '[data-nights-step="-1"]', card ).disabled = nights <= 1;
		};
		$$( '[data-nights-step]', card ).forEach( ( b ) => b.addEventListener( 'click', () => {
			nights = Math.min( 30, Math.max( 1, nights + parseInt( b.dataset.nightsStep, 10 ) ) );
			render();
		} ) );
		render();
	}

	/* Destinations page: region chips filter the cards and focus the map --- */
	function initRegionTabs() {
		$$( '[data-region-tabs]' ).forEach( ( group ) => {
			const root = group.closest( '[data-view-root]' ) || document;
			const chips = $$( '[data-region-filter]', group );
			chips.forEach( ( chip ) => {
				chip.addEventListener( 'click', () => {
					chips.forEach( ( c ) => {
						c.classList.toggle( 'is-active', c === chip );
						c.setAttribute( 'aria-pressed', String( c === chip ) );
					} );
					const slug = chip.dataset.regionFilter;
					$$( '[data-region-items] [data-location]', root ).forEach( ( card ) => {
						card.hidden = !! slug && card.dataset.location !== slug;
					} );
					const tab = chip.dataset.regionName && $( `.gp-map [data-region="${ chip.dataset.regionName }"]`, root );
					if ( tab ) tab.click();
				} );
			} );
		} );
	}

	/* FAQ accordion ------------------------------------------------------ */
	function initAccordions() {
		$$( '[data-accordion]' ).forEach( ( list ) => {
			$$( '.gp-faq__q button', list ).forEach( ( btn ) => {
				btn.addEventListener( 'click', () => {
					const item = btn.closest( '.gp-faq__item' );
					const open = ! item.classList.contains( 'is-open' );
					item.classList.toggle( 'is-open', open );
					btn.setAttribute( 'aria-expanded', String( open ) );
					document.getElementById( btn.getAttribute( 'aria-controls' ) ).hidden = ! open;
				} );
			} );
		} );
	}

	/* Insider secrets / benefit-style lists: highlight hovered item ------- */
	function initHoverLists() {
		[ '.gp-insider__item', '.gp-model' ].forEach( ( sel ) => {
			const items = $$( sel );
			items.forEach( ( item ) => {
				const on = () => items.forEach( ( i ) => i.classList.toggle( 'is-active', i === item ) );
				item.addEventListener( 'mouseenter', on );
				item.addEventListener( 'focus', on );
			} );
		} );
	}

	/* Club points calculator: nights × nightly price × points-per-$ ------- */
	function initClubCalc() {
		$$( '[data-calc]' ).forEach( ( calc ) => {
			const rate = parseFloat( calc.dataset.rate ) || 1;
			const range = $( '[data-calc-range]', calc );
			const dests = $$( '[data-calc-dest]', calc );
			const out = {
				nights: $( '[data-calc-nights]', calc ),
				total: $( '[data-calc-total]', calc ),
				ctaNights: $( '[data-calc-cta-nights]', calc ),
				ctaDest: $( '[data-calc-cta-dest]', calc ),
				cta: $( '[data-calc-cta]', calc ),
			};
			const rewards = $$( '[data-calc-reward]', calc );
			const fmt = new Intl.NumberFormat( 'en-US' );
			let dest = dests.find( ( d ) => d.classList.contains( 'is-active' ) ) || dests[ 0 ];

			const render = () => {
				const nights = parseInt( range.value, 10 ) || 1;
				const price = dest ? parseFloat( dest.dataset.price ) || 0 : 0;
				const points = Math.round( nights * price * rate );
				const pct = ( ( nights - range.min ) / ( range.max - range.min ) ) * 100;
				range.style.setProperty( '--pct', `${ pct }%` );
				out.nights.textContent = nights;
				out.total.textContent = fmt.format( points );
				out.ctaNights.textContent = nights;
				if ( dest ) {
					out.ctaDest.textContent = dest.dataset.name;
					const url = new URL( out.cta.href, window.location.href );
					url.searchParams.set( 'location', dest.dataset.calcDest );
					url.searchParams.set( 'nights', nights );
					out.cta.href = url.toString();
				}
				rewards.forEach( ( r ) => r.classList.toggle( 'is-reached', points >= parseInt( r.dataset.calcReward, 10 ) ) );
			};

			range.addEventListener( 'input', render );
			dests.forEach( ( d ) => d.addEventListener( 'click', () => {
				dest = d;
				dests.forEach( ( x ) => {
					x.classList.toggle( 'is-active', x === d );
					x.setAttribute( 'aria-checked', String( x === d ) );
				} );
				render();
			} ) );
			render();
		} );
	}

	/* Tabbed panels (About team): [data-team] with [data-team-tab] / [data-team-panel] */
	function initTeamTabs() {
		$$( '[data-team]' ).forEach( ( root ) => {
			const tabs = $$( '[data-team-tab]', root );
			const panels = $$( '[data-team-panel]', root );
			tabs.forEach( ( tab ) => tab.addEventListener( 'click', () => {
				tabs.forEach( ( t ) => {
					t.classList.toggle( 'is-active', t === tab );
					t.setAttribute( 'aria-selected', String( t === tab ) );
				} );
				panels.forEach( ( p ) => ( p.hidden = p.dataset.teamPanel !== tab.dataset.teamTab ) );
			} ) );
		} );
	}

	/* Owner revenue simulator (Investor Relations) -------------------------- */
	function initOwnerSim() {
		$$( '[data-sim]' ).forEach( ( root ) => {
			const adrLift = parseFloat( root.dataset.adrLift ) || 0;
			const occLift = parseFloat( root.dataset.occLift ) || 0;
			const ota = parseFloat( root.dataset.ota ) || 0;
			const inputs = {};
			$$( '[data-sim-in]', root ).forEach( ( i ) => ( inputs[ i.dataset.simIn ] = i ) );
			const fmt = ( n ) => new Intl.NumberFormat( 'en-US', { maximumFractionDigits: 0 } ).format( Math.round( n ) );
			const compact = ( n ) => ( n >= 1e6 ? ( n / 1e6 ).toFixed( 2 ) + 'M' : n >= 1e3 ? ( n / 1e3 ).toFixed( 1 ) + 'K' : fmt( n ) );
			const set = ( key, text ) => $$( `[data-sim-${ key }]`, root ).forEach( ( el ) => ( el.textContent = text ) );

			const render = () => {
				const keys = +inputs.keys.value;
				const adr = +inputs.adr.value;
				const occ = +inputs.occ.value;
				const cur = keys * 365 * adr * ( occ / 100 );
				const newAdr = Math.round( adr * ( 1 + adrLift ) );
				const newOcc = Math.min( 100, occ + occLift );
				const proj = keys * 365 * newAdr * ( newOcc / 100 );
				Object.keys( inputs ).forEach( ( k ) => {
					const i = inputs[ k ];
					i.style.setProperty( '--pct', `${ ( ( i.value - i.min ) / ( i.max - i.min ) ) * 100 }%` );
					const out = $( `[data-sim-out="${ k }"]`, root );
					if ( out ) out.textContent = out.dataset.format.replace( '%s', i.value ).replace( '%%', '%' );
				} );
				set( 'total', fmt( proj - cur ) );
				set( 'proj', fmt( proj ) );
				set( 'cur', fmt( cur ) );
				set( 'adr', fmt( newAdr ) );
				set( 'occ', fmt( newOcc ) );
				set( 'ota', compact( proj * ota ) );
			};
			Object.values( inputs ).forEach( ( i ) => i.addEventListener( 'input', render ) );
			render();
		} );
	}

	/* Share buttons: native share sheet, else copy the link ---------------- */
	function initShare() {
		$$( '[data-share]' ).forEach( ( btn ) => {
			btn.addEventListener( 'click', async () => {
				const data = { title: btn.dataset.shareTitle || document.title, url: btn.dataset.shareUrl || window.location.href };
				try {
					if ( navigator.share ) {
						await navigator.share( data );
					} else if ( navigator.clipboard ) {
						await navigator.clipboard.writeText( data.url );
						btn.classList.add( 'is-copied' );
						setTimeout( () => btn.classList.remove( 'is-copied' ), 1600 );
					}
				} catch ( e ) {
					// Share sheet dismissed — nothing to do.
				}
			} );
		} );
	}

	/* Self check-in: copy buttons, NFC unlock + arrival confirmation --------- */
	function initCheckin() {
		$$( '[data-copy]' ).forEach( ( btn ) => btn.addEventListener( 'click', async () => {
			try {
				await navigator.clipboard.writeText( btn.dataset.copy );
				btn.classList.add( 'is-copied' );
				setTimeout( () => btn.classList.remove( 'is-copied' ), 1400 );
			} catch ( e ) {
				// Clipboard blocked — nothing to do.
			}
		} ) );
		[ '[data-unlock]', '[data-arrived]' ].forEach( ( sel ) => $$( sel ).forEach( ( btn ) => btn.addEventListener( 'click', () => {
			btn.textContent = btn.dataset.done;
			btn.classList.add( 'is-done' );
			btn.disabled = true;
		} ) ) );
	}

	/* Phone hero search: one field at a time, custom location list + calendar (Figma 1455:20139 … 1458:10990) */
	function initHeroSearch() {
		const form = $( '[data-hero-search]' );
		if ( ! form ) return;
		const phone = window.matchMedia( '(max-width: 600px)' );
		const fields = $$( '[data-step-field]', form );
		const select = $( 'select[name="destination"]', form );
		const dateInput = $( 'input[name="checkin"]', form );
		const guestSel = $( 'select[name="guests"]', form );
		const dateLabel = $( '[data-date-label]', form );
		const menu = $( '[data-search-menu]', form );
		const cal = $( '[data-search-cal]', form );
		const nav = $( '[data-search-nav]', form );
		const stepper = $( '[data-stepper]', form );
		const count = $( '[data-guest-count]', form );
		const guestSteps = $$( 'option', guestSel ).map( ( o ) => o.value );
		const NIGHTS = 4;
		let step = 0;
		let view = null; // first day of the month shown in the calendar

		const pad = ( n ) => String( n ).padStart( 2, '0' );
		const iso = ( d ) => `${ d.getFullYear() }-${ pad( d.getMonth() + 1 ) }-${ pad( d.getDate() ) }`;
		const parse = ( s ) => { const [ y, m, d ] = s.split( '-' ).map( Number ); return new Date( y, m - 1, d ); };
		const today = () => { const t = new Date(); return new Date( t.getFullYear(), t.getMonth(), t.getDate() ); };

		const closePopups = () => { menu.hidden = true; cal.hidden = true; };
		const showStep = ( n ) => {
			step = Math.max( 0, Math.min( 3, n ) );
			form.dataset.step = String( step );
			fields.forEach( ( f, i ) => f.classList.toggle( 'is-current', i === step ) );
			nav.hidden = ! phone.matches || step === 0;
			stepper.hidden = ! phone.matches || step !== 2;
			form.classList.toggle( 'is-last', step === 3 );
			closePopups();
		};
		const syncDate = () => {
			if ( ! dateInput.value ) return;
			const d = parse( dateInput.value );
			dateLabel.textContent = `${ pad( d.getDate() ) }/${ pad( d.getMonth() + 1 ) }/${ d.getFullYear() }`;
		};
		const syncGuests = () => {
			const n = parseInt( guestSel.value, 10 ) || 1;
			count.textContent = String( n );
		};

		// Location list
		fields[ 0 ].addEventListener( 'click', ( e ) => {
			if ( ! phone.matches ) return;
			e.preventDefault();
			cal.hidden = true;
			menu.hidden = ! menu.hidden;
		} );
		$$( 'button', menu ).forEach( ( b ) => b.addEventListener( 'click', () => {
			select.value = b.dataset.value;
			showStep( 1 );
		} ) );

		// Calendar
		const renderCal = () => {
			const sel = dateInput.value ? parse( dateInput.value ) : null;
			const end = sel ? new Date( sel.getFullYear(), sel.getMonth(), sel.getDate() + NIGHTS ) : null;
			const first = new Date( view.getFullYear(), view.getMonth(), 1 );
			const lead = first.getDay();
			const days = new Date( view.getFullYear(), view.getMonth() + 1, 0 ).getDate();
			const months = Array.from( { length: 12 }, ( _, i ) => new Date( 2000, i, 1 ).toLocaleString( 'en', { month: 'short' } ) );
			const y0 = today().getFullYear();
			let cells = '';
			for ( let i = 0; i < 42; i++ ) {
				const d = new Date( first.getFullYear(), first.getMonth(), 1 - lead + i );
				if ( i >= 35 && d.getMonth() !== view.getMonth() ) break;
				const out = d.getMonth() !== view.getMonth();
				const past = d < today();
				const on = sel && ( d.getTime() === sel.getTime() || d.getTime() === end.getTime() );
				const mid = sel && d > sel && d < end;
				cells += `<button type="button" class="gp-cal__day${ out ? ' is-out' : '' }${ on ? ' is-on' : '' }${ mid ? ' is-mid' : '' }" data-day="${ iso( d ) }"${ past || out ? ' disabled' : '' }>${ d.getDate() }</button>`;
			}
			cal.innerHTML = `<div class="gp-cal"><div class="gp-cal__head"><button type="button" class="gp-cal__nav" data-cal-nav="-1" aria-label="Previous month">&lsaquo;</button>
				<select class="gp-cal__select" data-cal-month aria-label="Month">${ months.map( ( m, i ) => `<option value="${ i }"${ i === view.getMonth() ? ' selected' : '' }>${ m }</option>` ).join( '' ) }</select>
				<select class="gp-cal__select" data-cal-year aria-label="Year">${ [ 0, 1, 2 ].map( ( k ) => `<option value="${ y0 + k }"${ y0 + k === view.getFullYear() ? ' selected' : '' }>${ y0 + k }</option>` ).join( '' ) }</select>
				<button type="button" class="gp-cal__nav" data-cal-nav="1" aria-label="Next month">&rsaquo;</button></div>
				<div class="gp-cal__week">${ [ 'Su', 'Mo', 'Tu', 'We', 'Th', 'Fr', 'Sa' ].map( ( w ) => `<span>${ w }</span>` ).join( '' ) }</div>
				<div class="gp-cal__grid">${ cells }</div></div>`;
		};
		fields[ 1 ].addEventListener( 'click', ( e ) => {
			if ( ! phone.matches ) return;
			e.preventDefault();
			menu.hidden = true;
			if ( cal.hidden ) {
				const d = dateInput.value ? parse( dateInput.value ) : today();
				view = new Date( d.getFullYear(), d.getMonth(), 1 );
				renderCal();
				cal.hidden = false;
			} else {
				cal.hidden = true;
			}
		} );
		cal.addEventListener( 'click', ( e ) => {
			e.stopPropagation();
			const nav1 = e.target.closest( '[data-cal-nav]' );
			const day = e.target.closest( '[data-day]' );
			if ( nav1 ) {
				const next = new Date( view.getFullYear(), view.getMonth() + parseInt( nav1.dataset.calNav, 10 ), 1 );
				if ( next >= new Date( today().getFullYear(), today().getMonth(), 1 ) ) { view = next; renderCal(); }
			} else if ( day ) {
				dateInput.value = day.dataset.day;
				syncDate();
				cal.hidden = true;
			}
		} );
		cal.addEventListener( 'change', ( e ) => {
			if ( e.target.matches( '[data-cal-month], [data-cal-year]' ) ) {
				view = new Date( parseInt( $( '[data-cal-year]', cal ).value, 10 ), parseInt( $( '[data-cal-month]', cal ).value, 10 ), 1 );
				renderCal();
			}
		} );

		// Guests stepper
		$$( '[data-guests]', form ).forEach( ( b ) => b.addEventListener( 'click', () => {
			const i = Math.max( 0, Math.min( guestSteps.length - 1, guestSteps.indexOf( guestSel.value ) + parseInt( b.dataset.guests, 10 ) ) );
			guestSel.value = guestSteps[ i ];
			syncGuests();
		} ) );

		// Prev / next
		$( '[data-search-prev]', form ).addEventListener( 'click', () => showStep( step - 1 ) );
		$( '[data-search-next]', form ).addEventListener( 'click', () => showStep( step + 1 ) );

		// Outside click closes the pop-ups
		document.addEventListener( 'click', ( e ) => {
			if ( ! form.contains( e.target ) ) closePopups();
		} );
		const onResize = () => { showStep( phone.matches ? step : 0 ); };
		if ( phone.addEventListener ) phone.addEventListener( 'change', onResize );
		syncDate();
		syncGuests();
		showStep( 0 );
	}
	/* Header pop-overs (account menu, language / currency) ------------------- */
	function initPopovers() {
		const roots = $$( '[data-popover]' );
		const close = ( root ) => {
			const panel = $( '.gp-popover__panel', root );
			const btn = $( 'button', root );
			if ( panel ) panel.hidden = true;
			if ( btn ) btn.setAttribute( 'aria-expanded', 'false' );
		};
		roots.forEach( ( root ) => {
			const btn = $( 'button', root );
			const panel = $( '.gp-popover__panel', root );
			if ( ! btn || ! panel ) return;
			const open = () => {
				roots.forEach( ( r ) => r !== root && close( r ) );
				panel.hidden = false;
				btn.setAttribute( 'aria-expanded', 'true' );
			};
			btn.addEventListener( 'click', () => ( panel.hidden ? open() : close( root ) ) );
			if ( root.hasAttribute( 'data-hover' ) && window.matchMedia( '(hover: hover)' ).matches ) {
				let timer;
				root.addEventListener( 'mouseenter', () => { clearTimeout( timer ); open(); } );
				root.addEventListener( 'mouseleave', () => { timer = setTimeout( () => close( root ), 160 ); } );
			}
			$$( '[data-lang]', root ).forEach( ( b ) => b.addEventListener( 'click', () => {
				$$( '[data-lang]', root ).forEach( ( x ) => x.classList.toggle( 'is-active', x === b ) );
				const label = $( '.gp-locale__lang', root );
				if ( label ) label.textContent = b.dataset.lang === 'th' ? 'Th' : 'En';
				try { localStorage.setItem( 'gpHouseLang', b.dataset.lang ); } catch ( e ) {}
			} ) );
		} );
		document.addEventListener( 'click', ( e ) => roots.forEach( ( r ) => ! r.contains( e.target ) && close( r ) ) );
		document.addEventListener( 'keydown', ( e ) => e.key === 'Escape' && roots.forEach( close ) );
	}

	/* Member area: mobile account menu, booking filter, itinerary dialogs ---- */
	function initProfile() {
		const shell = $( '[data-pf]' );
		const toggle = $( '[data-pf-toggle]' );
		if ( shell && toggle ) {
			toggle.addEventListener( 'click', () => {
				const open = ! shell.classList.contains( 'is-menu' );
				shell.classList.toggle( 'is-menu', open );
				document.body.classList.toggle( 'gp-pf-menu', open );
				toggle.setAttribute( 'aria-expanded', String( open ) );
			} );
		}
		$$( '[data-bookings]' ).forEach( ( root ) => {
			const filters = $$( '[data-filter]', root );
			const items = $$( '.gp-bk', root );
			const none = $( '.gp-bookings__none', root );
			filters.forEach( ( f ) => f.addEventListener( 'click', () => {
				filters.forEach( ( x ) => {
					x.classList.toggle( 'is-active', x === f );
					x.setAttribute( 'aria-selected', String( x === f ) );
				} );
				let shown = 0;
				items.forEach( ( it ) => {
					const show = f.dataset.filter === 'all' || it.dataset.status === f.dataset.filter;
					it.hidden = ! show;
					if ( show ) shown++;
				} );
				if ( none ) none.hidden = shown > 0;
			} ) );
		} );
		$$( '[data-itinerary]' ).forEach( ( btn ) => btn.addEventListener( 'click', () => {
			const dlg = document.getElementById( btn.dataset.itinerary );
			if ( dlg && dlg.showModal ) dlg.showModal();
		} ) );
		$$( 'dialog.gp-it' ).forEach( ( dlg ) => {
			dlg.addEventListener( 'click', ( e ) => e.target === dlg && dlg.close() );
			$$( '[data-itinerary-close]', dlg ).forEach( ( b ) => b.addEventListener( 'click', () => dlg.close() ) );
			$$( '[data-print]', dlg ).forEach( ( b ) => b.addEventListener( 'click', () => window.print() ) );
		} );
	}

	document.addEventListener( 'DOMContentLoaded', () => {
		initPopovers();
		initHeroSearch();
		initProfile();
		initHeader();
		initSearch();
		initExplore();
		initCarousels();
		initFavourites();
		initBenefits();
		initJournal();
		initCurrency();
		initDropdowns();
		initRoomFilters();
		initGalleries();
		initRoomGallery();
		initBookingCard();
		initRegionTabs();
		initAccordions();
		initHoverLists();
		initClubCalc();
		initShare();
		initTeamTabs();
		initOwnerSim();
		initCheckin();
	} );
}() );
