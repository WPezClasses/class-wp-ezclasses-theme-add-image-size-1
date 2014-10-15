# Org: WPezClasses
### Product: Class_WP_ezClasses_Theme_Add_Image_Size_1

##### WordPress add_image_size(), set_post_thumbnail_size() and a couple other WP image bits get ezTized. Why this matters is listed below.

===============================================================================================

#### Overview

Instead of coding add_image_size() and set_post_thumbnail_size(), now you simply configure an array and pass that to this class / methods. Here's an example of what that array looks like: 

- https://github.com/WPezBoilerStrap/wp-ezboilerstrap-uno/blob/master/setup/uno/class-wp-ezboilerstrap-add-image-size.php

Note: This example also has settings (1) for:

- https://github.com/WPezPlugins/wp-ezplugins-templates-picturefill-js

- https://github.com/WPezClasses/class-wp-ezclasses-templates-picturefill-js

(1) The settings are simply an example of what's possible. The Picturefill plugin / class uses its own settings (for now) so that example remains free-standing. In an ideal WP dev world you'd lean on a single array that defines all your WP image needs. Why sprawl if you don't have to?


===============================================================================================


#### "Why is this a better way to think about WordPress themes and how to implement additional custom images?"

- Less actual code, a lot less. This is (mostly) simply configuring an array and using a couple methods. 

- Centralization: All the key WP theme image stuff wrapped up in one.

- Flexibility: An example: Set the width, pick an (aspect) ratio, and the height will be calculated for you. 

- Simplicity: New project / theme, same widths / breakpoints, set different ratios (to achieve a slightly differnt look)...Done.

- Pair it with Class_WP_ezClasses_Templates_Picturefill_js and dev life gets even better. 

- Dumb it down: Your new designer isn't all that WP savvy (yet); so give him / her your boilerplate widths and request they stick to the predefined ratios. Chance are 400 x 300 is just a good as 400 x 293 (or some close but none the less custom ratio).

- Control: Fully custom width and height are still possible. Possibilities have not been removed, just automated and supplemented. 


===============================================================================================


##### Get Social

- **https://github.com/WPezClasses**
    - https://twitter.com/WPezClasses
    - https://www.facebook.com/WPezClasses
- **https://github.com/WPezPlugins**
    - https://twitter.com/WPezPlugins
    - https://www.facebook.com/WPezPlugins
- **https://github.com/WPezDeveloper**
    - https://twitter.com/WPezDeveloper
    - https://www.facebook.com/WPezDeveloper
- **https://github.com/WPezBoilerStrap**
    - https://twitter.com/WPezBoilerStrap
    - https://www.facebook.com/WPezBoilerStrap