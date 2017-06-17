<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

/** @var \Illuminate\Database\Eloquent\Factory $factory */

$factory->define(App\Role::class, function (Faker\Generator $faker) {
    return [
        'name'          => $faker->jobTitle,
        'description'   => $faker->catchPhrase
    ];
});

$factory->define(App\User::class, function (Faker\Generator $faker) {
    static $password;

    return [
        'given_name'        => $faker->firstName,
        'middle_name'       => $faker->lastName,
        'last_name'         => $faker->lastName,
        'username'          => $faker->userName,
        'email'             => $faker->safeEmail,
        'password'          => $password ?: $password = bcrypt('secret'),
        'role_id'           => 1,
        'remember_token'    => str_random(10),
    ];
});


$factory->define(App\UserLog::class, function (Faker\Generator $faker) {
    return [
        'log'       => $faker->tld,
        'remarks'   => $faker->catchPhrase
    ];
});

$factory->define(App\UserPhoto::class, function (Faker\Generator $faker) {
    return [
        'primary'   => $faker->imageUrl($width = 640, $height = 480),
        'cover'     => $faker->imageUrl($width = 640, $height = 480)
    ];
});

$factory->define(App\DonorType::class, function (Faker\Generator $faker) {
    return [
        'name'          => $faker->jobTitle,
        'description'   => $faker->catchPhrase
    ];
});

$factory->define(App\Donor::class, function (Faker\Generator $faker) {
    return [
        'given_name'    => $faker->firstName,
        'middle_name'   => $faker->lastName,
        'last_name'     => $faker->lastName,
        'email'         => $faker->unique()->safeEmail,
        'donor_type_id' => 1,
    ];
});

$factory->define(App\Profile::class, function (Faker\Generator $faker) {
    return [
        'title'         => $faker->title,
        'address'       => $faker->address,
        'phone'         => $faker->phoneNumber,
        'tel'           => $faker->tollFreePhoneNumber,
        'company'       => $faker->company,
        'job_title'     => $faker->jobTitle,
        'catch_phrase'  => $faker->catchPhrase
    ];
});

$factory->define(App\StoreCredit::class, function (Faker\Generator $faker) {
    return [
        'amount' => $faker->numberBetween($min = 1000, $max = 9000),
    ];
});

$factory->define(App\Category::class, function (Faker\Generator $faker) {
    return [
        'name'          => $faker->company,
        'description'   => $faker->realText($maxNbChars = 50, $indexSize = 2)
    ];
});

$factory->define(App\ItemCodeType::class, function (Faker\Generator $faker) {
    return [
        'name'          => 'Barcode',
        'description'   => $faker->realText($maxNbChars = 50, $indexSize = 2)
    ];
});

$factory->define(App\Item::class, function (Faker\Generator $faker) {
    return [
        'name'          => $faker->company,
        'description'   => $faker->realText($maxNbChars = 50, $indexSize = 2),
    ];
});

$factory->define(App\ItemCode::class, function (Faker\Generator $faker) {
    return [
        'code'              => $faker->ean13,
        'item_code_type_id' => 1,
    ];
});

$factory->define(App\ItemStatus::class, function (Faker\Generator $faker) {
    return [
        'name'          => 'Status',
        'description'   => $faker->realText($maxNbChars = 50, $indexSize = 2)
    ];
});

$factory->define(App\ItemPrice::class, function (Faker\Generator $faker) {
    return [
        'market_price'      => $faker->numberBetween($min = 1000, $max = 9000),
        'percent_discount'  => 20,
        'item_id'           => $faker->numberBetween($min = 1, $max = 100),
    ];
});

$factory->define(App\ItemImage::class, function (Faker\Generator $faker) {
    return [
        'name'      => $faker->imageUrl($width = 640, $height = 480),
        'item_id'   => $faker->numberBetween($min = 1, $max = 100),
    ];
});

$factory->define(App\ItemDiscount::class, function (Faker\Generator $faker) {
    return [
        'percent'       => $faker->numberBetween($min = 5, $max = 80),
        'remarks'       => $faker->realText($maxNbChars = 20, $indexSize = 2),
        'start_date'    => Carbon\Carbon::now(),
        'end_date'      => Carbon\Carbon::now()->addWeeks(1),
        'user_id'       => 3,
    ];
});

$factory->define(App\Inventory::class, function (Faker\Generator $faker) {
    return [
        'user_id'           => 3,
        'donor_id'          => $faker->numberBetween($min = 1, $max = 30),
        'item_discount_id'  => $faker->numberBetween($min = 1, $max = 30),
        'item_status_id'    => $faker->numberBetween($min = 1, $max = 11),
        'transaction_id'    => $faker->numberBetween($min = 1, $max = 30),
        'remarks'           => $faker->realText($maxNbChars = 20, $indexSize = 2),
        'quantity'          => $faker->numberBetween($min = 1, $max = 30),
    ];
});

$factory->define(App\Transaction::class, function (Faker\Generator $faker) {
    return [
        'da_number'       => 'DA-'.$faker->randomNumber($nbDigits = NULL, $strict = false),
        'dt_number'       => 'DT-'.$faker->randomNumber($nbDigits = NULL, $strict = false),
        'payment_type_id' => $faker->numberBetween($min = 1, $max = 5),
    ];
});

$factory->define(App\PaymentType::class, function (Faker\Generator $faker) {
    return [
        'description'   => $faker->realText($maxNbChars = 50, $indexSize = 2),
    ];
});

$factory->define(App\ContactType::class, function (Faker\Generator $faker) {
    return [
        'name'          => 'Number',
        'description'   => $faker->realText($maxNbChars = 50, $indexSize = 2),
    ];
});

$factory->define(App\SecondaryContact::class, function (Faker\Generator $faker) {
    return [
        'information'       => $faker->safeEmail,
        'contact_type_id'   => 3,
        'profile_id'        => 1,
    ];
});

$factory->define(App\ProfilePhoto::class, function (Faker\Generator $faker) {
    return [
        'primary'       => $faker->imageUrl($width = 640, $height = 480),
        'cover'         => $faker->imageUrl($width = 640, $height = 480),
        'profile_id'    => 1,
    ];
});


