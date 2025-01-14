<?php

namespace Database\Seeders\Inventory;

use App\Models\Inventory\Unit;
use Illuminate\Database\Seeder;

class UnitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Unit::create([
            'ar_name' => 'كرتونة',
            'en_name' => 'CTN',
        ]);
        Unit::create([
            'ar_name' => 'قطعة',
            'en_name' => 'Pcs',
        ]);
        Unit::create([
            'ar_name' => 'قنينة',
            'en_name' => 'Bottle',
        ]);
        Unit::create([
            'ar_name' => 'غالون',
            'en_name' => 'Galon',
        ]);
        Unit::create([
            'ar_name' => 'كيلو',
            'en_name' => 'KG',
        ]);
        Unit::create([
            'ar_name' => 'شوال',
            'en_name' => 'Bag',
        ]);
        Unit::create([
            'ar_name' => 'ليتر',
            'en_name' => 'Liter',
        ]);
        Unit::create([
            'ar_name' => 'باكيت',
            'en_name' => 'Packet',
        ]);
        Unit::create([
            'ar_name' => 'سطل',
            'en_name' => 'Pot',
        ]);
        Unit::create([
            'ar_name' => 'تنكة',
            'en_name' => 'Tinker',
        ]);
        Unit::create([
            'ar_name' => '100غرام',
            'en_name' => '100g',
        ]);
        Unit::create([
            'ar_name' => 'طبق',
            'en_name' => 'Tray',
        ]);
        Unit::create([
            'ar_name' => '6قطع',
            'en_name' => '6pcs',
        ]);
        Unit::create([
            'ar_name' => 'كيس',
            'en_name' => 'Sac',
        ]);
        Unit::create([
            'ar_name' => 'علبة',
            'en_name' => 'Box',
        ]);
        Unit::create([
            'ar_name' => '500غرام',
            'en_name' => '500g',
        ]);
        Unit::create([
            'ar_name' => '2كغ',
            'en_name' => '2Kg',
        ]);
        Unit::create([
            'ar_name' => '2.5كغ',
            'en_name' => '2.5Kg',
        ]);



        Unit::create([
            'ar_name' => 'طقم',
            'en_name' => 'Set',
        ]);
        Unit::create([
            'ar_name' => 'دزينة',
            'en_name' => 'Dozen',
        ]);
        Unit::create([
            'ar_name' => 'دستة',
            'en_name' => 'Bunch',
        ]);
        Unit::create([
            'ar_name' => 'برميل',
            'en_name' => 'Barrel',
        ]);
        Unit::create([
            'ar_name' => 'متر',
            'en_name' => 'Meter',
        ]);
        Unit::create([
            'ar_name' => 'رول',
            'en_name' => 'Roll',
        ]);

        Unit::create([
            'ar_name' => 'طن',
            'en_name' => 'KG',
        ]);

        Unit::create([
            'ar_name' => 'ياردة',
            'en_name' => 'Yard',
        ]);
        Unit::create([
            'ar_name' => 'غرام',
            'en_name' => 'Gram',
        ]);
        Unit::create([
            'ar_name' => 'قدم',
            'en_name' => 'Feet',
        ]);
        Unit::create([
            'ar_name' => 'زوج',
            'en_name' => 'Pair',
        ]);
        Unit::create([
            'ar_name' => 'ربطة',
            'en_name' => 'Bundle',
        ]);
        Unit::create([
            'ar_name' => 'ألف',
            'en_name' => 'Thousand',
        ]);
        Unit::create([
            'ar_name' => 'ألف',
            'en_name' => 'Thousand',
        ]);
        Unit::create([
            'ar_name' => 'متر مربع',
            'en_name' => 'Square Meter',
        ]);
        Unit::create([
            'ar_name' => 'متر مكعب',
            'en_name' => 'Cubic Meter',
        ]);

        Unit::create([
            'ar_name' => 'عبوة',
            'en_name' => 'Pack',
        ]);

        Unit::create([
            'ar_name' => 'ليبرة',
            'en_name' => 'Pound',
        ]);
        Unit::create([
            'ar_name' => 'طرد',
            'en_name' => 'Parcel',
        ]);
        Unit::create([
            'ar_name' => 'تولة',
            'en_name' => 'Tola',
        ]);
        Unit::create([
            'ar_name' => 'بوري',
            'en_name' => 'Bori',
        ]);

        Unit::create([
            'ar_name' => 'حبة',
            'en_name' => 'Piece',
        ]);
        Unit::create([
            'ar_name' => 'لوح',
            'en_name' => 'Board',
        ]);
        Unit::create([
            'ar_name' => 'رول',
            'en_name' => 'Roll',
        ]);
        Unit::create([
            'ar_name' => 'بالة',
            'en_name' => 'Bale',
        ]);
        Unit::create([
            'ar_name' => 'سيخ',
            'en_name' => 'Stick',
        ]);

        Unit::create([
            'ar_name' => 'بوصة',
            'en_name' => 'Inch',
        ]);
        Unit::create([
            'ar_name' => 'سم',
            'en_name' => 'CM',
        ]);
        Unit::create([
            'ar_name' => 'ملم',
            'en_name' => 'MM',
        ]);
        Unit::create([
            'ar_name' => 'عدد',
            'en_name' => 'Qty',
        ]);
        Unit::create([
            'ar_name' => 'ماعون',
            'en_name' => 'Maon',
        ]);
    }
}
