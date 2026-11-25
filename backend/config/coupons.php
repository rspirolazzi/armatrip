<?php

return [

    /*
     * Database table name that will be used in migration
     * You can remove this key - value pair and we will
     * use default database name: 'coupons'
     */
    'table' => 'coupons',

    /*
     * Database pivot table name for coupons and users relation
     * use default database name: 'coupon_user'
     */
    'relation_table' => 'coupon_user',

    /*
     * List of characters, promo code generated from.
     * We have removed 1 (one) and I because with some
     * fonts you can't find deference between them
     */
    'characters' => '23456789ABCDEFGHJKLMNPQRSTUVWXYZ',

    /*
     * Promo code prefix.
     * This will be starting string of every coupon
     * You can set it to false or string
     *
     * Ex: foo
     * Output: foo-1234-1234
     */
    'prefix' => false,

    /*
     * Promo code suffix.
     * This will be ending string of every coupon
     * You can set it to false or string
     *
     * Ex: bar
     * Output: 1234-1234-bar
     */
    'suffix' => false,

    /*
     * Promo code mask.
     * Only asterisk will be replaced, so you can add
     * or remove as many asterisk as you with
     *
     * Ex: ***-**-***
     */
    'mask' => '****-****',

    /*
     * Promo code prefix and suffix separator.
     * Can be set any thing you wish
     */
    'separator' => '-',

    /**
     * User model
     */
    'user_model' => \App\User::class,

    /**
     * Foreign pivot key for many to many relationship
     * of coupon and user model
     */
    'foreign_pivot_key' => 'coupon_id',

    /**
     * Related pivot key for many to many relationship
     * of coupon and user model
     */
    'related_pivot_key' => 'user_id',

    /**
     * Por default dias de vencimiento del cupon
     */
    'default_expiration_days' => 30,

    'code_length' => 8,
];