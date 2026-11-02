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
			burger.addEventListener( 'click', () => {
				const open = burger.getAttribute( 'aria-expanded' ) !== 'true';
				burger.setAttribute( 'aria-expanded', String( open ) );
				menu.hidden = ! open;
			} );
		}
	}

	/* Hero search: clicking anywhere in a field opens its picker ---------- */
	function initSearch() {
		$$( '.gp-search__field' ).forEach( ( field ) => {
			const control = $( 'select, input[type="date"]', field );
			if ( ! control ) return;
			field.addEventListener( 'click', ( e ) => {
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
		const section = $( '.gp-explore' );
		if ( ! section ) return;

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

		const map = $( '.gp-map', section );
		if ( ! map ) return;

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

	document.addEventListener( 'DOMContentLoaded', () => {
		initHeader();
		initSearch();
		initExplore();
		initCarousels();
		initFavourites();
		initBenefits();
		initJournal();
		initCurrency();
	} );
}() );
