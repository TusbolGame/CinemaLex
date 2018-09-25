<?php
namespace App;

use Illuminate\Support\Facades\Storage;

class Helpers
{

    /*  Static function to get CDN URL of folder
     *
     *  @params $get is the name of CDN parameter folder
     *  @return string URL
     */

    public static function getAwsUrl($get)
    {
        $array = [
            'user' => config('aws.cloudfront.web').'/users_image/',
            'poster' => config('aws.cloudfront.web').'/posters/',
            'backdrop' => config('aws.cloudfront.web').'/backdrops/',
            'cast' => config('aws.cloudfront.web').'/casts/',
            'video' => config('aws.cloudfront.web').'/videos/',
            'subtitle'=> config('aws.cloudfront.web').'/subtitles/',
            'backup'=> config('aws.cloudfront.web').'/backup/',
            'tv'=> config('aws.cloudfront.web').'/tv/',

        ];
        return $array[$get];
    }

    /*  Static function to Calculate the percentage
     *
     *  @params $old The old number (Int)
     *  @params $new The new number (Int)
     *  @return Int
     */


    public static function getPercentage($old, $new)
    {
        $percentage = (($new - $old) / $old) * 100;
        return round($percentage, 0).'%';
    }

    /*  Static function to check if the array is empty or not
     *
     *  @params $array The array
     *  @return array - status code 204 No content
     */

    public static function checkIsEmptyArray($array)
    {
        if (count($array->all()) < 0 || $array->isEmpty()) {
            return true;
        } else {
            return false;
        }
    }


    /*  Static function to check if there is id exist escpecilly works with first() function
     *
     *  @params $array
     *  @return array - status code 404 No Found
     */

    public static function checkIsEmptyId($id)
    {
        if (is_null($id)) {
            return true;
        } else {
            return false;
        }
    }
}
