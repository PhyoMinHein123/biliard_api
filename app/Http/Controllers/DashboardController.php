<?php

namespace App\Http\Controllers;

use App\Models\OrderItem;
use App\Models\Product;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function getWeek()
    {
        try {

            $start_of_week = Carbon::now()->startOfWeek();
            $end_of_week = Carbon::now()->endOfWeek();
            $date = Carbon::now();

            $weekly_income = OrderItem::whereDate('ordered_at', '>=', $start_of_week)
                ->whereDate('ordered_at', '<=', $end_of_week)->get()->sum('total_price');
            $weekly_profit = OrderItem::whereDate('ordered_at', '>=', $start_of_week)
                ->whereDate('ordered_at', '<=', $end_of_week)->get()->sum('profit');
            $today_income = OrderItem::whereDate('ordered_at', $date)->get()->sum('total_price');
            $today_profit = OrderItem::whereDate('ordered_at', $date)->get()->sum('profit');
            $yesterday_income = OrderItem::whereDate('ordered_at', $date->yesterday())->sum('total_price');
            $yesterday_profit = OrderItem::whereDate('ordered_at', $date->yesterday())->sum('total_price');

            $datas = [
                'daily_income' => [],
                'daily_profit' => [],
                'today_income' => $today_income,
                'today_profit' => $today_profit,
                'yesterday_income' => $yesterday_income,
                'yesterday_profit' => $yesterday_profit,
                'weekly_income' => $weekly_income,
                'weekly_profit' => $weekly_profit,
                'date' => $date->format('Y-m-d'),
            ];

            for ($i = 0; $i < 7; $i++) {

                $day = $start_of_week;

                if ($day->day == $day->daysInMonth) {
                    $datas['daily_income'][] = OrderItem::whereDate('ordered_at', $day)->get()->sum('total_price'); //31
                    $datas['daily_profit'][] = OrderItem::whereDate('ordered_at', $day)->get()->sum('profit');
                    $day->addMonthNoOverflow();
                    $day->day = 1;
                }
                $datas['daily_income'][] = OrderItem::whereDate('ordered_at', $day)->get()->sum('total_price'); // 30, 1
                $datas['daily_profit'][] = OrderItem::whereDate('ordered_at', $day)->get()->sum('profit');
                $day->addDays(1);
            }

            return $this->success('weekly income and profit retrived successfully', $datas);

        } catch (Exception $e) {

            return $this->internalServerError();
        }
    }

    public function getYear()
    {
        try {

            $year = Carbon::now()->year;
            $yearly_income = OrderItem::whereYear('ordered_at', $year)->get()->sum('total_price');
            $yearly_profit = OrderItem::whereYear('ordered_at', $year)->get()->sum('profit');
            $month = Carbon::now();

            $monthly_income = OrderItem::whereMonth('ordered_at', $month)
                ->whereYear('ordered_at', $year)
                ->get()->sum('total_price');

            $monthly_profit = OrderItem::whereMonth('ordered_at', $month)
                ->whereYear('ordered_at', $year)
                ->get()->sum('profit');

            $last_month = $month->subMonths(1);
            $last_month_income = OrderItem::whereMonth('ordered_at', $last_month)
                ->whereYear('ordered_at', $year)
                ->get()->sum('total_price');

            $last_month_profit = OrderItem::whereMonth('ordered_at', $last_month)
                ->whereYear('ordered_at', $year)
                ->get()->sum('profit');

            $datas = [
                'twelve_months_income' => [],
                'twelve_months_profit' => [],
                'this_month_income' => $monthly_income,
                'this_month_profit' => $monthly_profit,
                'last_month_income' => $last_month_income,
                'last_month_profit' => $last_month_profit,
                'yearly_income' => $yearly_income,
                'yearly_profit' => $yearly_profit,
            ];

            /**
             * get twelve months
             * */
            for ($i = 0; $i < 10; $i++) {
                $datas['twelve_months_income'][] = OrderItem::whereMonth('ordered_at', $month)
                    ->whereYear('ordered_at', $year)
                    ->get()->sum('total_price');

                $datas['twelve_months_profit'][] = OrderItem::whereMonth('ordered_at', $month)
                    ->whereYear('ordered_at', $year)
                    ->get()->sum('profit');
                $month->addMonths(1);
            }

            return $this->success('yearly income and profit retrived successfully', $datas);

        } catch (Exception $e) {

            return $this->internalServerError();
        }

    }

    public function getTotalItem()
    {
        try {

            $products = Product::where('category_id', 1)->get()->mapWithKeys(function ($product) {
                return [$product->name => $product->qty];
            });

            $foods = Product::where('category_id', 1)->get()->count();
            $drinks = Product::where('category_id', 2)->get()->count();

            $data = [
                'products' => $products,
                'foods' => $foods,
                'drinks' => $drinks,
            ];

            return $this->success('items are retrived successfully', $data);

        } catch (Exception $e) {

            return $this->internalServerError();
        }
    }
}
