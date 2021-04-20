<?php

return [

    /**
     * Users connecting to the API with mobile apps whose version does not
     * satisfy this requirement will be forced to update their app.
     *
     * Should be specified in semver notation.
     * See https://getcomposer.org/doc/articles/versions.md.
     */
    'mobile_app_version_constraints' => env('MOBILE_APP_VERSION_CONSTRAINTS', '>=2.2'),

];
