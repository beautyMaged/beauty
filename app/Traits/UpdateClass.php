<?php

namespace App\Traits;

use App\Model\BusinessSetting;
use Illuminate\Support\Facades\DB;

trait UpdateClass
{
    public function insert_data_of($version_number)
    {
        if ($version_number == '13.0') {
            if (BusinessSetting::where(['type' => 'product_brand'])->first() == false) {
                DB::table('business_settings')->updateOrInsert(['type' => 'product_brand'], [
                    'value' => 1
                ]);
            }

            if (BusinessSetting::where(['type' => 'digital_product'])->first() == false) {
                DB::table('business_settings')->updateOrInsert(['type' => 'digital_product'], [
                    'value' => 1
                ]);
            }
        }

        if ($version_number == '13.1') {
            $refund_policy = BusinessSetting::where(['type' => 'refund-policy'])->first();
            if ($refund_policy) {
                $refund_value = json_decode($refund_policy['value'], true);
                if(!isset($refund_value['status'])){
                    BusinessSetting::where(['type' => 'refund-policy'])->update([
                        'value' => json_encode([
                            'status' => 1,
                            'content' => $refund_policy['value'],
                        ]),
                    ]);
                }
            }elseif(!$refund_policy){
                BusinessSetting::insert([
                    'type' => 'refund-policy',
                    'value' => json_encode([
                        'status' => 1,
                        'content' => '',
                    ]),
                ]);
            }

            $return_policy = BusinessSetting::where(['type' => 'return-policy'])->first();
            if ($return_policy) {
                $return_value = json_decode($return_policy['value'], true);
                if(!isset($return_value['status'])){
                    BusinessSetting::where(['type' => 'return-policy'])->update([
                        'value' => json_encode([
                            'status' => 1,
                            'content' => $return_policy['value'],
                        ]),
                    ]);
                }
            }elseif(!$return_policy){
                BusinessSetting::insert([
                    'type' => 'return-policy',
                    'value' => json_encode([
                        'status' => 1,
                        'content' => '',
                    ]),
                ]);
            }

            $cancellation_policy = BusinessSetting::where(['type' => 'cancellation-policy'])->first();
            if ($cancellation_policy) {
                $cancellation_value = json_decode($cancellation_policy['value'], true);
                if(!isset($cancellation_value['status'])){
                    BusinessSetting::where(['type' => 'cancellation-policy'])->update([
                        'value' => json_encode([
                            'status' => 1,
                            'content' => $cancellation_policy['value'],
                        ]),
                    ]);
                }
            }elseif(!$cancellation_policy){
                BusinessSetting::insert([
                    'type' => 'cancellation-policy',
                    'value' => json_encode([
                        'status' => 1,
                        'content' => '',
                    ]),
                ]);
            }

            if (BusinessSetting::where(['type' => 'offline_payment'])->first() == false) {
                DB::table('business_settings')->insert([
                    'type' => 'offline_payment',
                    'value' => json_encode([
                        'status' => 0,
                    ]),
                    'updated_at' => now()
                ]);
            }

            if (BusinessSetting::where(['type' => 'temporary_close'])->first() == false) {
                DB::table('business_settings')->insert([
                    'type' => 'temporary_close',
                    'value' => json_encode([
                        'status' => 0,
                    ]),
                    'updated_at' => now()
                ]);
            }

            if (BusinessSetting::where(['type' => 'vacation_add'])->first() == false) {
                DB::table('business_settings')->insert([
                    'type' => 'vacation_add',
                    'value' => json_encode([
                        'status' => 0,
                        'vacation_start_date' => null,
                        'vacation_end_date' => null,
                        'vacation_note' => null
                    ]),
                    'updated_at' => now()
                ]);
            }

            if (BusinessSetting::where(['type' => 'cookie_setting'])->first() == false) {
                DB::table('business_settings')->insert([
                    'type' => 'cookie_setting',
                    'value' => json_encode([
                        'status' => 0,
                        'cookie_text' => null
                    ]),
                    'updated_at' => now()
                ]);
            }

            DB::table('colors')
                ->whereIn('id', [16,38,93])
                ->delete();
        }
    }
}
