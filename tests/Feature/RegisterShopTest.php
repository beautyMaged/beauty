<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Http\UploadedFile;
use Illuminate\Http\Response;
use App\Model\DeliveryCompany;
use Illuminate\Support\Facades\DB;

class RegisterShopTest extends TestCase
{
    /** @test */
    public function store()
    {
        $image = UploadedFile::fake()->image('image.jpg');
        $logo = UploadedFile::fake()->image('logo.jpg');
        $banner = UploadedFile::fake()->image('banner.jpg');
        $badgeIcon = UploadedFile::fake()->image('badgeIcon.jpg');
        $deliveryCompanyId = DeliveryCompany::first()->id;

        $shopData = [
            'trade_name' => 'Example Trade Name',
            'e_trade_name' => 'Example E-Trade Name',
            'type' => 'company',
            'platform' => 'shopify',
            'image' => $image,
            'banner' => $banner,
            'commercial_record' => 123456789,
            'trade_gov_no' => 987654321,
            'auth_authority' => 'maroof',
            'AUTH_no' => 'example_AUTH_number',
            'tax_no' => 1234567890,
            'city_id' => 1,
            'country_id' => 1,
            'branches' => [
                [
                    'name' => 'Example Branch',
                    'city_id' => 1,
                    'district_id' => 1,
                    'description' => 'Example Branch Description',
                    'longitude' => 12.345,
                    'latitude' => 67.890,
                    'phone' => '1234567890',
                    'street' => 'Example Street',
                    'times' => [
                        [
                            'opening_time' => '2024-03-19T09:00:00',
                            'closing_time' => '2024-03-19T18:00:00',
                            'day' => 'Monday'
                        ]
                    ]
                ]
            ],
            'connections' => [
                [
                    'name' => 'Example Connection',
                    'email' => 'example@example.com',
                    'phone' => '1234567890',
                    'role' => 'management'
                ]
            ],
            'agency' => [
                'name' => 'Example Agency',
                'logo' => $logo,
                'country_id' => 1,
                'category_id' => 23
            ],
            'policies' => $this->generatePoliciesData(),
            'refund_policy' => [
                'refund_max' => 50,
                'substitution_max' => 25,
                'days_to_refund_before_reception' => 10,
                'min_days_to_refund' => 1,
                'max_days_to_refund' => 30
            ],
            'fast_deliveries' => [
                [
                    'city_id' => 1,
                    'cost' => 10,
                    'note' => 'Example Fast Delivery Note'
                ]
            ],
            'new_policies' => [
                [
                    'name' => 'Example New Policy',
                    'description' => 'Example New Policy Description',
                    'type' => 'refund',
                    'status' => true,
                    'note' => 'Example New Policy Note'
                ]
            ],
            'shop_repository' => [
                'country_id' => 1,
                'city_id' => 1,
                'opening_time' => '2024-03-19T09:00:00',
                'closing_time' => '2024-03-19T18:00:00',
                'friday_opening_time' => '2024-03-19T09:00:00',
                'friday_closing_time' => '2024-03-19T18:00:00',
                'longitude' => 12.345,
                'latitude' => 67.890
            ],
            'badges' => [
                [
                    'title' => 'Example Badge Title',
                    'note' => 'Example Badge Note',
                    'icon' => $badgeIcon
                ]
            ],
            'delivery_companies' => [
                [
                    'delivery_company_id' => $deliveryCompanyId,
                    'main_cities_fees' => 10,
                    'towns_fees' => 5,
                    'vilages_fees' => 3,
                    'link' => 'example_delivery_company_link'
                ]
            ],
            'one_day_deliveries' => [
                [
                    'city_id' => 1,
                    'cost' => 15,
                    'note' => 'Example One Day Delivery Note'
                ]
            ]
           // 'refund_duration' => 15
        ];

        $response = $this->post('/registerShop', $shopData);

        $response->assertStatus(Response::HTTP_CREATED);
    }

    private function generatePoliciesData()
    {
        $ids = DB::table("policies")->where('is_approved', '1')->where('is_global', '1')->pluck('id');
        $policies = [];
        foreach ($ids as $id) {
            $policies[] = [
                'policy_id' => $id,
                'status' => true, 
                'note' => 'Example Policy Note' 
            ];
        }
        return $policies;
    }
}

