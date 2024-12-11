# Unused Image Marker

## Description
The **Unused Image Marker** WordPress plugin is designed to streamline media library management. It allows administrators to easily identify and label unused images with a "Delete_" prefix and remove this prefix when needed. This ensures better media organization and cleanup efficiency.

This plugin is inspired by the techniques discussed in [this YouTube video](https://www.youtube.com/watch?v=x4ueJJRZ548) by **Web Squadron**. Special thanks to Web Squadron for their invaluable tutorial that sparked the creation of this plugin.

## Features
- Mark unused images in the media library by adding a "Delete_" prefix to their titles.
- Remove the "Delete_" prefix from image titles when no longer needed.
- AJAX-powered buttons for efficient management.
- Supports media analysis across:
    - Site logo and favicon.
    - WooCommerce products (if WooCommerce is installed).
    - Posts and pages (including drafts and private posts).

## Installation
1. Download the plugin.
2. Upload the plugin folder to the `/wp-content/plugins/` directory.
3. Activate the plugin through the **Plugins** menu in WordPress.

## Usage
1. Go to the **Media Library** in your WordPress admin panel.
2. You will see two new buttons:
    - **Mark Unused Images**: Scans the media library and marks unused images by adding a "Delete_" prefix to their titles.
    - **Remove Delete_ Prefix**: Removes the "Delete_" prefix from image titles.
3. Click the respective button to perform the desired action.

## AJAX Functionality
The plugin uses AJAX to process tasks efficiently without requiring a full page reload. The buttons perform the following:
- **Mark Unused Images**:
    - Checks media usage across the site.
    - Adds the "Delete_" prefix to unused images.
    - Provides a summary of used and unused images.
- **Remove Delete_ Prefix**:
    - Scans media for titles starting with "Delete_".
    - Removes the prefix and updates the title.
    - Displays the count of updated images.

## Special Notes
- The plugin is restricted to users with **manage_options** capabilities.
- It automatically integrates with WooCommerce (if installed) to analyze product images.

## Credits
- **Web Squadron**: Inspiration and foundational tutorial from [this video](https://www.youtube.com/watch?v=x4ueJJRZ548).

## Support
For issues or feature requests, feel free to reach out to the plugin developer or contribute via the GitHub repository (if available).

## Changelog
### Version 1.0.0
- Initial release with:
    - "Mark Unused Images" functionality.
    - "Remove Delete_ Prefix" functionality.
    - Integration with WooCommerce and WordPress posts/pages.

