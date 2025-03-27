
Third-Party Libraries for Moodle Plugin
=======================================

This file describes the third-party libraries included in the plugin, along with their installation, usage, and license information.

Libraries Included:
-------------------

1. **Lightbox2**
   - **Location**: `amd/src/lightbox2.js`
   - **Version**: 2.11.3
   - **License**: MIT
   - **Description**: Lightbox2 is used for creating responsive image modals in the plugin.
     The library is included as a minified JavaScript file at the specified location.

   Installation and Usage:
   ------------------------
   - The Lightbox2 library is already included in the plugin. No additional steps are required for installation.
   - It is used to create image galleries with responsive modals for viewing images in a lightbox style.
   - The minified JavaScript file (`lightbox2.js`) is located in the `amd/src/` directory.

2. **SSD MobileNet V1 (Shard 1)**
   - **Location**: `thirdpartylibs/models/ssd_mobilenetv1_model-shard1`
   - **Version**: 1.0
   - **License**: Apache-2.0
   - **Description**: This file is part of the SSD MobileNet V1 object detection model.

   Installation and Usage:
   ------------------------
   - Place the `ssd_mobilenetv1_model-shard1` file in the `thirdpartylibs/model/` directory within your plugin.
   - This file is required as part of the object detection model used in the plugin for machine learning purposes.
   - Ensure both shard 1 and shard 2 files are available and correctly linked in the plugin.  
   This is necessary to enable object detection functionality.

3. **SSD MobileNet V1 (Shard 2)**
   - **Location**: `thirdpartylibs/models/ssd_mobilenetv1_model-shard2`
   - **Version**: 1.0
   - **License**: Apache-2.0
   - **Description**: This file is part of the SSD MobileNet V1 object detection model.

   Installation and Usage:
   ------------------------
   - Place the `ssd_mobilenetv1_model-shard2` file in the `thirdpartylibs/model/` directory within your plugin.
   - This file, along with shard 1, is required to enable object detection functionality in the plugin.
   - Make sure that both shards are included in the correct directory path to ensure the proper loading of the model.

License Information:
--------------------
1. **Lightbox2**: MIT License
   - The library is open-source and can be freely used, modified, and distributed under the terms of the MIT license.

2. **SSD MobileNet V1 Model**: Apache License 2.0
   - The model is licensed under the Apache 2.0 license, which allows users to freely use, modify, and distribute the files,
     provided that they comply with the terms of the license.

Full text of the licenses can be found in the respective libraries.

Changelog:
----------
- **Version 1.0**:  
  Initial release of the plugin, including Lightbox2 and SSD MobileNet V1 model shards.
