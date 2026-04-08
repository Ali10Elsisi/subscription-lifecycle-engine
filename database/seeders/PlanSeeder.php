<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Plan;
use App\Models\PlanPrice;

class PlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
                $plans = [
            [
                'name'        => 'Starter',
                'description' => 'Perfect for individuals getting started.',
                'trial_days'  => 14,
                'is_active'   => true,
                'prices'      => [
                    ['billing_cycle' => 'monthly', 'currency' => 'AED', 'amount' => 9900],   
                    ['billing_cycle' => 'monthly', 'currency' => 'USD', 'amount' => 2700],   
                    ['billing_cycle' => 'monthly', 'currency' => 'EGP', 'amount' => 83000],  
                    ['billing_cycle' => 'yearly',  'currency' => 'AED', 'amount' => 99000],  
                    ['billing_cycle' => 'yearly',  'currency' => 'USD', 'amount' => 27000],  
                    ['billing_cycle' => 'yearly',  'currency' => 'EGP', 'amount' => 830000], 
                ],
            ],
            [
                'name'        => 'Pro',
                'description' => 'For growing teams and businesses.',
                'trial_days'  => 7,
                'is_active'   => true,
                'prices'      => [
                    ['billing_cycle' => 'monthly', 'currency' => 'AED', 'amount' => 29900],
                    ['billing_cycle' => 'monthly', 'currency' => 'USD', 'amount' => 7900],
                    ['billing_cycle' => 'monthly', 'currency' => 'EGP', 'amount' => 250000],
                    ['billing_cycle' => 'yearly',  'currency' => 'AED', 'amount' => 299000],
                    ['billing_cycle' => 'yearly',  'currency' => 'USD', 'amount' => 79000],
                    ['billing_cycle' => 'yearly',  'currency' => 'EGP', 'amount' => 2500000],
                ],
            ],
            [
                'name'        => 'Enterprise',
                'description' => 'For large organizations with custom needs.',
                'trial_days'  => 0,
                'is_active'   => true,
                'prices'      => [
                    ['billing_cycle' => 'monthly', 'currency' => 'AED', 'amount' => 99900],
                    ['billing_cycle' => 'monthly', 'currency' => 'USD', 'amount' => 27200],
                    ['billing_cycle' => 'monthly', 'currency' => 'EGP', 'amount' => 840000],
                    ['billing_cycle' => 'yearly',  'currency' => 'AED', 'amount' => 999000],
                    ['billing_cycle' => 'yearly',  'currency' => 'USD', 'amount' => 272000],
                    ['billing_cycle' => 'yearly',  'currency' => 'EGP', 'amount' => 8400000],
                ],
            ],
        ];
 
        foreach ($plans as $planData) {
            $prices = $planData['prices'];
            unset($planData['prices']);
 
            $plan = Plan::updateOrCreate(['name' => $planData['name']], $planData);
 
            foreach ($prices as $price) {
                PlanPrice::updateOrCreate(
                    [
                        'plan_id'       => $plan->id,
                        'billing_cycle' => $price['billing_cycle'],
                        'currency'      => $price['currency'],
                    ],
                    ['amount' => $price['amount']]
                );
            }
        }
    }
}
