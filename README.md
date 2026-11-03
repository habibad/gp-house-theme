# GP House — theme architecture

The site is a custom WordPress build in two parts:

| Part | Folder | Holds |
|---|---|---|
| **Theme** `gp-house` | `wp-content/themes/gp-house` | Everything visual: templates, components, CSS, JS, design images |
| **Plugin** `gp-house-core` | `wp-content/plugins/gp-house-core` | Content model and logic: post types, fields, demo import. Later also auth, member dashboard and booking |

Rule of thumb: if it would still be needed after a redesign, it goes in the plugin. If it only exists to look like the Figma file, it goes in the theme.

## Folder map (theme)

```
gp-house/
├── functions.php            Loads the inc/ modules, nothing else
├── header.php / footer.php  Site header and footer (footer includes the "Ready for your next stay?" CTA)
├── front-page.php           Home page; one template part per Figma section
├── index.php                Fallback template
├── inc/
│   ├── setup.php            Theme supports, image sizes, menu locations
│   ├── assets.php           CSS/JS loading, per-page CSS (gp_current_page_key)
│   ├── components.php       Component API: gp_component(), gp_button(), gp_chip(), gp_attrs()
│   ├── helpers.php          gp_img(), gp_icon(), gp_inline_svg(), gp_price(), gp_opt()
│   ├── defaults.php         Customizer defaults and Figma demo content
│   ├── content.php          Data layer: gp_get_stays(), gp_get_destinations(), gp_get_testimonials(), gp_get_journal()
│   ├── customizer.php       Appearance → Customize → GP House
│   └── menus.php            Menu links with Figma fallbacks
├── components/              Reusable UI blocks (see the table below)
├── template-parts/<page>/   Sections that belong to one page only
└── assets/
    ├── css/base.css         Tokens (colours, fonts), reset, typography
    ├── css/components.css   Buttons, chips, tabs, cards, carousel
    ├── css/layout.css       Header, footer, CTA
    ├── css/pages/<page>.css Loaded only on that page
    ├── js/main.js           All interactions; no dependencies
    └── img/                 icons/ (SVG), photos/ (pre-cropped JPG), decor/ (backgrounds)
```

## Components

Each component matches a component on the Figma **Component** page. Render one with `gp_component( 'name', $args )`.

| Component | Figma | Args |
|---|---|---|
| `gp_button()` | Button / tab | `label, url, variant (primary\|navy\|outline), size (xs\|sm\|tab\|md\|lg), text ('navy')` |
| `gp_chip()` | Location label | `text, modifier` |
| `section-head` | Kicker + title (+ actions) | `kicker, title, tone ('light'), align ('center'), actions[], actions_html` |
| `stay-card` | Card / Featured Sanctuaries | `stay, saved` |
| `dest-card` | Card 1 (destination) | `dest` |
| `story-card` | Journal wide card | `story, index` |
| `testimonial-card` | Review card | `testimonial` |
| `page-hero` | Hero /Destinations (inner pages) | `image, image_mobile, kicker, title, text` |
| `room-card` | Stays row: gallery + card (320:1388) | `room, hidden` |
| `gp_dropdown()` | Location tab / Room / Bed / Short by | `name, label, options, value, show_value, class, align` |

When a new page reuses something from another page, move it into `components/` and `components.css`. Don't copy it.

## Data flow

Pages built so far: Home (`front-page.php`) and Stays (`archive-gp_stay.php`, /stays/, lists `gp_room` posts with filters).

Templates never query WordPress directly. They call the getters in `inc/content.php`, and every getter returns the same array shape whether the data comes from:

1. **The plugin's content** (Stays, Destinations, Guest Stories, Journal posts), or
2. **The Figma demo content** in `inc/defaults.php`, used while the site is still empty.

To load the demo content into wp-admin, go to **Tools → GP House Demo**.

## Adding a page (workflow used for every phase)

1. Read the frame from Figma (desktop and 402px mobile) and download its assets into `assets/img/`. Crop photos to the frame's aspect ratio.
2. Write the template: `page-<slug>.php`, an archive template, or a single template. Put its sections in `template-parts/<slug>/`.
3. Write the styles in `assets/css/pages/<slug>.css`. Use tokens from `base.css` and existing components first.
4. Screenshot the page at 1440px and 402px with headless Chrome and compare it with Figma, section by section.

## Design tokens

Colours: `--gp-primary #FFC646`, `--gp-navy #011041`, `--gp-black #1A1A1A`, `--gp-text #6B6B6B`, `--gp-stroke #E4E4E4`, `--gp-sand #E1E0DD`.

Fonts: **Plus Jakarta Sans** for headings, **DM Sans** for body text (opsz 14). Both are self-hosted in `assets/fonts/` (`assets/css/fonts.css`).

Reference frame widths: 1440px desktop and 402px mobile. The content column is 1128px.
