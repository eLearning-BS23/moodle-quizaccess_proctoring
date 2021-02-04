<?php

function proctoring_get_image_url($webcampicture, $context){
    // if $webcampicture length != 32 we have an URL
    if(strlen($webcampicture) != 32){
        return $webcampicture;
    }else{
        return moodle_url::make_pluginfile_url(
            $context->id,
            'quizaccess_proctoring',
            'picture',
            0,
            '/',
            $webcampicture
        );
    }
}