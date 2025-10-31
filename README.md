# Horeka Discover & Quick

A production-grade WordPress plugin for the Horeka marketplace ecosystem.

## Overview

Horeka Discover & Quick enables a configurable homepage and discovery layer for connecting local resellers and customers in the home goods and small appliances marketplace.

## Features

### 🎛️ Admin Configuration
- Top-level "Horeka" menu with Dashboard and Settings
- Visual settings interface with media library integration
- Configurable welcome screen with CTA cards
- Feature toggles for sections
- Statistics dashboard

### 📦 Custom Post Types
- **Resellers** - Full profile management with contact info and serviceable areas
- **Reservations** - Secure order tracking system
- **Brands** - Hierarchical taxonomy for product organization

### 🔌 REST API
- `GET /wp-json/horeka/v1/resellers` - Paginated reseller listings
- `GET /wp-json/horeka/v1/products` - Product data (WooCommerce compatible)
- `POST /wp-json/horeka/v1/reservations` - Create reservations with validation

### 🎨 Frontend Components
- **Welcome Screen** - Dual CTA cards for Discover and Quick flows
- **Discovery Home** - Featured resellers, brands, and products
- **Reseller List** - Filterable, paginated listings with reserve buttons

### 🔒 Security
- Nonce verification on all AJAX/REST requests
- Capability checks for admin functions
- Input sanitization and output escaping
- IP-based rate limiting via transients
- Phone number validation

### ⚡ Performance
- Transient caching for heavy queries
- Lazy loading images
- Deferred JavaScript
- Pagination on all listings

## Installation

1. Download the plugin ZIP file
2. Go to WordPress Admin > Plugins > Add New > Upload Plugin
3. Upload the ZIP and click "Install Now"
4. Activate the plugin
5. Go to Horeka > Settings to configure

## Usage

### Admin Configuration

Navigate to **Horeka > Settings** to configure:

- Welcome title and CTA text
- Upload images for Discover and Quick cards
- Set target URLs
- Configure featured product limits
- Enable/disable sections

### Shortcodes

Add these shortcodes to any page or post:

```
[horeka_welcome]
```
Displays the welcome screen with dual CTAs.

```
[horeka_discover_home]
```
Complete discovery interface with resellers, brands, and products.

```
[horeka_reseller_list per_page="10" featured="1"]
```
Reseller listing with optional parameters:
- `per_page` - Items per page (default: 10)
- `featured` - Show only featured resellers (0 or 1)

### Adding Resellers

1. Go to **Resellers > Add New**
2. Enter reseller name and description
3. Upload a featured image
4. Fill in the Reseller Details:
   - Contact Number
   - Address
   - Serviceable Pincodes (pipe-separated: 110001|110002)
   - Featured checkbox
5. Publish

### Managing Reservations

Reservations are created via the frontend "Reserve Now" button or REST API. View all reservations at **Horeka > Reservations**.

## REST API Examples

### Get Resellers

```bash
curl https://yoursite.com/wp-json/horeka/v1/resellers?page=1&per_page=10
```

### Get Featured Resellers

```bash
curl https://yoursite.com/wp-json/horeka/v1/resellers?featured=1
```

### Create Reservation

```bash
curl -X POST https://yoursite.com/wp-json/horeka/v1/reservations \
  -H "Content-Type: application/json" \
  -H "X-WP-Nonce: YOUR_NONCE" \
  -d '{
    "reseller_id": 123,
    "phone": "9876543210",
    "name": "John Doe",
    "source": "mobile"
  }'
```

## File Structure

```
horeka-discover-quick/
├── horeka-discover-quick.php       # Main plugin file
├── includes/
│   ├── class-horeka-admin.php      # Admin menu and settings
│   ├── class-horeka-rest.php       # REST API endpoints
│   ├── class-horeka-shortcodes.php # Frontend shortcodes
│   ├── class-horeka-security.php   # Security utilities
│   └── helpers.php                 # Helper functions
├── assets/
│   ├── css/
│   │   ├── admin.css               # Admin styles
│   │   └── frontend.css            # Frontend styles
│   └── js/
│       ├── admin.js                # Admin JavaScript
│       └── frontend.js             # Frontend JavaScript
├── readme.txt                      # WordPress.org readme
└── README.md                       # This file
```

## Requirements

- WordPress 6.0 or higher
- PHP 8.1 or higher
- MySQL 5.7+ or MariaDB 10.3+

## Optional Dependencies

- WooCommerce (for product integration)

## Security Features

### Rate Limiting
- 10 requests per hour per IP address
- 3 reservations per hour per phone number
- Configurable via transients

### Data Validation
- Phone numbers: 10-digit validation
- URLs: WordPress `esc_url_raw()`
- Text fields: `sanitize_text_field()`
- All meta data sanitized before storage

### Access Control
- Admin functions require `manage_options` capability
- Nonce verification on all form submissions
- REST API authentication via WordPress nonces

## Performance Optimization

### Caching Strategy
- Reseller listings cached for 1 hour
- Product queries cached for 1 hour
- Cache keys include query parameters for proper invalidation

### Asset Loading
- JavaScript loaded in footer (non-blocking)
- Images lazy-loaded by default
- Minimal inline styles
- No external dependencies

## Support

For issues, feature requests, or contributions:
- GitHub: [Horeka Repository]
- Email: support@horeka.app
- Documentation: https://docs.horeka.app

## License

GPL v2 or later - https://www.gnu.org/licenses/gpl-2.0.html

## Credits

Developed by the Horeka Team
Copyright © 2024 Horeka

---

**Made with ❤️ for local businesses**